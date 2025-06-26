<?php
require_once '../../config/config_db.php';
require_once('../../config/securite.php');
require_once '../phpqrcode/qrlib.php';

$message = '';

// Récupérer employés sans badge actif
$personnes = $pdo->query("
    SELECT p.id_personnel, p.nom, p.prenom, po.libelle AS poste_libelle, s.libelle AS service_libelle
    FROM personnel p
    LEFT JOIN poste po ON p.id_poste = po.id_poste
    LEFT JOIN service s ON p.id_service = s.id_service
    LEFT JOIN badge b ON p.id_personnel = b.id_personnel
    WHERE b.code_badge IS NULL OR b.actif != 'oui'
    ORDER BY p.nom, p.prenom
")->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code_badge = $_POST['code_badge'];
    $date_emission = $_POST['date_emission'];
    $id_personnel = $_POST['id_personnel'];

    $conn = new mysqli('localhost', 'root', '', 'template');
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    // Vérifier si le code_badge existe déjà
    $check = $conn->prepare("SELECT COUNT(*) FROM badge WHERE code_badge = ?");
    $check->bind_param("s", $code_badge);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        $message = "<div class='alert alert-danger'>Le code badge existe déjà.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO badge (code_badge, actif, date_emission, id_personnel) VALUES (?, 'oui', ?, ?)");
        $stmt->bind_param("ssi", $code_badge, $date_emission, $id_personnel);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Badge ajouté avec succès !</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de l'ajout du badge : " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
    $conn->close();

    // Générer le QR code tout de suite après avoir attribué le badge
    if (!empty($code_badge) && $id_personnel > 0) {
        $qr_dir = __DIR__ . '/../qr_codes/';
        if (!is_dir($qr_dir)) mkdir($qr_dir, 0777, true);

        $qr_filename = 'qr_' . $code_badge . '.png';
        $qr_path = $qr_dir . $qr_filename;
        $qr_code_rel = 'qr_codes/' . $qr_filename;

        QRcode::png($code_badge, $qr_path, 'H', 10, 2);

        // Met à jour la table personnel
        $stmt = $pdo->prepare("UPDATE personnel SET qr_code = :qr_code WHERE id_personnel = :id_personnel");
        $stmt->bindValue(':qr_code', $qr_code_rel, PDO::PARAM_STR);
        $stmt->bindValue(':id_personnel', $id_personnel, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gentelella Alela! | </title>
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../build/css/custom.min.css" rel="stylesheet">
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <!-- SIDE BAR -->
            <?php include("../side_bar/side_bar.php"); ?>
            <!-- END SIDE BAR -->

            <!-- top navigation -->
            <?php include("../navigation.php"); ?>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>Formulaire badge</h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Attribuer Badge</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <?php if (!empty($message)) echo $message; ?>

                                    <form action="" method="post">
                                        <span class="section"></span>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Employés
                                                <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" id="id_personnel" name="id_personnel" required>
                                                    <option value="">-- Sélectionner --</option>
                                                    <?php foreach ($personnes as $perso): ?>
                                                        <option value="<?= $perso['id_personnel'] ?>"
                                                            data-poste="<?= htmlspecialchars($perso['poste_libelle'] ?? '') ?>"
                                                            data-service="<?= htmlspecialchars($perso['service_libelle'] ?? '') ?>">
                                                            <?= htmlspecialchars($perso['nom'] . ' ' . $perso['prenom']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Poste
                                                <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" class="form-control" id="poste" name="poste" readonly>
                                            </div>
                                        </div>
                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Service
                                                <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" class="form-control" id="service" name="service" readonly>
                                            </div>
                                        </div>

                                        <div class="x_title">
                                            <h2>Détails du badge</h2>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align">Code Badge<span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" name="code_badge" required="required"
                                                    type="text" placeholder="Sélectionner code badge"  />
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Date d'émission <span class="required">*</span></label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <input name="date_emission" class="date-picker form-control" placeholder="dd-mm-yyyy" required="required"
                                                    type="date">
                                            </div>
                                        </div>

                                        <div class="ln_solid">
                                            <div class="form-group">
                                                <div class="col-md-6 offset-md-3">
                                                    <div class="text-center">
                                                        <h2>Informations de connexion</h2>
                                                        <button type='submit' class="btn btn-primary">Soumettre</button>
                                                        <button type='reset' class="btn btn-success">Annuler</button>
                                                    </div>
                                                </div>
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

            <!-- footer content -->
            <footer>
                <div class="pull-right">
                    Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
    </div>

    <!-- jQuery -->
    <script src="../../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FastClick -->
    <script src="../../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../vendors/nprogress/nprogress.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../../build/js/custom.min.js"></script>

    <script>
        document.getElementById('id_personnel').addEventListener('change', function() {
        var selected = this.options[this.selectedIndex];
        document.getElementById('poste').value = selected.getAttribute('data-poste') || '';
        document.getElementById('service').value = selected.getAttribute('data-service') || '';
    });
    </script>
</body>
</html>

