<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Male;
use App\Models\Femelle;
use App\Traits\Notifiable;

class LapinController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $femelles = Femelle::latest()->paginate(10, ['*'], 'femelles_page');
        $males = Male::latest()->paginate(10, ['*'], 'males_page');

        return view('lapins.index', compact('femelles', 'males'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lapins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:male,femelle',  // ✅ Already correct
            'nom' => 'required|string|max:255',
            'race' => 'required|string|max:255',
            'origine' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'etat' => 'required|in:active,inactive',  // ✅ This is correct (form sends English)
        ]);

        // Generate unique code
        $code = ($request->type === 'male' ? 'MAL-' : 'FEM-') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        if ($request->type === 'male') {
            $male = Male::create([
                'code' => $code,
                'nom' => $request->nom,
                'race' => $request->race,
                'origine' => $request->origine,
                'date_naissance' => $request->date_naissance,
                'etat' => $request->etat === 'active' ? 'Active' : 'Inactive',  // ✅ Converts to French
            ]);

            // Create notification
            $this->notifyUser([
                'type' => 'success',
                'title' => 'Nouveau Lapin Enregistré',
                'message' => "Mâle '{$male->nom}' ({$male->code}) ajouté à l'élevage.",
                'action_url' => route('males.show', $male),
            ]);

            // Flash toast
            session()->flash('toast', [
                'type' => 'success',
                'title' => 'Succès !',
                'message' => "Mâle '{$male->nom}' créé avec succès.",
                'action_url' => route('males.index'),
                'duration' => 6000,
                'timestamp' => now()->toIso8601String()
            ]);

            return redirect()->route('males.index')
                ->with('success', 'Mâle créé avec succès.');
        } else {
            // ✅ FIXED: For females, default to 'Active' (not 'active')
            $femelle = Femelle::create([
                'code' => $code,
                'nom' => $request->nom,
                'race' => $request->race,
                'origine' => $request->origine,
                'date_naissance' => $request->date_naissance,
                'etat' => 'Active',  // ✅ Femelles always start as 'Active' (French)
            ]);

            // Create notification
            $this->notifyUser([
                'type' => 'success',
                'title' => 'Nouvelle Lapine Enregistrée',
                'message' => "Femelle '{$femelle->nom}' ({$femelle->code}) ajoutée à l'élevage.",
                'action_url' => route('femelles.show', $femelle),
            ]);

            // Flash toast
            session()->flash('toast', [
                'type' => 'success',
                'title' => 'Succès !',
                'message' => "Femelle '{$femelle->nom}' créée avec succès.",
                'action_url' => route('femelles.index'),
                'duration' => 6000,
                'timestamp' => now()->toIso8601String()
            ]);

            return redirect()->route('femelles.index')
                ->with('success', 'Femelle créée avec succès.');
        }
    }
}
