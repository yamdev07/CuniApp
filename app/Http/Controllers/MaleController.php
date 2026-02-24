<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Male;

class MaleController extends Controller
{
    public function index()
    {
        $males = Male::orderBy('created_at', 'desc')->paginate(10);
        return view('males.index', compact('males'));
    }

    public function create()
    {
        return view('males.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:males,code',
            'nom' => 'required|string|max:255',
            'race' => 'nullable|string|max:255',  // Changed from required
            'origine' => 'required|in:Interne,Achat',
            'date_naissance' => 'required|date',
            'etat' => 'required|in:Active,Inactive,Malade',  // Added Malade option
        ]);

        Male::create($request->all());
        return redirect()->route('males.index')->with('success', 'Mâle ajouté avec succès.');
    }

    public function edit(string $id)
    {
        $male = Male::findOrFail($id);
        return view('males.edit', compact('male'));
    }

    public function update(Request $request, string $id)
    {
        $male = Male::findOrFail($id);
        $request->validate([
            'code' => 'required|unique:males,code,' . $male->id,
            'nom' => 'required|string|max:255',
            'race' => 'nullable|string|max:255',  // Changed from required
            'origine' => 'required|in:Interne,Achat',
            'date_naissance' => 'required|date',
            'etat' => 'required|in:Active,Inactive,Malade',  // Added Malade option
        ]);

        $male->update($request->all());
        return redirect()->route('males.index')->with('success', 'Mâle modifié avec succès.');
    }

    public function destroy(string $id)
    {
        $male = Male::findOrFail($id);
        $male->delete();

        return redirect()->route('males.index')->with('success', 'Mâle supprimé avec succès.');
    }

    /**
     * Bascule l'état d'un mâle.
     */
    public function toggleEtat(Male $male)
    {
        $etats = ['Active', 'Inactive'];
        $currentIndex = array_search($male->etat, $etats);
        $nextIndex = ($currentIndex + 1) % count($etats);
        $male->etat = $etats[$nextIndex];
        $male->save();

        return redirect()->back()->with('success', 'État mis à jour avec succès !');
    }
}
