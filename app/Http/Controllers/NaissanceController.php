<?php
// app/Http/Controllers/NaissanceController.php
namespace App\Http\Controllers;

use App\Models\Naissance;
use App\Models\Lapereau; // ✅ Added
use App\Models\Femelle;
use App\Models\Saillie;
use App\Models\MiseBas;
use Illuminate\Http\Request;
use App\Traits\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NaissanceController extends Controller
{
    use Notifiable;

    public function index()
    {
        $naissances = Naissance::with(['femelle', 'lapereaux']) // ✅ Load lapereaux
            ->active()
            ->latest('date_naissance')
            ->paginate(15);

        $stats = [
            'total' => Naissance::active()->count(),
            'this_month' => Naissance::active()
                ->whereMonth('date_naissance', now()->month)
                ->whereYear('date_naissance', now()->year)
                ->count(),
            'nb_vivant_total' => Lapereau::whereHas('naissance', fn($q) => $q->active())
                ->where('etat', 'vivant')
                ->count(),
            'taux_survie_moyen' => Naissance::active()->get()->avg(function ($n) {
                return $n->taux_survie;
            }),
            'pending_verification' => Naissance::pendingVerification()->count(),
        ];

        return view('naissances.index', compact('naissances', 'stats'));
    }

    public function create()
    {
        $femelles = Femelle::where('etat', '!=', 'Vide')->orderBy('nom')->get();
        $saillies = Saillie::with(['femelle', 'male'])->whereHas('femelle', function ($q) {
            $q->where('etat', '!=', 'Vide');
        })->orderBy('date_saillie', 'desc')->get();
        $miseBas = MiseBas::latest()->take(10)->get();
        return view('naissances.create', compact('femelles', 'saillies', 'miseBas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'saillie_id' => 'nullable|exists:saillies,id',
            'mise_bas_id' => 'nullable|exists:mises_bas,id',
            'date_naissance' => 'required|date',
            'heure_naissance' => 'nullable|date_format:H:i',
            'lieu_naissance' => 'nullable|string|max:100',
            // 'nb_vivant' => 'required|integer', // ✅ Removed: Calculated from rabbits
            // 'nb_mort_ne' => 'nullable|integer', // ✅ Removed
            'poids_moyen_naissance' => 'nullable|numeric|min:0|max:200',
            'etat_sante' => 'required|in:Excellent,Bon,Moyen,Faible',
            'observations' => 'nullable|string|max:1000',
            'date_sevrage_prevue' => 'nullable|date|after_or_equal:date_naissance',
            'date_vaccination_prevue' => 'nullable|date|after_or_equal:date_naissance',
            // ✅ NEW: Validate rabbits array
            'rabbits' => 'nullable|array',
            'rabbits.*.sex' => 'required|in:male,female',
            'rabbits.*.nom' => 'nullable|string|max:50',
            'rabbits.*.code' => 'nullable|string|max:20|unique:lapereaux,code',
            'rabbits.*.etat' => 'required|in:vivant,mort,vendu',
        ], [
            'date_sevrage_prevue.after_or_equal' => 'La date de sevrage doit être après la date de naissance',
            'date_vaccination_prevue.after_or_equal' => 'La date de vaccination doit être après la date de naissance',
        ]);

        $validated['user_id'] = Auth::id();
        // Calculate nb_vivant from rabbits array
        $rabbits = $validated['rabbits'] ?? [];
        $validated['nb_vivant'] = count(array_filter($rabbits, fn($r) => $r['etat'] === 'vivant'));

        // Remove rabbits from validated data for Naissance creation
        unset($validated['rabbits']);

        if (empty($validated['date_sevrage_prevue'])) {
            $validated['date_sevrage_prevue'] = Carbon::parse($validated['date_naissance'])->addWeeks(6)->format('Y-m-d');
        }

        DB::beginTransaction();
        try {
            $naissance = Naissance::create($validated);

            // ✅ Create Individual Rabbits
            foreach ($rabbits as $rabbitData) {
                $rabbitData['naissance_id'] = $naissance->id;
                // Auto-generate code if empty
                if (empty($rabbitData['code'])) {
                    $rabbitData['code'] = 'LAP-' . strtoupper(uniqid());
                }
                Lapereau::create($rabbitData);
            }

            $femelle = Femelle::find($validated['femelle_id']);
            if ($femelle && $femelle->etat === 'Gestante') {
                $femelle->update(['etat' => 'Allaitante']);
            }

            $this->notifyUser([
                'type' => 'success',
                'title' => '🐰 Nouvelle Naissance Enregistrée',
                'message' => "Portée de {$femelle->nom}: {$validated['nb_vivant']} lapereaux enregistrés individuellement",
                'action_url' => route('naissances.show', $naissance),
            ]);

            DB::commit();
            return redirect()->route('naissances.index')->with('success', 'Naissance et lapereaux enregistrés avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()]);
        }
    }

    public function show(Naissance $naissance)
    {
        $naissance->load(['femelle', 'saillie.male', 'miseBas', 'user', 'lapereaux']); // ✅ Load lapereaux
        return view('naissances.show', compact('naissance'));
    }

    public function edit(Naissance $naissance)
    {
        $femelles = Femelle::where('etat', '!=', 'Vide')->orderBy('nom')->get();
        $saillies = Saillie::with(['femelle', 'male'])->orderBy('date_saillie', 'desc')->get();
        $miseBas = MiseBas::latest()->get();
        $naissance->load('lapereaux'); // ✅ Load existing rabbits
        return view('naissances.edit', compact('naissance', 'femelles', 'saillies', 'miseBas'));
    }

    public function update(Request $request, Naissance $naissance)
    {
        $validated = $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'date_naissance' => 'required|date',
            'heure_naissance' => 'nullable|date_format:H:i',
            'lieu_naissance' => 'nullable|string|max:100',
            'poids_moyen_naissance' => 'nullable|numeric|min:0|max:200',
            'etat_sante' => 'required|in:Excellent,Bon,Moyen,Faible',
            'observations' => 'nullable|string|max:1000',
            'date_sevrage_prevue' => 'nullable|date|after:date_naissance',
            'date_vaccination_prevue' => 'nullable|date|after:date_naissance',
            'sex_verified' => 'nullable|boolean',
            // ✅ Validate rabbits array for sync
            'rabbits' => 'nullable|array',
            'rabbits.*.id' => 'nullable|exists:lapereaux,id',
            'rabbits.*.sex' => 'required|in:male,female',
            'rabbits.*.nom' => 'nullable|string|max:50',
            'rabbits.*.code' => 'nullable|string|max:20|unique:lapereaux,code,' . ($rabbit['id'] ?? null),
            'rabbits.*.etat' => 'required|in:vivant,mort,vendu',
        ]);

        DB::beginTransaction();
        try {
            // Update Naissance
            $validated['nb_vivant'] = count(array_filter($validated['rabbits'] ?? [], fn($r) => $r['etat'] === 'vivant'));
            unset($validated['rabbits']);

            $wasUnverified = !$naissance->sex_verified;
            $naissance->update($validated);

            // ✅ Sync Rabbits (Simple implementation: delete all and recreate or update by ID)
            // For robustness, we'll update by ID if present, create if not.
            $incomingRabbits = $request->input('rabbits', []);
            $existingIds = [];

            foreach ($incomingRabbits as $rabbitData) {
                if (!empty($rabbitData['id'])) {
                    // Update existing
                    $lapereau = Lapereau::find($rabbitData['id']);
                    if ($lapereau && $lapereau->naissance_id === $naissance->id) {
                        $lapereau->update($rabbitData);
                        $existingIds[] = $lapereau->id;
                    }
                } else {
                    // Create new
                    $rabbitData['naissance_id'] = $naissance->id;
                    if (empty($rabbitData['code'])) {
                        $rabbitData['code'] = 'LAP-' . strtoupper(uniqid());
                    }
                    $newRabbit = Lapereau::create($rabbitData);
                    $existingIds[] = $newRabbit->id;
                }
            }

            // Delete removed rabbits
            Lapereau::where('naissance_id', $naissance->id)->whereNotIn('id', $existingIds)->delete();

            if ($wasUnverified && $naissance->sex_verified) {
                $this->notifyUser([
                    'type' => 'success',
                    'title' => '✅ Vérification de Portée Complétée',
                    'message' => "La portée de {$naissance->femelle->nom} a été vérifiée avec succès",
                    'action_url' => route('naissances.show', $naissance),
                ]);
            }

            DB::commit();
            return redirect()->route('naissances.index')->with('success', 'Naissance mise à jour avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur: ' . $e->getMessage()]);
        }
    }

    // ... (destroy, archive, restore methods remain similar)
    public function destroy(Naissance $naissance)
    {
        $femelleName = $naissance->femelle->nom ?? 'Inconnue';
        $naissance->delete(); // Cascade will delete lapereaux
        $this->notifyUser([
            'type' => 'warning',
            'title' => '🗑️ Naissance Supprimée',
            'message' => "La naissance de {$femelleName} a été supprimée",
            'action_url' => route('naissances.index'),
        ]);
        return redirect()->route('naissances.index')->with('success', 'Naissance supprimée avec succès !');
    }

    public function archive(Naissance $naissance)
    {
        // ... same as before
        $naissance->update(['is_archived' => true, 'archived_at' => now()]);
        return back()->with('success', 'Naissance archivée !');
    }

    public function restore(Naissance $naissance)
    {
        // ... same as before
        $naissance->update(['is_archived' => false, 'archived_at' => null]);
        return back()->with('success', 'Naissance restaurée !');
    }
}
