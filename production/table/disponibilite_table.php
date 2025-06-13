<?php
require_once '../../config/config_db.php';

// Suppression si demandé
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM disponibilite WHERE id_disponibilite = :id_disponibilite");
    $stmt->execute(['id_disponibilite' => $id]);
    header("Location: disponibilite_table.php");
    exit;
}

// Récupération des disponibilités
$dispos = $pdo->query("SELECT id_disponibilite, libelle FROM disponibilite ORDER BY id_disponibilite DESC")->fetchAll();
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
    <!-- iCheck -->
   <link href="../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">

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
                <h3>LISTES - DISPONIBILITÉS</h3>
              </div>
              <div class="clearfix"></div>

              <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>DISPONIBILITÉS </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                     
                      
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Disponibilité</th>
                          <th>Modifier</th>
                          <th>Supprimer</th>
                        </tr>
                      </thead>
                      <tbody>
<?php $num = 1; ?>
<?php foreach ($dispos as $dispo): ?>
    <tr>
        <th scope="row"><?= $num++ ?></th>
        <td><?= htmlspecialchars($dispo['libelle']) ?></td>
        <td>
            <a href='/TEMPLATE/production/modif/modif_dispo_liste.php?id=<?= $dispo['id_disponibilite'] ?>' class='btn btn-edit'><i class='fa fa-edit'></i></a>
        </td>
        <td>
            <a href='disponibilite_table.php?delete=<?= $dispo['id_disponibilite'] ?>' class='btn btn-delete' onclick='return confirm("Êtes-vous sûr de vouloir supprimer cette disponibilité ?")'><i class='fa fa-trash'></i></a>
        </td>
    </tr>
<?php endforeach; ?>
                      </tbody>
                    </table>
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
    <!-- iCheck -->
    <script src="../../vendors/iCheck/icheck.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../../build/js/custom.min.js"></script>
  </body>
</html>
