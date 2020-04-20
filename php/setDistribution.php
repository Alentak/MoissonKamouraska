<?php
    //Permet d'exécuter les ordres qui se situent dans le tableau reçu de la requête ajax lors de la saisie d'une distribution de denrées

    include_once('classConnexionBD.php');  

    $connection = ConnexionBD::getConnexion();

    $jSon = json_decode($_POST['jObjOrdre']);

    foreach($jSon as $item) {
        $req = $connection->query($item);
    }

    $connection = null;

?>