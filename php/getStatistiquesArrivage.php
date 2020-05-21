<?php

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    $Historique = array();

    //Ordre de selection de tout les arrivages et de leur prix d'un groupe à une certaine date
    $req = $connection->prepare(
        'SELECT tpr_libelle, Round(sum(LOT_PoidsUnitaire * LOT_NbProduit),2) as Poids, sum(LOT_NbProduit) as nb,(sum(LOT_NbProduit)*tpr_prix) as prix 
        from t_lot l inner join t_typeproduit t on t.TPR_ID=l.TPR_ID 
        where GRO_ID= :idGroupe AND LOT_Date=:dateLOT
        group by t.TPR_ID order by tpr_libelle'
    );

    $req->execute(array(
        "idGroupe" => $_POST['GRO_ID'],
        "dateLOT" => $_POST['LOT_Date'],
     ));

    $Historique[0] = $req->fetchAll(PDO::FETCH_ASSOC);

    //On récupére la date pour définir l'année à laquelle elle correspond
    $mois = date("n",strtotime($_POST['LOT_Date']));
    $annee = date('Y', strtotime($_POST['LOT_Date']));

    //Si la date est inférieur à Avril alors l'année va de Avril de l'année d'avant jusqu'a Mars de cette année
    if ($mois<4) {
        $date1 = ((int)$annee-1)."-04-01";
        $date2 = ((int)$annee)."-03-31";
    }
    else { // Sinon de Avril de cette année à Mars de l'année prochaine
        $date1 = ((int)$annee)."-04-01";
        $date2 = ((int)$annee+1)."-03-31";
    }

    //Ordre Sql qui permet d'obtenir le poids des arrivages correspondant au mois de l'année, le premier union permet d'obtenir également les mois où il n'y a pas d'arrivage, le deuxiéme permet de trier la requete pour avoir Avril a Decembre avant et ensuite Janvier à Mars
    $req = $connection->prepare(
        'SELECT Round(sum(LOT_PoidsUnitaire * LOT_NbProduit),2) as Poids,MOI_Libelle as Date
            from t_lot INNER JOIN (select MOI_Id,MOI_Libelle from t_mois where MOI_ID>3) T ON MONTH(LOT_Date) = T.MOI_Id
            WHERE GRO_ID = :idGroupe AND LOT_Date between :date1 AND :date2
            GROUP BY MOI_Libelle
        UNION 
        SELECT "0",MOI_Libelle 
            from t_mois 
            where MOI_ID not in (Select MONTH(LOT_Date) as Date 
                                 from t_lot 
                                 WHERE GRO_ID = :idGroupe AND LOT_Date between :date1 AND :date2) AND MOI_ID > 3 
        UNION
        SELECT Round(sum(LOT_PoidsUnitaire * LOT_NbProduit),2) as Poids,MOI_Libelle as Date
            from t_lot INNER JOIN (select MOI_Id,MOI_Libelle from t_mois where MOI_ID<4) M ON MONTH(LOT_Date) = M.MOI_Id
            WHERE GRO_ID = :idGroupe AND LOT_Date between :date1 AND :date2
            GROUP BY MOI_Libelle
        UNION 
        SELECT "0",MOI_Libelle 
            from t_mois 
            where MOI_ID not in (Select MONTH(LOT_Date) as Date 
                                from t_lot 
                                WHERE GRO_ID = :idGroupe AND LOT_Date between :date1 AND :date2) AND MOI_ID < 4'
    );

    $req->execute(array(
        "idGroupe" => $_POST['GRO_ID'],
        "date1" => $date1,
        "date2" => $date2
     ));

    $Historique[1] = $req->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($Historique);

    $connection = null;

?>