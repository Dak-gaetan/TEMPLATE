<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : '';
$pseudo = isset($_SESSION['pseudo']) ? $_SESSION['pseudo'] : '';
?>

<div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.php" class="site_title"><i class="fa fa-paw"></i> <span>PRESENCE</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="/TEMPLATE/production/images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Bienvenue</span>
                <h2><?php echo htmlspecialchars($pseudo); ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      
                      <li><a href="/TEMPLATE/production/index.php">Dashboard</a></li>
                    </ul>
                  </li>
                
                 <li><a><i class="fa fa-edit"></i> Scanne <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/TEMPLATE/production/scan/scan.php">Scanner </a></li>
                      <li><a href="/TEMPLATE/production/table/manuel_table.php">Manuel Badge</a></li>
           
                    </ul>
                  </li>

                  <li><a><i class="fa fa-edit"></i> Employé <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/TEMPLATE/production/formulaire/employe_form.php">Ajouter</a></li>
                      <li><a href="/TEMPLATE/production/table/employe_table.php">Liste</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> Utilisateur <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/TEMPLATE/production/formulaire/user_form.php">Ajouter</a></li>
                      <li><a href="/TEMPLATE/production/table/user_table.php">Liste</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> Poste <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/TEMPLATE/production/formulaire/poste_form.php">Ajouter</a></li>
                      <li><a href="/TEMPLATE/production/table/poste_table.php">Liste</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> Service <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/TEMPLATE/production/formulaire/service_form.php">Ajouter</a></li>
                      <li><a href="/TEMPLATE/production/table/service_table.php">Liste</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> Disponibilite <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/TEMPLATE/production/formulaire/form_disponibilite.php">Ajouter</a></li>
                      <li><a href="/TEMPLATE/production/table/disponibilite_table.php">Liste</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> Badge <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
<<<<<<< HEAD
                      <li><a href="/TEMPLATE/production/scan/scan_officiel.php">Scanner Badge</a></li>
=======
                     
>>>>>>> 1016759 (ELA)
                      <li><a href="/TEMPLATE/production/formulaire/attribute_form.php">Attribuer Badge</a></li>
                       <li><a href="/TEMPLATE/production/table/badge_table.php">Liste Badge</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> Niveau d'habilitation <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/TEMPLATE/production/formulaire/niveau_form.php">Ajouter</a></
                      <li><a href="/TEMPLATE/production/table/niveau_table.php">Liste</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>