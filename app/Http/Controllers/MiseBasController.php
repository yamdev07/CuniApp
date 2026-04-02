<?php
namespace App\Http\Controllers;

use App\Models\MiseBas;
use App\Models\Femelle;
use App\Models\Saillie;
use Illuminate\Http\Request;
use App\Traits\Notifiable;
use Carbon\Carbon;
use App\Models\FirmAuditLog;

class MiseBasController extends Controller
{
    use Notifiable;

    public function index(Request $request)
    {
        $query = MiseBas::with(['femelle', 'saillie.male', 'naissances.lapereaux']);

        // 🔍 Filtre de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('femelle', function ($q) use ($search) {
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
        // ✅ CRITICAL: Check if user has a firm
        if (!auth()->user()->firm_id) {
            return back()
                ->withErrors(['error' => 'Votre compte n\'est associé à aucune entreprise. Contactez le support.'])
                ->withInput();
        }

        $femelles = Femelle::where(fn($q) => $q->where('user_id', auth()->id()))
            ->orderBy('nom')
            ->get();

        $saillies = Saillie::with(['femelle', 'male'])
            ->where(fn($q) => $q->where('user_id', auth()->id()))
            ->whereHas('femelle', fn($q) => $q->where('etat', 'Gestante'))
            ->orderBy('date_saillie', 'desc')
            ->get();

        return view('mises_bas.create', compact('femelles', 'saillies'));
    }

    public function store(Request $request)
    {
        // ✅ CRITICAL: Check if user has a firm (todo.md Step 4)
        if (!auth()->user()->firm_id) {
            return back()
                ->withErrors(['error' => 'Votre compte n\'est associé à aucune entreprise. Contactez le support.'])
                ->withInput();
        }

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

        // ✅ TODO.MD STEP 4: Pass null for firm_id to let Model handle auto-detection
        FirmAuditLog::log(
            null,  // ✅ Safe detection
            auth()->id(),
            'misebas_created',
            'nb_vivant',
            null,
            $miseBas->nb_vivant
        );

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
        // ✅ SECURITY FIX: Explicit Ownership Check (todo.md Step 4)
        if ($miseBas->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        $miseBas->load(['femelle', 'saillie.male', 'naissances.lapereaux']);

        return view('mises_bas.show', compact('miseBas'));
    }

    public function edit(MiseBas $miseBas)
    {
        // ✅ SECURITY FIX: Explicit Ownership Check (todo.md Step 4)
        if ($miseBas->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        // ✅ CRITICAL: Check if user has a firm (even for updates) (todo.md Step 4)
        if (!auth()->user()->firm_id) {
            return back()
                ->withErrors(['error' => 'Votre compte n\'est associé à aucune entreprise. Contactez le support.'])
                ->withInput();
        }

        $femelles = Femelle::all();
        $saillies = Saillie::with(['femelle', 'male'])->get();

        return view('mises_bas.edit', compact('miseBas', 'femelles', 'saillies'));
    }

    public function update(Request $request, MiseBas $miseBas)
    {
        // ✅ SECURITY FIX: Explicit Ownership Check (todo.md Step 4)
        if ($miseBas->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        // ✅ CRITICAL: Check if user has a firm (todo.md Step 4)
        if (!auth()->user()->firm_id) {
            return back()
                ->withErrors(['error' => 'Votre compte n\'est associé à aucune entreprise. Contactez le support.'])
                ->withInput();
        }

        $validated = $request->validate([
            'femelle_id' => 'required|exists:femelles,id',
            'saillie_id' => 'nullable|exists:saillies,id',
            'date_mise_bas' => 'required|date',
            'date_sevrage' => 'nullable|date|after:date_mise_bas',
            'poids_moyen_sevrage' => 'nullable|numeric|min:0|max:5',
        ]);

        $miseBas->update($validated);

        // ✅ TODO.MD STEP 4: Pass null for firm_id to let Model handle auto-detection
        FirmAuditLog::log(
            null,  // ✅ Safe detection
            auth()->id(),
            'misebas_updated',
            'date_mise_bas',
            $miseBas->getOriginal('date_mise_bas'),
            $miseBas->date_mise_bas
        );

        return redirect()->route('mises_bas.show', $miseBas)
            ->with('success', 'Mise bas mise à jour !');
    }

    public function destroy(MiseBas $miseBas)
    {
        // ✅ SECURITY FIX: Explicit Ownership Check (todo.md Step 4)
        if ($miseBas->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        // ✅ CRITICAL: Check if user has a firm (todo.md Step 4)
        if (!auth()->user()->firm_id) {
            return back()
                ->withErrors(['error' => 'Votre compte n\'est associé à aucune entreprise. Contactez le support.'])
                ->withInput();
        }

        $femelleName = $miseBas->femelle->nom ?? 'Inconnue';
        $totalLapereaux = $miseBas->total_lapereaux;

        // ✅ TODO.MD STEP 4: Pass null for firm_id to let Model handle auto-detection
        FirmAuditLog::log(
            null,  // ✅ Safe detection
            auth()->id(),
            'misebas_deleted',
            'id',
            $miseBas->id,
            null
        );

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