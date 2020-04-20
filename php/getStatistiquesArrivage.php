<?php

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    $Historique = array();

    //Ordre de selection de tout les arrivages et de leur prix d'un groupe à une certaine date
    $req = $connection->prepare(
        'SELECT tpr_libelle, HIS_PoidsUnitaire, HIS_NbProduit,(HIS_PoidsUnitaire*HIS_NbProduit*tpr_prix) as prix 
        from t_historique h inner join t_typeproduit t on t.TPR_ID=h.TPR_ID 
        where GRO_ID= :idGroupe AND HIS_Date=:dateHis AND HIS_TypeEchange = :type 
        order by tpr_libelle'
    );

    $req->execute(array(
        "idGroupe" => $_POST['GRO_ID'],
        "dateHis" => $_POST['HIS_Date'],
        "type" => "A" // Correspond à un arrivage
     ));

    $Historique[0] = $req->fetchAll(PDO::FETCH_ASSOC);

    //On récupére la date pour définir l'année à laquelle elle correspond
    $mois = date("n",strtotime($_POST['HIS_Date']));
    $annee = date('Y', strtotime($_POST['HIS_Date']));

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
        'SELECT Round(sum(HIS_PoidsUnitaire * HIS_NbProduit),2) as Poids,MOI_Libelle as Date
            from t_historique INNER JOIN (select MOI_Id,MOI_Libelle from t_mois where MOI_ID>3) T ON MONTH(His_Date) = T.MOI_Id
            WHERE GRO_ID = :idGroupe AND HIS_Date between :date1 AND :date2 AND HIS_TypeEchange = :type
            GROUP BY MOI_Libelle
        UNION 
        SELECT "0",MOI_Libelle 
            from t_mois 
            where MOI_ID not in (Select MONTH(HIS_Date) as Date 
                                 from t_historique 
                                 WHERE GRO_ID = :idGroupe AND HIS_Date between :date1 AND :date2 AND HIS_TypeEchange = :type) AND MOI_ID > 3 
        UNION
        SELECT Round(sum(HIS_PoidsUnitaire * HIS_NbProduit),2) as Poids,MOI_Libelle as Date
            from t_historique INNER JOIN (select MOI_Id,MOI_Libelle from t_mois where MOI_ID<4) M ON MONTH(His_Date) = M.MOI_Id
            WHERE GRO_ID = :idGroupe AND HIS_Date between :date1 AND :date2 AND HIS_TypeEchange = :type
            GROUP BY MOI_Libelle
        UNION 
        SELECT "0",MOI_Libelle 
            from t_mois 
            where MOI_ID not in (Select MONTH(HIS_Date) as Date 
                                from t_historique 
                                WHERE GRO_ID = :idGroupe AND HIS_Date between :date1 AND :date2 AND HIS_TypeEchange = :type) AND MOI_ID < 4'
    );

    $req->execute(array(
        "idGroupe" => $_POST['GRO_ID'],
        "type" => "A",
        "date1" => $date1,
        "date2" => $date2
     ));

    $Historique[1] = $req->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($Historique);

    $connection = null;

?>