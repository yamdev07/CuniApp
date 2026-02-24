<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Male;
use App\Models\Femelle;

class LapinController extends Controller
{


    /**
     * Affiche la liste des femelles et mâles.
     */

    public function index()
    {
        $femelles = Femelle::paginate(10, ['*'], 'femelles_page');
        $males = Male::paginate(10, ['*'], 'males_page');

        return view('lapins.index', compact('femelles', 'males'));
    }



    // Affiche le formulaire
    public function create()
    {
        return view('lapins.create'); // Vue du formulaire
    }

    // Stocke le lapin
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:male,femelle',
            'nom' => 'required|string|max:255',
            'race' => 'required|string|max:255',
            'origine' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'etat' => 'required|in:active,inactive',
        ]);

        // Génération automatique du code unique
        $code = ($request->type === 'male' ? 'MAL-' : 'FEM-') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        if ($request->type === 'male') {
            Male::create([
                'code' => $code,
                'nom' => $request->nom,
                'race' => $request->race,
                'origine' => $request->origine,
                'date_naissance' => $request->date_naissance,
                'etat' => $request->etat
            ]);

            return redirect()->route('males.index')->with('success', 'Mâle créé avec succès.');
        } else {
            Femelle::create([
                'code' => $code,
                'nom' => $request->nom,
                'race' => $request->race,
                'origine' => $request->origine,
                'date_naissance' => $request->date_naissance,
                'etat' => $request->etat
            ]);

            return redirect()->route('femelles.index')->with('success', 'Femelle créée avec succès.');
        }
    }
}
