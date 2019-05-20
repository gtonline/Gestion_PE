<?php
include 'bdd.php';

// Récupération des évenements
function vide($var)
{
    // retourne lorsque l'entrée n'est pas vide
	if (!empty($var)) {
		return($var);
	}
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="./favicon.png" />
	<link rel="stylesheet" href="theme.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@3.7.0/animate.min.css">
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.8.2/js/all.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.15.0/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-notify@3.1.3/bootstrap-notify.min.js"></script>
	<script>
    $( document ).ready(function(){
      $(document).on("click", 'a', function(data){
        var action = data.currentTarget.id;
        var id_row = data.currentTarget.offsetParent.parentNode.firstChild.innerText;
				var nom_stagiaire = data.currentTarget.offsetParent.parentNode.children[1].innerText;
        switch (action){
          case "edit":
            $.post ("change_bdd.php", {page: "stagiaires", action: "edit", id: id_row}, function(){
              $("#modal_edit_stagiaire").modal('toggle');
            }, "script");
            break;
          case "trash":
            $.post( "change_bdd.php", {page: "stagiaires", action: "trash", id: id_row }, function() {
              $( "tr[id='"+id_row+"']" ).detach();
							var notify = $.notify({
                icon: 'fas mr-1 fa-info-circle',
                title: '',
                message: nom_stagiaire+" a été supprimée de la base de données."
              },{
                type: "success",
                placement: {
                  from: "bottom",
                  align: "center"
                },
                delay: 4000,
                timer: 500,
                animate: {
                  enter: 'animated fadeInUp',
                  exit: 'animated fadeOutDown'
                }
              });
            });
            break;
          case "profil":
            $.post( "change_bdd.php", {page: "stagiaires", action: "profil", id: id_row }, function(data) {
              if (data.man_stagiaire == 1){
                $( "#profil_man" ).removeClass( "fa-square" );
                $("#profil_man").addClass("fa-check-square");
              } else  {
                $( "#profil_man" ).removeClass( "fa-check-square" );
                $("#profil_man").addClass("fa-square");
              }
              $("h5#profil_nom").html(data.civil_stagiaire+" "+data.nom_stagiaire+" "+data.prenom_stagiaire);
              $("span#profil_naissance").html(data.naissance_stagiaire);
              $("span#profil_adresse").html(data.adresse_stagiaire+"<br />"+data.cp_stagiaire+" "+data.ville_stagiaire);
              $("span#profil_phone").html(data.phone_stagiaire);
              $("span#profil_mail").html(data.mail_stagiaire);
              $("span#profil_chambre").html(data.chambre_stagiaire+" ("+data.poste_stagiaire+")");
              if (data.chambre_stagiaire == '') {
                $("#li_chambre").hide();
              } else {
                $("#li_chambre").show();
              }
            }, "json");
            $("#modal_profil_stagiaire").modal('toggle');
            break;
        }
      });
      $( "select#promo_choose").change(function(event){
          $(".tbody").empty();
          $.post( "change_bdd.php", {page: "stagiaires", action: "list", id_promo: $( this ).val()}, function(data){
            obj = JSON.parse(data);
            obj.forEach(function(element) {
              $(".tbody").append('<tr id="'+element.id_stagiaire+'"><th scope="row">'+element.id_stagiaire+'</th><td>'+element.civil_stagiaire+' '+element.nom_stagiaire+' '+element.prenom_stagiaire+'</td><td>'+element.adresse_stagiaire+'<br />'+element.cp_stagiaire+' '+element.ville_stagiaire+'</td><td>'+element.naissance_stagiaire+'</td><td>'+element.phone_stagiaire+'</td><td><a class="btn btn-primary btn-sm mr-1" href="#" id="profil"><i class="far fa-fw fa-1x fa-user"></i></a><a class="btn btn-primary btn-sm mr-1" href="mailto:'+element.mail_stagiaire+'" id="sendmail"><i class="far fa-fw fa-1x fa-paper-plane"></i></a><a class="btn btn-primary btn-sm mr-1" href="#" id="edit"><i class="far fa-fw fa-1x fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td></tr>');
            });
          });
          $("#promo").val($( "select#promo_choose" ).val());
      });
        $( "#ajout_stagiaire" ).submit(function( event ) {
          event.preventDefault();
          var man_checked = $( "input#man:checked" ).length;
          var my_data = { page: "stagiaires", action: "add", civil_stagiaire: $('#civilite').val(), nom_stagiaire: $('#nom').val(), prenom_stagiaire: $('#prenom').val(), naissance_stagiaire: $('#naissance').val(), adresse_stagiaire: $('#adresse').val(), cp_stagiaire: $('#cp').val(), ville_stagiaire: $('#ville').val(), man_stagiaire: man_checked, chambre_stagiaire: $('#chambre').val(), poste_stagiaire: $('#poste').val(), phone_stagiaire: $('#phone').val(), mail_stagiaire: $('input#mail').val(), promo: $('#promo').val() };
          $.post( "change_bdd.php", my_data, function( data, status, xhr ) {
            $('#modal_ajout_stagiaire').modal('toggle');
          }, "script");
        });
        $( "#edit_stagiaire" ).submit(function( event ) {
            event.preventDefault();
            var id_stagiaire = $('#id_edit_stagiaire').val();
            var man_checked = $( "input#edit_man:checked" ).length;
            var my_data = { page: "stagiaires", action: "update", id_stagiaire: id_stagiaire, civil_stagiaire: $('#edit_civilite').val(), nom_stagiaire: $('#edit_nom').val(), prenom_stagiaire: $('#edit_prenom').val(), naissance_stagiaire: $('#edit_naissance').val(), adresse_stagiaire: $('#edit_adresse').val(), cp_stagiaire: $('#edit_cp').val(), ville_stagiaire: $('#edit_ville').val(), man_stagiaire: man_checked, chambre_stagiaire: $('#edit_chambre').val(), poste_stagiaire: $('#edit_poste').val(), phone_stagiaire: $('#edit_phone').val(), mail_stagiaire: $('#edit_mail').val() };
            $.post( "change_bdd.php", my_data, function() {
              $("tr[id='"+id_stagiaire+"']").replaceWith('<tr id='+id_stagiaire+'><th scope="row">'+id_stagiaire+'</th><td>'+event.currentTarget[1].value+' '+event.currentTarget[3].value+' '+event.currentTarget[4].value+'</td><td>'+event.currentTarget[5].value+'<br /> '+event.currentTarget[6].value+' '+event.currentTarget[7].value.toUpperCase()+'</td><td>'+event.currentTarget[2].value+'</td><td>'+event.currentTarget[8].value+'</td><td><a class="btn btn-primary btn-sm mr-1" href="#" id="profil"><i class="far fa-fw fa-1x fa-user"></i></a><a class="btn btn-primary btn-sm mr-1" href="mailto:'+event.currentTarget[9].value+'" id="sendmail"><i class="far fa-fw fa-1x fa-paper-plane"></i></a><a class="btn btn-primary btn-sm mr-1" href="#" id="edit"><i class="far fa-fw fa-1x fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td></tr>');
              $("#modal_edit_stagiaire").modal('toggle');
            });
        });
    });

   </script>
</head>

<body class="m-3">
  <!-- Début de la barre de navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php"><i class="far d-inline fa-lg fa-calendar-alt mr-1"></i><b> Gestion des PE</b></a>
      <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar17"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navbar17">
        <ul class="navbar-nav mr-auto">
          <!--<li class="nav-item"> <a class="nav-link" href="index.php">Calendrier</a> </li>-->
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"> <a class="nav-link" href="entreprises.php">Entreprises</a> </li>
          <li class="nav-item active"> <a class="nav-link" href="stagiaires.php">Stagiaires</a> </li>
          <li class="nav-item"> <a class="nav-link" href="promotions.php">Promotions<br></a> </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Fin de la barre de navigation -->
  <!-- Début de la page principale -->
  <div class="py-2">
    <div class="container-fluid border-right">
      <div class="row">
        <div class="col-md-12">
            <h3 class="">Gestion des stagiaires</h3>
            <form class="form-inline mb-2">
            <div class="form-group">
                <label for="promo_choose" class="mr-1">Selectionnez une promotion</label>
                <select class="form-control" id="promo_choose">
                <?php
                  $sql_promo = "SELECT id, nom_promo FROM ".$pre."_promo ORDER BY id DESC";
                  $query_promo = $link->query($sql_promo);
                  foreach ($query_promo as $row){
                    echo '<option value="'.$row['id'].'">'.$row['nom_promo'].'</option>';
                  }
                ?>
                </select>
              </div>
              </form>
            <table class="table table-bordered table-striped table-sm table-hover" id="table_stagiaire">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Stagiaire</th>
                    <th scope="col">Adresse</th>
                    <th scope="col">Date de naissance</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody class="tbody">
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_ajout_stagiaire"><i class="fas fa-plus"></i></button>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin de la page principale -->
  <!-- Début de la boite de dialogue d'ajout -->
  <div class="modal fade" style="" id="modal_ajout_stagiaire" >
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter</h5> <button type="button" class="close" data-dismiss="modal"> <span class="">×</span></button>
        </div>
        <div class="modal-body">
        <form id="ajout_stagiaire">
            <input type="hidden" id="promo" value="">
            <div class="form-row">
              <div class="form-group col-md-6"><label class="">Civilité</label><select class="form-control" id="civilite" required="required">
                <option value="Monsieur">Monsieur</option>
                <option value="Madame">Madame</option>
              </select></div>
              <div class='form-group col-md-6'><label class="">Date de naissance</label><input type="date" class="form-control" id="naissance" required="required"></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6"><label>Nom</label><input type="text" class="form-control" id="nom" required="required" placeholder="Nom"></div>
              <div class="form-group col-md-6"><label>Prénom</label><input type="text" class="form-control" id="prenom" required="required" placeholder="Prénom"></div>
            </div>
            <div class="form-group"><label>Adresse</label><input type="text" class="form-control" id="adresse" placeholder="Adresse"></div>
            <div class="form-row">
              <div class="form-group col-md-3"><label>Code postal</label><input type="text" class="form-control" id="cp" placeholder="Code Postal"></div>
              <div class="form-group col-md-9"><label>Ville</label><input type="text" class="form-control" id="ville" placeholder="Ville"></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4"><label>Téléphone</label><input type="tel" class="form-control" placeholder="Téléphone" id="phone"></div>
              <div class="form-group col-md-8"><label>Email</label><input type="email" class="form-control" id="mail" placeholder="Email"></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4"><div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="man" value="1"><label class="form-check-label" for="man">Mise à niveau</label></div>
              </div>
              <div class="form-group col-md-4"><label>Chambre</label><input type="text" class="form-control" id="chambre" placeholder="Numéro de chambre"></div>
              <div class="form-group col-md-4"><label>Poste</label><input type="number" class="form-control" id="poste" placeholder="Numéro de poste"></div>
            </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">Ajouter</button></div>
      </form>
      </div>
    </div>
  </div>
  </div>
  <!-- Fin de la boite de dialogue d'ajout -->
  <!-- Début de la boite de dialogue d'edition -->
  <div class="modal fade" style="" id="modal_edit_stagiaire" >
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editer</h5> <button type="button" class="close" data-dismiss="modal"> <span class="">×</span></button>
        </div>
        <div class="modal-body">
        <form id="edit_stagiaire">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">ID</span>
              </div>
              <input id="id_edit_stagiaire" type="text" class="form-control" placeholder="ID" aria-label="ID" aria-describedby="basic-addon1" readonly>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6"><label class="">Civilité</label><select class="form-control" id="edit_civilite" required="required">
                <option value="Monsieur">Monsieur</option>
                <option value="Madame">Madame</option>
              </select></div>
              <div class='form-group col-md-6'><label class="">Date de naissance</label><input type="date" class="form-control" id="edit_naissance" required="required"></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6"><label>Nom</label><input type="text" class="form-control" id="edit_nom" required="required" placeholder="Nom"></div>
              <div class="form-group col-md-6"><label>Prénom</label><input type="text" class="form-control" id="edit_prenom" required="required" placeholder="Prénom"></div>
            </div>
            <div class="form-group"><label>Adresse</label><input type="text" class="form-control" id="edit_adresse" placeholder="Adresse"></div>
            <div class="form-row">
              <div class="form-group col-md-3"><label>Code postal</label><input type="text" class="form-control" id="edit_cp" placeholder="Code Postal"></div>
              <div class="form-group col-md-9"><label>Ville</label><input type="text" class="form-control" id="edit_ville" placeholder="Ville"></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4"><label>Téléphone</label><input type="tel" class="form-control" placeholder="Téléphone" id="edit_phone"></div>
              <div class="form-group col-md-8"><label>Email</label><input type="email" class="form-control" id="edit_mail" placeholder="Email"></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4"><div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="edit_man" value="1"><label class="form-check-label" for="man">Mise à niveau</label></div></div>
              <div class="form-group col-md-4"><label>Chambre</label><input type="text" class="form-control" id="edit_chambre" placeholder="Numéro de chambre"></div>
              <div class="form-group col-md-4"><label>Poste</label><input type="number" class="form-control" id="edit_poste" placeholder="Numéro de poste"></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Mettre à jour</button></div>
        </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin de la boite d'edition -->
  <!-- Début de la boite profil -->
  <div class="modal fade" id="modal_profil_stagiaire">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="profil_nom"></h5> <button type="button" class="close" data-dismiss="modal"> <span>×</span> </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <ul class="list-group list-group-flush text-left">
                  <li class="list-group-item"><i class="fas text-primary mr-2 fa-birthday-cake"></i><span id="profil_naissance"></span></li>
                  <li class="list-group-item"><i class="fas text-primary mr-2 fa-home"></i> <span id="profil_adresse"></span></li>
                  <li class="list-group-item"><i class="fas text-primary mr-2 fa-phone"></i><span id="profil_phone"></span></li>
                  <li class="list-group-item"><i class="far text-primary mr-2 fa-paper-plane"></i><span id="profil_mail"></span></li>
                  <li class="list-group-item"><i class="far text-primary mr-2 fa-square" id="profil_man"></i> Mise à niveau</li>
                  <li class="list-group-item" id="li_chambre"><i class="fas text-primary mr-2 fa-bed"></i><span id="profil_chambre"></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button> </div>
      </div>
    </div>
  </div>
  <!-- Fin de la boite profil -->
  <script>
  $("#promo").val($( "select#promo_choose" ).val());
  $.post( "change_bdd.php", {page: "stagiaires", action: "list", id_promo: $( "select#promo_choose" ).val()}, function (data, status) {
    obj = JSON.parse(data);
    obj.forEach(function(element) {
      $(".tbody").append('<tr id="'+element.id_stagiaire+'"><th scope="row">'+element.id_stagiaire+'</th><td>'+element.civil_stagiaire+' '+element.nom_stagiaire+' '+element.prenom_stagiaire+'</td><td>'+element.adresse_stagiaire+'<br />'+element.cp_stagiaire+' '+element.ville_stagiaire+'</td><td>'+element.naissance_stagiaire+'</td><td>'+element.phone_stagiaire+'</td><td><a class="btn btn-primary btn-sm mr-1" href="#" id="profil"><i class="far fa-fw fa-1x fa-user"></i></a><a class="btn btn-primary btn-sm mr-1" href="mailto:'+element.mail_stagiaire+'" id="sendmail"><i class="far fa-fw fa-1x fa-paper-plane"></i></a><a class="btn btn-primary btn-sm mr-1" href="#" id="edit"><i class="far fa-fw fa-1x fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td></tr>');
    });
  });
  </script>
</body>
</html>
