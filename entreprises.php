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
$sql = "SELECT * FROM ".$pre."_entreprises ORDER BY denomination";
$query = $link->query($sql);
$all_entreprises = $query->fetchAll();
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
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-notify@3.1.3/bootstrap-notify.min.js"></script>
	<script>

    $( document ).ready(function(){
        $( "#ajout_entreprise" ).submit(function( event ) {
          event.preventDefault();
          var my_param = {page: "entreprises", action: "add", denomination: $("#denomination").val(), effectif: $("#effectif").val(), code_naf: $("#code_naf").val(), adresse: $("#adresse").val(), ville: $("#ville").val(), cp: $("#cp").val(), tel: $("#tel").val(), fax: $("#fax").val(), civil_dir: $("#civil_dir").val(), nom_directeur: $("#nom_directeur").val(), prenom_directeur: $("#prenom_directeur").val(), civil_maitre: $("#civil_maitre").val(), nom_maitre: $("#nom_maitre").val(), prenom_maitre: $("#prenom_maitre").val(), mail: $("#mail").val() };
          $.post( "change_bdd.php", my_param , function( data ) {
            $(".tbody").append('<tr id="'+data+'"><th scope="row">'+data+'</th><td>'+$("#denomination").val()+'</td><td>'+$("#adresse").val().replace(/\n/g, "<"+"br/>")+'<br />'+$("#cp").val()+' '+$("#ville").val()+'</td><td>'+$("#tel").val()+'</td><td>'+$("#civil_maitre").val()+' '+$("#nom_maitre").val().toUpperCase()+' '+$("#prenom_maitre").val().charAt(0).toUpperCase() + $("#prenom_maitre").val().substring(1).toLowerCase()+'</td><td><a class="btn btn-primary btn-sm mr-1" href="mailto:'+$("mail").val()+'" id="sendmail"><i class="far fa-fw fa-1x fa-paper-plane"></i></a><a class="btn btn-primary btn-sm mr-1" href="" id="edit" data-toggle="modal" data-target="#modal_edit_entreprise" data-whatever="'+data+'"><i class="far fa-fw fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td></tr>');
            $('#modal_ajout_entreprise').modal('toggle');
          }, "json");
        });
        $( "#edit_entreprise" ).submit(function( event ) {
            event.preventDefault();
            var id_entreprise = $('#id_edit_entreprise').val();
            var my_param = { page: "entreprises", action: "update", id_entreprise: id_entreprise, denomination: $("#edit_denomination").val(), effectif: $("#edit_effectif").val(), code_naf: $("#edit_code_naf").val(), adresse: $("#edit_adresse").val(), ville: $("#edit_ville").val(), cp: $("#edit_cp").val(), tel: $("#edit_tel").val(), fax: $("#edit_fax").val(), civil_dir: $("#edit_civil_dir").val(), nom_directeur: $("#edit_nom_directeur").val(), prenom_directeur: $("#edit_prenom_directeur").val(), civil_maitre: $("#edit_civil_maitre").val(), nom_maitre: $("#edit_nom_maitre").val(), prenom_maitre: $("#edit_prenom_maitre").val(), mail: $("#edit_mail").val() };
            $.post( "change_bdd.php", my_param, function ( ) {
              $("tr[id='"+id_entreprise+"']").replaceWith('<tr id="'+id_entreprise+'"><th scope="row">'+id_entreprise+'</th><td>'+event.currentTarget[1].value+'</td><td>'+event.currentTarget[4].value.replace(/\n/g, "<"+"br/>")+'<br />'+event.currentTarget[5].value+' '+event.currentTarget[6].value.toUpperCase()+'</td><td>'+event.currentTarget[7].value+'</td><td>'+event.currentTarget[12].value+' '+event.currentTarget[13].value.toUpperCase()+' '+event.currentTarget[14].value.charAt(0).toUpperCase() + event.currentTarget[14].value.substring(1).toLowerCase()+'</td><td><a class="btn btn-primary btn-sm mr-1" href="mailto:'+event.currentTarget[15].value+'" target="_blank" id="sendmail"><i class="far fa-fw fa-1x fa-paper-plane"></i></a><a class="btn btn-primary btn-sm mr-1" href="" id="edit" data-toggle="modal" data-target="#modal_edit_entreprise" data-whatever="'+id_entreprise+'"><i class="far fa-fw fa-1x fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td>');
              $("#modal_edit_entreprise").modal('toggle');
            });
        });
        $("#modal_edit_entreprise").on('show.bs.modal', function(event){
          var button = $(event.relatedTarget)
          var id_entreprise = button.data('whatever')
          $.post ("change_bdd.php", {page: "entreprises", action: "edit", id: id_entreprise}, function(data){
                  $("input#id_edit_entreprise").val(id_entreprise);
                  $('input#edit_denomination').val(data.denomination);
                  $('input#edit_effectif').val(data.effectif);
                  $('input#edit_code_naf').val(data.code_naf);
                  $('textarea#edit_adresse').val(data.adresse);
                  $('input#edit_cp').val(data.cp);
                  $('input#edit_ville').val(data.ville);
                  $('input#edit_tel').val(data.tel);
                  $('input#edit_fax').val(data.fax);
                  $('#edit_civil_dir option[value="'+data.civil_dir+'"]').prop('selected', true);
                  $('input#edit_nom_directeur').val(data.nom_directeur);
                  $('input#edit_prenom_directeur').val(data.prenom_directeur);
                  $('#edit_civil_maitre option[value="'+data.civil_maitre+'"]').prop('selected', true);
                  $('input#edit_nom_maitre').val(data.nom_maitre);
                  $('input#edit_prenom_maitre').val(data.prenom_maitre);
                  $('input#edit_mail').val(data.mail);
                  $("#modal_edit_stagiaire").modal('toggle');
          }, "json");
        })
        $(document).on( "click", 'a#trash', function(data){
          data.preventDefault();
          var id_entreprise = data.currentTarget.offsetParent.parentNode.firstChild.innerText;
					var denomination = data.currentTarget.offsetParent.parentNode.children[1].innerText;
          $.post( "change_bdd.php", {page: "entreprises", action: "trash", id: id_entreprise }, function() {
            $( "tr[id='"+id_entreprise+"']" ).detach();
						var notify = $.notify({
              icon: 'fas mr-1 fa-info-circle',
              title: '',
              message: "L'entreprise "+denomination+" a été supprimée de la base de données."
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
        });
    });

   </script>
</head>

<body class="m-3">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid"> <a class="navbar-brand" href="index.php">
        <i class="far d-inline fa-lg fa-calendar-alt mr-1"></i>
        <b> Gestion des PE</b>
      </a> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar17">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar17">
        <ul class="navbar-nav mr-auto">
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active"> <a class="nav-link" href="entreprises.php">Entreprises</a> </li>
          <li class="nav-item"> <a class="nav-link" href="stagiaires.php">Stagiaires</a> </li>
          <li class="nav-item"> <a class="nav-link" href="promotions.php">Promotions<br></a> </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="py-2">
    <div class="container-fluid border-right">
      <div class="row">
      <div class="col-md-6"><h3 class="">Gestion des entreprises</h3></div><div class="col-md-6 text-right"><?php echo count($all_entreprises)?> Entreprises</div>
      </div>
      <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-sm table-hover" id="table_entreprise">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Dénomination</th>
                    <th scope="col">Adresse</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Maitre de stage</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody class="tbody">
                <?php
                    foreach($all_entreprises as $result){
                      echo '<tr id="'.$result['id'].'"><th scope="row">'.$result['id'].'</th><td>'.$result['denomination'].'</td><td>'.nl2br($result['adresse']).'<br />'.$result['cp'].' '.$result['ville'].'</td><td>'.$result['tel'].'</td><td>'.$result['civil_maitre'].' '.$result['nom_maitre'].' '.$result['prenom_maitre'].'</td>';
                      echo '<td><a class="btn btn-primary btn-sm mr-1" href="mailto:'.$result['mail'].'" target="_blank" id="sendmail"><i class="far fa-fw fa-1x fa-paper-plane"></i></a><a role="button" class="btn btn-primary btn-sm mr-1" id="edit" href="" data-toggle="modal" data-target="#modal_edit_entreprise" data-whatever="'.$result['id'].'"><i class="far fa-fw fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td></tr>';
                    }
                ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_ajout_entreprise"><i class="fas fa-plus"></i></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" style="" id="modal_ajout_entreprise" >
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter</h5> <button type="button" class="close" data-dismiss="modal"> <span class="">×</span></button>
        </div>
        <div class="modal-body">
        <form id="ajout_entreprise">
            <div class="form-row">
              <div class="form-group col-md-7"><label>Dénomination</label><input type="text" class="form-control" placeholder="Entrez la dénomination de l'entreprise" id="denomination" required></div>
              <div class="form-group col-md-3"><label>Effectif</label><input type="number" class="form-control" placeholder="" id="effectif"></div>
              <div class="form-group col-md-2"><label>Code NAF</label><input type="text" class="form-control" id="code_naf" placeholder="Code NAF" required></div>
            </div>
            <div class="form-row mb-1"><label>Adresse</label><textarea class="form-control" rows="2" id="adresse" placeholder="Adresse de l'entreprise" required></textarea></div>
            <div class="form-row">
              <div class="form-group col-md-4"><label>CP</label><input type="text" class="form-control" id="cp" placeholder="Code Postal" required></div>
              <div class="form-group col-md-8"><label>Ville</label><input type="text" class="form-control" id="ville" placeholder="Ville" required></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6"><label>Téléphone</label><input type="text" class="form-control" id="tel" placeholder="Téléphone"></div>
              <div class="form-group col-md-6"><label>Fax</label><input type="text" class="form-control" id="fax" placeholder="Fax"></div>
            </div>
            <div class="form-row border-top pt-2"><h5>Directeur</h5></div>
            <div class="form-row">
              <div class="form-group col-md-2"><label class="">Civilité</label><select class="form-control" id="civil_dir">
                  <option value="Monsieur">Monsieur</option>
                  <option value="Madame">Madame</option>
                </select>
              </div>
              <div class="form-group col-md-5"><label>Nom</label><input type="text" class="form-control" id="nom_directeur" placeholder="Nom du directeur" required></div>
              <div class="form-group col-md-5"><label>Prénom</label><input type="text" class="form-control" id="prenom_directeur" placeholder="Prénom du directeur"></div>
            </div>
            <div class="form-row border-top pt-2"><h5>Maitre de stage</h5></div>
            <div class="form-row">
              <div class="form-group col-md-2"><label class="">Civilité</label><select class="form-control" id="civil_maitre">
                  <option value="Monsieur">Monsieur</option>
                  <option value="Madame">Madame</option>
                </select>
              </div>
              <div class="form-group col-md-5"><label>Nom</label><input type="text" class="form-control" id="nom_maitre" placeholder="Nom du maitre de stage" required></div>
              <div class="form-group col-md-5"><label>Prénom</label><input type="mail" class="form-control" id="prenom_maitre" placeholder="Prénom du maitre de stage"></div>
            </div>
            <div class="form-row"><label>Email</label><input type="text" class="form-control" id="mail" placeholder="Email de contact" required></div>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">Ajouter</button></div></form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal_edit_entreprise" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editer</h5> <button type="button" class="close" data-dismiss="modal"> <span class="">×</span></button>
        </div>
        <div class="modal-body">
        <form id="edit_entreprise">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">ID</span>
              </div>
              <input id="id_edit_entreprise" type="text" class="form-control" placeholder="ID" aria-label="ID" aria-describedby="basic-addon1" readonly>
            </div>
            <div class="form-row">
              <div class="form-group col-md-7"><label>Dénomination</label><input type="text" class="form-control" placeholder="Entrez la dénomination de l'entreprise" id="edit_denomination" required></div>
              <div class="form-group col-md-3"><label>Effectif</label><input type="number" class="form-control" placeholder="" id="edit_effectif"></div>
              <div class="form-group col-md-2"><label>Code NAF</label><input type="text" class="form-control" id="edit_code_naf" placeholder="Code NAF" required></div>
            </div>
            <div class="form-row mb-1"><label>Adresse</label><textarea class="form-control" rows="2" id="edit_adresse" placeholder="Adresse de l'entreprise" required></textarea></div>
            <div class="form-row">
              <div class="form-group col-md-4"><label>CP</label><input type="text" class="form-control" id="edit_cp" placeholder="Code Postal" required></div>
              <div class="form-group col-md-8"><label>Ville</label><input type="text" class="form-control" id="edit_ville" placeholder="Ville" required></div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6"><label>Téléphone</label><input type="text" class="form-control" id="edit_tel" placeholder="Téléphone"></div>
              <div class="form-group col-md-6"><label>Fax</label><input type="text" class="form-control" id="edit_fax" placeholder="Fax"></div>
            </div>
            <div class="form-row border-top pt-2"><h5>Directeur</h5></div>
            <div class="form-row">
              <div class="form-group col-md-2"><label class="">Civilité</label><select class="form-control" id="edit_civil_dir">
                  <option value="Monsieur">Monsieur</option>
                  <option value="Madame">Madame</option>
                </select>
              </div>
              <div class="form-group col-md-5"><label>Nom</label><input type="text" class="form-control" id="edit_nom_directeur" placeholder="Nom du directeur" required></div>
              <div class="form-group col-md-5"><label>Prénom</label><input type="text" class="form-control" id="edit_prenom_directeur" placeholder="Prénom du directeur"></div>
            </div>
            <div class="form-row border-top pt-2"><h5>Maitre de stage</h5></div>
            <div class="form-row">
              <div class="form-group col-md-2"><label class="">Civilité</label><select class="form-control" id="edit_civil_maitre">
                  <option value="Monsieur">Monsieur</option>
                  <option value="Madame">Madame</option>
                </select>
              </div>
              <div class="form-group col-md-5"><label>Nom</label><input type="text" class="form-control" id="edit_nom_maitre" placeholder="Nom du maitre de stage" required></div>
              <div class="form-group col-md-5"><label>Prénom</label><input type="mail" class="form-control" id="edit_prenom_maitre" placeholder="Prénom du maitre de stage"></div>
            </div>
            <div class="form-row"><label>Email</label><input type="text" class="form-control" id="edit_mail" placeholder="Email de contact" required></div>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">Mettre à jour</button> <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button></div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
