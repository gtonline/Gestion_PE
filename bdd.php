<?php
$serveur = "localhost";
$login = "root";
$pass = "root";
$bdd = "stage4";
$pre = "stage";

$link = new PDO('mysql:host='.$serveur.';dbname='.$bdd.';charset=utf8', $login, $pass);
// Configuration facultative de la connexion
$link->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); // les noms de champs seront en caractères minuscules
$link->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION); // les erreurs lanceront des exceptions
$link->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
date_default_timezone_set( 'Europe/Paris' );
?>