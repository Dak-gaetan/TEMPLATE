<?php
session_start();
session_unset();
session_destroy();
header("Location: /TEMPLATE/production/formulaire/login_form.php");

exit;