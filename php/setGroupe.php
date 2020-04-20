<?php

    //Permet d'ajouter ou de modifier un groupe en fonction d'un ID non reçu pour l'ajout et reçu pour la modification

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    if(!isset($_POST['ID']))
    {
        if(isset($_POST['Priorite']))
        {
            $req = $connection->prepare("INSERT INTO T_Groupe (GRO_LIBELLE, PRI_ID, MRC_ID) VALUES (:nomGroupe, :Priorite, :MRCGroupe)");

            $req->execute(array(
                "nomGroupe" => $_POST['nomGroupe'],
                "Priorite"=> $_POST['Priorite'],
                "MRCGroupe" => $_POST['MRCGroupe']
            ));
        }
        else
        {
            $req = $connection->prepare("INSERT INTO T_Groupe (GRO_LIBELLE) VALUES (:nomGroupe)");

            $req->execute(array(
                "nomGroupe" => $_POST['nomGroupe']
            ));
        }
    }
    else
    {
        if(isset($_POST['Priorite']))
        {
            $ID = $_POST['ID'];

            $req = $connection->prepare("UPDATE T_Groupe SET GRO_LIBELLE = :nomGroupe, PRI_ID = :Priorite, MRC_ID = :MRCGroupe WHERE GRO_ID = :ID");

            $req->execute(array(
                "ID" => $ID,
                "nomGroupe" => $_POST['nomGroupe'],
                "Priorite" => $_POST['Priorite'],
                "MRCGroupe" => $_POST['MRCGroupe']
            ));
        }
        else
        {
            $ID = $_POST['ID'];

            $req = $connection->prepare("UPDATE T_Groupe SET GRO_LIBELLE = :nomGroupe WHERE GRO_ID = :ID");

            $req->execute(array(
                "ID" => $ID,
                "nomGroupe" => $_POST['nomGroupe']
            ));
        }
    }

    $ListeGroupe = array();

    if (isset($_POST['Priorite'])) 
    {
        $req = $connection->query('SELECT GRO_ID, GRO_LIBELLE, PRI_ID, MRC_ID FROM T_Groupe WHERE PRI_ID IS NOT NULL');
    }
    else 
    {
        $req = $connection->query('SELECT GRO_ID, GRO_LIBELLE, PRI_ID FROM T_Groupe WHERE PRI_ID IS NULL');
    }
    
    $ListeGroupe = $req->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($ListeGroupe);

    $connection = null;

?>