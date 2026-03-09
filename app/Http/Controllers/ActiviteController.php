<?php

namespace App\Http\Controllers;

use App\Models\Naissance;
use App\Models\Saillie;
use App\Models\Sale;
use App\Models\Male;
use App\Models\Femelle;
use App\Models\MiseBas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActiviteController extends Controller
{
    public function index()
    {
        // Récupérer TOUTES les activités fusionnées

        // Naissances (vert) → remplace MiseBas
        $naissances = Naissance::with(['femelle', 'miseBas'])
            ->get()
            ->filter(fn($n) => $n->nb_vivant > 0)
            ->sortByDesc(fn($n) => $n->date_naissance)
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
                'url' => route('naissances.show', $n->id),
                'id' => $n->id,  // ← ✅ AJOUTER CETTE LIGNE

                'icon' => 'bi-egg-fill',
            ]);

        // Saillies (violet) 
        $saillies = Saillie::with('femelle', 'male')
            ->latest('date_saillie')
            ->get()
            ->map(fn($s) => [
                'type' => 'purple',
                'title' => 'Saillie programmée',
                'desc' => ($s->femelle?->nom ?? "F#{$s->femelle_id}") .
                    ' × ' .
                    ($s->male?->nom ?? "M#{$s->male_id}"),
                'time' => Carbon::parse($s->created_at)->diffForHumans(),
                'date' => $s->created_at,
                'url' => route('saillies.show', $s->id),
                'id' => $s->id,
                'icon' => 'bi-heart',
            ]);

        // Ventes (bleu)
        $ventes = Sale::latest('created_at')
            ->get()
            ->map(fn($v) => [
                'type' => 'blue',
                'title' => 'Vente enregistrée',
                'desc' => number_format($v->total_amount, 0, ',', ' ') . ' FCFA',
                'time' => Carbon::parse($v->created_at)->diffForHumans(),
                'date' => $v->created_at,
                'url' => route('sales.show', $v->id),
                'id' => $v->id,
                'icon' => 'bi-cart-check',
            ]);



        // Enregistrement de lapins (cyan)
        $nouveauxLapins = collect();

        // Mâles enregistrés
        $nouveauxMales = Male::latest('created_at')->limit(10)->get()
            ->map(fn($m) => [
                'type' => 'cyan',
                'title' => 'Mâle enregistré',
                'desc' => "{$m->nom} ({$m->code}) - {$m->race}",
                'time' => Carbon::parse($m->created_at)->diffForHumans(),
                'date' => $m->created_at,
                'url' => route('males.show', $m->id),
                'id' => $m->id,
                'model' => 'male',
                'icon' => 'bi-arrow-up-right-square',
            ]);

        // Femelles enregistrées
        $nouvellesFemelles = Femelle::latest('created_at')->limit(10)->get()
            ->map(fn($f) => [
                'type' => 'cyan',
                'title' => 'Femelle enregistrée',
                'desc' => "{$f->nom} ({$f->code}) - {$f->race}",
                'time' => Carbon::parse($f->created_at)->diffForHumans(),
                'date' => $f->created_at,
                'url' => route('femelles.show', $f->id),
                'id' => $f->id,
                'model' => 'female',
                'icon' => 'bi-arrow-down-right-square',
            ]);

        $nouveauxLapins = collect([...$nouveauxMales, ...$nouvellesFemelles]);


        // NOUVEAU : Mises Bas (amber/jaune)
        $misesBas = MiseBas::with('femelle')
            ->latest('date_mise_bas')
            ->limit(10)
            ->get()
            ->map(fn($m) => [
                'type' => 'amber',
                'title' => ' Mise bas enregistrée',
                'desc' => sprintf(
                    '%s : %d lapereaux (%d vivants)',
                    $m->femelle?->nom ?? 'Inconnue',
                    $m->nb_vivant + ($m->nb_mort_ne ?? 0),
                    $m->nb_vivant
                ),
                'time' => Carbon::parse($m->created_at)->diffForHumans(),
                'date' => $m->created_at,
                'url' => route('mises-bas.show', $m->id),
                'id' => $m->id,
                'icon' => 'bi-egg',
            ]);


        // Fusionner et trier par date décroissante
        $allActivities = collect([
            ...$naissances->toArray(),
            ...$saillies->toArray(),
            // ...$alertes->toArray(),
            ...$ventes->toArray(),
            ...$nouveauxLapins->toArray(),
            ...$misesBas->toArray(),

        ])
            ->sortByDesc('date')
            ->values();

        // Après avoir créé $allActivities
        $filter = request()->get('type');

        if ($filter) {
            $allActivities = $allActivities->where('type', $filter);
        }

        // Pagination manuelle (20 par page)
        $perPage = 20;
        $currentPage = request()->get('page', 1);
        $paginatedActivities = $allActivities->forPage($currentPage, $perPage);


        $stats = [
            'total' => $allActivities->count(),
            'naissances' => $allActivities->where('type', 'green')->count(),
            'saillies' => $allActivities->where('type', 'purple')->count(),
            'ventes' => $allActivities->where('type', 'blue')->count(),
            'lapins' => $allActivities->where('type', 'cyan')->count(),
            'misesBas' => $allActivities->where('type', 'amber')->count(),  // ✅ AJOUT

        ];
        return view('activites.index', [
            'activities' => $paginatedActivities,
            'currentFilter' => $filter,
            'stats' => $stats,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'lastPage' => ceil($allActivities->count() / $perPage),
        ]);
    }


    /**
     * Supprimer une activité
     */
    public function destroy(Request $request, string $type, int $id)
    {
        // Gestion spéciale pour les nouveaux lapins (cyan)
        if ($type === 'cyan') {
            $model = $request->get('model');

            if ($model === 'male') {
                $male = \App\Models\Male::find($id);
                if ($male) {
                    $male->delete();
                    return back()->with('success', '✅ Mâle supprimé avec succès !');
                }
            } elseif ($model === 'female') {
                $femelle = \App\Models\Femelle::find($id);
                if ($femelle) {
                    $femelle->delete();
                    return back()->with('success', '✅ Femelle supprimée avec succès !');
                }
            }
            return back()->with('error', '❌ Lapin non trouvé');
        }

        // Mapping type → Modèle pour les autres activités
        $models = [
            'green' => \App\Models\Naissance::class,   // Naissances
            'purple' => \App\Models\Saillie::class,     // Saillies
            'blue' => \App\Models\Sale::class,          // Ventes
            'amber' => \App\Models\MiseBas::class,      // Mises Bas
        ];

        if (!isset($models[$type])) {
            return back()->with('error', '❌ Type d\'activité non valide');
        }

        $model = $models[$type];
        $record = $model::find($id);

        if (!$record) {
            return back()->with('error', '❌ Activité non trouvée');
        }

        // Vérification des permissions
        if (isset($record->user_id) && $record->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        $record->delete();

        $messages = [
            'green' => '✅ Naissance supprimée avec succès !',
            'purple' => '✅ Saillie supprimée avec succès !',
            'blue' => '✅ Vente supprimée avec succès !',
            'amber' => '✅ Mise bas supprimée avec succès !',
        ];

        return back()->with('success', $messages[$type] ?? '✅ Activité supprimée !');
    }
}
