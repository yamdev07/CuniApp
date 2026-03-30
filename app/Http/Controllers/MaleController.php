<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Male;
use App\Traits\Notifiable;
use App\Models\FirmAuditLog;

class MaleController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Male::query();

        // Filtre de recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhere('race', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->get('etat'));
        }

        $males = $query->latest()->paginate(10);

        return view('males.index', compact('males'));
    }

    /**
     * Show the form for creating a new resource.
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ TODO.MD STEP 4: CRITICAL - Check if user has a firm
        if (!auth()->user()->firm_id) {
            return back()
                ->withErrors(['error' => 'Votre compte n\'est associé à aucune entreprise. Contactez le support.'])
                ->withInput();
        }

        $request->validate([
            'code' => 'required|unique:males,code',
            'nom' => 'required|string|max:255',
            'race' => 'nullable|string|max:255',
            'origine' => 'required|in:Interne,Achat',
            'date_naissance' => 'nullable|date',
            'etat' => 'required|in:Active,Inactive,Malade,vendu',
        ]);

        // ✅ BelongsToUser Trait will automatically assign user_id and firm_id
        $male = Male::create($request->all());

        // ✅ TODO.MD STEP 4: Pass null for firm_id to let Model handle auto-detection
        FirmAuditLog::log(
            null,  // ✅ Let the model auto-detect from authenticated user
            auth()->id(),
            'male_created',
            'code',
            null,
            $male->code
        );

        // Create notification using Notifiable trait
        $this->notifyUser([
            'type' => 'success',
            'title' => 'Nouveau Mâle Enregistré',
            'message' => "Mâle '{$male->nom}' ({$male->code}) ajouté à l'élevage.",
            'action_url' => route('males.show', $male),
        ]);

        // Flash toast for real-time UI feedback
        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Succès !',
            'message' => "Mâle '{$male->nom}' enregistré avec succès.",
            'action_url' => route('males.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('males.index')
            ->with('success', 'Mâle ajouté avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $male = Male::findOrFail($id);

        // ✅ SECURITY FIX: Explicit Ownership Check
        if ($male->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        return view('males.show', compact('male'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $male = Male::findOrFail($id);

        // ✅ SECURITY FIX: Explicit Ownership Check
        if ($male->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        return view('males.edit', compact('male'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $male = Male::findOrFail($id);

        // ✅ SECURITY FIX: Explicit Ownership Check
        if ($male->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        // ✅ TODO.MD STEP 4: Check if user has a firm (even for updates)
        if (!auth()->user()->firm_id) {
            return back()
                ->withErrors(['error' => 'Votre compte n\'est associé à aucune entreprise. Contactez le support.'])
                ->withInput();
        }

        $request->validate([
            'code' => 'required|unique:males,code,' . $male->id,
            'nom' => 'required|string|max:255',
            'race' => 'nullable|string|max:255',
            'origine' => 'required|in:Interne,Achat',
            'date_naissance' => 'required|date',
            'etat' => 'required|in:Active,Inactive,Malade,vendu',
        ]);

        $oldNom = $male->nom;
        $male->update($request->all());

        // ✅ TODO.MD STEP 4: Pass null for firm_id to let Model handle auto-detection
        FirmAuditLog::log(
            null,  // ✅ Safe detection
            auth()->id(),
            'male_updated',
            'nom',
            $oldNom,
            $male->nom
        );

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

        // ✅ SECURITY FIX: Explicit Ownership Check
        if ($male->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        $maleName = $male->nom;
        $male->delete();

        // ✅ TODO.MD STEP 4: Pass null for firm_id to let Model handle auto-detection
        FirmAuditLog::log(
            null,  // ✅ Safe detection
            auth()->id(),
            'male_deleted',
            'nom',
            $maleName,
            null
        );

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
        // ✅ SECURITY FIX: Explicit Ownership Check
        if ($male->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        $etats = ['Active', 'Inactive', 'Malade'];
        $currentIndex = array_search($male->etat, $etats);
        $nextIndex = ($currentIndex + 1) % count($etats);
        $oldEtat = $male->etat;
        $newEtat = $etats[$nextIndex];

        $male->etat = $newEtat;
        $male->save();

        // ✅ TODO.MD STEP 4: Pass null for firm_id
        FirmAuditLog::log(
            null,
            auth()->id(),
            'male_state_toggled',
            'etat',
            $oldEtat,
            $newEtat
        );

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

    /**
     * Check if a code is available (AJAX)
     */
    public function checkCode(Request $request)
    {
        $exists = Male::where('code', $request->code)->exists();
        return response()->json(['available' => !$exists]);
    }
}
