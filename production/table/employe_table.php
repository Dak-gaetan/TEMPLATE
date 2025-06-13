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
$sql = "SELECT p.id_personnel, p.nom, p.prenom, p.email, p.tel, p.nom_utilisateur,
               poste.libelle AS poste, service.libelle AS service, disponibilite.libelle AS disponibilite
        FROM personnel p
        LEFT JOIN poste ON p.id_poste = poste.id_poste
        LEFT JOIN service ON p.id_service = service.id_service
        LEFT JOIN disponibilite ON p.id_disponibilite = disponibilite.id_disponibilite
        ORDER BY p.id_personnel DESC";
$employes = $pdo->query($sql)->fetchAll();
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
                <h3>Liste des employés</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Employés <small>Tableau dynamique</small></h2>
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
                            <th>#</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Nom d'utilisateur</th>
                            <th>Poste</th>
                            <th>Service</th>
                            <th>Disponibilité</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $num = 1; foreach ($employes as $emp): ?>
                          <tr>
                            <td><?= $num++ ?></td>
                            <td><?= htmlspecialchars($emp['nom']) ?></td>
                            <td><?= htmlspecialchars($emp['prenom']) ?></td>
                            <td><?= htmlspecialchars($emp['email']) ?></td>
                            <td><?= htmlspecialchars($emp['tel']) ?></td>
                            <td><?= htmlspecialchars($emp['nom_utilisateur']) ?></td>
                            <td><?= htmlspecialchars($emp['poste']) ?></td>
                            <td><?= htmlspecialchars($emp['service']) ?></td>
                            <td><?= htmlspecialchars($emp['disponibilite']) ?></td>
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