<?php
namespace App\Http\Controllers;

use App\Models\Naissance;
use App\Models\Lapereau;
use App\Models\MiseBas;
use App\Models\Femelle;
use Illuminate\Http\Request;
use App\Traits\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NaissanceController extends Controller {
    use Notifiable;

    public function index() {
        $naissances = Naissance::with(['miseBas.femelle', 'lapereaux'])
            ->active()
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Naissance::active()->count(),
            'this_month' => Naissance::active()
                ->whereHas('miseBas', fn($q) => 
                    $q->whereMonth('date_mise_bas', now()->month)
                      ->whereYear('date_mise_bas', now()->year)
                )
                ->count(),
            'nb_vivant_total' => Lapereau::whereHas('naissance', fn($q) => $q->active())
                ->where('etat', 'vivant')
                ->count(),
            'taux_survie_moyen' => Naissance::active()->get()->avg(function ($n) {
                return $n->taux_survie ?? 0;
            }),
            'pending_verification' => Naissance::pendingVerification()->count(),
        ];

        return view('naissances.index', compact('naissances', 'stats'));
    }

    public function create(Request $request) {
        $miseBas = null;
        if ($request->has('mise_bas_id')) {
            $miseBas = MiseBas::with('femelle')->find($request->mise_bas_id);
        }

        $misesBas = MiseBas::with('femelle')
            ->whereDoesntHave('naissances')
            ->orderBy('date_mise_bas', 'desc')
            ->get();

        return view('naissances.create', compact('miseBas', 'misesBas'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'mise_bas_id' => 'required|exists:mises_bas,id',
            'poids_moyen_naissance' => 'nullable|numeric|min:0|max:200',
            'etat_sante' => 'required|in:Excellent,Bon,Moyen,Faible',
            'observations' => 'nullable|string|max:1000',
            'date_sevrage_prevue' => 'nullable|date|after_or_equal:date_mise_bas',
            'date_vaccination_prevue' => 'nullable|date|after_or_equal:date_mise_bas',
            // ✅ Lapereaux with REQUIRED code and sex (can be null for 10 days)
            'rabbits' => 'required|array|min:1',
            'rabbits.*.nom' => 'nullable|string|max:50',
            'rabbits.*.sex' => 'nullable|in:male,female', // ✅ NULL allowed initially
            'rabbits.*.etat' => 'required|in:vivant,mort,vendu',
        ]);

        $miseBas = MiseBas::with('femelle')->findOrFail($validated['mise_bas_id']);

        // ✅ Calculate sevrage date if not provided (6 weeks from birth)
        if (empty($validated['date_sevrage_prevue'])) {
            $validated['date_sevrage_prevue'] = Carbon::parse($miseBas->date_mise_bas)
                ->addWeeks(6)
                ->format('Y-m-d');
        }

        DB::beginTransaction();
        try {
            // Create Naissance record
            $naissance = Naissance::create($validated);

            // ✅ Create Individual Lapereaux with AUTO-GENERATED CODE
            foreach ($validated['rabbits'] as $rabbitData) {
                $rabbitData['naissance_id'] = $naissance->id;
                // Code is auto-generated in model boot() if empty
                Lapereau::create($rabbitData);
            }

            $this->notifyUser([
                'type' => 'success',
                'title' => '🐰 Naissance & Lapereaux Enregistrés',
                'message' => "Portée de {$miseBas->femelle->nom}: {$naissance->total_lapereaux} lapereaux (codes auto-générés)",
                'action_url' => route('naissances.show', $naissance),
            ]);

            DB::commit();
            return redirect()->route('naissances.show', $naissance)
                ->with('success', 'Naissance et lapereaux enregistrés ! Sexe à vérifier après 10 jours.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur: ' . $e->getMessage()]);
        }
    }

    public function show(Naissance $naissance) {
        $naissance->load(['miseBas.femelle', 'miseBas.saillie.male', 'lapereaux']);
        
        $canVerifySex = $naissance->can_verify_sex;
        $daysUntilVerification = max(0, 10 - $naissance->jours_depuis_naissance);
        
        return view('naissances.show', compact('naissance', 'canVerifySex', 'daysUntilVerification'));
    }

    public function edit(Naissance $naissance) {
        $naissance->load(['miseBas.femelle', 'lapereaux']);
        $canVerifySex = $naissance->can_verify_sex;
        
        return view('naissances.edit', compact('naissance', 'canVerifySex'));
    }

    public function update(Request $request, Naissance $naissance) {
        $validated = $request->validate([
            'poids_moyen_naissance' => 'nullable|numeric|min:0|max:200',
            'etat_sante' => 'required|in:Excellent,Bon,Moyen,Faible',
            'observations' => 'nullable|string|max:1000',
            'date_sevrage_prevue' => 'nullable|date|after:date_mise_bas',
            'date_vaccination_prevue' => 'nullable|date|after:date_mise_bas',
            'sex_verified' => 'nullable|boolean',
            // ✅ Lapereaux with sex verification
            'rabbits' => 'required|array|min:1',
            'rabbits.*.id' => 'nullable|exists:lapereaux,id',
            'rabbits.*.nom' => 'nullable|string|max:50',
            'rabbits.*.sex' => 'required|in:male,female', // ✅ REQUIRED when editing after 10 days
            'rabbits.*.etat' => 'required|in:vivant,mort,vendu',
        ]);

        // ✅ Check if sex verification is allowed (10+ days)
        if (!$naissance->can_verify_sex && $request->has('sex_verified')) {
            return back()->withErrors(['sex_verified' => 'La vérification du sexe n\'est possible qu\'après 10 jours.']);
        }

        DB::beginTransaction();
        try {
            // Update Naissance
            $wasUnverified = !$naissance->sex_verified;
            $naissance->update($validated);

            // ✅ Sync Lapereaux
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
                    // Create new (with auto-generated code)
                    $rabbitData['naissance_id'] = $naissance->id;
                    $newRabbit = Lapereau::create($rabbitData);
                    $existingIds[] = $newRabbit->id;
                }
            }

            // Delete removed rabbits
            Lapereau::where('naissance_id', $naissance->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            // Mark as verified if checkbox checked
            if ($wasUnverified && $naissance->sex_verified) {
                $naissance->markSexAsVerified();
                
                $this->notifyUser([
                    'type' => 'success',
                    'title' => '✅ Vérification de Portée Complétée',
                    'message' => "La portée de {$naissance->femelle->nom} a été vérifiée ({$naissance->total_lapereaux} lapereaux)",
                    'action_url' => route('naissances.show', $naissance),
                ]);
            }

            DB::commit();
            return redirect()->route('naissances.show', $naissance)
                ->with('success', 'Naissance mise à jour !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur: ' . $e->getMessage()]);
        }
    }

    public function destroy(Naissance $naissance) {
        $femelleName = $naissance->femelle->nom ?? 'Inconnue';
        $totalLapereaux = $naissance->total_lapereaux;
        
        $naissance->delete(); // Cascade deletes lapereaux

        $this->notifyUser([
            'type' => 'warning',
            'title' => '🗑️ Naissance Supprimée',
            'message' => "Naissance de {$femelleName} ({$totalLapereaux} lapereaux) supprimée",
            'action_url' => route('naissances.index'),
        ]);

        return redirect()->route('naissances.index')
            ->with('success', 'Naissance supprimée !');
    }

    public function archive(Naissance $naissance) {
        $naissance->update(['is_archived' => true, 'archived_at' => now()]);
        return back()->with('success', 'Naissance archivée !');
    }

    public function restore(Naissance $naissance) {
        $naissance->update(['is_archived' => false, 'archived_at' => null]);
        return back()->with('success', 'Naissance restaurée !');
    }
}