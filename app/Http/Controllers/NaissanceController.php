<?php
// app/Http/Controllers/NaissanceController.php

namespace App\Http\Controllers;

use App\Models\Naissance;
use App\Models\Femelle;
use App\Models\Saillie;
use App\Models\MiseBas;
use Illuminate\Http\Request;
use App\Traits\Notifiable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NaissanceController extends Controller
{
    use Notifiable;

    public function index()
    {
        $naissances = Naissance::with(['femelle', 'saillie', 'user'])
            ->active()
            ->latest('date_naissance')
            ->paginate(15);

        $stats = [
            'total' => Naissance::active()->count(),
            'this_month' => Naissance::active()->thisMonth()->count(),
            'nb_vivant_total' => Naissance::active()->sum('nb_vivant'),
            'taux_survie_moyen' => Naissance::active()->get()->avg(function ($n) {
                return $n->taux_survie;
            }),
            'pending_verification' => Naissance::pendingVerification()->count(),
        ];

        return view('naissances.index', compact('naissances', 'stats'));
    }

    public function create()
    {
        // ✅ Get ALL femelles (not just Gestante/Allaitante)
        $femelles = Femelle::where('etat', '!=', 'Vide')
            ->orderBy('nom')
            ->get();

        // ✅ Get saillies with proper relationships
        $saillies = Saillie::with(['femelle', 'male'])
            ->whereHas('femelle', function ($q) {
                $q->where('etat', '!=', 'Vide');
            })
            ->orderBy('date_saillie', 'desc')
            ->get();

        $miseBas = MiseBas::latest()->take(10)->get();

        return view('naissances.create', compact('femelles', 'saillies', 'miseBas'));
    }

    public function store(Request $request)
    {
        // app/Http/Controllers/NaissanceController.php - store() method
        $validated = $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'saillie_id' => 'nullable|exists:saillies,id',
            'mise_bas_id' => 'nullable|exists:mises_bas,id',
            'date_naissance' => 'required|date',
            'heure_naissance' => 'nullable|date_format:H:i',
            'lieu_naissance' => 'nullable|string|max:100',
            'nb_vivant' => 'required|integer|min:0|max:20',
            'nb_mort_ne' => 'nullable|integer|min:0|max:20',
            'poids_moyen_naissance' => 'nullable|numeric|min:0|max:200',
            'etat_sante' => 'required|in:Excellent,Bon,Moyen,Faible',
            'observations' => 'nullable|string|max:1000',
            // ✅ FIX: Use after_or_equal instead of after, and add date_naissance first
            'date_sevrage_prevue' => 'nullable|date|after_or_equal:date_naissance',
            'date_vaccination_prevue' => 'nullable|date|after_or_equal:date_naissance',
            'sex_verified' => 'nullable|boolean',
        ], [
            // ✅ Add custom error messages
            'date_sevrage_prevue.after_or_equal' => 'La date de sevrage doit être après la date de naissance',
            'date_vaccination_prevue.after_or_equal' => 'La date de vaccination doit être après la date de naissance',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['nb_mort_ne'] = $validated['nb_mort_ne'] ?? 0;
        $validated['sex_verified'] = $validated['sex_verified'] ?? false;

        if (empty($validated['date_sevrage_prevue'])) {
            $validated['date_sevrage_prevue'] = Carbon::parse($validated['date_naissance'])
                ->addWeeks(6)
                ->format('Y-m-d');
        }

        $naissance = Naissance::create($validated);

        $femelle = Femelle::find($validated['femelle_id']);
        if ($femelle && $femelle->etat === 'Gestante') {
            $femelle->update(['etat' => 'Allaitante']);
        }

        $this->notifyUser([
            'type' => 'success',
            'title' => '🐰 Nouvelle Naissance Enregistrée',
            'message' => "Portée de {$femelle->nom}: {$validated['nb_vivant']} vivants, {$validated['nb_mort_ne']} mort-nés",
            'action_url' => route('naissances.show', $naissance),
        ]);

        return redirect()->route('naissances.index')
            ->with('success', 'Naissance enregistrée avec succès !');
    }

    public function show(Naissance $naissance)
    {
        $naissance->load(['femelle', 'saillie.male', 'miseBas', 'user']);
        return view('naissances.show', compact('naissance'));
    }

    public function edit(Naissance $naissance)
    {
        // ✅ Load ALL femelles for selection
        $femelles = Femelle::where('etat', '!=', 'Vide')
            ->orderBy('nom')
            ->get();

        // ✅ Load saillies with relationships
        $saillies = Saillie::with(['femelle', 'male'])
            ->orderBy('date_saillie', 'desc')
            ->get();

        $miseBas = MiseBas::latest()->get();

        return view('naissances.edit', compact('naissance', 'femelles', 'saillies', 'miseBas'));
    }

    public function update(Request $request, Naissance $naissance)
    {
        $validated = $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'saillie_id' => 'nullable|exists:saillies,id',
            'mise_bas_id' => 'nullable|exists:mises_bas,id',
            'date_naissance' => 'required|date',
            'heure_naissance' => 'nullable|date_format:H:i',
            'lieu_naissance' => 'nullable|string|max:100',
            'nb_vivant' => 'required|integer|min:0|max:20',
            'nb_mort_ne' => 'nullable|integer|min:0|max:20',
            'nb_sevre' => 'nullable|integer|min:0|max:20',
            'poids_moyen_naissance' => 'nullable|numeric|min:0|max:200',
            'etat_sante' => 'required|in:Excellent,Bon,Moyen,Faible',
            'observations' => 'nullable|string|max:1000',
            'date_sevrage_prevue' => 'nullable|date|after:date_naissance',
            'date_vaccination_prevue' => 'nullable|date|after:date_naissance',
            'sex_verified' => 'nullable|boolean',
        ]);

        // Check if verification status changed
        $wasUnverified = !$naissance->sex_verified;
        $isNowVerified = !empty($validated['sex_verified']);

        $naissance->update($validated);

        // Send notification if verification was just completed
        if ($wasUnverified && $isNowVerified) {
            $this->notifyUser([
                'type' => 'success',
                'title' => '✅ Vérification de Portée Complétée',
                'message' => "La portée de {$naissance->femelle->nom} a été vérifiée avec succès",
                'action_url' => route('naissances.show', $naissance),
            ]);
        }

        $this->notifyUser([
            'type' => 'info',
            'title' => '✏️ Naissance Modifiée',
            'message' => "Les informations de la naissance ont été mises à jour",
            'action_url' => route('naissances.show', $naissance),
        ]);

        return redirect()->route('naissances.index')
            ->with('success', 'Naissance modifiée avec succès !');
    }

    public function destroy(Naissance $naissance)
    {
        $femelleName = $naissance->femelle->nom ?? 'Inconnue';
        $naissance->delete();

        $this->notifyUser([
            'type' => 'warning',
            'title' => '🗑️ Naissance Supprimée',
            'message' => "La naissance de {$femelleName} a été supprimée",
            'action_url' => route('naissances.index'),
        ]);

        return redirect()->route('naissances.index')
            ->with('success', 'Naissance supprimée avec succès !');
    }

    public function archive(Naissance $naissance)
    {
        $femelleName = $naissance->femelle->nom ?? 'Inconnue';

        $naissance->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        // Notification
        $this->notifyUser([
            'type' => 'warning',
            'title' => '🗑️ Naissance Archivée',
            'message' => "La naissance de {$femelleName} a été archivée",
            'action_url' => route('naissances.index'),
        ]);

        return back()->with('success', 'Naissance archivée !');
    }

    public function restore(Naissance $naissance)
    {
        $naissance->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);
        return back()->with('success', 'Naissance restaurée !');
    }
}
