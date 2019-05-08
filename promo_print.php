<?php
    include('bdd.php');
    $sql = "SELECT ".$pre."_promo.id, ".$pre."_promo.nom_promo, ".$pre."_promo.debut_promo, ".$pre."_promo.fin_promo, ".$pre."_stagiaire.id_stagiaire, ".$pre."_stagiaire.nom_stagiaire, ".$pre."_stagiaire.prenom_stagiaire, ".$pre."_stagiaire.naissance_stagiaire, ".$pre."_stagiaire.cp_stagiaire, ".$pre."_stagiaire.ville_stagiaire, ".$pre."_stagiaire.man_stagiaire, ".$pre."_stagiaire.chambre_stagiaire, ".$pre."_stagiaire.poste_stagiaire, ".$pre."_stagiaire.promo FROM ".$pre."_promo, ".$pre."_stagiaire WHERE ".$pre."_promo.id = ".$_GET['id']." AND ".$pre."_stagiaire.promo = ".$_GET['id']." ORDER BY nom_stagiaire ASC";
    $query = $link->query($sql);
    $nbr = $query->rowCount();
    $print = $query->fetchAll();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Promotion <?php echo $print['0']['nom_promo'] ?></title>
        <link rel="stylesheet" href="theme_print.css" media="print">
        <link rel="stylesheet" href="theme.css" media="screen">
        <style type="text/css" media="print">
            @page {
            size: landscape;
            margin-top: 2cm
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.0/dist/jquery.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha256-ZvOgfh+ptkpoa2Y4HkRY28ir89u/+VRyDE7sB7hEEcI=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-notify@3.1.3/bootstrap-notify.min.js" integrity="sha256-DRllCE/8rrevSAnSMWB4XO3zpr+3WaSuqUSNLD5NAzg=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>
    </head>
    <body class="m-1">
        <div class="pb-4">
            <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <h2 class="text-center"><?php echo $print['0']['nom_promo'] ?></h2>
                </div>
            </div>
            </div>
        </div>
        <div class="pb-4">
            <div class="container-fluid">
            <div class="row">
                <div class="bg-dark text-light text-left col-md-4 font-weight-bold">Entrée le <?php echo date("d/m/Y", strtotime($print['0']['debut_promo'])); ?></div>
                <div class="bg-dark text-light text-left col-md-4 font-weight-bold">Sortie le <?php echo date("d/m/Y", strtotime($print['0']['fin_promo'])); ?></div>
                <div class="bg-dark text-light text-right col-md-4 font-weight-bold"><?php echo $nbr ?> stagiaires</div>
            </div>
            </div>
        </div>
        <div class="pb-4">
            <div class="container-fluid">
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th class="text-center">NOM</th>
                        <th class="text-center">PRENOM</th>
                        <th class="text-center">AGE</th>
                        <th class="text-center">CP</th>
                        <th class="text-center">VILLE</th>
                        <th class="text-center">MAN</th>
                        <th class="text-center">CHAMBRE</th>
                        <th class="text-center">POSTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($print as $row){
                            if ($row['man_stagiaire'] == 1) { $man = "<b>X</b>"; } else {$man="";};
                            $age = date("Y") - date("Y", strtotime($row['naissance_stagiaire']));
                            echo "<tr>
                            <th>".$row['nom_stagiaire']."</th>
                            <td>".$row['prenom_stagiaire']."</td>
                            <td>".$age." (".date("d/m/Y", strtotime($row['naissance_stagiaire'])).")</td>
                            <td>".$row['cp_stagiaire']."</td>
                            <td>".$row['ville_stagiaire']."</td>
                            <td class=\"text-center\">".$man."</td>
                            <td class=\"text-center\">".$row['chambre_stagiaire']."</td>
                            <td class=\"text-center\">".$row['poste_stagiaire']."</td></tr>";
                        }
                        ?>
                    </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>
