<?php
$message = '';
$ok = false;

// Récupération des personnels et des niveaux d'habilitation
$personnes = $pdo->query("SELECT id_personnel, nom, prenom FROM personnel ORDER BY nom, prenom")->fetchAll(PDO::FETCH_ASSOC);
$niveaux = $pdo->query("SELECT id_niveau, libelle FROM niveau ORDER BY libelle")->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if (isset($_POST['btn_valider'])) {
    $id_personnel = (int)($_POST['id_personnel'] );
    $pseudo = trim($_POST['pseudo']);
    $password = $_POST['password'] ;
    $confirm_password = $_POST['confirm_password'] ;
    $id_niveau = (int)($_POST['id_niveau']);

    if ($id_personnel && $pseudo && $password && $confirm_password && $id_niveau) {
        // Vérifier si le pseudo existe déjà
        $checkPseudo = $pdo->prepare("SELECT COUNT(*) FROM compte WHERE pseudo = ?");
        $checkPseudo->execute([$pseudo]);
        if ($checkPseudo->fetchColumn() > 0) {
            $message = "<div class='alert alert-danger'>Ce pseudo existe déjà. Veuillez en choisir un autre.</div>";
        } else {
            if ($password !== $confirm_password) {
                $message = "<div class='alert alert-warning'>Les mots de passe ne correspondent pas.</div>";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO compte (id_personnel, pseudo, mot_de_passe, id_niveau) VALUES (?, ?, ?, ?)");
                $ok = $stmt->execute([$id_personnel, $pseudo, $password_hash, $id_niveau]);
                if ($ok) {
                    $message = '<div class="alert alert-success">Compte créé avec succès</div>';
                } else {
                    $message = '<div class="alert alert-danger">Erreur lors de la création du compte</div>';
                }
            }
        }
    } else {
        $message = "Veuillez remplir tous les champs";
    }
}
?>