<?php
require_once '../../config/config_db.php';
// Récupérer les employés actifs ayant un badge
$employes = [];
$sql = "SELECT e.id_employe, e.nom, e.prenom 
        FROM employe e
        INNER JOIN badge b ON e.id_employe = b.id_employe
        WHERE e.deleted = 0";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employes[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_employe = $_POST['employeSelect'];
    $date = $_POST['pointageDate'];
    $heure_entree = $_POST['heureEntree'];
    $heure_sortie = $_POST['heureSortie'];

    // Calcul de la durée
    $duree = null;
    if ($heure_entree && $heure_sortie) {
        $entree = strtotime($heure_entree);
        $sortie = strtotime($heure_sortie);
        $diff = $sortie - $entree;
        if ($diff < 0) $diff += 24 * 3600;
        $hours = floor($diff / 3600);
        $minutes = floor(($diff % 3600) / 60);
        $seconds = $diff % 60;
        $duree = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    $check = $conn->prepare("SELECT COUNT(*) FROM pointage WHERE id_employe=? AND date=? AND heure_entree=?");
    $check->bind_param("iss", $id_employe, $date, $heure_entree);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count == 0) {
        $stmt = $conn->prepare("INSERT INTO pointage (id_employe, date, heure_entree, heure_sortie, duree_total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_employe, $date, $heure_entree, $heure_sortie, $duree);
        if ($stmt->execute()) {
            $success = "Pointage enregistré avec succès.";
        } else {
            $error = "Erreur lors de l'enregistrement.";
        }
        $stmt->close();
    } else {
        $error = "Un pointage existe déjà pour cet employé à cette date et heure d'entrée.";
    }
}

// Récupérer les pointages avec les infos employé
$pointages = [];
$sql = "SELECT p.id_pointage, e.nom, e.prenom, p.date, p.heure_entree, p.heure_sortie
        FROM pointage p
        JOIN employe e ON p.id_employe = e.id_employe
        ORDER BY p.date DESC, p.heure_entree DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Calcul de la durée si heure sortie présente
        if ($row['heure_sortie']) {
            $entree = strtotime($row['heure_entree']);
            $sortie = strtotime($row['heure_sortie']);
            $duree = $sortie - $entree;
            if ($duree < 0) $duree += 24 * 3600;
            $hours = floor($duree / 3600);
            $minutes = floor(($duree % 3600) / 60);
            $seconds = $duree % 60;
            $row['duree'] = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        } else {
            $row['duree'] = "-";
        }
        $pointages[] = $row;
    }
}
?>