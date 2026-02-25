<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Male;
use App\Traits\Notifiable;

class MaleController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $males = Male::latest()->paginate(10);
        return view('males.index', compact('males'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create()
    {
        // Generate next available unique code
        $lastCode = Male::where('code', 'LIKE', 'MAL-%')
            ->orderBy('code', 'desc')
            ->value('code');

        $nextNumber = $lastCode ? intval(substr($lastCode, 4)) + 1 : 1;
        $suggestedCode = 'MAL-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('males.create', compact('suggestedCode'));
    }

// In store method - validation already handles uniqueness!
// No changes needed to validation rules

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $male = Male::findOrFail($id);
        return view('males.show', compact('male'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $male = Male::findOrFail($id);
        return view('males.edit', compact('male'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $male = Male::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:males,code,' . $male->id,
            'nom' => 'required|string|max:255',
            'race' => 'nullable|string|max:255',
            'origine' => 'required|in:Interne,Achat',
            'date_naissance' => 'required|date',
            'etat' => 'required|in:Active,Inactive,Malade',
        ]);

        $oldNom = $male->nom;
        $male->update($request->all());

        // Create notification
        $this->notifyUser([
            'type' => 'info',
            'title' => 'Mâle Modifié',
            'message' => "Informations du mâle '{$male->nom}' mises à jour.",
            'action_url' => route('males.show', $male),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'info',
            'title' => 'Mise à jour !',
            'message' => "Mâle '{$male->nom}' modifié avec succès.",
            'action_url' => route('males.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('males.index')
            ->with('success', 'Mâle modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $male = Male::findOrFail($id);
        $maleName = $male->nom;

        $male->delete();

        // Create notification
        $this->notifyUser([
            'type' => 'warning',
            'title' => 'Mâle Supprimé',
            'message' => "Le mâle '{$maleName}' a été supprimé de l'élevage.",
            'action_url' => route('males.index'),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'warning',
            'title' => 'Supprimé !',
            'message' => "Mâle '{$maleName}' supprimé avec succès.",
            'duration' => 5000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('males.index')
            ->with('success', 'Mâle supprimé avec succès.');
    }

    /**
     * Toggle male state
     */
    public function toggleEtat(Male $male)
    {
        $etats = ['Active', 'Inactive', 'Malade'];
        $currentIndex = array_search($male->etat, $etats);
        $nextIndex = ($currentIndex + 1) % count($etats);
        $oldEtat = $male->etat;
        $newEtat = $etats[$nextIndex];

        $male->etat = $newEtat;
        $male->save();

        // Create notification
        $this->notifyUser([
            'type' => 'info',
            'title' => 'État du Mâle Mis à Jour',
            'message' => "État de '{$male->nom}' : {$oldEtat} → {$newEtat}",
            'action_url' => route('males.show', $male),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'info',
            'title' => 'État mis à jour !',
            'message' => "État de '{$male->nom}' : {$newEtat}",
            'action_url' => route('males.index'),
            'duration' => 4000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->back()
            ->with('success', 'État mis à jour avec succès !');
    }

    public function checkCode(Request $request)
    {
        $exists = Male::where('code', $request->code)->exists();
        return response()->json(['available' => !$exists]);
    }
}
