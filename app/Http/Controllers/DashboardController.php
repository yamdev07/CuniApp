<?php

namespace App\Http\Controllers;

use App\Models\Male;
use App\Models\Femelle;
use App\Models\Saillie;
use App\Models\MiseBas;
use App\Models\Sale;
use Carbon\Carbon;
use App\Models\Naissance;

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
        // Événements pour le calendrier
        $events = [
            // Saillies (violet)
            'saillies' => Saillie::with(['femelle', 'male'])
                ->select('id', 'date_saillie', 'femelle_id', 'male_id')
                ->get()
                ->map(function ($saillie) {
                    $nomFemelle = $saillie->femelle?->nom
                        ?? $saillie->femelle?->tag
                        ?? $saillie->femelle?->name
                        ?? "F#{$saillie->femelle_id}";
                    $nomMale = $saillie->male?->nom
                        ?? $saillie->male?->tag
                        ?? $saillie->male?->name
                        ?? "M#{$saillie->male_id}";
                    return [
                        'date' => $saillie->date_saillie
                            ? \Carbon\Carbon::parse($saillie->date_saillie)->format('Y-m-d')
                            : null,
                        'label' => "{$nomFemelle} × {$nomMale}",
                        'saillie_id' => $saillie->id,
                    ];
                })
                ->filter(fn($e) => $e['date'] !== null)
                ->toArray(),

            // ✅ NEW CODE (use accessors)
            'naissances' => \App\Models\Naissance::with(['femelle', 'miseBas'])
                ->whereHas('miseBas', function ($q) {
                    $q->whereNotNull('date_mise_bas');
                })
                ->get()
                ->filter(fn($n) => $n->nb_vivant > 0)
                ->map(fn($n) => [
                    'date' => $n->date_naissance?->format('Y-m-d'),
                    'label' => sprintf('Naissance: %s (%d nés)', $n->femelle?->nom ?? 'Inconnue', $n->nb_vivant ?? 0)
                ])
                ->toArray(),

            //  Sexuations (bleu) → J+10, SEULEMENT si nb_vivant > 0
            'sexuations' => \App\Models\Naissance::with('femelle')
                ->whereNotNull('date_naissance')
                ->where('nb_vivant', '>', 0) // ✅ FILTRE : uniquement les vivants
                ->where('sex_verified', false) // Optionnel : seulement les non-vérifiées
                ->get()
                ->map(function ($n) {
                    $nomAffiche = $n->femelle?->nom
                        ?? $n->femelle?->tag
                        ?? null;

                    return [
                        'date' => \Carbon\Carbon::parse($n->date_naissance)->addDays(10)->format('Y-m-d'),
                        'label' => $nomAffiche
                            ? "Sexage: {$nomAffiche} (#{$n->id})"
                            : "Sexage: Portée #{$n->id}",
                        'type' => 'sexuation'
                    ];
                })
                ->toArray(),
        ];

        // Timeline d'activité dynamique
        $timelineActivities = collect();

        //Récupérer les dernières NAISSANCES (vert) 
        $recentNaissances = Naissance::with('femelle')
            ->where('nb_vivant', '>', 0)
            ->latest('created_at')
            ->get()
            ->map(fn($n) => [
                'type' => 'green',
                'title' => 'Naissance enregistrée',
                'desc' => sprintf(
                    '%s (%d nés)',
                    $n->femelle?->nom ?? 'Inconnue',
                    $n->nb_vivant ?? 0
                ),
                'time' => Carbon::parse($n->created_at)->diffForHumans(),
                'date' => $n->created_at,
                'url' => route('naissances.show', $n->id) ?? '#', // ✅ Route vers Naissance
            ]);

        // Récupérer les dernières saillies (violet) → inchangé
        $recentSaillies = Saillie::with('femelle', 'male')
            ->latest('date_saillie')
            ->limit(1)
            ->get()
            ->map(fn($s) => [
                'type' => 'purple',
                'title' => 'Saillie programmée',
                'desc' => ($s->femelle?->nom ?? "F#{$s->femelle_id}") .
                    ' × ' .
                    ($s->male?->nom ?? "M#{$s->male_id}"),
                'time' => Carbon::parse($s->created_at)->diffForHumans(),
                'date' => $s->created_at,
                'url' => route('saillies.show', $s->id) ?? '#',
            ]);

        // Dernières ventes (bleu) → inchangé
        $recentSales = Sale::latest('created_at')
            ->limit(1)
            ->get()
            ->map(fn($v) => [
                'type' => 'blue',
                'title' => 'Vente enregistrée',
                'desc' => number_format($v->total_amount, 0, ',', ' ') . ' FCFA',
                'time' => Carbon::parse($v->created_at)->diffForHumans(),
                'date' => $v->created_at,
                'url' => route('sales.show', $v->id) ?? '#',
            ]);

        $recentMisesBas = MiseBas::with('saillie.femelle')
            ->latest('date_mise_bas')
            ->limit(1)
            ->get()
            ->map(fn($m) => [
                'type' => 'amber',
                'title' => ' Mise bas enregistrée',
                'desc' => sprintf(
                    '%s : %d lapereaux',
                    $m->saillie?->femelle?->nom ?? 'Inconnue',
                    $m->nb_vivant + ($m->nb_mort_ne ?? 0)
                ),
                'time' => Carbon::parse($m->created_at)->diffForHumans(),
                'date' => $m->created_at,
                'url' => route('mises-bas.show', $m->id),
            ]);

        // Nouveaux Lapins (AJOUT) - Mâles et Femelles
        $nouveauxMales = Male::latest('created_at')
            ->limit(1)
            ->get()
            ->map(fn($m) => [
                'type' => 'cyan',
                'title' => 'Mâle enregistré',
                'desc' => "{$m->nom} ({$m->code}) - {$m->race}",
                'time' => Carbon::parse($m->created_at)->diffForHumans(),
                'date' => $m->created_at,
                'url' => route('males.show', $m->id),
            ]);

        $nouvellesFemelles = Femelle::latest('created_at')
            ->limit(1)
            ->get()
            ->map(fn($f) => [
                'type' => 'cyan',
                'title' => 'Femelle enregistrée',
                'desc' => "{$f->nom} ({$f->code}) - {$f->race}",
                'time' => Carbon::parse($f->created_at)->diffForHumans(),
                'date' => $f->created_at,
                'url' => route('femelles.show', $f->id),
            ]);

        $nouveauxLapins = collect([...$nouveauxMales, ...$nouvellesFemelles]);


        // Fusionner, trier par date décroissante et limiter à 6 items
        $timelineActivities = collect([
            ...$recentNaissances->toArray(),
            ...$recentSaillies->toArray(),
            ...$recentSales->toArray(),
            ...$recentMisesBas->toArray(),
            ...$nouveauxLapins->toArray(),
        ])
            ->sortByDesc('date')
            ->take(5)
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
