


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

            <!-- top navigation -->
            <?php include("../navigation.php"); ?>
            <!-- /top navigation -->


        </div>

        <div class="card-body">
            <!-- Barre de recherche et filtres -->


            <!-- Tableau des pointages -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>TABLEAU POINTAGE</h3>
                        </div>

                        <div class="title_right">
                            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">

                            </div>




                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addPointageModal">
                                <i class="fa fa-plus me-1"></i> Ajouter
                            </button>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 ">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Presences</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>

                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card-box table-responsive">

                                                <table id="datatable" class="table table-striped table-bordered"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Employe</th>
                                                            <th>Date</th>
                                                            <th>Heure d'entrée</th>
                                                            <th>Heure de sortie</th>
                                                            <th>Durée</th>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                     </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Modal d'ajout de pointage -->
                                <div class="modal fade" id="addPointageModal" tabindex="-1"
                                    aria-labelledby="addPointageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-blue text-white">
                                                <h5 class="modal-title" id="addPointageModalLabel">Ajouter un pointage
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="pointageForm" method="post">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="employeSelect" class="form-label">Employé</label>
                                                        <select class="form-select" id="employeSelect"
                                                            name="employeSelect" required>
                                                            <option value="">Sélectionner un employé</option>
                                                            <?php foreach ($manuels as $man): ?>
                                                                <option value="<?= htmlspecialchars($man['id_manuel']) ?>">
                                                                    <?= htmlspecialchars($man['prenom'] . ' ' . $man['nom']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="pointageDate" class="form-label">Date</label>
                                                        <input type="date" class="form-control" id="pointageDate"
                                                            name="pointageDate" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="heureEntree" class="form-label">Heure
                                                                d'entrée</label>
                                                            <input type="time" class="form-control" id="heureEntree"
                                                                name="heureEntree" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="heureSortie" class="form-label">Heure de
                                                                sortie</label>
                                                            <input type="time" class="form-control" id="heureSortie"
                                                                name="heureSortie">
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="fas fa-save me-1"></i> Enregistrer
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <script
                                    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                                <script>
                                    // Script pour calculer automatiquement la durée
                                    document.getElementById('heureSortie').addEventListener('change', function () {
                                        const heureEntree = document.getElementById('heureEntree').value;
                                        const heureSortie = this.value;

                                        if (heureEntree && heureSortie) {
                                            const [h1, m1] = heureEntree.split(':').map(Number);
                                            const [h2, m2] = heureSortie.split(':').map(Number);

                                            let totalMinutes = (h2 * 60 + m2) - (h1 * 60 + m1);
                                            if (totalMinutes < 0) totalMinutes += 24 * 60;

                                            const hours = Math.floor(totalMinutes / 60);
                                            const minutes = totalMinutes % 60;

                                            console.log(`Durée: ${hours}h${minutes.toString().padStart(2, '0')}`);
                                        }
                                    });

                                    // Script pour la recherche
                                    document.getElementById('searchInput').addEventListener('input', function () {
                                        const searchText = this.value.toLowerCase();
                                        const rows = document.querySelectorAll('tbody tr');

                                        rows.forEach(row => {
                                            const rowText = row.textContent.toLowerCase();
                                            row.style.display = rowText.includes(searchText) ? '' : 'none';
                                        });
                                    });

                                    document.getElementById('pointageForm').addEventListener('submit', function (e) {
                                        this.querySelector('button[type="submit"]').disabled = true;
                                    });
                                </script>

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
                                <script
                                    src="../../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
                                <script
                                    src="../../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
                                <script src="../../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
                                <script src="../../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
                                <script src="../../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
                                <script
                                    src="../../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
                                <script
                                    src="../../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
                                <script
                                    src="../../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
                                <script
                                    src="../../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
                                <script
                                    src="../../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
                                <script src="../../vendors/jszip/dist/jszip.min.js"></script>
                                <script src="../../vendors/pdfmake/build/pdfmake.min.js"></script>
                                <script src="../../vendors/pdfmake/build/vfs_fonts.js"></script>

                                <!-- Custom Theme Scripts -->
                                <script src="../../build/js/custom.min.js"></script>

</body>

</html> 