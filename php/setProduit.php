<?php

    //Permet d'ajouter ou de modifier un type de produit en fonction d'un ID non reçu pour l'ajout et reçu pour la modification

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    if(!isset($_POST['ID']))
    {
        $Prio = json_decode($_POST['Prio']); 

        $req = $connection->prepare("INSERT INTO T_TypeProduit (TPR_Libelle, TPR_Prix, TPR_PourcentMoisson, TPR_NbJourAlerte) VALUES (:nomProduit, :prixProduit, :PMoisson, :nbJourAlerte)");

        $req->execute(array(
            "nomProduit" => $_POST['Libelle'],
            "prixProduit" => $_POST['Prix'],
            "PMoisson" => $_POST['PMoisson'],
            "nbJourAlerte" => $_POST['nbJourAlerte']

        ));

        $req = $connection->query("SELECT LAST_INSERT_ID() FROM T_TypeProduit");

        $ID = $req->fetchColumn();

        $req = $connection->prepare("INSERT INTO T_Portion (TPR_ID, PRI_ID, POR_Pourcentage) VALUES (:TPR_ID, :PRI_ID, :Pourcentage)");

        for ($i=0; $i <= 2; $i++)
        { 
            $req->execute(array(
                "TPR_ID" => $ID,
                "PRI_ID" => $i + 1,
                "Pourcentage" => $Prio[$i]
            ));
        }
    }
    else
    {
       
        $Prio = json_decode($_POST['Prio']); 

        $ID = $_POST['ID'];

        $req = $connection->prepare("UPDATE T_TypeProduit SET TPR_Libelle = :nomProduit, TPR_Prix = :prixProduit, TPR_PourcentMoisson = :PMoisson, TPR_NbJourAlerte = :nbJourAlerte WHERE TPR_ID = :ID");

        $req->execute(array(
            "ID" => $ID,
            "nomProduit" => $_POST['Libelle'],
            "prixProduit" => $_POST['Prix'],
            "PMoisson" => $_POST['PMoisson'],
            "nbJourAlerte" => $_POST['nbJourAlerte']

        ));
        
        $req = $connection->prepare("UPDATE T_Portion SET POR_Pourcentage = :Pourcentage WHERE TPR_ID = :TPR_ID AND PRI_ID = :PRI_ID");

        for ($i=0; $i <= 2; $i++)
        { 
            $req->execute(array(
                "TPR_ID" => $ID,
                "PRI_ID" => $i + 1,
                "Pourcentage" => $Prio[$i]
            ));
        }
    }

    $ListeProduit = array();

    $req = $connection->query('SELECT TPR_ID, TPR_Libelle, TPR_Prix, TPR_PourcentMoisson, TPR_NbJourAlerte FROM T_TypeProduit');
    
    while ($data = $req->fetch()) {

        $prio = array();

        $req2 = $connection->query('SELECT POR_Pourcentage FROM t_portion WHERE TPR_ID = ' . $data["TPR_ID"] . ' ORDER BY PRI_ID');
        
        while ($data2 = $req2->fetch()) 
        {
            array_push($prio, $data2["POR_Pourcentage"]);
        }

        $ListeProduit[] =  array(
            "TPR_ID" => $data["TPR_ID"],
            "TPR_Libelle" => $data["TPR_Libelle"],
            "TPR_Prix" => $data["TPR_Prix"],
            "P1" => $prio[0],
            "P2" => $prio[1],
            "P3" => $prio[2],
            "TPR_PourcentMoisson" => $data["TPR_PourcentMoisson"],
            "TPR_NbJourAlerte" => $data["TPR_NbJourAlerte"]
        );
    }
    
    echo json_encode($ListeProduit);

    $connection = null;

?>