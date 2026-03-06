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
use Illuminate\Support\Facades\DB;


class ActiviteController extends Controller
{
    public function index()
    {
        // Naissances (vert) → remplace MiseBas
        $naissances = Naissance::with(['femelle', 'miseBas'])
            ->get()
            ->filter(fn($n) => $n->nb_vivant > 0)
            ->sortByDesc(fn($n) => $n->date_naissance)
            ->map(fn($n) => [
                'id' => $n->id,              
                'model_type' => 'naissance',
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
                'icon' => 'bi-egg-fill',
            ]);

        // Saillies (violet) 
        $saillies = Saillie::with('femelle', 'male')
            ->latest('date_saillie')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,              
                'model_type' => 'saillie',
                'type' => 'purple',
                'title' => 'Saillie programmée',
                'desc' => ($s->femelle?->nom ?? "F#{$s->femelle_id}") .
                    ' × ' .
                    ($s->male?->nom ?? "M#{$s->male_id}"),
                'time' => Carbon::parse($s->created_at)->diffForHumans(),
                'date' => $s->created_at,
                'url' => route('saillies.show', $s->id),
                'icon' => 'bi-heart',
            ]);

        // Alertes : naissances incomplètes (orange) → basé sur Naissance
        // $alertes = Naissance::whereNull('femelle_id')
        //     ->orWhereDoesntHave('femelle')
        //     ->latest('created_at')
        //     ->limit(10)
        //     ->get()
        //     ->map(fn($n) => [
        //         'type' => 'orange',
        //         'title' => '⚠️ Naissance incomplète',
        //         'desc' => "Naissance #{$n->id} sans femelle associée",
        //         'time' => Carbon::parse($n->created_at)->diffForHumans(),
        //         'date' => $n->created_at,
        //         'url' => route('naissances.edit', $n->id),
        //         'icon' => 'bi-exclamation-triangle',
        //     ]);

        // Ventes (bleu)
        $ventes = Sale::latest('created_at')
            ->get()
            ->map(fn($v) => [
                'id' => $v->id,              
                'model_type' => 'sale',
                'type' => 'blue',
                'title' => 'Vente enregistrée',
                'desc' => number_format($v->total_amount, 0, ',', ' ') . ' FCFA',
                'time' => Carbon::parse($v->created_at)->diffForHumans(),
                'date' => $v->created_at,
                'url' => route('sales.show', $v->id),
                'icon' => 'bi-cart-check',
            ]);



        // Enregistrement de lapins (cyan)
        $nouveauxLapins = collect();

        // Mâles enregistrés
        $nouveauxMales = Male::latest('created_at')->limit(10)->get()
            ->map(fn($m) => [
                'id' => $m->id,              
                'model_type' => 'male',
                'type' => 'cyan',
                'title' => 'Mâle enregistré',
                'desc' => "{$m->nom} ({$m->code}) - {$m->race}",
                'time' => Carbon::parse($m->created_at)->diffForHumans(),
                'date' => $m->created_at,
                'url' => route('males.show', $m->id),
                'icon' => 'bi-arrow-up-right-square',
            ]);

        // Femelles enregistrées
        $nouvellesFemelles = Femelle::latest('created_at')->limit(10)->get()
            ->map(fn($f) => [
                'id' => $f->id,              
                'model_type' => 'femelle',
                'type' => 'cyan',
                'title' => 'Femelle enregistrée',
                'desc' => "{$f->nom} ({$f->code}) - {$f->race}",
                'time' => Carbon::parse($f->created_at)->diffForHumans(),
                'date' => $f->created_at,
                'url' => route('femelles.show', $f->id),
                'icon' => 'bi-arrow-down-right-square',
            ]);

        $nouveauxLapins = collect([...$nouveauxMales, ...$nouvellesFemelles]);


        // NOUVEAU : Mises Bas (amber/jaune)
        $misesBas = MiseBas::with('femelle')
            ->latest('date_mise_bas')
            ->limit(10)
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,              
                'model_type' => 'mise_bas',
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
 * Supprimer une activité (AJAX)
 */
public function destroy(Request $request)
{
    $request->validate([
        'id' => 'required|integer',
        'model_type' => 'required|in:naissance,saillie,sale,male,femelle,mise_bas',
    ]);

    $id = $request->id;
    $modelType = $request->model_type;

    DB::beginTransaction();
    try {
        $modelName = '';

        switch ($modelType) {
            case 'naissance':
                $model = Naissance::findOrFail($id);
                $modelName = "Naissance #{$id}";
                break;

            case 'saillie':
                $model = Saillie::findOrFail($id);
                $modelName = "Saillie #{$id}";
                break;

            case 'sale':
                $model = Sale::findOrFail($id);
                $modelName = "Vente #{$id}";
                break;

            case 'male':
                $model = Male::findOrFail($id);
                $modelName = "Mâle {$model->nom}";
                break;

            case 'femelle':
                $model = Femelle::findOrFail($id);
                $modelName = "Femelle {$model->nom}";
                break;

            case 'mise_bas':
                $model = MiseBas::findOrFail($id);
                $modelName = "Mise bas #{$id}";
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Type de modèle invalide'
                ], 400);
        }

        $model->delete(); // ✅ Suppression effective
        DB::commit();

        // ✅ Réponse SIMPLE : juste un message de succès
        return response()->json([
            'success' => true,
            'message' => 'Suppression réussie'  // ← Message court et clair
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        
        // ✅ En production : ne pas exposer le message d'erreur complet
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression'
        ], 500);
    }
}
}
