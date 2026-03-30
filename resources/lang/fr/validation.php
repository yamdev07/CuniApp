<?php
// resources/lang/fr/validation.php - Extraits pour le password reset

return [

    'required' => 'Le champ :attribute est obligatoire.',
    'email' => 'Le champ :attribute doit être une adresse email valide.',
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'password' => [
        'letters' => 'Le champ :attribute doit contenir au moins une lettre.',
        'mixed' => 'Le champ :attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers' => 'Le champ :attribute doit contenir au moins un chiffre.',
        'symbols' => 'Le champ :attribute doit contenir au moins un symbole.',
        'uncompromised' => 'Le :attribute apparaît dans une fuite de données. Veuillez en choisir un autre.',
    ],

     'unique' => 'Le :attribute a déjà été utilisé.',
    // Noms de champs en français
    'attributes' => [
        'email' => 'adresse email',
        'password' => 'mot de passe',
        'code' => 'code',
        'password_confirmation' => 'confirmation du mot de passe',
        'token' => 'jeton de réinitialisation',
    ],

];