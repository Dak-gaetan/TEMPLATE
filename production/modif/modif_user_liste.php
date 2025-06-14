<?php
require_once '../../config/config_db.php';

// Récupération de l'ID du compte à modifier
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /TEMPLATE/production/table/user_table.php");
    exit;
}
$id = (int) $_GET['id'];

// Récupération des niveaux d'habilitation
$niveaux = $pdo->query("SELECT id_niveau, libelle FROM niveau")->fetchAll();

// Récupération des infos du compte
$stmt = $pdo->prepare("SELECT c.*, p.nom, p.prenom FROM compte c LEFT JOIN personnel p ON c.id_personnel = p.id_personnel WHERE c.id_compte = :id");
$stmt->execute(['id' => $id]);
$compte = $stmt->fetch();

if (!$compte) {
    header("Location: /TEMPLATE/production/table/user_table.php");
    exit;
}

$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_niveau = $_POST['id_niveau'] ?? $compte['id_niveau'];
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (!empty($mot_de_passe)) {
        $sql = "UPDATE compte SET id_niveau = :id_niveau, mot_de_passe = :mot_de_passe WHERE id_compte = :id";
        $params = [
            'id_niveau' => $id_niveau,
            'mot_de_passe' => password_hash($mot_de_passe, PASSWORD_DEFAULT),
            'id' => $id
        ];
    } else {
        $sql = "UPDATE compte SET id_niveau = :id_niveau WHERE id_compte = :id";
        $params = [
            'id_niveau' => $id_niveau,
            'id' => $id
        ];
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $success = true;

    // Mettre à jour les données affichées après modification
    $stmt = $pdo->prepare("SELECT c.*, p.nom, p.prenom FROM compte c LEFT JOIN personnel p ON c.id_personnel = p.id_personnel WHERE c.id_compte = :id");
    $stmt->execute(['id' => $id]);
    $compte = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un compte utilisateur</title>
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="../../build/css/custom.min.css" rel="stylesheet">
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <?php include("../side_bar/side_bar.php"); ?>
            <?php include("../navigation.php"); ?>

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>Modifier un utilisateur</h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Modification enregistrée avec succès.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <script>
                            setTimeout(function () {
                                window.location.href = "/TEMPLATE/production/table/user_table.php";
                            }, 1500);
                        </script>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-8 col-sm-12 mx-auto">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Formulaire de modification</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <form method="post" class="form-horizontal form-label-left">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Utilisateur</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($compte['nom'] . ' ' . $compte['prenom']) ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Niveau d'habilitation</label>
                                            <div class="col-md-9">
                                                <select name="id_niveau" class="form-control" required>
                                                    <?php foreach ($niveaux as $niveau): ?>
                                                        <option value="<?= $niveau['id_niveau'] ?>" <?= ($compte['id_niveau'] == $niveau['id_niveau']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($niveau['libelle']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Mot de passe</label>
                                            <div class="col-md-9">
                                                <input type="password" name="mot_de_passe" class="form-control" placeholder="Laisser vide pour ne pas changer">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-9 offset-md-3">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-save"></i> Enregistrer
                                                </button>
                                                <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                                                    <i class="fa fa-arrow-left"></i> Annuler
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->

            <footer>
                <div class="pull-right">
                    Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
                </div>
                <div class="clearfix"></div>
            </footer>
        </div>
    </div>
    <script src="../../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendors/fastclick/lib/fastclick.js"></script>
    <script src="../../vendors/nprogress/nprogress.js"></script>
    <script src="../../vendors/iCheck/icheck.min.js"></script>
    <script src="../../build/js/custom.min.js"></script>
</body>
</html>