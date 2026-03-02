<?php

namespace App\Http\Controllers;

use App\Models\Saillie;
use App\Models\Femelle;
use App\Models\Male;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\Notifiable;

class SaillieController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saillies = Saillie::with(['femelle', 'male'])->latest()->paginate(10);
        
        return view('saillies.index', compact('saillies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $femelles = Femelle::all();
        $males = Male::all();
        
        return view('saillies.create', compact('femelles', 'males'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'male_id' => 'required|exists:males,id',
            'date_saillie' => 'required|date',
            'date_palpage' => 'nullable|date',
            'palpation_resultat' => 'nullable|in:+,-',
        ]);

        $saillie = new Saillie();
        $saillie->femelle_id = $request->femelle_id;
        $saillie->male_id = $request->male_id;
        $saillie->date_saillie = $request->date_saillie;
        $saillie->date_palpage = $request->date_palpage;
        $saillie->palpation_resultat = $request->palpation_resultat;
        $saillie->date_mise_bas_theorique = Carbon::parse($request->date_saillie)->addDays(31);
        $saillie->save();

        // Get femelle and male names for notification
        $femelle = Femelle::find($request->femelle_id);
        $male = Male::find($request->male_id);

        // Create notification
        $this->notifyUser([
            'type' => 'success',
            'title' => 'Nouvelle Saillie Enregistrée',
            'message' => "Saillie entre {$femelle->nom} et {$male->nom} planifiée pour le " . Carbon::parse($request->date_saillie)->format('d/m/Y'),
            'action_url' => route('saillies.show', $saillie),
        ]);

        // Flash toast for real-time display
        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Succès !',
            'message' => "Saillie entre {$femelle->nom} et {$male->nom} enregistrée avec succès.",
            'action_url' => route('saillies.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('saillies.index')
            ->with('success', 'Saillie enregistrée avec succès ✅');
    }

    /**
     * Display the specified resource.
     */
    public function show(Saillie $saillie)
    {
        $saillie->load(['femelle', 'male']);
        return view('saillies.show', compact('saillie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Saillie $saillie)
    {
        $femelles = Femelle::all();
        $males = Male::all();
        
        return view('saillies.edit', compact('saillie', 'femelles', 'males'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Saillie $saillie)
    {
        $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'male_id' => 'required|exists:males,id',
            'date_saillie' => 'required|date',
            'date_palpage' => 'nullable|date',
            'palpation_resultat' => 'nullable|in:+,-',
        ]);

        $oldFemelle = Femelle::find($saillie->femelle_id);
        $oldMale = Male::find($saillie->male_id);

        $saillie->update([
            'femelle_id' => $request->femelle_id,
            'male_id' => $request->male_id,
            'date_saillie' => $request->date_saillie,
            'date_palpage' => $request->date_palpage,
            'palpation_resultat' => $request->palpation_resultat,
            'date_mise_bas_theorique' => Carbon::parse($request->date_saillie)->addDays(31),
        ]);

        $newFemelle = Femelle::find($request->femelle_id);
        $newMale = Male::find($request->male_id);

        // Create notification
        $this->notifyUser([
            'type' => 'info',
            'title' => 'Saillie Modifiée',
            'message' => "Saillie mise à jour : {$newFemelle->nom} × {$newMale->nom} (date: " . Carbon::parse($request->date_saillie)->format('d/m/Y') . ")",
            'action_url' => route('saillies.show', $saillie),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'info',
            'title' => 'Mise à jour !',
            'message' => "Saillie entre {$newFemelle->nom} et {$newMale->nom} modifiée avec succès.",
            'action_url' => route('saillies.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('saillies.index')
            ->with('success', 'Saillie mise à jour avec succès ✅');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Saillie $saillie)
    {
        $femelle = Femelle::find($saillie->femelle_id);
        $male = Male::find($saillie->male_id);

        $saillie->delete();

        // Create notification
        $this->notifyUser([
            'type' => 'warning',
            'title' => 'Saillie Supprimée',
            'message' => "Saillie entre {$femelle->nom} et {$male->nom} a été supprimée du système.",
            'action_url' => route('saillies.index'),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'warning',
            'title' => 'Supprimé !',
            'message' => "Saillie entre {$femelle->nom} et {$male->nom} supprimée avec succès.",
            'duration' => 5000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('saillies.index')
            ->with('success', 'Saillie supprimée avec succès ✅');
    }

    /**
     * Update palpation result
     */
    public function updatePalpation(Request $request, Saillie $saillie)
    {
        $request->validate([
            'palpation_resultat' => 'required|in:+,-',
            'date_palpage' => 'required|date',
        ]);

        $oldResult = $saillie->palpation_resultat;
        $saillie->palpation_resultat = $request->palpation_resultat;
        $saillie->date_palpage = $request->date_palpage;
        $saillie->save();

        $femelle = Femelle::find($saillie->femelle_id);
        $male = Male::find($saillie->male_id);

        $resultText = $request->palpation_resultat === '+' ? 'Positif (Gestante)' : 'Négatif';
        $type = $request->palpation_resultat === '+' ? 'success' : 'warning';

        // Create notification
        $this->notifyUser([
            'type' => $type,
            'title' => 'Palpation Réalisée',
            'message' => "Palpation de {$femelle->nom} × {$male->nom} : {$resultText}",
            'action_url' => route('saillies.show', $saillie),
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => $type,
            'title' => 'Palpation enregistrée !',
            'message' => "Résultat : {$resultText}",
            'action_url' => route('saillies.show', $saillie),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('saillies.show', $saillie)
            ->with('success', 'Palpation enregistrée avec succès ✅');
    }
}