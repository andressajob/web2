<?php
session_start();

if((!isset ($_SESSION['usuario']) == true) and (!isset ($_SESSION['senha']) == true))
{
    print_r($_SESSION);
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('location:indexLogada.php');
}

$logado = $_SESSION['usuario'];
