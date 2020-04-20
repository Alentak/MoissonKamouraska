<?php

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    //Ordre sql permettant de récuperer l'historique des distributions faites aux mrc dans l'année, on prend le poids et le prix
    $req = $connection->prepare('select sum(HIS_PoidsUnitaire) as poids from t_historique where (HIS_TypeEchange = "U" OR HIS_TypeEchange = "S") AND HIS_Date between :date1 AND :date2');

    $req->execute(array(
        "date1" => ((int)$_POST['Annee'])."-04-01",
        "date2" => ((int)$_POST['Annee']+1)."-03-31",
     ));

    $Historique =  $req->fetch();

    echo $Historique[0];

    $connection = null;

?>