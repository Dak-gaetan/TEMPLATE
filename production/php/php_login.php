<?php
include("login_form.php");

if(isset($_POST['btn_sign'])){
    $username=$_POST['username'];
    $password=hash("sha256",$_POST['password']);
    //$password=$_POST['password'];

    $con=$bdd->prepare("SELECT * FROM user WHERE username='$username' AND password='$password' ");
    $con->execute();
    $econ=$con->fetch();

    if(isset($econ['id_user'])){
        $_SESSION['user_id']=$econ['id_user'];
        $_SESSION['name']=$econ['name'];
        $_SESSION['username']=$econ['username'];
        $_SESSION['email']=$econ['email'];
        
        header("Location: index.php");
    }else{
        header("Location:page-login.php?erreur=Nom de l'utilisateur ou mot de passe erroné!");
    }
}
?>