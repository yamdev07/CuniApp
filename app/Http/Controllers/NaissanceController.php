<?php

namespace App\Http\Controllers;

use App\Models\Naissance;
use Illuminate\Http\Request;
use App\Traits\Notifiable;

class NaissanceController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $naissances = Naissance::latest()->paginate(10);
        return view('naissances.index', compact('naissances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('naissances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_lapin' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'poids' => 'required|numeric|min:0.01',
        ]);

        $naissance = Naissance::create($request->all());

        $sexeText = $request->sexe === 'M' ? 'Mâle' : 'Femelle';

        // Create notification
        $this->notifyUser([
            'type' => 'success',
            'title' => 'Nouvelle Naissance Enregistrée',
            'message' => "Nouveau {$sexeText} '{$naissance->nom_lapin}' né le " . \Carbon\Carbon::parse($naissance->date_naissance)->format('d/m/Y'),
            'action_url' => route('naissances.show', $naissance),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Naissance enregistrée !',
            'message' => "Nouveau {$sexeText} '{$naissance->nom_lapin}' ajouté.",
            'action_url' => route('naissances.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('naissances.index')
            ->with('success', 'Naissance ajoutée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $naissance = Naissance::findOrFail($id);
        return view('naissances.show', compact('naissance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $naissance = Naissance::findOrFail($id);
        return view('naissances.edit', compact('naissance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $naissance = Naissance::findOrFail($id);

        $request->validate([
            'nom_lapin' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'poids' => 'required|numeric|min:0.01',
        ]);

        $oldNom = $naissance->nom_lapin;
        $naissance->update($request->all());

        $sexeText = $request->sexe === 'M' ? 'Mâle' : 'Femelle';

        // Create notification
        $this->notifyUser([
            'type' => 'info',
            'title' => 'Naissance Modifiée',
            'message' => "Informations de '{$naissance->nom_lapin}' mises à jour.",
            'action_url' => route('naissances.show', $naissance),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'info',
            'title' => 'Mise à jour !',
            'message' => "Naissance '{$naissance->nom_lapin}' modifiée avec succès.",
            'action_url' => route('naissances.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('naissances.index')
            ->with('success', 'Naissance mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $naissance = Naissance::findOrFail($id);
        $nom = $naissance->nom_lapin;

        $naissance->delete();

        // Create notification
        $this->notifyUser([
            'type' => 'warning',
            'title' => 'Naissance Supprimée',
            'message' => "Enregistrement de '{$nom}' supprimé.",
            'action_url' => route('naissances.index'),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'warning',
            'title' => 'Supprimée !',
            'message' => "Naissance '{$nom}' supprimée avec succès.",
            'duration' => 5000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('naissances.index')
            ->with('success', 'Naissance supprimée avec succès !');
    }
}