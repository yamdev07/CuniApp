<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Male;
use App\Models\Femelle;
use App\Traits\Notifiable;
use Illuminate\Pagination\LengthAwarePaginator;

class LapinController extends Controller
{
    use Notifiable;

    /**
     * Display a listing of the resource.
     */
    //  public function index(Request $request)
    // {
    //     // ✅ Fonction helper pour appliquer les filtres communs
    //     $applyFilters = function ($query, Request $request) {
    //         // Filtre de recherche texte
    //         if ($request->filled('search')) {
    //             $search = $request->search;
    //             $query->where(function($q) use ($search) {
    //                 $q->where('nom', 'LIKE', "%{$search}%")
    //                   ->orWhere('code', 'LIKE', "%{$search}%")
    //                   ->orWhere('race', 'LIKE', "%{$search}%");
    //             });
    //         }

    //         // Filtre par état
    //         if ($request->filled('etat')) {
    //             $query->where('etat', $request->etat);
    //         }

    //         // Filtre par origine
    //         if ($request->filled('origine')) {
    //             $query->where('origine', $request->origine);
    //         }
    //     };

    //     // ✅ Query pour les femelles avec filtres
    //     $femellesQuery = Femelle::query();
    //     $applyFilters($femellesQuery, $request);

    //     // ✅ Query pour les mâles avec filtres
    //     $malesQuery = Male::query();
    //     $applyFilters($malesQuery, $request);

    //     // ✅ Filtre par type (male/female)
    //     if ($request->filled('type')) {
    //         if ($request->type === 'male') {
    //             $femellesQuery->whereRaw('1 = 0'); // Aucun résultat pour femelles
    //         } elseif ($request->type === 'female') {
    //             $malesQuery->whereRaw('1 = 0'); // Aucun résultat pour mâles
    //         }
    //     }

    //     // ✅ Pagination avec préservation des paramètres
    //     $femelles = $femellesQuery->latest()->paginate(10, ['*'], 'femelles_page')
    //         ->appends($request->except('femelles_page'));
    //     $males = $malesQuery->latest()->paginate(10, ['*'], 'males_page')
    //         ->appends($request->except('males_page'));

