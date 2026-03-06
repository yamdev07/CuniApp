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

class NaissanceController extends Controller
{
    use Notifiable;

    public function index(Request $request)
    {
        $query = Naissance::with(['miseBas.femelle', 'lapereaux'])
            ->latest();

        // ✅ Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('miseBas.femelle', function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        // ✅ Filter by health status
        if ($request->has('etat_sante')) {
            $query->where('etat_sante', $request->etat_sante);
        }

        // ✅ Filter by verification status
        if ($request->has('sex_verified')) {
            $query->where('sex_verified', $request->boolean('sex_verified'));
        }

        $naissances = $query->paginate(15);

        $stats = [
            'total' => Naissance::count(),
            'this_month' => Naissance::whereHas(
                    'miseBas',
                    fn($q) =>
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

    public function create(Request $request)
    {
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

    public function store(Request $request)
    {
        // ✅ VALIDATION 1: Basic fields
        $validated = $request->validate([
            'mise_bas_id' => 'required|exists:mises_bas,id',
            'poids_moyen_naissance' => 'nullable|numeric|min:0|max:200',
            'etat_sante' => 'required|in:Excellent,Bon,Moyen,Faible',
            'observations' => 'nullable|string|max:1000',
            'date_sevrage_prevue' => 'nullable|date|after_or_equal:date_mise_bas',
            'date_vaccination_prevue' => 'nullable|date|after_or_equal:date_mise_bas',
        ], [
            'mise_bas_id.required' => 'La mise bas est obligatoire',
            'mise_bas_id.exists' => 'La mise bas sélectionnée n\'existe pas',
            'etat_sante.in' => 'L\'état de santé doit être Excellent, Bon, Moyen ou Faible',
        ]);

        $miseBas = MiseBas::with('femelle')->findOrFail($validated['mise_bas_id']);

        // ✅ VALIDATION 2: Get max allowed lapereaux from mise_bas
        $maxVivant = $miseBas->nb_vivant ?? 0;
        $maxMortNe = $miseBas->nb_mort_ne ?? 0;
        $maxTotal = $maxVivant + $maxMortNe;

        // If mise_bas doesn't have counts, allow creation but warn
        if ($maxTotal === 0) {
            // Allow but this is unusual - mise_bas should have counts
        }

        // ✅ VALIDATION 3: Lapereaux array
        $rabbitsRules = [
            'rabbits' => 'required|array|min:1',
            'rabbits.*.nom' => 'nullable|string|max:50',
            'rabbits.*.sex' => 'nullable|in:male,female',
            'rabbits.*.etat' => 'required|in:vivant,mort,vendu',
            'rabbits.*.poids_naissance' => 'nullable|numeric|min:0|max:200',
            'rabbits.*.etat_sante' => 'nullable|in:Excellent,Bon,Moyen,Faible',
            'rabbits.*.observations' => 'nullable|string|max:500',
            // ✅ FIXED: Only validate uniqueness if code is provided
            'rabbits.*.code' => 'nullable|string|max:20',
        ];

        $validated = array_merge($validated, $request->validate($rabbitsRules));

        // ✅ VALIDATION 4: Count validation against mise_bas
        $vivantCount = collect($validated['rabbits'])
            ->where('etat', 'vivant')
            ->count();
        $mortCount = collect($validated['rabbits'])
            ->where('etat', 'mort')
            ->count();
        $venduCount = collect($validated['rabbits'])
            ->where('etat', 'vendu')
            ->count();
        $totalRabbits = count($validated['rabbits']);

        $errors = [];

        // Check against mise_bas counts if they exist
        if ($maxTotal > 0) {
            if ($totalRabbits > $maxTotal) {
                $errors[] = "Vous essayez de créer {$totalRabbits} lapereaux mais la mise bas indique un maximum de {$maxTotal} ({$maxVivant} vivants + {$maxMortNe} morts-nés).";
            }

            if ($vivantCount > $maxVivant) {
                $errors[] = "Trop de lapereaux vivants déclarés ({$vivantCount}) par rapport à la mise bas ({$maxVivant}).";
            }

            if ($mortCount > $maxMortNe) {
                $errors[] = "Trop de lapereaux morts-nés déclarés ({$mortCount}) par rapport à la mise bas ({$maxMortNe}).";
            }
        }

        // ✅ VALIDATION 5: Check for duplicate codes if manually entered
        foreach ($validated['rabbits'] as $index => $rabbit) {
            if (!empty($rabbit['code']) && $rabbit['code'] !== 'Auto-généré') {
                if (!Lapereau::isCodeUnique($rabbit['code'])) {
                    $errors[] = "Le code '{$rabbit['code']}' pour le lapereau #" . ($index + 1) . " existe déjà.";
                }
            }
        }

        if (!empty($errors)) {
            return back()
                ->withErrors($errors)
                ->withInput();
        }

        // ✅ Calculate sevrage date if not provided (6 weeks from birth)
        if (empty($validated['date_sevrage_prevue'])) {
            $validated['date_sevrage_prevue'] = Carbon::parse($miseBas->date_mise_bas)
                ->addWeeks(6)
                ->format('Y-m-d');
        }

        DB::beginTransaction();
        try {
            // ✅ Create Naissance record (NO femelle_id - comes through mise_bas)
            $naissance = Naissance::create(array_merge($validated, [
                'user_id' => auth()->id(),
            ]));

            // ✅ Create Individual Lapereaux
            foreach ($validated['rabbits'] as $rabbitData) {
                $rabbitData['naissance_id'] = $naissance->id;

                // Auto-generate code if not provided
                if (empty($rabbitData['code'])) {
                    $rabbitData['code'] = Lapereau::generateUniqueCode();
                }

                Lapereau::create($rabbitData);
            }

            // ✅ Update femelle status to Allaitante
            $femelle = $miseBas->femelle;
            if ($femelle && $femelle->etat === 'Gestante') {
                $femelle->update(['etat' => 'Allaitante']);
            }

            $this->notifyUser([
                'type' => 'success',
                'title' => '🐰 Naissance & Lapereaux Enregistrés',
                'message' => "Portée de {$femelle->nom}: {$totalRabbits} lapereaux enregistrés",
                'action_url' => route('naissances.show', $naissance),
            ]);

            DB::commit();

            return redirect()->route('naissances.show', $naissance)
                ->with('success', 'Naissance et lapereaux enregistrés ! Sexe à vérifier après 10 jours.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Naissance $naissance, Request $request)
    {
        $naissance->load(['miseBas.femelle', 'miseBas.saillie.male', 'lapereaux']);

        $canVerifySex = $naissance->can_verify_sex;
        $daysUntilVerification = max(0, 10 - $naissance->jours_depuis_naissance);

        // ✅ Search lapereaux
        $lapereauxQuery = $naissance->lapereaux();

        if ($request->has('search_lapereau')) {
            $search = $request->search_lapereau;
            $lapereauxQuery->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        // ✅ Filter by status
        if ($request->has('filter_etat')) {
            $lapereauxQuery->where('etat', $request->filter_etat);
        }

        // ✅ Filter by sex
        if ($request->has('filter_sex')) {
            $lapereauxQuery->where('sex', $request->filter_sex);
        }

        // ✅ Paginate lapereaux (10 per page)
        $lapereaux = $lapereauxQuery->paginate(10);

        return view('naissances.show', compact('naissance', 'canVerifySex', 'daysUntilVerification', 'lapereaux'));
    }

    public function edit(Naissance $naissance)
    {
        $naissance->load(['miseBas.femelle', 'lapereaux']);
        $canVerifySex = $naissance->can_verify_sex;

        // ✅ ADD THIS: Calculate days until sex verification is allowed
        $daysUntilVerification = max(0, 10 - $naissance->jours_depuis_naissance);

        // ✅ Get max allowed from mise_bas
        $maxAllowed = $naissance->max_allowed_lapereaux;
        $currentCount = $naissance->lapereaux()->count();

        return view('naissances.edit', compact(
            'naissance',
            'canVerifySex',
            'daysUntilVerification',  // ✅ ADD THIS
            'maxAllowed',
            'currentCount'
        ));
    }

    public function update(Request $request, Naissance $naissance)
    {
        $validated = $request->validate([
            'poids_moyen_naissance' => 'nullable|numeric|min:0|max:200',
            'etat_sante' => 'required|in:Excellent,Bon,Moyen,Faible',
            'observations' => 'nullable|string|max:1000',
            'date_sevrage_prevue' => 'nullable|date|after:date_mise_bas',
            'date_vaccination_prevue' => 'nullable|date|after:date_mise_bas',
            'sex_verified' => 'nullable|boolean',

            // ✅ Lapereaux with individual health/weight
            'rabbits' => 'required|array|min:1',
            'rabbits.*.id' => 'nullable|exists:lapereaux,id',
            'rabbits.*.nom' => 'nullable|string|max:50',
            'rabbits.*.sex' => 'required|in:male,female',
            'rabbits.*.etat' => 'required|in:vivant,mort,vendu',
            'rabbits.*.poids_naissance' => 'nullable|numeric|min:0|max:200',
            'rabbits.*.etat_sante' => 'nullable|in:Excellent,Bon,Moyen,Faible',
            'rabbits.*.observations' => 'nullable|string|max:500',
            'rabbits.*.code' => 'nullable|string|max:20',
        ]);

        // ✅ Check if sex verification is allowed (10+ days)
        if (!$naissance->can_verify_sex && $request->has('sex_verified')) {
            return back()->withErrors([
                'sex_verified' => 'La vérification du sexe n\'est possible qu\'après 10 jours.'
            ])->withInput();
        }

        // ✅ VALIDATION: Count against mise_bas
        $maxAllowed = $naissance->max_allowed_lapereaux;
        $newCount = count($validated['rabbits']);

        if ($maxAllowed > 0 && $newCount > $maxAllowed) {
            return back()->withErrors([
                'rabbits' => "Vous ne pouvez pas créer plus de {$maxAllowed} lapereaux pour cette mise bas."
            ])->withInput();
        }

        // ✅ VALIDATION: Check unique codes
        foreach ($validated['rabbits'] as $index => $rabbit) {
            if (!empty($rabbit['code'])) {
                $excludeId = $rabbit['id'] ?? null;
                if (!Lapereau::isCodeUnique($rabbit['code'], $excludeId)) {
                    return back()->withErrors([
                        "rabbits.{$index}.code" => "Le code '{$rabbit['code']}' existe déjà."
                    ])->withInput();
                }
            }
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
                        // Auto-generate code if empty
                        if (empty($rabbitData['code'])) {
                            $rabbitData['code'] = $lapereau->code;
                        }
                        $lapereau->update($rabbitData);
                        $existingIds[] = $lapereau->id;
                    }
                } else {
                    // Create new (with auto-generated code)
                    $rabbitData['naissance_id'] = $naissance->id;
                    if (empty($rabbitData['code'])) {
                        $rabbitData['code'] = Lapereau::generateUniqueCode();
                    }
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
            return back()
                ->withErrors(['error' => 'Erreur: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // ✅ NEW: Check lapereau code availability (AJAX)
    public function checkCode(Request $request)
    {
        $exists = Lapereau::where('code', $request->code)->exists();
        return response()->json(['available' => !$exists]);
    }

    public function destroy(Naissance $naissance)
    {
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
}
