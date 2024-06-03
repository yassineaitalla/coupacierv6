<?php

// Importer la classe Kernel de l'application Symfony
use App\Kernel;

// Charger le fichier autoload_runtime.php à partir du répertoire vendor
// Ce fichier contient toutes les dépendances nécessaires à l'application
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Retourner une fonction anonyme qui prend un tableau $context en entrée
// Cette fonction sera utilisée comme point d'entrée pour l'application
return function (array $context) {
    // Créer une nouvelle instance du Kernel de l'application Symfony
    // En passant l'environnement (ex: dev, prod) et le mode de débogage
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
