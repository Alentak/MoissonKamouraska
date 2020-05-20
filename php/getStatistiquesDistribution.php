<?php

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    $requete = $connection->query("SELECT CLI_PRENOM, CLI_NOM, Round(SUM(HIS_PoidsUnitaire*HIS_NbProduit), 2) as PoidsTotal, HIS_Date FROM t_historique INNER JOIN t_client on (t_historique.CLI_ID = t_client.CLI_ID) WHERE HIS_TypeEchange ='D' GROUP BY CLI_NOM, CLI_PRENOM, YEAR(HIS_Date), MONTH(HIS_Date) ORDER BY CLI_NOM, YEAR(HIS_Date), MONTH(HIS_Date)");


    $resultat = $requete->fetchAll();



?>