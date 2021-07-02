<?php
//start de la session et connexion à la bdd via pdo
session_start();
$bdd_host = "localhost";
$bdd_name = "bdd_mfr";
$bdd_username = "root";
$bdd_userpwd = "root";

try {
    $pdo = new PDO("mysql:host=$bdd_host;dbname=$bdd_name;charset=utf8", $bdd_username, $bdd_userpwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $pdo->exec("set names utf8");
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
