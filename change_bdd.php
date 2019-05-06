<?php
include 'bdd.php';
switch ($_POST['page']) {
    case 'promotions' :
        switch ($_POST['action']) {
            case 'add':
                $sql = "INSERT INTO ".$pre."_promo (nom_promo, debut_promo, fin_promo) VALUES ('".$_POST['nom_promo']."','".$_POST['debut_promo']."','".$_POST['fin_promo']."')";
                $query = $link->query($sql);
                echo $link->lastInsertId();
                break;

            case 'edit':
                $sql = "UPDATE ".$pre."_promo SET nom_promo = '".$_POST['nom_promo']."', debut_promo = '".$_POST['debut_promo']."', fin_promo = '".$_POST['fin_promo']."' WHERE id=".$_POST['id'];
                $query = $link->query($sql);
                break;

            case 'trash':
                $sql = "DELETE FROM ".$pre."_promo WHERE id=".$_POST['id'];
                $query = $link->query($sql);
                break;
        }
        break;

    case 'stagiaires' :
        switch ($_POST['action']) {
            case 'list':
                $sql = "SELECT * FROM ".$pre."_stagiaire WHERE promo=".$_POST['id_promo']." ORDER BY nom_stagiaire";
                $query = $link->query($sql);
                echo json_encode($query->fetchAll());
                break;

            case 'add':
                if ($_POST['cp_stagiaire'] == ''){
                    $_POST['cp_stagiaire'] = 0;
                }
                if ($_POST['poste_stagiaire'] == ''){
                    $_POST['poste_stagiaire'] = 0;
                }
                if ($_POST['phone_stagiaire'] == ''){
                    $_POST['phone_stagiaire'] = 0;
                }
                $sql = "INSERT INTO ".$pre."_stagiaire (civil_stagiaire, nom_stagiaire, prenom_stagiaire, naissance_stagiaire, adresse_stagiaire, cp_stagiaire, ville_stagiaire, man_stagiaire, chambre_stagiaire, poste_stagiaire, phone_stagiaire, mail_stagiaire, promo) VALUES ('".$_POST['civil_stagiaire']."','".mb_strtoupper($_POST['nom_stagiaire'])."','".ucfirst($_POST['prenom_stagiaire'])."','".$_POST['naissance_stagiaire']."','".addslashes($_POST['adresse_stagiaire'])."',".$_POST['cp_stagiaire'].",'".mb_strtoupper($_POST['ville_stagiaire'])."',".$_POST['man_stagiaire'].",'".mb_strtoupper($_POST['chambre_stagiaire'])."',".$_POST['poste_stagiaire'].",".$_POST['phone_stagiaire'].",'".$_POST['mail_stagiaire']."',".$_POST['promo'].")";
                $query = $link->query($sql);
                $last_id = $link->lastInsertId();
                echo '$(\'.tbody\').append(\'<tr id="'.$last_id.'"><th scope="row">'.$last_id.'</th><td>'.$_POST['civil_stagiaire'].' '.$_POST['nom_stagiaire'].' '.$_POST['prenom_stagiaire'].'</td><td>'.$_POST['adresse_stagiaire'].'<br />'.$_POST['cp_stagiaire'].' '.$_POST['ville_stagiaire'].'</td><td>'.$_POST['naissance_stagiaire'].'</td><td>'.$_POST['phone_stagiaire'].'</td><td><a class="btn btn-primary btn-sm mr-1" href="#" id="profil"><i class="far fa-fw fa-1x fa-user"></i></a><a class="btn btn-primary btn-sm mr-1" href="mailto:'.$_POST['mail_stagiaire'].'" id="sendmail"><i class="far fa-fw fa-1x fa-paper-plane"></i></a><a class="btn btn-primary btn-sm mr-1" href="#" id="edit"><i class="far fa-fw fa-1x fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td></tr>\');';
                break;

            case "edit":
                $sql = "SELECT * FROM ".$pre."_stagiaire WHERE id_stagiaire = ".$_POST['id'];
                $query = $link->query($sql);
                $my_stagiaire = $query->fetch();
                echo "$('input#id_edit_stagiaire').val(".$_POST['id'].");";
                echo "$('#edit_civilite option[value=\"".$my_stagiaire['civil_stagiaire']."\"]').prop('selected', true);";
                echo "$('input#edit_naissance').val(\"".$my_stagiaire['naissance_stagiaire']."\");";
                echo "$('input#edit_nom').val(\"".$my_stagiaire['nom_stagiaire']."\");";
                echo "$('input#edit_prenom').val(\"".$my_stagiaire['prenom_stagiaire']."\");";
                echo "$('input#edit_adresse').val(\"".$my_stagiaire['adresse_stagiaire']."\");";
                echo "$('input#edit_cp').val(".$my_stagiaire['cp_stagiaire'].");";
                echo "$('input#edit_ville').val(\"".$my_stagiaire['ville_stagiaire']."\");";
                echo "$('input#edit_phone').val(\"".$my_stagiaire['phone_stagiaire']."\");";
                echo "$('input#edit_mail').val(\"".$my_stagiaire['mail_stagiaire']."\");";
                if ($my_stagiaire['man_stagiaire'] == 1){
                    echo "$('input#edit_man').prop('checked', true);";
                }                
                echo "$('input#edit_chambre').val(\"".$my_stagiaire['chambre_stagiaire']."\");";
                echo "$('input#edit_poste').val(\"".$my_stagiaire['poste_stagiaire']."\");";
                break;

            case "update":
                $sql = "UPDATE ".$pre."_stagiaire SET civil_stagiaire='".$_POST['civil_stagiaire']."', nom_stagiaire='".mb_strtoupper($_POST['nom_stagiaire'])."', prenom_stagiaire='".ucfirst($_POST['prenom_stagiaire'])."', naissance_stagiaire='".$_POST['naissance_stagiaire']."', adresse_stagiaire='".$_POST['adresse_stagiaire']."', cp_stagiaire=".$_POST['cp_stagiaire'].", ville_stagiaire='".mb_strtoupper($_POST['ville_stagiaire'])."', man_stagiaire=".$_POST['man_stagiaire'].", chambre_stagiaire='".mb_strtoupper($_POST['chambre_stagiaire'])."', poste_stagiaire=".$_POST['poste_stagiaire'].", phone_stagiaire=".$_POST['phone_stagiaire'].", mail_stagiaire='".$_POST['mail_stagiaire']."' WHERE id_stagiaire = ".$_POST['id_stagiaire'];
                $query = $link->query($sql);
                break;
            
            case "profil":
                $sql = "SELECT * FROM ".$pre."_stagiaire WHERE id_stagiaire = ".$_POST['id'];
                $query = $link->query($sql);
                $my_stagiaire = $query->fetch();
                echo json_encode($my_stagiaire);
                break;

            case 'trash':
                $sql = "DELETE FROM ".$pre."_stagiaire WHERE id_stagiaire=".$_POST['id'];
                $query = $link->query($sql);
                break;
        }
        break;

    case "entreprises":
        switch ($_POST['action']) {
            case "add":
                $sql = "INSERT INTO ".$pre."_entreprises (denomination, effectif, code_naf, adresse, ville, cp, tel, fax, civil_dir, nom_directeur, prenom_directeur, civil_maitre, nom_maitre, prenom_maitre, mail) VALUES ('".$_POST['denomination']."','".$_POST['effectif']."','".mb_strtoupper($_POST['code_naf'])."','".$_POST['adresse']."','".ucfirst($_POST['ville'])."','".$_POST['cp']."','".$_POST['tel']."','".$_POST['fax']."','".$_POST['civil_dir']."','".mb_strtoupper($_POST['nom_directeur'])."','".ucfirst($_POST['prenom_directeur'])."','".$_POST['civil_maitre']."','".mb_strtoupper($_POST['nom_maitre'])."','".ucfirst($_POST['prenom_maitre'])."','".$_POST['mail']."')";
                $query = $link->query($sql);
                echo $link->lastInsertId();
                break;

            case "edit":
                $sql = "SELECT * FROM ".$pre."_entreprises WHERE id = ".$_POST['id'];
                $query = $link->query($sql);
                $my_stagiaire = $query->fetch();
                echo json_encode($my_stagiaire);
                break;

            case "update":
                $sql = "UPDATE ".$pre."_entreprises SET denomination='".ucfirst($_POST['denomination'])."', effectif='".$_POST['effectif']."', code_naf='".$_POST['code_naf']."', adresse='".addslashes($_POST['adresse'])."', ville='".mb_strtoupper($_POST['ville'])."', cp='".$_POST['cp']."', tel='".$_POST['tel']."', fax='".$_POST['fax']."', civil_dir='".$_POST['civil_dir']."', nom_directeur='".mb_strtoupper($_POST['nom_directeur'])."', prenom_directeur='".ucfirst($_POST['prenom_directeur'])."', civil_maitre='".$_POST['civil_maitre']."', nom_maitre='".mb_strtoupper($_POST['nom_maitre'])."', prenom_maitre='".ucfirst($_POST['prenom_maitre'])."', mail='".$_POST['mail']."' WHERE id = ".$_POST['id_entreprise'];
                $query = $link->query($sql);
                break;

            case "trash":
                $sql = "DELETE FROM ".$pre."_entreprises WHERE id=".$_POST['id'];
                $query = $link->query($sql);
                break;
        }
        break;

    case "event":
        switch ($_POST['action']){
            case 'add':
                $end_event = date("Y-m-d", (strtotime($_POST['end_event']) + 86400));
                $sql = "INSERT INTO ".$pre."_events (id_promo, id_stagiaire, id_entreprise, start_event, end_event) VALUES (".$_POST['id_promo'].",".$_POST['id_stagiaire'].",".$_POST['id_entreprise'].",'".$_POST['start_event']."','".$end_event."')";
                $query = $link->query($sql);
                echo $end_event;
                break;

            case 'update':
                $start_event = date("Y-m-d", strtotime($_POST['start']));
                $end_event = date("Y-m-d", strtotime($_POST['end']));
                $sql = "UPDATE ".$pre."_events SET start_event = '".$start_event."', end_event = '".$end_event."' WHERE id=".$_POST['id'];
                $result = $link->query($sql);
                break;

            case 'info':
                $sql = "SELECT ".$pre."_events.id, ".$pre."_events.id_stagiaire, ".$pre."_events.id_entreprise, ".$pre."_events.start_event as start, ".$pre."_events.end_event as end, ".$pre."_events.color, ".$pre."_stagiaire.id_stagiaire, ".$pre."_stagiaire.civil_stagiaire, ".$pre."_stagiaire.nom_stagiaire, ".$pre."_stagiaire.prenom_stagiaire, ".$pre."_stagiaire.promo, ".$pre."_entreprises.id as id_entreprise, ".$pre."_entreprises.denomination, ".$pre."_entreprises.adresse, ".$pre."_entreprises.ville, ".$pre."_entreprises.cp, ".$pre."_entreprises.tel, ".$pre."_entreprises.mail, ".$pre."_entreprises.civil_dir, ".$pre."_entreprises.nom_directeur, ".$pre."_entreprises.prenom_directeur, ".$pre."_entreprises.civil_maitre, ".$pre."_entreprises.nom_maitre, ".$pre."_entreprises.prenom_maitre, ".$pre."_promo.id as id_promo, ".$pre."_promo.nom_promo FROM ".$pre."_events, ".$pre."_stagiaire, ".$pre."_entreprises, ".$pre."_promo WHERE ".$pre."_events.id = ".$_POST['id']." AND ".$pre."_stagiaire.id_stagiaire = ".$pre."_events.id_stagiaire AND ".$pre."_entreprises.id = ".$pre."_events.id_entreprise AND ".$pre."_stagiaire.promo = ".$pre."_promo.id";
                $query = $link->query($sql);
                $info_event = $query->fetch();
                echo json_encode($info_event);
                break;
            case 'statut':
                $sql = "UPDATE ".$pre."_events SET color = '".$_POST['statut']."' WHERE id = ".$_POST['id'];
                $result = $link->query($sql);
                break;

            case 'trash':
                $sql = "DELETE FROM ".$pre."_events WHERE id=".$_POST['id'];
                $query = $link->query($sql);
                break;
        }
        break;
}
?>