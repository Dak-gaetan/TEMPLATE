<?php
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code_badge = $_POST['code_badge'];
    $actif = $_POST['actif'];
    $date_emission = $_POST['date_emission'];
    $etat = $_POST['etat'];
    $id_personnel = $_POST['id_personnel'];

    $conn = new mysqli('localhost', 'utilisateur', 'motdepasse', 'nom_de_la_db');
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO badge (code_badge, actif, date_emission, etat, id_personnel) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissi", $code_badge, $actif, $date_emission, $etat, $id_personnel);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Badge ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger'>Erreur lors de l'ajout du badge : " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>