<?php session_start(); ?>
<?php
include 'autenticacao.php';
session_destroy();
header('Location: login.php');
exit;
?>