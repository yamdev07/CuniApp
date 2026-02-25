<?php

namespace App\Http\Controllers;

use App\Models\Femelle;
use Illuminate\Http\Request;
use App\Traits\Notifiable;

class FemelleController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $femelles = Femelle::latest()->paginate(10);
        return view('femelles.index', compact('femelles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lastCode = Femelle::where('code', 'LIKE', 'FEM-%')
            ->orderBy('code', 'desc')
            ->value('code');

        $nextNumber = $lastCode ? intval(substr($lastCode, 4)) + 1 : 1;
        $suggestedCode = 'FEM-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('femelles.create', compact('suggestedCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:femelles,code',
            'nom' => 'required|string',
            'race' => 'nullable|string',
            'origine' => 'required|in:Interne,Achat',
            'date_naissance' => 'nullable|date',
            'etat' => 'required|in:Active,Gestante,Allaitante,Vide',
        ]);

        $femelle = Femelle::create($request->all());

        // Create notification
        $this->notifyUser([
            'type' => 'success',
            'title' => 'Nouvelle Femelle Enregistrée',
            'message' => "Femelle '{$femelle->nom}' ({$femelle->code}) ajoutée à l'élevage.",
            'action_url' => route('femelles.show', $femelle),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Succès !',
            'message' => "Femelle '{$femelle->nom}' enregistrée avec succès.",
            'action_url' => route('femelles.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('femelles.index')
            ->with('success', 'Lapin femelle ajouté avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $femelle = Femelle::findOrFail($id);
        return view('femelles.show', compact('femelle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $femelle = Femelle::findOrFail($id);
        return view('femelles.edit', compact('femelle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $femelle = Femelle::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:femelles,code,' . $femelle->id,
            'nom' => 'required|string',
            'race' => 'nullable|string',
            'origine' => 'required|in:Interne,Achat',
            'date_naissance' => 'nullable|date',
            'etat' => 'required|in:Active,Gestante,Allaitante,Vide',
        ]);

        $oldNom = $femelle->nom;
        $femelle->update($request->all());

        // Create notification
        $this->notifyUser([
            'type' => 'info',
            'title' => 'Femelle Modifiée',
            'message' => "Informations de la femelle '{$femelle->nom}' mises à jour.",
            'action_url' => route('femelles.show', $femelle),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'info',
            'title' => 'Mise à jour !',
            'message' => "Femelle '{$femelle->nom}' modifiée avec succès.",
            'action_url' => route('femelles.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('femelles.index')
            ->with('success', 'Lapin femelle modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $femelle = Femelle::findOrFail($id);
        $femelleName = $femelle->nom;

        $femelle->delete();

        // Create notification
        $this->notifyUser([
            'type' => 'warning',
            'title' => 'Femelle Supprimée',
            'message' => "La femelle '{$femelleName}' a été supprimée de l'élevage.",
            'action_url' => route('femelles.index'),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'warning',
            'title' => 'Supprimée !',
            'message' => "Femelle '{$femelleName}' supprimée avec succès.",
            'duration' => 5000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('femelles.index')
            ->with('success', 'Lapin femelle supprimé avec succès !');
    }

    /**
     * Toggle femelle state
     */
    public function toggleEtat(Femelle $femelle)
    {
        $etats = ['Active', 'Gestante', 'Allaitante', 'Vide'];
        $currentIndex = array_search($femelle->etat, $etats);
        $nextIndex = ($currentIndex + 1) % count($etats);
        $oldEtat = $femelle->etat;
        $newEtat = $etats[$nextIndex];

        $femelle->etat = $newEtat;
        $femelle->save();

        // Create notification
        $this->notifyUser([
            'type' => 'info',
            'title' => 'État de la Femelle Mis à Jour',
            'message' => "État de '{$femelle->nom}' : {$oldEtat} → {$newEtat}",
            'action_url' => route('femelles.show', $femelle),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'info',
            'title' => 'État mis à jour !',
            'message' => "État de '{$femelle->nom}' : {$newEtat}",
            'action_url' => route('femelles.index'),
            'duration' => 4000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->back()
            ->with('success', 'État mis à jour avec succès !');
    }

    public function checkCode(Request $request)
    {
        $exists = Femelle::where('code', $request->code)->exists();
        return response()->json(['available' => !$exists]);
    }
}
