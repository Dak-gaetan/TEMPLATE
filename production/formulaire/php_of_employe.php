<?php
require_once '../../config/config_db.php';

// Récupération des listes pour les select
$postes = $pdo->query("SELECT id_poste, libelle FROM poste")->fetchAll();
$services = $pdo->query("SELECT id_service, libelle FROM service")->fetchAll();
$dispos = $pdo->query("SELECT id_disponibilite, libelle FROM disponibilite")->fetchAll();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des champs
    $fields = ['nom', 'prenom', 'email', 'confirm_email', 'telephone', 'id_poste', 'id_service', 'id_disponibilite', 'username', 'password', 'confirm_password'];
    foreach ($fields as $field) {
        $$field = trim($_POST[$field] ?? '');
    }

    // Vérification des champs obligatoires
    if (in_array('', [$nom, $prenom, $email, $confirm_email, $telephone, $id_poste, $id_service, $id_disponibilite, $username, $password, $confirm_password], true)) {
        $message = "<div class='alert alert-warning'>Veuillez remplir tous les champs.</div>";
    } elseif ($email !== $confirm_email) {
        $message = "<div class='alert alert-warning'>Les emails ne correspondent pas.</div>";
    } elseif ($password !== $confirm_password) {
        $message = "<div class='alert alert-warning'>Les mots de passe ne correspondent pas.</div>";
    } else {
        // Vérifier si l'email existe déjà
        $check = $pdo->prepare("SELECT COUNT(*) FROM personnel WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetchColumn() > 0) {
            $message = "<div class='alert alert-danger'>Cet email existe déjà dans la base.</div>";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO personnel 
                (nom, prenom, email, tel, id_poste, id_service, id_disponibilite, nom_utilisateur, mot_de_passe) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $ok = $stmt->execute([
                $nom, $prenom, $email, $telephone, $id_poste, $id_service, $id_disponibilite, $username, $password_hash
            ]);
            if ($ok) {
                header("Location: /TEMPLATE/production/table/compte_table.php");
                exit;
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de l'ajout du compte.</div>";
            }
        }
    }
}
?>