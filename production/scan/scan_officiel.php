<?php
require_once '../../config/config_db.php';
require_once('../../config/securite.php');

// Suppression employé si demandé
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM personnel WHERE id_personnel = :id_personnel");
    $stmt->execute(['id_personnel' => $id]);
    header("Location: /TEMPLATE/production/table/employe_table.php");
    exit;
}

// Récupération des employés avec jointures pour afficher les libellés
$sql = "SELECT p.id_personnel, p.nom, p.prenom, p.email, p.tel, p.nom_utilisateur,
               poste.libelle AS poste, service.libelle AS service, disponibilite.libelle AS disponibilite,
               niveau.libelle AS niveau_habilitation
        FROM personnel p
        LEFT JOIN poste ON p.id_poste = poste.id_poste
        LEFT JOIN service ON p.id_service = service.id_service
        LEFT JOIN disponibilite ON p.id_disponibilite = disponibilite.id_disponibilite
        LEFT JOIN compte c ON p.id_personnel = c.id_personnel
        LEFT JOIN niveau ON c.id_niveau = niveau.id_niveau
        ORDER BY p.id_personnel DESC";
$employes = $pdo->query($sql)->fetchAll();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>DataTables | Gentelella</title>

  <!-- Bootstrap -->
  <link href="cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- Datatables -->

  <link href="../../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <link href="../../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="../../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
  <link href="../../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
  <link href="../../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="../../build/css/custom.min.css" rel="stylesheet">
   <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
     

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
       
          <!-- /menu profile quick info -->

          <br />

          <!-- sidebar menu -->
          <?php include("../side_bar/side_bar.php"); ?>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          
          <!-- /menu footer buttons -->
      
      <!-- top navigation -->
           <?php include("../navigation.php"); ?>
      <!-- /top navigation -->

      <!-- page content -->
      <?php include("scan.php"); ?>
      <!-- /page content -->

      <!-- footer content -->
     
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
  <!-- iCheck -->
  <script src="../../vendors/iCheck/icheck.min.js"></script>
  <!-- Datatables -->
  <script src="../../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="../../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="../../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
  <script src="../../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="../../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="../../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="../../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
  <script src="../../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
  <script src="../../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
  <script src="../../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
  <script src="../../vendors/jszip/dist/jszip.min.js"></script>
  <script src="../../vendors/pdfmake/build/pdfmake.min.js"></script>
  <script src="../../vendors/pdfmake/build/vfs_fonts.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="../../build/js/custom.min.js"></script>

</body>

</html>