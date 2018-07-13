<?php
require_once __DIR__ . '/../include/init.php';
adminSecurity();

$query = 'DELETE FROM categorie WHERE id = ' . (int)$_GET['id'];
$pdo->exec($query);

setFlashMessage('La categorie est supprimée');
header('Location: categories.php');
die;