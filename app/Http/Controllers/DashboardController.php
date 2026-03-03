<?php

namespace App\Http\Controllers;

use App\Models\Male;
use App\Models\Femelle;
use App\Models\Saillie;
use App\Models\MiseBas;
use App\Models\Sale;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Totaux actuels
        $nbMales = Male::count();
        $nbFemelles = Femelle::count();
        $nbSaillies = Saillie::count();
        $nbMisesBas = MiseBas::count();

        // Chiffre d'affaires
        try {
            $totalRevenue = Sale::sum('total_amount');
        } catch (\Exception $e) {
            $totalRevenue = 0;
        }

        // Calcul des pourcentages d'évolution
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $oldMales = Male::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldFemelles = Femelle::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldSaillies = Saillie::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldMisesBas = MiseBas::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();

        $malePercent = $oldMales > 0 ? (($nbMales - $oldMales) / $oldMales) * 100 : 0;
        $femalePercent = $oldFemelles > 0 ? (($nbFemelles - $oldFemelles) / $oldFemelles) * 100 : 0;
        $sailliePercent = $oldSaillies > 0 ? (($nbSaillies - $oldSaillies) / $oldSaillies) * 100 : 0;
        $miseBasPercent = $oldMisesBas > 0 ? (($nbMisesBas - $oldMisesBas) / $oldMisesBas) * 100 : 0;

        // Listes récentes
        $males = Male::latest()->paginate(10);
        $femelles = Femelle::latest()->paginate(10);



        // Événements pour le calendrier
        $events = [
            // Saillies : uniquement les colonnes qui existent
            'saillies' => Saillie::with(['femelle', 'male'])
                ->select('id', 'date_saillie', 'femelle_id', 'male_id')
                ->get()
                ->map(function ($saillie) {
                    // Récupérer les noms avec fallback intelligent
                    $nomFemelle = $saillie->femelle?->nom
                        ?? $saillie->femelle?->tag
                        ?? $saillie->femelle?->name
                        ?? "F#{$saillie->femelle_id}";

                    $nomMale = $saillie->male?->nom
                        ?? $saillie->male?->tag
                        ?? $saillie->male?->name
                        ?? "M#{$saillie->male_id}";

                    return [
                        'date' => $saillie->date_saillie,
                        'label' => "{$nomFemelle} × {$nomMale}",
                        'saillie_id' => $saillie->id,
                    ];
                })
                ->toArray(),

            // Naissances
            'naissances' => MiseBas::select('id', 'date_mise_bas')->get()->map(fn($m) => [
                'date' => $m->date_mise_bas,
                'label' => 'Naissance #' . $m->id
            ])->toArray(),


            'sexuations' => MiseBas::with('saillie.femelle')->get()->map(function ($miseBas) {
                $nomAffiche = null;

                // La mise bas est liée à une saillie qui a une femelle
                if ($miseBas->saillie?->femelle) {
                    $femelle = $miseBas->saillie->femelle;
                    $nomAffiche = $femelle->nom
                        ?? $femelle->name
                        ?? $femelle->tag
                        ?? $femelle->identifiant
                        ?? null;
                }
                // Cas 2 : Fallback - si vous avez un champ femelle_id direct dans mises_bas (optionnel)
                elseif ($miseBas->femelle_id && $miseBas->femelle) {
                    $nomAffiche = $miseBas->femelle->nom ?? $miseBas->femelle->name ?? null;
                }
    
                // Label avec fallback intelligent
                $label = $nomAffiche
                    ? " Sexage: {$nomAffiche}"
                    : " Sexage portée #{$miseBas->id}";

                return [
                    'date' => Carbon::parse($miseBas->date_mise_bas)->addDays(10)->format('Y-m-d'),
                    'label' => $label,
                    'type' => 'sexuation'
                ];
            })->toArray(),

        ];

        // Ajoutez temporairement ceci avant le return pour déboguer
// Debug : voir la chaîne de données
        logger('🔍 Vérif finale:', MiseBas::with('saillie.femelle')->get()->map(fn($m) => [
            'id' => $m->id,
            'saillie_id' => $m->saillie_id,
            'femelle' => $m->saillie?->femelle?->nom ?? '❌ NON LIÉ',
        ])->toArray());


        // ✅ Timeline d'activité dynamique
        $timelineActivities = collect();

        // 1. Récupérer les dernières mises bas (vert)
        $recentMisesBas = MiseBas::with('saillie.femelle')
            ->latest('date_mise_bas')
            ->limit(3)
            ->get()
            ->map(fn($m) => [
                'type' => 'green',
                'title' => ' Mise bas enregistrée',
                'desc' => $m->saillie?->femelle?->nom
                    ?? $m->saillie?->femelle?->tag
                    ?? "Portée #{$m->id}",
                'time' => Carbon::parse($m->created_at)->diffForHumans(),
                'date' => $m->created_at,
                'url' => route('mises-bas.index', $m->id) ?? '#',
            ]);

        // Récupérer les dernières saillies (violet)
        $recentSaillies = Saillie::with('femelle', 'male')
            ->latest('date_saillie')
            ->limit(2)
            ->get()
            ->map(fn($s) => [
                'type' => 'purple',
                'title' => ' Saillie programmée',
                'desc' => ($s->femelle?->nom ?? "F#{$s->femelle_id}") .
                    ' × ' .
                    ($s->male?->nom ?? "M#{$s->male_id}"),
                'time' => Carbon::parse($s->created_at)->diffForHumans(),
                'date' => $s->created_at,
                'url' => route('saillies.index', $s->id) ?? '#',
            ]);

        // 3. Alertes : mises bas sans saillie liée (orange) ⚠️
        $alertesOrphelines = MiseBas::whereNull('saillie_id')
            ->latest('created_at')
            ->limit(2)
            ->get()
            ->map(fn($m) => [
                'type' => 'orange',
                'title' => '⚠️Portée non liée',
                'desc' => "Mise bas #{$m->id} sans saillie associée",
                'time' => Carbon::parse($m->created_at)->diffForHumans(),
                'date' => $m->created_at,
                'url' => route('mises-bas.index', $m->id) ?? '#',
            ]);

        // 4. Dernières ventes (bleu)
        $recentSales = Sale::latest('created_at')
            ->limit(2)
            ->get()
            ->map(fn($v) => [
                'type' => 'blue',
                'title' => ' Vente enregistrée',
                'desc' => number_format($v->total_amount, 0, ',', ' ') . ' FCFA',
                'time' => Carbon::parse($v->created_at)->diffForHumans(),
                'date' => $v->created_at,
                'url' => route('sales.index', $v->id) ?? '#',
            ]);

        // Fusionner, trier par date décroissante et limiter à 6 items
        $timelineActivities = collect([
            ...$recentMisesBas->toArray(),
            ...$recentSaillies->toArray(),
            ...$alertesOrphelines->toArray(),
            ...$recentSales->toArray(),
        ])
            ->sortByDesc('date')
            ->take(6)
            ->values();



        return view('dashboard', compact(
            'nbMales',
            'nbFemelles',
            'nbSaillies',
            'nbMisesBas',
            'oldMales',
            'oldFemelles',
            'oldSaillies',
            'oldMisesBas',
            'malePercent',
            'femalePercent',
            'sailliePercent',
            'miseBasPercent',
            'males',
            'femelles',
            'totalRevenue',
            'events',
            'timelineActivities'
        ));
    }
}