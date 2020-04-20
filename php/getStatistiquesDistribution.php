<?php

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    $Historique = array();

    $compteur = 0;

    //Ordre sql permettant de récuperer l'historique des distributions faites aux mrc dans l'année, on prend le poids et le prix
    $req = $connection->prepare('SELECT YEAR(HIS_Date),g.MRC_ID, MRC_Libelle, Round(sum(HIS_PoidsUnitaire*HIS_NbProduit),2) as PoidsTotal, Round(sum(HIS_PoidsUnitaire*HIS_NbProduit*TPR_Prix),2) as PrixTotal from t_historique h inner join t_typeproduit t on t.TPR_ID=h.TPR_ID inner join t_groupe g on g.GRO_ID=h.GRO_ID inner join t_mrc m on m.MRC_ID=g.MRC_ID where HIS_TypeEchange="D" AND HIS_Date between :date1 AND :date2 group by MRC_Libelle, YEAR(HIS_Date)');

    $req->execute(array(
        "date1" => ((int)$_POST['Annee'])."-04-01",
        "date2" => ((int)$_POST['Annee']+1)."-03-31"
     ));

    //Pour chaque MRC on veut le détail des groupes de la MRC à qui on a distribué
    while($data = $req->fetch()){
        $Historique[$compteur][] = array("MRC_Libelle"=>$data['MRC_Libelle'],"PoidsTotal"=>$data['PoidsTotal'],"PrixTotal"=>$data['PrixTotal']);

            //Meme principe que la requete précédente mais cette fois ci avec les groupes
            $req2 = $connection->prepare('SELECT  GRO_LIBELLE, Round(sum(HIS_PoidsUnitaire*HIS_NbProduit),2) as PoidsTotal, Round(sum(HIS_PoidsUnitaire*HIS_NbProduit*TPR_Prix),2) as PrixTotal from t_historique h inner join t_typeproduit t on t.TPR_ID=h.TPR_ID inner join t_groupe g on g.GRO_ID=h.GRO_ID where HIS_TypeEchange="D" AND HIS_Date between :date1 AND :date2 AND MRC_ID=:id group by GRO_LIBELLE, YEAR(HIS_Date)');

            $req2->execute(array(
                "id" => $data['MRC_ID'],
                "date1" => ((int)$_POST['Annee'])."-04-01",
                "date2" => ((int)$_POST['Annee']+1)."-03-31"
            ));

            while ($data2 = $req2->fetch()) {
                $Historique[$compteur][] = array("GRO_LIBELLE"=>$data2['GRO_LIBELLE'],"PoidsTotal"=>$data2['PoidsTotal'],"PrixTotal"=>$data2['PrixTotal']);
            }
            $compteur++;
    }

    echo json_encode($Historique);

    $connection = null;

?>