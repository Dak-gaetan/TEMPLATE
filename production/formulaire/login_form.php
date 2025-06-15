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
    <!-- Animate.css -->
    <link href="../../vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method="POST" action="">
              <h1>Login Form</h1>
              <div>
                <input type="text" name="pseudo" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-default submit">Log in</button>
                <a class="reset_pass" href="#">Lost your password?</a>
              </div>

              <div class="clearfix"></div>

              <?php
              require_once '../../config/config_db.php';
              session_start();

              $message = '';

              if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                  $pseudo = trim($_POST['pseudo'] ?? '');
                  $password = $_POST['password'] ?? '';

                  if ($pseudo && $password) {
                      // Vérification dans la table compte
                      $stmt = $pdo->prepare("SELECT * FROM compte WHERE pseudo = :pseudo");
                      $stmt->execute(['pseudo' => $pseudo]);
                      $compte = $stmt->fetch();

                      if ($compte && password_verify($password, $compte['mot_de_passe'])) {
                          // Connexion réussie, redirection
                          $_SESSION['user_id'] = $compte['id_compte'];
                          header("Location: /TEMPLATE/production/index.php");
                          exit;
                      } else {
                          $message = "<div class='alert alert-danger'>Pseudo ou mot de passe incorrect.</div>";
                      }
                  } else {
                      $message = "<div class='alert alert-warning'>Veuillez remplir tous les champs.</div>";
                  }
              }
              ?>

              
              
            </form>
          </section>
        </div>

        
      </div>
    </div>
  </body>
</html>
