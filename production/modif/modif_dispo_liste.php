<?php
require_once '../../config/config_db.php';

$message = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les infos de la disponibilité à modifier
$stmt = $pdo->prepare("SELECT libelle FROM disponibilite WHERE id_disponibilite = :id_disponibilite");
$stmt->execute(['id_disponibilite' => $id]);
$dispo = $stmt->fetch();

if (!$dispo) {
    echo "<div class='alert alert-danger'>Disponibilité introuvable.</div>";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = trim($_POST['libelle'] ?? '');

    if (!empty($libelle)) {
        $update = $pdo->prepare("UPDATE disponibilite SET libelle = :libelle WHERE id_disponibilite = :id_disponibilite");
        if ($update->execute(['libelle' => $libelle, 'id_disponibilite' => $id])) {
            $message = "<div class='alert alert-success'>Disponibilité modifiée avec succès !</div>";
            $dispo['libelle'] = $libelle;
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la modification.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Veuillez remplir le champ libellé.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une disponibilité</title>
    <!-- Gentelella & Bootstrap CSS -->
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../build/css/custom.min.css" rel="stylesheet">
</head>
<body class="nav-md">
<div class="container body">
  <div class="main_container">
    <?php include("../side_bar/side_bar.php"); ?>
    <?php include("../navigation.php"); ?>
    <div class="right_col" role="main">
      <div class="page-title">
        <div class="title_left">
          <h3>Modifier une disponibilité</h3>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-12 col-sm-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Formulaire de modification</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php if ($message) echo $message; ?>
            <form method="post">
              <div class="form-group">
                <label for="libelle">Libellé de la disponibilité</label>
                <input type="text" class="form-control" id="libelle" name="libelle" value="<?= htmlspecialchars($dispo['libelle']) ?>" required>
              </div>
              <button type="submit" class="btn btn-primary">Enregistrer</button>
              <a href="/TEMPLATE/production/table/disponibilite_table.php" class="btn btn-secondary">Retour à la liste</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- JS scripts -->
<script src="../../vendors/jquery/dist/jquery.min.js"></script>
<script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../vendors/fastclick/lib/fastclick.js"></script>
<script src="../../vendors/nprogress/nprogress.js"></script>
<script src="../../build/js/custom.min.js"></script>
</body>
</html>