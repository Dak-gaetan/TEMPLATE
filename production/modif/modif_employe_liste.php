<?php
require_once '../../config/config_db.php';

// Récupération de l'ID du personnel à modifier
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /TEMPLATE/production/table/employe_table.php");
    exit;
}
$id = (int)$_GET['id'];

// Récupération des listes pour les select
$postes = $pdo->query("SELECT id_poste, libelle FROM poste")->fetchAll();
$services = $pdo->query("SELECT id_service, libelle FROM service")->fetchAll();
$dispos = $pdo->query("SELECT id_disponibilite, libelle FROM disponibilite")->fetchAll();

// Récupération des infos du personnel
$stmt = $pdo->prepare("SELECT * FROM personnel WHERE id_personnel = :id");
$stmt->execute(['id' => $id]);
$personnel = $stmt->fetch();

if (!$personnel) {
    header("Location: /TEMPLATE/production/table/employe_table.php");
    exit;
}

$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $nom_utilisateur = $_POST['nom_utilisateur'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $id_poste = $_POST['id_poste'] ?? null;
    $id_service = $_POST['id_service'] ?? null;
    $id_disponibilite = $_POST['id_disponibilite'] ?? null;

    // Si le mot de passe est vide, on ne le modifie pas
    if (!empty($mot_de_passe)) {
        $sql = "UPDATE personnel SET nom = :nom, prenom = :prenom, email = :email, tel = :tel, nom_utilisateur = :nom_utilisateur, mot_de_passe = :mot_de_passe, id_poste = :id_poste, id_service = :id_service, id_disponibilite = :id_disponibilite WHERE id_personnel = :id";
        $params = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'tel' => $tel,
            'nom_utilisateur' => $nom_utilisateur,
            'mot_de_passe' => password_hash($mot_de_passe, PASSWORD_DEFAULT),
            'id_poste' => $id_poste,
            'id_service' => $id_service,
            'id_disponibilite' => $id_disponibilite,
            'id' => $id
        ];
    } else {
        $sql = "UPDATE personnel SET nom = :nom, prenom = :prenom, email = :email, tel = :tel, nom_utilisateur = :nom_utilisateur, id_poste = :id_poste, id_service = :id_service, id_disponibilite = :id_disponibilite WHERE id_personnel = :id";
        $params = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'tel' => $tel,
            'nom_utilisateur' => $nom_utilisateur,
            'id_poste' => $id_poste,
            'id_service' => $id_service,
            'id_disponibilite' => $id_disponibilite,
            'id' => $id
        ];
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $success = true;

    // Mettre à jour les données affichées après modification
    $stmt = $pdo->prepare("SELECT * FROM personnel WHERE id_personnel = :id");
    $stmt->execute(['id' => $id]);
    $personnel = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un personnel</title>
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
                <h3>Modifier un personnel</h3>
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
                setTimeout(function() {
                  window.location.href = "/TEMPLATE/production/table/employe_table.php";
                }, 1500);
              </script>
            <?php endif; ?>
            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Formulaire de modification</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form method="post" class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3">Nom</label>
                        <div class="col-md-9">
                          <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($personnel['nom']) ?>" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3">Prénom</label>
                        <div class="col-md-9">
                          <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($personnel['prenom']) ?>" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3">Email</label>
                        <div class="col-md-9">
                          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($personnel['email']) ?>" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3">Téléphone</label>
                        <div class="col-md-9">
                          <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($personnel['tel']) ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3">Nom d'utilisateur</label>
                        <div class="col-md-9">
                          <input type="text" name="nom_utilisateur" class="form-control" value="<?= htmlspecialchars($personnel['nom_utilisateur']) ?>" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3">Mot de passe</label>
                        <div class="col-md-9">
                          <input type="password" name="mot_de_passe" class="form-control" placeholder="Laisser vide pour ne pas changer">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3">Poste</label>
                        <div class="col-md-9">
                          <select name="id_poste" class="form-control" required>
                            <option value="">Sélectionner...</option>
                            <?php foreach ($postes as $poste): ?>
                              <option value="<?= $poste['id_poste'] ?>" <?= $personnel['id_poste'] == $poste['id_poste'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($poste['libelle']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3">Service</label>
                        <div class="col-md-9">
                          <select name="id_service" class="form-control" required>
                            <option value="">Sélectionner...</option>
                            <?php foreach ($services as $service): ?>
                              <option value="<?= $service['id_service'] ?>" <?= $personnel['id_service'] == $service['id_service'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($service['libelle']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3">Disponibilité</label>
                        <div class="col-md-9">
                          <select name="id_disponibilite" class="form-control" required>
                            <option value="">Sélectionner...</option>
                            <?php foreach ($dispos as $dispo): ?>
                              <option value="<?= $dispo['id_disponibilite'] ?>" <?= $personnel['id_disponibilite'] == $dispo['id_disponibilite'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dispo['libelle']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-9 col-md-offset-3">
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