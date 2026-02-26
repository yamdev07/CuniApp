<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Male;
use App\Models\Femelle;
use App\Models\Saillie;
use App\Models\MiseBas;
use App\Models\Sale; // ✅ IMPORT OBLIGATOIRE POUR LES VENTES
use Carbon\Carbon;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     // Période actuelle : cette semaine
    //     $startOfWeek = Carbon::now()->startOfWeek();
    //     $endOfWeek = Carbon::now()->endOfWeek();

    //     // Période précédente : semaine dernière
    //     $startLastWeek = Carbon::now()->subWeek()->startOfWeek();
    //     $endLastWeek = Carbon::now()->subWeek()->endOfWeek();

    //     // Comptage actuel
    //     $nbMales = Male::count();
    //     $nbFemelles = Femelle::count();
    //     $nbSaillies = Saillie::count();
    //     $nbMisesBas = MiseBas::count();

    //     // Comptage période précédente
    //     $oldMales = Male::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
    //     $oldFemelles = Femelle::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
    //     $oldSaillies = Saillie::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
    //     $oldMisesBas = MiseBas::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();

    //     // Pourcentages d'évolution
    //     $malePercent = $oldMales > 0 ? (($nbMales - $oldMales) / $oldMales) * 100 : 0;
    //     $femalePercent = $oldFemelles > 0 ? (($nbFemelles - $oldFemelles) / $oldFemelles) * 100 : 0;
    //     $sailliePercent = $oldSaillies > 0 ? (($nbSaillies - $oldSaillies) / $oldSaillies) * 100 : 0;
    //     $miseBasPercent = $oldMisesBas > 0 ? (($nbMisesBas - $oldMisesBas) / $oldMisesBas) * 100 : 0;

    //     // Liste des mâles récents
    //     $males = Male::orderBy('created_at', 'desc')->paginate(10);

    //     // Liste des femelles récentes (optionnel)
    //     $femelles = Femelle::orderBy('created_at', 'desc')->paginate(10);

    //     return view('dashboard', compact(
    //         'nbMales','nbFemelles','nbSaillies','nbMisesBas',
    //         'malePercent','femalePercent','sailliePercent','miseBasPercent',
    //         'males','femelles'
    //     ));
    // }



    public function index()
    {
<<<<<<< HEAD
        // Période actuelle : cette semaine
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        // Période précédente : semaine dernière
        $startLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endLastWeek = Carbon::now()->subWeek()->endOfWeek();
        
        // Comptage actuel
=======
        // Limite temporelle : début de cette semaine
        $startOfThisWeek = Carbon::now()->startOfWeek();

        // 1. Totaux Actuels (Stock complet à cet instant)
>>>>>>> dev
        $nbMales = Male::count();
        $nbFemelles = Femelle::count();
        $nbSaillies = Saillie::count();
        $nbMisesBas = MiseBas::count();
<<<<<<< HEAD
        
        // ✅ CALCUL DU CHIFFRE D'AFFAIRES (avec protection si table absente)
        try {
            $totalRevenue = Sale::sum('total_amount');
        } catch (\Exception $e) {
            $totalRevenue = 0; // Valeur par défaut si table non migrée
        }
        
        // Comptage période précédente
        $oldMales = Male::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldFemelles = Femelle::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldSaillies = Saillie::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldMisesBas = MiseBas::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        
        // Pourcentages d'évolution
        $malePercent = $oldMales > 0 ? (($nbMales - $oldMales) / $oldMales) * 100 : 0;
        $femalePercent = $oldFemelles > 0 ? (($nbFemelles - $oldFemelles) / $oldFemelles) * 100 : 0;
        $sailliePercent = $oldSaillies > 0 ? (($nbSaillies - $oldSaillies) / $oldSaillies) * 100 : 0;
        $miseBasPercent = $oldMisesBas > 0 ? (($nbMisesBas - $oldMisesBas) / $oldMisesBas) * 100 : 0;
        
        // Liste des mâles récents
        $males = Male::orderBy('created_at', 'desc')->paginate(10);
        // Liste des femelles récentes
        $femelles = Femelle::orderBy('created_at', 'desc')->paginate(10);
        
        return view('dashboard', compact(
            'nbMales', 'nbFemelles', 'nbSaillies', 'nbMisesBas',
            'malePercent', 'femalePercent', 'sailliePercent', 'miseBasPercent',
            'males', 'femelles',
            'totalRevenue' // ✅ VARIABLE PASSÉE À LA VUE
        ));
    }
}
=======

        // 2. Totaux de la semaine dernière (Stock tel qu'il était dimanche dernier)
        $oldMales = Male::where('created_at', '<', $startOfThisWeek)->count();
        $oldFemelles = Femelle::where('created_at', '<', $startOfThisWeek)->count();

        // Pour les activités (Saillies/Mises bas), on compare le volume de la semaine dernière uniquement
        $lastWeekSaillies = Saillie::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
        $thisWeekSaillies = Saillie::whereBetween('created_at', [$startOfThisWeek, Carbon::now()->endOfWeek()])->count();

        $lastWeekMisesBas = MiseBas::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
        $thisWeekMisesBas = MiseBas::whereBetween('created_at', [$startOfThisWeek, Carbon::now()->endOfWeek()])->count();

        // 3. Calcul des pourcentages de croissance
        // $malePercent = $oldMales > 0 ? (($nbMales - $oldMales) / $oldMales) * 100 : 0;
        // $femalePercent = $oldFemelles > 0 ? (($nbFemelles - $oldFemelles) / $oldFemelles) * 100 : 0;

        // // Pour l'activité, on compare le volume hebdomadaire
        // $sailliePercent = $lastWeekSaillies > 0 ? (($thisWeekSaillies - $lastWeekSaillies) / $lastWeekSaillies) * 100 : 0;
        // $miseBasPercent = $lastWeekMisesBas > 0 ? (($thisWeekMisesBas - $lastWeekMisesBas) / $lastWeekMisesBas) * 100 : 0;


        $malePercent = $oldMales > 0 ? (($nbMales - $oldMales) / $oldMales) * 100 : ($nbMales > 0 ? 100 : 0);
        $femalePercent = $oldFemelles > 0 ? (($nbFemelles - $oldFemelles) / $oldFemelles) * 100 : ($nbFemelles > 0 ? 100 : 0);

        // Pour l'activité (Saillies et Mises bas)
        $sailliePercent = $lastWeekSaillies > 0 ? (($thisWeekSaillies - $lastWeekSaillies) / $lastWeekSaillies) * 100 : ($thisWeekSaillies > 0 ? 100 : 0);
        $miseBasPercent = $lastWeekMisesBas > 0 ? (($thisWeekMisesBas - $lastWeekMisesBas) / $lastWeekMisesBas) * 100 : ($thisWeekMisesBas > 0 ? 100 : 0);

        return view('dashboard', compact(
            'nbMales',
            'nbFemelles',
            'nbSaillies',
            'nbMisesBas',
            'malePercent',
            'femalePercent',
            'sailliePercent',
            'miseBasPercent'
        ));
    }

}
>>>>>>> dev
