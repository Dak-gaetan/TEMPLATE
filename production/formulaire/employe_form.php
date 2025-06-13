<?php
require_once '../../config/config_db.php';
// Récupération des listes pour les select
$postes = $pdo->query("SELECT id_poste, libelle FROM poste")->fetchAll();
$services = $pdo->query("SELECT id_service, libelle FROM service")->fetchAll();
$dispos = $pdo->query("SELECT id_disponibilite, libelle FROM disponibilite")->fetchAll();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et sécuriser les données du formulaire
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $confirm_email = trim($_POST['confirm_email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $id_poste = (int)($_POST['id_poste'] ?? 0);
    $id_service = (int)($_POST['id_service'] ?? 0);
    $id_disponibilite = (int)($_POST['id_disponibilite'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $tel = trim($_POST['telephone'] ?? ''); // récupère le champ du formulaire "telephone" dans la variable $tel

    // Vérification des champs obligatoires et des correspondances
    if (
        $nom && $prenom && $email && $confirm_email && $tel &&
        $id_poste && $id_service && $id_disponibilite &&
        $username && $password && $confirm_password
    ) {
        if ($email !== $confirm_email) {
            $message = "<div class='alert alert-warning'>Les emails ne correspondent pas.</div>";
        } elseif ($password !== $confirm_password) {
            $message = "<div class='alert alert-warning'>Les mots de passe ne correspondent pas.</div>";
        } else {
            // Vérifier si l'email existe déjà
            $check = $pdo->prepare("SELECT COUNT(*) FROM personnel WHERE email = :email");
            $check->execute(['email' => $email]);
            if ($check->fetchColumn() > 0) {
                $message = "<div class='alert alert-danger'>Cet email existe déjà dans la base.</div>";
            } else {
                // Hash du mot de passe
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Préparation de la requête d'insertion
                $stmt = $pdo->prepare("INSERT INTO personnel 
                    (nom, prenom, email, tel, id_poste, id_service, id_disponibilite, nom_utilisateur, mot_de_passe) 
                    VALUES 
                    (:nom, :prenom, :email, :tel, :id_poste, :id_service, :id_disponibilite, :nom_utilisateur, :mot_de_passe)");

                $ok = $stmt->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'tel' => $tel,
                    'id_poste' => $id_poste,
                    'id_service' => $id_service,
                    'id_disponibilite' => $id_disponibilite,
                    'nom_utilisateur' => $username,
                    'mot_de_passe' => $password_hash
                ]);

                if ($ok) {
                    $message = "<div class='alert alert-success'>Employé ajouté avec succès !</div>";
                    header("Location: /TEMPLATE/production/table/employe_table.php");
                    exit;
                } else {
                    $message = "<div class='alert alert-danger'>Erreur lors de l'ajout de l'employé.</div>";
                }
            }
        }
    } else {
        $message = "<div class='alert alert-warning'>Veuillez remplir tous les champs.</div>";
    }
}
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
            <?php include("../navigation.php"); ?>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>Form Validation</h3>
                        </div>

                        <div class="title_right">
                            <div class="col-md-5 col-sm-5 form-group pull-right top_search">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search for...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Go!</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Form validation <small>sub title</small></h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#">Settings 1</a>
                                                <a class="dropdown-item" href="#">Settings 2</a>
                                            </div>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">


<form action="" method="post" novalidate>
    <span class="section">Informations personnelles</span>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Nom <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <input class="form-control" name="nom" required="required" />
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Prénom <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <input class="form-control" name="prenom" required="required" />
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Email <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <input class="form-control" name="email" type="email" required="required" />
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Confirmer Email <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <input class="form-control" name="confirm_email" type="email" required="required" />
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Téléphone <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <input class="form-control" name="telephone" type="tel" required="required" />
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Poste <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <select class="form-control" name="id_poste" required>
                <option value="">Sélectionner un poste</option>
                <?php foreach ($postes as $poste): ?>
                    <option value="<?= $poste['id_poste'] ?>"><?= htmlspecialchars($poste['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Service <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <select class="form-control" name="id_service" required>
                <option value="">Sélectionner un service</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= $service['id_service'] ?>"><?= htmlspecialchars($service['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Disponibilité <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <select class="form-control" name="id_disponibilite" required>
                <option value="">Sélectionner une disponibilité</option>
                <?php foreach ($dispos as $dispo): ?>
                    <option value="<?= $dispo['id_disponibilite'] ?>"><?= htmlspecialchars($dispo['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <span class="section">Compte utilisateur</span>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Nom d'utilisateur <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <input class="form-control" name="username" required="required" />
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Mot de passe <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <input class="form-control" type="password" name="password" required="required" />
        </div>
    </div>
    <div class="field item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align">Confirmer mot de passe <span class="required">*</span></label>
        <div class="col-md-6 col-sm-6">
            <input class="form-control" type="password" name="confirm_password" required="required" />
        </div>
    </div>
    <div class="ln_solid">
        <div class="form-group">
            <div class="col-md-6 offset-md-3">
                <button type='submit' class="btn btn-primary">Enregistrer</button>
                <button type='reset' class="btn btn-success">Réinitialiser</button>
            </div>
        </div>
    </div>
</form>
<?php if ($message): ?>
    <div class="alert-container" style="position: absolute; top: 10px; right: 10px; z-index: 1000; width: auto;">
        <?= $message ?>
    </div>
<?php endif; ?>
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
        document.forms[0].onsubmit = function(e) {
            var email = document.querySelector('[name="email"]').value;
    var confirmEmail = document.querySelector('[name="confirm_email"]').value;
    var pass = document.querySelector('[name="password"]').value;
    var confirmPass = document.querySelector('[name="confirm_password"]').value;
    var valid = true;
    var msg = "";

    if (email !== confirmEmail) {
        msg += "Les emails ne correspondent pas.\n";
        valid = false;
    }
    if (pass !== confirmPass) {
        msg += "Les mots de passe ne correspondent pas.\n";
        valid = false;
    }
    if (!valid) {
        alert(msg);
        e.preventDefault();
    }
        };
        // on form "reset" event
        document.forms[0].onreset = function(e) {
            validator.reset();
        };
        // stuff related ONLY for this demo page:
        $('.toggleValidationTooltips').change(function() {
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

</body>

</html>
