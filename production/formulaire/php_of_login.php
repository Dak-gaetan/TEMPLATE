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

            // Récupérer les infos du personnel lié
            $info_pers = $pdo->prepare("SELECT * FROM compte, personnel WHERE personnel.id_personnel = compte.id_personnel AND compte.id_compte = ?");
            $info_pers->execute([$_SESSION['user_id']]);
            $info_compte = $info_pers->fetch();

            $_SESSION['id_personnel'] = $info_compte['id_personnel'];
            $_SESSION['nom'] = $info_compte['nom'];
            $_SESSION['prenom'] = $info_compte['prenom'];
            $_SESSION['pseudo'] = $info_compte['pseudo'];

            header("Location: /TEMPLATE/production/index.php");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Pseudo ou mot de passe incorrect.</div>";
        }
    }
}
?>