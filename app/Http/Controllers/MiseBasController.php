<?php

namespace App\Http\Controllers;

use App\Models\MiseBas;
use App\Models\Femelle;
use Illuminate\Http\Request;

class MiseBasController extends Controller
{
    /**
     * Liste des mises bas
     */
    public function index()
    {
        $misesBas = MiseBas::with('femelle')->latest()->paginate(10);
        return view('mises_bas.index', compact('misesBas'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $femelles = Femelle::all();
        return view('mises_bas.create', compact('femelles'));
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'date_mise_bas' => 'required|date',
            'nb_vivant' => 'required|integer|min:1',  // Changed from nombre_jeunes
            'nb_mort_ne' => 'nullable|integer|min:0',
            'date_sevrage' => 'nullable|date',
            'poids_moyen_sevrage' => 'nullable|numeric',
        ]);

        MiseBas::create($request->all());

        return redirect()->route('mises-bas.index')
            ->with('success', 'Mise bas enregistrée avec succès.');
    }

    /**
     * Affichage d'une mise bas
     */
    public function show(MiseBas $miseBas)
    {
        return view('mises_bas.show', compact('miseBas'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(MiseBas $miseBas)
    {
        $femelles = Femelle::all();
        return view('mises_bas.edit', compact('miseBas', 'femelles'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, MiseBas $miseBas)
    {
        $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'date_mise_bas' => 'required|date',
            'nb_vivant' => 'required|integer|min:1',  // Changed from nombre_jeunes
            'nb_mort_ne' => 'nullable|integer|min:0',
            'date_sevrage' => 'nullable|date',
            'poids_moyen_sevrage' => 'nullable|numeric',
        ]);

        $miseBas->update($request->all());

        return redirect()->route('mises-bas.index')
            ->with('success', 'Mise bas mise à jour avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(MiseBas $miseBas)
    {
        $miseBas->delete();

        return redirect()->route('mises-bas.index')
            ->with('success', 'Mise bas supprimée avec succès.');
    }
}
