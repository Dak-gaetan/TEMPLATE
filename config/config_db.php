<?php
session_start();

try 
{
    $pdo = new PDO('mysql:host=localhost;dbname=template', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}
catch (Exception $erreur)
{
    die('Erreur: ' . $erreur->getMessage());
}

if (!$pdo) {
    echo json_encode(['found' => false, 'error' => 'Erreur connexion BDD']);
    exit;
}
?>