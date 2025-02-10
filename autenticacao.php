<?php
function estaAutenticado() {
    return isset($_SESSION['id']) && isset($_SESSION['utilizador']) && isset($_SESSION['tipo']);
}

function eAdmin() {
    return estaAutenticado() && $_SESSION['tipo'] == 'admin';
}

function proibirAutenticado() {
    if(estaAutenticado()) {
        header('Location: index.php');
    }
}

function proibirNaoAdmin() {
    if(!eAdmin()) {
        header('Location: login.php');
    }
}
?>
