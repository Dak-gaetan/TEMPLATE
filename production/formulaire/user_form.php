<?php
require_once '../../config/config_db.php';
// Récupération des listes pour les select
$postes = $pdo->query("SELECT id_poste, libelle FROM poste")->fetchAll();
$services = $pdo->query("SELECT id_service, libelle FROM service")->fetchAll();
$dispos = $pdo->query("SELECT id_disponibilite, libelle FROM disponibilite")->fetchAll();
$niveaux = $pdo->query("SELECT id_niveau, libelle FROM niveau")->fetchAll(); // Ajout de la récupération des niveaux

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
    $id_niveau = (int)($_POST['id_niveau'] ?? 0); // Ajout récupération niveau
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $tel = trim($_POST['telephone'] ?? '');

    // Vérification des champs obligatoires et des correspondances
    if (
        $nom && $prenom && $email && $confirm_email && $tel &&
        $id_poste && $id_service && $id_disponibilite && $id_niveau &&
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

                // Préparation de la requête d'insertion avec id_niveau
                $stmt = $pdo->prepare("INSERT INTO personnel 
                    (nom, prenom, email, tel, id_poste, id_service, id_disponibilite, id_niveau, nom_utilisateur, mot_de_passe) 
                    VALUES 
                    (:nom, :prenom, :email, :tel, :id_poste, :id_service, :id_disponibilite, :id_niveau, :nom_utilisateur, :mot_de_passe)");

                $ok = $stmt->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'tel' => $tel,
                    'id_poste' => $id_poste,
                    'id_service' => $id_service,
                    'id_disponibilite' => $id_disponibilite,
                    'id_niveau' => $id_niveau,
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
