<?php
$pdo = new PDO(
    'mysql:host=localhost;dbname=boutique_php', // chaîne de connexion
    'root', // utilisateur
    '', // mot de passe
    // Tableau d'options de connexion à la BDD
    [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);
