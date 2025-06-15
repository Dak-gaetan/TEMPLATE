<?php
require_once '../../config/config_db.php';
$personnes = $pdo->query("
    SELECT p.id_personnel, p.nom, p.prenom, p.id_poste, p.id_service, 
           poste.libelle AS poste_libelle, service.libelle AS service_libelle
    FROM personnel p
    LEFT JOIN poste ON p.id_poste = poste.id_poste
    LEFT JOIN service ON p.id_service = service.id_service
    ORDER BY p.nom, p.prenom
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentelella Alela! | </title>

    <!-- Bootstrap -->
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../../build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <!-- SIDE BAR -->
            <?php include("../side_bar/side_bar.php"); ?>
            <!-- END SIDE BAR -->

            <!-- top navigation -->
            <?php
            include("../navigation.php");
            ?>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>Formulaire  badge</h3>
                        </div>


                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Attribuer Badge</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>

                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                     <!--php 
                                    require_once '../../config/config_db.php';
                                    $message = '';
                                    $ok = false;

                                    // Récupération des personnels et des niveaux d'habilitation
                                    $personnes = $pdo->query("SELECT id_personnel, nom, prenom FROM personnel ORDER BY nom, prenom")->fetchAll();
                                    $niveaux = $pdo->query("SELECT id_niveau, libelle FROM niveau ORDER BY libelle")->fetchAll();

                                    // Traitement du formulaire
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                        $id_personnel = (int)($_POST['id_personnel'] ?? 0);
                                        $pseudo = trim($_POST['pseudo'] ?? '');
                                        $password = $_POST['password'] ?? '';
                                        $confirm_password = $_POST['confirm_password'] ?? '';
                                        $id_niveau = (int)($_POST['id_niveau'] ?? 0);

                                        if ($id_personnel && $pseudo && $password && $confirm_password && $id_niveau) {
                                            if ($password !== $confirm_password) {
                                                $message = "<div class='alert alert-warning'>Les mots de passe ne correspondent pas.</div>";
                                            } else {
                                                // Vérifier si un compte existe déjà pour cette personne
                                                $check = $pdo->prepare("SELECT COUNT(*) FROM compte WHERE id_personnel = :id_personnel");
                                                $check->execute(['id_personnel' => $id_personnel]);
                                                if ($check->fetchColumn() > 0) {
                                                    $message = "<div class='alert alert-danger'>Un compte existe déjà pour cette personne.</div>";
                                                } else {
                                                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                                                    $stmt = $pdo->prepare("INSERT INTO compte (id_personnel, pseudo, mot_de_passe, id_niveau) VALUES (:id_personnel, :pseudo, :mot_de_passe, :id_niveau)");
                                                    $ok = $stmt->execute([
                                                        'id_personnel' => $id_personnel,
                                                        'pseudo' => $pseudo,
                                                        'mot_de_passe' => $password_hash,
                                                        'id_niveau' => $id_niveau
                                                    ]);
                                                }
                                            }
                                        } else {
                                            $message = "<div class='alert alert-warning'>Veuillez remplir tous les champs.</div>";
                                        }
                                    }
                                    -->
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
                                                            data-poste="<?= htmlspecialchars($perso['poste_libelle']) ?>"
                                                            data-service="<?= htmlspecialchars($perso['service_libelle']) ?>"
                                                        >
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
                                    <ul class="nav navbar-right panel_toolbox">
                                       
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                               
                                        <div class="field item form-group">
                                            <!-- <label class="col-form-label col-md-3 col-sm-3  label-align">Tag ID<span
                                                    class="required">*</span></label>
                                                    
                                            <div class="col-md-6 col-sm-6">
                                                <input class="form-control" name="pseudo" required="required"
                                                    type="text" placeholder="Sélectionner tag id" />
                                            </div> -->
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
                                    <!--php if ($ok): ?>
                                        <div class='alert alert-success'>Compte créé avec succès !</div>
                                    <!-php elseif ($message): ?>
                                        <?= $message ?>
                                    <!--php endif; -->

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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="../../vendors/validator/multifield.js"></script>
    <script src="../../vendors/validator/validator.js"></script>

    <!-- Javascript functions	-->


    <script>
        // initialize a validator instance from the "FormValidator" constructor.
        // A "<form>" element is optionally passed as an argument, but is not a must
        var validator = new FormValidator({
            "events": ['blur', 'input', 'change']
        }, document.forms[0]);
        // on form "submit" event
        document.forms[0].onsubmit = function (e) {
            var submit = true,
                validatorResult = validator.checkAll(this);
            console.log(validatorResult);
            return !!validatorResult.valid;
        };
        // on form "reset" event
        document.forms[0].onreset = function (e) {
            validator.reset();
        };
        // stuff related ONLY for this demo page:
        $('.toggleValidationTooltips').change(function () {
            validator.settings.alerts = !this.checked;
            if (this.checked)
                $('form .alert').remove();
        }).prop('checked', false);

    </script>

    <!-- jQuery -->
    <script src="../../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FastClick -->
    <script src="../../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../vendors/nprogress/nprogress.js"></script>
    <!-- validator -->
    <!-- <script src="../vendors/validator/validator.js"></script> -->

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

<?php
$message = '';
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
        $stmt = $conn->prepare("INSERT INTO badge (code_badge, date_emission, id_personnel) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $code_badge, $date_emission, $id_personnel);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Badge ajouté avec succès !</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de l'ajout du badge : " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

