<?php
    //Récupération de la liste des groupes

    include_once('classConnexionBD.php');
    

    $connection = ConnexionBD::getConnexion();

    $ListeGroupe = array();

    $req = $connection->query('SELECT GRO_ID, GRO_LIBELLE FROM T_Groupe WHERE PRI_ID IS NULL');

    $ListeGroupe = $req->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($ListeGroupe);

    $connection = null;

?>