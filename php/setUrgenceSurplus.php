<?php

    //Permet d'ajouter à l'historique un dépannage d'urgence ou de surplus
    
    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    $req = $connection->prepare("INSERT INTO T_Historique (HIS_PoidsUnitaire, HIS_TypeEchange, HIS_Date) VALUES (:poids, :typeDistrib, :dateDistrib)");

    $req->execute(array(
        "poids" => $_POST['poids'],
        "typeDistrib" => $_POST['type'],
        "dateDistrib" => $_POST['date']

    ));

    $connection = null;

?>