<?php
    //Récupération de la liste des produits

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

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