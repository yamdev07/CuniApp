<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Afficher les Conditions Générales d'Utilisation
     */
    public function terms()
    {
        // Force le chargement de la session si nécessaire
        if (!session()->isStarted()) {
            session()->start();
        }
        
        return view('legal.terms');
    }

    /**
     * Afficher la Politique de Confidentialité
     */
    public function privacy()
    {
        if (!session()->isStarted()) {
            session()->start();
        }
        
        return view('legal.privacy');
    }
}