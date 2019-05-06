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
$sql = "SELECT id as id, nom_promo as nom_promo, debut_promo as debut, fin_promo as fin FROM ".$pre."_promo ORDER BY fin_promo DESC";
$query = $link->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="./favicon.png" />
  <link rel="stylesheet" href="theme.css">
  <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.8.1/js/all.min.js" integrity="sha256-HT9Zb3b1PVPvfLH/7/1veRtUvWObQuTyPn8tezb5HEg=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.0/dist/jquery.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha256-ZvOgfh+ptkpoa2Y4HkRY28ir89u/+VRyDE7sB7hEEcI=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>
  <script>

    $( document ).ready(function(){
        $( "#ajout_promo" ).submit(function( event ) {
          event.preventDefault();
          $.post( "change_bdd.php", {page: "promotions", action: "add", nom_promo: $("#nom_promo").val(), debut_promo: $("#debut_promo").val(), fin_promo: $("#fin_promo").val() }, function( data ) {
            $(".tbody").append('<tr id="'+data+'"><th scope="row">'+data+'</th><td>'+$("#nom_promo").val()+'</td><td>'+$("#debut_promo").val()+'</td><td>'+$("#fin_promo").val()+'</td><td><a class="btn btn-primary btn-sm mr-1" href="#" id="edit"><i class="far fa-fw fa-1x fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td></tr>');
          $('#modal_ajout_promo').modal('toggle');
          }, "json");
        });
        $( "#edit_promo" ).submit(function( event ) {
            event.preventDefault();
            var id_promo = $('#id_edit_promo').val();
            $.post( "change_bdd.php", { page: "promotions", action: "edit", id: id_promo, nom_promo: event.currentTarget[1].value, debut_promo: event.currentTarget[2].value, fin_promo: event.currentTarget[3].value }, function( ) { }, "json");
            $("tr[id='"+id_promo+"']").replaceWith('<tr id="'+id_promo+'"><th scope="row">'+id_promo+'</th><td>'+event.currentTarget[1].value+'</td><td>'+event.currentTarget[2].value+'</td><td>'+event.currentTarget[3].value+'</td><td><a class="btn btn-primary btn-sm mr-1" href="#" id="edit"><i class="far fa-fw fa-1x fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td>');
            $("#modal_edit_promo").modal('toggle');
        });
        $(document).on( "click", 'a', function(data){
          var action = data.currentTarget.id;
          var id_promo = data.currentTarget.offsetParent.parentNode.firstChild.innerText;
          switch (action){
            case "edit":
              $("input#id_edit_promo").val(id_promo);
              $("input#nom_edit_promo").val(data.currentTarget.parentNode.parentElement.cells[1].innerHTML);
              $("input#debut_edit_promo").val(data.currentTarget.parentNode.parentElement.cells[2].innerHTML);
              $("input#fin_edit_promo").val(data.currentTarget.parentNode.parentElement.cells[3].innerHTML);
              $("#modal_edit_promo").modal('toggle');
              break;
            case "trash":
              $.post( "change_bdd.php", {page: "promotions", action: action, id: id_promo }, function() {
                $( "tr[id='"+id_promo+"']" ).detach();
              });
              break;
          }
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
          <!--<li class="nav-item"> <a class="nav-link" href="index.php">Calendrier</a> </li>-->
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"> <a class="nav-link" href="entreprises.php">Entreprises</a> </li>
          <li class="nav-item"> <a class="nav-link" href="stagiaires.php">Stagiaires</a> </li>
          <li class="nav-item active"> <a class="nav-link" href="promotions.php">Promotions<br></a> </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="py-2">
    <div class="container-fluid border-right">
      <div class="row">
        <div class="col-md-12">
            <h3 class="">Gestion des promotions</h3>
            <table class="table table-bordered table-striped table-sm table-hover" id="table_promo">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Promotion</th>
                    <th scope="col">Date de début</th>
                    <th scope="col">Date de fin</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody class="tbody">
                <?php
                    while ($result = $query->fetch()) {
                        echo '<tr id="'.$result['id'].'"><th scope="row">'.$result['id'].'</th><td>'.$result['nom_promo'].'</td><td>'.$result['debut'].'</td><td>'.$result['fin'].'</td>';
                        echo '<td><a class="btn btn-primary btn-sm mr-1" href="#" id="edit"><i class="far fa-fw fa-edit"></i></a><a class="btn btn-sm btn-danger" href="#" id="trash"><i class="far fa-fw fa-1x fa-trash-alt"></i></a></td></tr>';
                    }
                ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_ajout_promo"><i class="fas fa-plus"></i></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" style="" id="modal_ajout_promo" >
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter</h5> <button type="button" class="close" data-dismiss="modal"> <span class="">×</span></button>
        </div>
        <div class="modal-body">
        <form id="ajout_promo">
            <div class="form-group"><label>Nom de la promotion</label><input type="text" class="form-control" placeholder="Entrer le nom de la promotion" id="nom_promo" required></div>
            <div class="form-row">
                <div class="form-group col-md-6"><label>Date d'entrée</label><input type="date" class="form-control" id="debut_promo"></div>
                <div class="form-group col-md-6"><label>Date de sortie</label><input type="date" class="form-control" id="fin_promo"></div>
            </div>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">Ajouter</button></div></form>
      </div>
    </div>
  </div>
  <div class="modal fade" style="" id="modal_edit_promo" >
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editer</h5> <button type="button" class="close" data-dismiss="modal"> <span class="">×</span></button>
        </div>
        <div class="modal-body">
        <form id="edit_promo">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">ID</span>
              </div>
              <input id="id_edit_promo" type="text" class="form-control" placeholder="ID" aria-label="ID" aria-describedby="basic-addon1" readonly>
            </div>
            <div class="form-group"><label>Nom de la promotion</label><input type="text" class="form-control" placeholder="Entrer le nom de la promotion" id="nom_edit_promo" required></div>
            <div class="form-row">
                <div class="form-group col-md-6"><label>Date d'entrée</label><input type="date" class="form-control" id="debut_edit_promo"></div>
                <div class="form-group col-md-6"><label>Date de sortie</label><input type="date" class="form-control" id="fin_edit_promo"></div>
            </div>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">Mettre à jour</button></div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>