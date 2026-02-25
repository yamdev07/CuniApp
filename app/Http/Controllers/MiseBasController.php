<?php

namespace App\Http\Controllers;

use App\Models\MiseBas;
use App\Models\Femelle;
use Illuminate\Http\Request;
use App\Traits\Notifiable;

class MiseBasController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $misesBas = MiseBas::with('femelle')->latest()->paginate(10);
        return view('mises_bas.index', compact('misesBas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $femelles = Femelle::all();
        return view('mises_bas.create', compact('femelles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'date_mise_bas' => 'required|date',
            'nb_vivant' => 'required|integer|min:1',
            'nb_mort_ne' => 'nullable|integer|min:0',
            'date_sevrage' => 'nullable|date',
            'poids_moyen_sevrage' => 'nullable|numeric',
        ]);

        $miseBas = MiseBas::create([
            'femelle_id' => $request->femelle_id,
            'date_mise_bas' => $request->date_mise_bas,
            'nb_vivant' => $request->nb_vivant,
            'nb_mort_ne' => $request->nb_mort_ne ?? 0,
            'date_sevrage' => $request->date_sevrage,
            'poids_moyen_sevrage' => $request->poids_moyen_sevrage,
        ]);

        $femelle = Femelle::find($request->femelle_id);
        $total = $request->nb_vivant + ($request->nb_mort_ne ?? 0);

        // Create notification
        $this->notifyUser([
            'type' => 'success',
            'title' => 'Nouvelle Mise Bas Enregistrée',
            'message' => "Mise bas de {$femelle->nom} : {$total} lapereaux ({$request->nb_vivant} vivants)",
            'action_url' => route('mises-bas.show', $miseBas),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Mise bas enregistrée !',
            'message' => "{$total} lapereaux nés de {$femelle->nom}",
            'action_url' => route('mises-bas.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('mises-bas.index')
            ->with('success', 'Mise bas enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $miseBas = MiseBas::with('femelle')->findOrFail($id);
        return view('mises_bas.show', compact('miseBas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $miseBas = MiseBas::findOrFail($id);
        $femelles = Femelle::all();
        return view('mises_bas.edit', compact('miseBas', 'femelles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $miseBas = MiseBas::findOrFail($id);

        $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'date_mise_bas' => 'required|date',
            'nb_vivant' => 'required|integer|min:1',
            'nb_mort_ne' => 'nullable|integer|min:0',
            'date_sevrage' => 'nullable|date',
            'poids_moyen_sevrage' => 'nullable|numeric',
        ]);

        $oldTotal = $miseBas->nb_vivant + $miseBas->nb_mort_ne;
        $newTotal = $request->nb_vivant + ($request->nb_mort_ne ?? 0);

        $miseBas->update([
            'femelle_id' => $request->femelle_id,
            'date_mise_bas' => $request->date_mise_bas,
            'nb_vivant' => $request->nb_vivant,
            'nb_mort_ne' => $request->nb_mort_ne ?? 0,
            'date_sevrage' => $request->date_sevrage,
            'poids_moyen_sevrage' => $request->poids_moyen_sevrage,
        ]);

        $femelle = Femelle::find($request->femelle_id);

        // Create notification
        $this->notifyUser([
            'type' => 'info',
            'title' => 'Mise Bas Modifiée',
            'message' => "Mise bas de {$femelle->nom} mise à jour : {$newTotal} lapereaux",
            'action_url' => route('mises-bas.show', $miseBas),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'info',
            'title' => 'Mise à jour !',
            'message' => "Mise bas de {$femelle->nom} modifiée avec succès.",
            'action_url' => route('mises-bas.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('mises-bas.index')
            ->with('success', 'Mise bas mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $miseBas = MiseBas::with('femelle')->findOrFail($id);
        $femelleName = $miseBas->femelle->nom;
        $total = $miseBas->nb_vivant + $miseBas->nb_mort_ne;

        $miseBas->delete();

        // Create notification
        $this->notifyUser([
            'type' => 'warning',
            'title' => 'Mise Bas Supprimée',
            'message' => "Mise bas de {$femelleName} ({$total} lapereaux) supprimée.",
            'action_url' => route('mises-bas.index'),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'warning',
            'title' => 'Supprimée !',
            'message' => "Mise bas de {$femelleName} supprimée avec succès.",
            'duration' => 5000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('mises-bas.index')
            ->with('success', 'Mise bas supprimée avec succès.');
    }
}