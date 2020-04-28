<?php
include 'php/classConnexionBD.php';

$db = ConnexionBD::getConnexion();
$clients = $db->query("SELECT * FROM t_client");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="lib/bootstrap.min.css">
    <link href="lib/fontawesome-5.7.2/css/fontawesome.min.css" rel="stylesheet">
    <link href="lib/fontawesome-5.7.2/css/brands.min.css" rel="stylesheet">
    <link href="lib/fontawesome-5.7.2/css/solid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/listeModifClient.css">
    <title>Client - Liste et modification</title>
</head>

<body>
    <header>
        <div>
            <a href="index.html"><img src="img/Logo_MoissonKam.png" alt="logo"></a>
            <h1>Liste et modification de clients</h1>
        </div>
    </header>
    <table class="table table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Nom et prénom du bénéficiaire</th>
                <th>Famille</th>
                <th>Date</th>
                <th>Age</th>
                <th>Degré d'urgence</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client) : ?>
                <tr>
                    <td><?= $client["CLI_NOM"]. " ".$client["CLI_PRENOM"]?> <a href="detailsClient.php?cid=<?=$client["CLI_ID"]?>"> >  Détails</a></td>
                    <td>
                        <?php 
                            $typeFamille = "Grande";
                            if($client["CLI_TAILLEFAMILLE"] < 7)
                                $typeFamille = "Moyenne";
                            if($client["CLI_TAILLEFAMILLE"] < 5)
                                $typeFamille = "Petite";
                            echo $typeFamille;
                        ?>
                    </td>
                    <td><?= $client["CLI_DATE"]?> <button><i class="fas fa-plus-circle fa-2x"></i></button></td>
                    <td><?= $client["CLI_AGE"]?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>