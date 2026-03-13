<?php

namespace App\Http\Controllers;

use App\Models\MiseBas;
use App\Models\Femelle;
use App\Models\Saillie;
use Illuminate\Http\Request;
use App\Traits\Notifiable;
use Carbon\Carbon;

class MiseBasController extends Controller
{
    use Notifiable;

   public function index(Request $request)
{
    $query = MiseBas::with(['femelle', 'saillie.male', 'naissances.lapereaux']);

    // 🔍 Filtre de recherche
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('femelle', function($q) use ($search) {
            $q->where('nom', 'LIKE', "%{$search}%")
              ->orWhere('code', 'LIKE', "%{$search}%");
        });
    }

    // 📅 Filtre par date (optionnel)
    if ($request->filled('date_from')) {
        $query->whereDate('date_mise_bas', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('date_mise_bas', '<=', $request->date_to);
    }

    $misesBas = $query->latest()->paginate(15)->withQueryString();

    return view('mises_bas.index', compact('misesBas'));
}

    public function create()
    {
        $femelles = Femelle::where('etat', 'Gestante')
            ->orderBy('nom')
            ->get();

        $saillies = Saillie::with(['femelle', 'male'])
            ->whereHas('femelle', fn($q) => $q->where('etat', 'Gestante'))
            ->orderBy('date_saillie', 'desc')
            ->get();

        return view('mises_bas.create', compact('femelles', 'saillies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'saillie_id' => 'nullable|exists:saillies,id',
            'date_mise_bas' => 'required|date|before_or_equal:today',
            'date_sevrage' => 'nullable|date|after:date_mise_bas',
            'poids_moyen_sevrage' => 'nullable|numeric|min:0|max:5',
        ]);

        // Verify saillie belongs to same femelle
        if (!empty($validated['saillie_id'])) {
            $saillie = Saillie::find($validated['saillie_id']);
            if ($saillie->femelle_id !== $validated['femelle_id']) {
                return back()->withErrors(['saillie_id' => 'La saillie sélectionnée ne correspond pas à cette femelle.']);
            }
        }

        $miseBas = MiseBas::create($validated);

        // Update femelle status
        $femelle = Femelle::find($validated['femelle_id']);
        if ($femelle && $femelle->etat === 'Gestante') {
            $femelle->update(['etat' => 'Allaitante']);
        }

        $this->notifyUser([
            'type' => 'success',
            'title' => '🐰 Mise Bas Enregistrée',
            'message' => "Mise bas de {$femelle->nom} enregistrée. Ajoutez les lapereaux maintenant.",
            'action_url' => route('naissances.create', ['mise_bas_id' => $miseBas->id]),
        ]);

        return redirect()->route('naissances.create', ['mise_bas_id' => $miseBas->id])
            ->with('success', 'Mise bas enregistrée ! Maintenant, enregistrez les lapereaux.');
    }

    public function show(MiseBas $miseBas)
    {
        $miseBas->load(['femelle', 'saillie.male', 'naissances.lapereaux']);
        return view('mises_bas.show', compact('miseBas'));
    }

    public function edit(MiseBas $miseBas)
    {
        $femelles = Femelle::all();
        $saillies = Saillie::with(['femelle', 'male'])->get();
        return view('mises_bas.edit', compact('miseBas', 'femelles', 'saillies'));
    }

    public function update(Request $request, MiseBas $miseBas)
    {
        $validated = $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'saillie_id' => 'nullable|exists:saillies,id',
            'date_mise_bas' => 'required|date',
            'date_sevrage' => 'nullable|date|after:date_mise_bas',
            'poids_moyen_sevrage' => 'nullable|numeric|min:0|max:5',
        ]);

        $miseBas->update($validated);

        return redirect()->route('mises_bas.show', $miseBas)
            ->with('success', 'Mise bas mise à jour !');
    }

    public function destroy(MiseBas $miseBas)
    {
        $femelleName = $miseBas->femelle->nom ?? 'Inconnue';
        $totalLapereaux = $miseBas->total_lapereaux;

        $miseBas->delete();

        $this->notifyUser([
            'type' => 'warning',
            'title' => '🗑️ Mise Bas Supprimée',
            'message' => "Mise bas de {$femelleName} ({$totalLapereaux} lapereaux) supprimée.",
            'action_url' => route('mises_bas.index'),
        ]);

        return redirect()->route('mises_bas.index')
            ->with('success', 'Mise bas supprimée !');
    }
}
