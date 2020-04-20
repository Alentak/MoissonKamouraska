<?php

    //Permet d'ajouter un lot lors de la saisie d'un arrivage avec ajout dans l'historique

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();    

    $jSon = json_decode($_POST['jObjArrivage']);
    
    foreach($jSon as $produit) 
    {

        $req = $connection->prepare("INSERT INTO T_Lot (TPR_ID, GRO_ID, LOT_PoidsUnitaire, LOT_DLC, LOT_NbProduit, LOT_Date) VALUES (:idProduit, :idGroupe, :PoidsUnitaire, :DLC, :nbProd, :dateArriv)");

        $req->execute(array(
            "idProduit" => $produit->{'idType'},
            "idGroupe" => $produit->{'idGroupe'},
            "PoidsUnitaire" => $produit->{'PoidsUnitaire'},
            "DLC" => $produit->{'DLC'},
            "nbProd" => $produit->{'nbProd'},
            "dateArriv" => $produit->{'dateArriv'}

        ));

        $req = $connection->prepare("INSERT INTO T_Historique (TPR_ID, GRO_ID, HIS_PoidsUnitaire, HIS_DLC, HIS_NbProduit, HIS_TypeEchange, HIS_Date) VALUES (:idProduit, :idGroupe, :PoidsUnitaire, :DLC, :nbProd, :TypeEchange, :dateArriv)");

        $req->execute(array(
            "idProduit" => $produit->{'idType'},
            "idGroupe" => $produit->{'idGroupe'},
            "PoidsUnitaire" => $produit->{'PoidsUnitaire'},
            "DLC" => $produit->{'DLC'},
            "nbProd" => $produit->{'nbProd'},
            "TypeEchange" => 'A',
            "dateArriv" => $produit->{'dateArriv'}

        ));
    }

    $connection = null;

?>