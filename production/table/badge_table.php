<?php
require_once '../../config/config_db.php';

// Suppression employé si demandé
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM personnel WHERE id_personnel = :id_personnel");
    $stmt->execute(['id_personnel' => $id]);
    header("Location: /TEMPLATE/production/table/employe_table.php");
    exit;
}

// Récupération des employés avec jointures pour afficher les libellés
$sql = "SELECT 
          p.id_personnel, 
          p.nom, 
          p.prenom, 
          b.code_badge, 
          b.date_emission, 
          b.actif
        FROM personnel p
        LEFT JOIN badge b ON p.id_personnel = b.id_personnel
        ORDER BY p.nom, p.prenom";
$employes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des employés</title>
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
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
                <h3>Liste Badges</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Badges</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="card-box table-responsive">
                      <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                          <tr>
                            <th>Employés</th>
                            <th>ÉTAT</th>
                            <th>CODE BADGE</th>
                            <th>DATE D'ÉMISSION</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($employes as $emp): ?>
                          <tr>
                            <td><?= htmlspecialchars($emp['nom'] . ' ' . $emp['prenom']) ?></td>
                            <td>
                              <?php
                                if (empty($emp['code_badge'])) {
                                  echo '<span style="color:red;">Inactif</span>';
                                } else {
                                  echo ($emp['actif'] === 'oui') ? '<span style="color:green;">Actif</span>' : '<span style="color:red;">Inactif</span>';
                                }
                              ?>
                            </td>
                            <td><?= !empty($emp['code_badge']) ? htmlspecialchars($emp['code_badge']) : '—' ?></td>
                            <td><?= !empty($emp['date_emission']) ? htmlspecialchars($emp['date_emission']) : '—' ?></td>
                            <td>
                              <a href='/TEMPLATE/production/modif/modif_employe_liste.php?id=<?= $emp['id_personnel'] ?>' class='btn btn-edit btn-sm'><i class='fa fa-edit'></i></a>
                              <a href='employe_table.php?delete=<?= $emp['id_personnel'] ?>' class='btn btn-delete btn-sm' onclick='return confirm("Supprimer cet employé ?")'><i class='fa fa-trash'></i></a>
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