    //     return view('lapins.index', compact('femelles', 'males'));
    // }




public function index(Request $request)
{
    // 1. Nettoyage
    $type = $request->has('type') ? strtolower(trim($request->type)) : '';
    $search = trim($request->get('search', ''));
    $etat = trim($request->get('etat', ''));
    $origine = trim($request->get('origine', ''));

    $femellesCollection = collect([]);
    $malesCollection = collect([]);

    $applyFilters = function ($query) use ($search, $etat, $origine) {
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('race', 'LIKE', "%{$search}%");
            });
        }
        if ($etat !== '') $query->where('etat', $etat);
        if ($origine !== '') $query->where('origine', $origine);
    };

   // Chargement Femelles
    if ($type === '' || $type === 'female') {
        $femellesCollection = Femelle::query()
            ->when($search !== '' || $etat !== '' || $origine !== '', fn($q) => $applyFilters($q))
            ->latest()->get()
            ->map(function ($item) {
                $item->type = 'female';
                return $item;
            });
    }

    // Chargement Mâles
    if ($type === '' || $type === 'male') {
        $malesCollection = Male::query()
            ->when($search !== '' || $etat !== '' || $origine !== '', fn($q) => $applyFilters($q))
            ->latest()->get()
            ->map(function ($item) {
                $item->type = 'male';
                return $item;
            });
    }

   
    $arrayFemelles = $femellesCollection->toArray();
    $arrayMales = $malesCollection->toArray();
    
    // Fusion des deux tableaux
    $allRabbitsArray = array_merge($arrayFemelles, $arrayMales);
    
    // Recréation de la collection
    $allRabbits = collect($allRabbitsArray)
        ->sortByDesc('created_at')
        ->values() // Réindexation propre
        ->map(function ($item) {
            // RECONVERSION EN OBJET pour que la vue fonctionne avec $lapin->code
            return (object) $item;
        });


    // Pagination (le reste est identique)
    $perPage = 20;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $currentItems = $allRabbits->forPage($currentPage, $perPage);

    $paginatedRabbits = new LengthAwarePaginator(
        $currentItems,
        $allRabbits->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => $request->query()]
    );

    return view('lapins.index', compact('paginatedRabbits'));
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
            'type' => 'required|in:male,femelle',
            'nom' => 'required|string|max:255',
            'race' => 'required|string|max:255',
            'origine' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'etat' => 'required|in:active,inactive',
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
                'etat' => $request->etat === 'active' ? 'Active' : 'Inactive',
            ]);

            $this->notifyUser([
                'type' => 'success',
                'title' => 'Nouveau Lapin Enregistré',
                'message' => "Mâle '{$male->nom}' ({$male->code}) ajouté à l'élevage.",
                'action_url' => route('males.show', $male),
            ]);

            session()->flash('toast', [
                'type' => 'success',
                'title' => 'Succès !',
                'message' => "Mâle '{$male->nom}' créé avec succès.",
                'action_url' => route('lapins.index'),
                'duration' => 6000,
                'timestamp' => now()->toIso8601String()
            ]);

            return redirect()->route('lapins.index')
                ->with('success', 'Mâle créé avec succès.');
        } else {
            $femelle = Femelle::create([
                'code' => $code,
                'nom' => $request->nom,
                'race' => $request->race,
                'origine' => $request->origine,
                'date_naissance' => $request->date_naissance,
                'etat' => 'Active',
            ]);

            $this->notifyUser([
                'type' => 'success',
                'title' => 'Nouvelle Lapine Enregistrée',
                'message' => "Femelle '{$femelle->nom}' ({$femelle->code}) ajoutée à l'élevage.",
                'action_url' => route('femelles.show', $femelle),
            ]);

            session()->flash('toast', [
                'type' => 'success',
                'title' => 'Succès !',
                'message' => "Femelle '{$femelle->nom}' créée avec succès.",
                'action_url' => route('lapins.index'),
                'duration' => 6000,
                'timestamp' => now()->toIso8601String()
            ]);

            return redirect()->route('lapins.index')
                ->with('success', 'Femelle créée avec succès.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Try to find in both tables
        $male = Male::find($id);
        $femelle = Femelle::find($id);

        if ($male) {
            $lapin = (object) [
                'id' => $male->id,
                'type' => 'male',
                'code' => $male->code,
                'nom' => $male->nom,
                'race' => $male->race,
                'origine' => $male->origine,
                'date_naissance' => $male->date_naissance,
                'etat' => strtolower($male->etat),
            ];
        } elseif ($femelle) {
            $lapin = (object) [
                'id' => $femelle->id,
                'type' => 'femelle',
                'code' => $femelle->code,
                'nom' => $femelle->nom,
                'race' => $femelle->race,
                'origine' => $femelle->origine,
                'date_naissance' => $femelle->date_naissance,
                'etat' => strtolower($femelle->etat),
            ];
        } else {
            abort(404);
        }

        return view('lapins.edit', compact('lapin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:male,femelle',
            'nom' => 'required|string|max:255',
            'race' => 'required|string|max:255',
            'origine' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'etat' => 'required|in:active,inactive',
        ]);

        $male = Male::find($id);
        $femelle = Femelle::find($id);

        if ($male) {
            $oldNom = $male->nom;
            $male->update([
                'nom' => $request->nom,
                'race' => $request->race,
                'origine' => $request->origine,
                'date_naissance' => $request->date_naissance,
                'etat' => $request->etat === 'active' ? 'Active' : 'Inactive',
            ]);

            $this->notifyUser([
                'type' => 'info',
                'title' => 'Mâle Modifié',
                'message' => "Informations du mâle '{$male->nom}' mises à jour.",
                'action_url' => route('males.show', $male),
            ]);

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
        } elseif ($femelle) {
            $oldNom = $femelle->nom;
            $femelle->update([
                'nom' => $request->nom,
                'race' => $request->race,
                'origine' => $request->origine,
                'date_naissance' => $request->date_naissance,
                'etat' => $request->etat === 'active' ? 'Active' : 'Inactive',
            ]);

            $this->notifyUser([
                'type' => 'info',
                'title' => 'Femelle Modifiée',
                'message' => "Informations de la femelle '{$femelle->nom}' mises à jour.",
                'action_url' => route('femelles.show', $femelle),
            ]);

            session()->flash('toast', [
                'type' => 'info',
                'title' => 'Mise à jour !',
                'message' => "Femelle '{$femelle->nom}' modifiée avec succès.",
                'action_url' => route('femelles.index'),
                'duration' => 6000,
                'timestamp' => now()->toIso8601String()
            ]);

            return redirect()->route('femelles.index')
                ->with('success', 'Femelle modifiée avec succès.');
        }

        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $male = Male::find($id);
        $femelle = Femelle::find($id);

        if ($male) {
            $maleName = $male->nom;
            $male->delete();

            $this->notifyUser([
                'type' => 'warning',
                'title' => 'Mâle Supprimé',
                'message' => "Le mâle '{$maleName}' a été supprimé de l'élevage.",
                'action_url' => route('males.index'),
            ]);

            session()->flash('toast', [
                'type' => 'warning',
                'title' => 'Supprimé !',
                'message' => "Mâle '{$maleName}' supprimé avec succès.",
                'duration' => 5000,
                'timestamp' => now()->toIso8601String()
            ]);

            return redirect()->route('males.index')
                ->with('success', 'Mâle supprimé avec succès.');
        } elseif ($femelle) {
            $femelleName = $femelle->nom;
            $femelle->delete();

            $this->notifyUser([
                'type' => 'warning',
                'title' => 'Femelle Supprimée',
                'message' => "La femelle '{$femelleName}' a été supprimée de l'élevage.",
                'action_url' => route('femelles.index'),
            ]);

            session()->flash('toast', [
                'type' => 'warning',
                'title' => 'Supprimée !',
                'message' => "Femelle '{$femelleName}' supprimée avec succès.",
                'duration' => 5000,
                'timestamp' => now()->toIso8601String()
            ]);

            return redirect()->route('femelles.index')
                ->with('success', 'Femelle supprimée avec succès.');
        }

        abort(404);
    }

    /**
     * Check if a lapereau code is available (AJAX)
     */
    public function checkCode(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response()->json(['available' => false], 400);
        }

        // Use the existing Lapereau::isCodeUnique() static method
        $isUnique = \App\Models\Lapereau::isCodeUnique($code);

        return response()->json(['available' => $isUnique]);
    }

    /**
     * Display the specified rabbit (unified view for male/female).
     */
    public function show($id)
    {
        // Try to find in both tables
        $male = Male::find($id);
        $femelle = Femelle::find($id);

        if ($male) {
            // Redirect to male show page for full details
            return redirect()->route('males.show', $male->id);
        } elseif ($femelle) {
            // Redirect to femelle show page for full details
            return redirect()->route('femelles.show', $femelle->id);
        }

        // If not found in either table
        abort(404, 'Lapin non trouvé');
    }
}
