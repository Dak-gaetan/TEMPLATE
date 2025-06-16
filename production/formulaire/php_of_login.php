<?php

$message = '';

if (isset($_POST['valider'])) {
    $pseudo = $_POST['pseudo'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($pseudo) || empty($password)) {
        $message = "<div class='alert alert-warning'>Veuillez remplir tous les champs.</div>";
    } else {
        // On récupère le compte avec ce pseudo
        $stmt = $pdo->prepare("SELECT * FROM compte WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $compte = $stmt->fetch();

        // On vérifie le mot de passe avec password_verify
        if ($compte && password_verify($password, $compte['mot_de_passe'])) {
            $_SESSION['user_id'] = $compte['id_compte'];
            $_SESSION['pseudo'] = $compte['pseudo'];
            $_SESSION['id_personnel'] = $compte['id_personnel'];
            $_SESSION['id_niveau'] = $compte['id_niveau'];

            header("Location: /TEMPLATE/production/index.php");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Pseudo ou mot de passe incorrect.</div>";
        }
    }
}
?>