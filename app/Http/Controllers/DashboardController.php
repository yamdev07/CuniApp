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

        // Calcul des pourcentages d'évolution (semaine dernière vs cette semaine)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $oldMales = Male::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldFemelles = Femelle::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldSaillies = Saillie::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $oldMisesBas = MiseBas::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();

        $malePercent    = $oldMales > 0 ? (($nbMales - $oldMales) / $oldMales) * 100 : 0;
        $femalePercent  = $oldFemelles > 0 ? (($nbFemelles - $oldFemelles) / $oldFemelles) * 100 : 0;
        $sailliePercent = $oldSaillies > 0 ? (($nbSaillies - $oldSaillies) / $oldSaillies) * 100 : 0;
        $miseBasPercent = $oldMisesBas > 0 ? (($nbMisesBas - $oldMisesBas) / $oldMisesBas) * 100 : 0;

        // Listes récentes (pour affichage éventuel)
        $males = Male::latest()->paginate(10);
        $femelles = Femelle::latest()->paginate(10);

        return view('dashboard', compact(
            'nbMales', 'nbFemelles', 'nbSaillies', 'nbMisesBas',
            'malePercent', 'femalePercent', 'sailliePercent', 'miseBasPercent',
            'males', 'femelles', 'totalRevenue'
        ));
    }
}