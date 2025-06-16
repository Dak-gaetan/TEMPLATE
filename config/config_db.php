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
?>