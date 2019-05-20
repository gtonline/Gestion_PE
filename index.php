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
$sql = "SELECT ".$pre."_events.id as id, ".$pre."_events.id_stagiaire as id_event_stagiaire, ".$pre."_events.id_entreprise as id_event_entreprise, ".$pre."_events.start_event as start, ".$pre."_events.end_event as end, ".$pre."_events.color as color, ".$pre."_stagiaire.id_stagiaire as id_stagiaire, ".$pre."_stagiaire.nom_stagiaire as nom_stagiaire, ".$pre."_stagiaire.prenom_stagiaire as prenom_stagiaire, ".$pre."_entreprises.id as id_event_entreprise, ".$pre."_entreprises.denomination as denomination FROM ".$pre."_events, ".$pre."_stagiaire, ".$pre."_entreprises WHERE ".$pre."_stagiaire.id_stagiaire = ".$pre."_events.id_stagiaire AND ".$pre."_entreprises.id = ".$pre."_events.id_entreprise ORDER BY ".$pre."_events.start_event";
$query = $link->query($sql);
$json_events = "";
while ($result = $query->fetch()) {
	$result['title'] = $result['nom_stagiaire']." ".$result['prenom_stagiaire']." (".$result['denomination'].")";
	if ($result['color'] == "yellow") {
    $result['textColor'] = "#333";
	} else {
    $result['textColor'] = "white";
  }
  $json_events .= json_encode(array_filter($result, "vide")) . ", ";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
  <link rel="icon" type="image/png" href="favicon.png" />
	<link rel="stylesheet" href="theme.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.1.0/main.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.1.0/main.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@4.1.0/main.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@3.7.0/animate.min.css">
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.1.0/main.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.1.0/main.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list@4.1.0/main.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.1.0/locales/fr.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@4.1.0/main.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.8.2/js/all.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.15.0/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-notify@3.1.3/bootstrap-notify.min.js"></script>
  <script>

    $( document ).ready(function(){
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'interaction', 'list' ],
        timeZone: 'UTC',
        editable: true,
        eventStartEditable: true,
        eventResizableFromStart: true,
        eventDurationEditable: true,
	      locale: 'fr',
        defaultView: 'dayGridMonth',
        weekends: true,
        weekNumbers: true,
        weekNumbersWithinDays: true,
        selectable: true,
        unselectAuto: true,
        defaultView: 'dayGridMonth',
        displayEventTime: false,
        height: 'parent',
        events: [<?php echo $json_events ?>],

        eventResize: function(info) {
			    $.post( "change_bdd.php", {page: 'event', action: 'update', id:info.event.id, start:info.event.start.toISOString(), end:info.event.end.toISOString()}, function(data) {}, "json");
        },
        eventDrop: function(info) {
          $.post( "change_bdd.php", {page: 'event', action: 'update', id:info.event.id, start:info.event.start.toISOString(), end:info.event.end.toISOString()}, function(data) {}, "json");
        },
        eventClick: function(info) {
          $.post( "change_bdd.php", {page: 'event', action: 'info', id: info.event.id}, function( data_event ) {
            $("#id_info_event").text(info.event.id);
            $("#info_promo").text(data_event.nom_promo);
            $("#info_stagiaire").text(data_event.civil_stagiaire+" "+data_event.nom_stagiaire+" "+data_event.prenom_stagiaire);
            $("#info_entreprise").text(data_event.denomination);
            $("#adresse1").html(data_event.adresse.replace(/(\r\n|\n|\r)/gm,"<br />"));
            $("#adresse2").text(data_event.cp+" "+data_event.ville);
            $("#directeur").text(data_event.civil_dir+" "+data_event.nom_directeur+" "+data_event.prenom_directeur);
            $("#maitre").text(data_event.civil_maitre+" "+data_event.nom_maitre+" "+data_event.prenom_maitre);
            $("#tel").text(data_event.tel);
            $("#mail").attr("href", "mailto:"+data_event.mail);
            $("#mail").text(data_event.mail);
            $('#select_suivi option[value="'+data_event.color+'"]').prop('selected', true);
			    }, "json");
          $('#info_event').modal('toggle');
        },

        header: {
          left: 'title',
          right: 'today,prev,next'
        },

        select: function(info) {
          $("#start_event").val(info.startStr);
          $("#end_event").val(info.endStr);
          $("#modal_add_event").modal('toggle');
        }
      });

      calendar.render();

      $("#add_event").submit(function(event){
        event.preventDefault();
        var nom_stagiaire = document.getElementById("stagiaire_choose").options.item(document.getElementById("stagiaire_choose").options.selectedIndex).text;
        var nom_entreprise = document.getElementById("entreprise_choose").options.item(document.getElementById("entreprise_choose").options.selectedIndex).text;
        $.post( "change_bdd.php", {page: "event", action: "add", id_promo: $("#promo_choose").val(), id_stagiaire: $("#stagiaire_choose").val(), id_entreprise: $("#entreprise_choose").val(), start_event: $("#start_event").val(), end_event: $("#end_event").val()}, function(data){
          calendar.addEvent({
            title: nom_stagiaire+" ("+nom_entreprise+")",
            start: $("#start_event").val(),
            end: data,
            textColor: "white"
          });
          $("#modal_add_event").modal('toggle');
        });
      });

      $( "select#promo_choose").change(function(event){
          $("#stagiaire_choose").empty();
          $.post( "change_bdd.php", {page: "stagiaires", action: "list", id_promo: $( this ).val()}, function(data){
            obj = JSON.parse(data);
            obj.forEach(function(element) {
              $("#stagiaire_choose").append('<option value="'+element.id_stagiaire+'">'+element.nom_stagiaire+' '+element.prenom_stagiaire+'</option>');
            });
          });
      });

      $("select#select_suivi").change(function(event){
        var new_color = $(this).val();
        var id_event = $("span#id_info_event").text();
        $.post( "change_bdd.php", {page: 'event', action: 'statut', id:id_event, statut:new_color}, function() {
          var my_event = calendar.getEventById(id_event);
          my_event.setProp( "color", new_color );
        });
      });

      $("#trash_event").click(function(){
        var id_event = $("span#id_info_event").text();
        $.post( "change_bdd.php", {page: 'event', action: 'trash', id:id_event}, function() {
          $('#info_event').modal('toggle');
          var my_event = calendar.getEventById(id_event);
          my_event.remove();
          var notify = $.notify({
            icon: 'fas mr-1 fa-info-circle',
            title: $("#info_stagiaire").text(),
            message: "<br />La période dans l'entreprise "+$("#info_entreprise").text()+" a été supprimée."
          },{
            type: "success",
            delay: 2000,
            timer: 500,
            animate: {
              enter: 'animated fadeInDown',
              exit: 'animated zoomOutUp'
            }
          });
        });
      });

			$("#print").click(function(info){
				var id_event = $("span#id_info_event").text();
				alert ("La fonction n'est pas encore implémentée.");
				//var my_window = window.open("event_print.php?id="+id_event, "_blank", "fullscreen=yes,location=no,menubar=no,status=no,titlebar=no,toolbar=no");
				/*var my_window = window.open("./origin/Q-F-Enquete de satisfaction entreprise.pdf", "_blank", "fullscreen=yes,location=no,menubar=no,status=no,titlebar=no,toolbar=no");
				console.log(my_window);
				my_window.onload = function(){
					alert ("La fenêtre est chargée");
					my_window.print();
					my_window.close();
				};*/
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
          <li class="nav-item"> <a class="nav-link" href="promotions.php">Promotions<br></a> </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="py-2">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12" id="calendar"></div>
      </div>
    </div>
  </div>
  <div class="modal fade" role="dialog" id="info_event" >
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Informations</h5> <button type="button" class="close" data-dismiss="modal"> <span>×</span></button>
        </div>
        <div class="modal-body">
          <div class="row invisible"><span id="id_info_event" class="invisible col-md-12"></span></div>
          <div class="row">
            <div class="col-md-3">Promotion :</div><div class="col-md-9" id="info_promo"></div>
            <div class="col-md-3">Stagiaire :</div><div class="col-md-9" id="info_stagiaire"></div>
            <div class="col-md-3">Entreprise :</div><div class="col-md-9" id="info_entreprise"></div>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><i class="fas text-primary mr-2 fa-address-book"></i><span id="adresse1"></span></li>
            <li class="list-group-item"><i class="fas text-primary mr-2 fa-city"></i><span id="adresse2"></span></li>
            <li class="list-group-item"><i class="fas text-primary mr-2 fa-users"></i><span id="directeur"></span></li>
            <li class="list-group-item"><i class="fas text-primary mr-2 fa-chalkboard-teacher"></i><span id="maitre"></span></li>
            <li class="list-group-item"><i class="fas text-primary mr-2 fa-phone"></i><span id="tel"></a></li>
            <li class="list-group-item list-group-item-action"><i class="fas text-primary mr-2 fa-envelope"></i><a href="" target="_blank" id="mail"></a></li>
          </ul>
          <div class="row">
          <div class="col-md-12">
          <form>
            <div class="form-group" id="form-suivi">
              <label>Suivi de la période en entreprise</label>
              <select class="form-control" id="select_suivi">
                <option value="#3788d8">Pas de suivi</option>
                <option value="#dc3545">A rappeler</option>
                <option value="#ffc107">A visiter</option>
                <option value="#28a745">Suivi terminé</option>
               </select>
            </div>
          </form>
          </div>
          </div>
        </div>
        <div class="modal-footer"> <button type="button" class="btn btn-primary" id="print"><i class="fas fa-lg fa-print"></i></button><button type="button" class="btn btn-primary"><i class="fas fa-edit fa-lg"></i></button><button class="btn btn-danger" id="trash_event"><i class="fa fa-fw fa-trash fa-lg"></i></button> </div>
      </div>
    </div>
  </div>
  <div class="modal fade" role="dialog" id="modal_add_event" >
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ajout</h5> <button type="button" class="close" data-dismiss="modal"> <span class="">×</span></button>
        </div>
        <div class="modal-body">
          <form id="add_event">
            <div class="form-group"><label>Promotion</label>
              <select class="form-control" id="promo_choose">
                <?php
                  $sql_promo = "SELECT id, nom_promo FROM ".$pre."_promo ORDER BY id ASC";
                  $query_promo = $link->query($sql_promo);
                  foreach ($query_promo as $row){
                    echo '<option value="'.$row['id'].'">'.$row['nom_promo'].'</option>';
                  }
                 ?>
               </select>
            </div>
            <div class="form-group"><label>Stagiaire</label>
              <select class="form-control" id="stagiaire_choose">
              </select>
            </div>
            <div class="form-group"><label>Entreprise</label>
              <select class="form-control" id="entreprise_choose">
                <?php
                  $sql_entreprises = "SELECT id, denomination FROM ".$pre."_entreprises ORDER BY denomination ASC";
                  $query_entreprises = $link->query($sql_entreprises);
                  foreach ($query_entreprises as $row){
                    echo '<option value="'.$row['id'].'">'.$row['denomination'].'</option>';
                  }
                 ?>
              </select>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6"><label>Date de début</label><input type="date" class="form-control" id="start_event"></div>
                <div class="form-group col-md-6"><label>Date de fin</label><input type="date" class="form-control" id="end_event"></div>
            </div>
        </div>
        <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i></button></div></form>
      </div>
    </div>
  </div>
  <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 200px;">
  <div class="toast" style="position: absolute; top: 0; right: 0;">
    <div class="toast-header">
      <img src="" class="rounded mr-2" alt="">
      <strong class="mr-auto">Bootstrap</strong>
      <small>11 mins ago</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
  </div>
</div>
  <script>
  $( document ).ready(function(){
    $.post( "change_bdd.php", {page: "stagiaires", action: "list", id_promo: $( "select#promo_choose" ).val()}, function (data, status) {
      obj = JSON.parse(data);
      obj.forEach(function(element) {
        $("#stagiaire_choose").append('<option value="'+element.id_stagiaire+'">'+element.nom_stagiaire+' '+element.prenom_stagiaire+'</option>');
      });
    });
  });
  </script>
</body>
</html>
