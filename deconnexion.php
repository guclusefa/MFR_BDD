<?php
//déconnexion session
session_start();
session_destroy();
session_start();
$_SESSION['erreur'] = "Déconnexion avec succès";
exit(header("location:index.php"));
