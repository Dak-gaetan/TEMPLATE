<?php
require_once '../../config/config_db.php';
require_once('../../config/securite.php');

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
            <?php include("scan2.php"); ?>
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

    <script src="../../vendors/validator/multifield.js"></script>
    <script src="../../vendors/validator/validator.js"></script>
    <script src="../../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendors/fastclick/lib/fastclick.js"></script>
    <script src="../../vendors/nprogress/nprogress.js"></script>
    <script src="../../build/js/custom.min.js"></script>
    <script>
    document.getElementById('id_personnel').addEventListener('change', function() {
        var s    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
elected = this.options[this.selectedIndex];
        document.getElementById('poste').value = selected.getAttribute('data-poste') || '';
        document.getElementById('service').value = selected.getAttribute('data-service') || '';
    });
    </script>
</body>
</html>

