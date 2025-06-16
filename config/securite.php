<?php
if (!isset($_SESSION['pseudo'])) {
    header("Location: /TEMPLATE/production/formulaire/login_form.php");
    exit;
}

?>