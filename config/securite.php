<?php
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: /TEMPLATE/production/formulaire/login_form.php");
    exit;
}

?>