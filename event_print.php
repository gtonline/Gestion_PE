<?php
echo "Evenement ID : ".$_GET['id'];
include 'bdd.php';
$sql = "SELECT ".$pre."_events.id as id_event,".$pre."_events.id_promo,".$pre."_events.id_stagiaire, ".$pre."_events.id_entreprise, ".$pre."_events.start_event, ".$pre."_events.end_event, ".$pre."_stagaire.id_stagaire, ".$pre."_stagiaire.civil_stagiaire, ".$pre."_stagiaire.nom_stagiaire, ".$pre."_stagiaire.prenom_stagiaire, ".$pre."_stagiaire.adresse_stagiaire FROM ".$pre."_events WHERE ".$pre."_events.id = ".$_GET['id'];
echo $sql;
 ?>
