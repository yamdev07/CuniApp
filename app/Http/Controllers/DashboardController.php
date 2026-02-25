<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Male;
use App\Models\Femelle;
use App\Models\Saillie;
use App\Models\MiseBas;
use App\Models\Sale; // ✅ Ajouté pour les ventes
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Période actuelle : cette semaine
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        // Période précédente : semaine dernière
        $startLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endLastWeek = Carbon::now()->subWeek()->endOfWeek();
        
        // Comptage actuel
        $nbMales = Male::count();
        $nbFemelles = Femelle::count();
        $nbSaillies = Saillie::count();
        $nbMisesBas = MiseBas::count();
        $totalRevenue = Sale::sum('total_amount'); // ✅ CA TOTAL DES VENTES
        
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
            'totalRevenue' // ✅ Passé à la vue
        ));
    }
}