<?php

include_once('classConnexionBD.php');

$bdd = ConnexionBD::getConnexion();

$ListeProduit = array();

//Ordre de selection de tout les groupes a qui on peut faire une distribution

$selectGroup = $bdd->query('SELECT CLI_ID, CLI_NOM, CLI_PRENOM, CLI_TAILLEFAMILLE FROM t_client ORDER BY CLI_NOM');

$ListeProduit[0] = array();

while ($dataGroupe = $selectGroup->fetch()) {
    $ListeProduit[0][] = array("CLI_ID" => $dataGroupe['CLI_ID'], "CLI_NOM" => $dataGroupe['CLI_NOM'], "CLI_PRENOM" => $dataGroupe['CLI_PRENOM'], "CLI_TAILLEFAMILLE" => $dataGroupe['CLI_TAILLEFAMILLE']);
}
$selectGroup->closeCursor();

$ListeProduit[1] = array();

//Ordre de selection de tout les type de produit different ainsi que que la somme du poids, la dlc la plus proche et le nombre, le tout par produit

$selectTypeProduit = $bdd->query('SELECT t.tpr_id as id,tpr_Libelle,ROUND(SUM(LOT_Poidsunitaire*LOT_NbProduit),1) as Poids, MIN(LOT_DLC) as Date, SUM(LOT_Nbproduit) as NbProduit,TPR_Prix,TPR_NbJourAlerte,TPR_PourcentMoisson from t_lot l inner join t_typeproduit t on t.TPR_ID=l.TPR_ID group by tpr_libelle order by Date');

$compteur = 0;
//On veut faire un ordre pour chaque type de produit donc on le fait dans le while
while ($dataTypeProduit = $selectTypeProduit->fetch()) {
    $prio = array();

    //On selectionne les 3 pourcentage different pour chaque produit
    $selectPourcent = $bdd->query('select POR_Pourcentage from t_portion where TPR_ID ="' . $dataTypeProduit['id'] . '"   order by PRI_ID');
    while ($dataPourcent = $selectPourcent->fetch()) {
        array_push($prio, $dataPourcent['POR_Pourcentage']);
    }
    $selectPourcent->closeCursor();

    //On genere la ligne global du produit
    $ListeProduit[1][$compteur][] = array("tpr_Libelle" => $dataTypeProduit['tpr_Libelle'], "NbProduit" => $dataTypeProduit['NbProduit'], "Date" => $dataTypeProduit['Date'], "Poids" => $dataTypeProduit['Poids'], "id" => $dataTypeProduit['id'], "TPR_Prix" => $dataTypeProduit['TPR_Prix'], "Prio" => $prio, "NbJourAlerte" => $dataTypeProduit['TPR_NbJourAlerte'], "PourcentMoisson" => $dataTypeProduit['TPR_PourcentMoisson']);

    //Ordre sql de selection de chaque lot de produit du stock
    $selectLot = $bdd->query('select LOT_ID,l.tpr_id as idproduit,LOT_NbProduit,LOT_DLC,LOT_POIDSUNITAIRE*LOT_NbProduit as LOT_POIDS FROM t_lot l inner join t_typeproduit p on l.TPR_ID=p.TPR_ID where tpr_libelle="' . $dataTypeProduit['tpr_Libelle'] . '" order by LOT_DLC');

    //on genere la liste dans le meme array de tout les lots correspondant à la ligne global du produit
    while ($dataLot = $selectLot->fetch()) {
        $ListeProduit[1][$compteur][]  = array("LOT_ID" => $dataLot['LOT_ID'], "idproduit" => $dataLot['idproduit'], "LOT_NbProduit" => $dataLot['LOT_NbProduit'], "LOT_DLC" => $dataLot['LOT_DLC'], "LOT_POIDS" => $dataLot['LOT_POIDS']);
    }

    $selectLot->closeCursor();
    $compteur++;
}
$selectTypeProduit->closeCursor();

$ListeProduit[2] = array();

//On selectionne toute les MRC
$selectMRC = $bdd->query('SELECT MRC_ID, MRC_Libelle from t_MRC');

while ($dataMRC = $selectMRC->fetch()) {
    $ListeProduit[2][] = array("MRC_ID" => $dataMRC['MRC_ID'], "MRC_Libelle" => $dataMRC['MRC_Libelle']);
}
$selectMRC->closeCursor();

echo json_encode($ListeProduit);

$bdd = null;
