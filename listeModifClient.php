<?php
include 'php/classConnexionBD.php';

$db = ConnexionBD::getConnexion();
$clients = $db->query("SELECT * FROM t_client ORDER BY CLI_NOM");
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
    <?php
    if(isset($_GET["success"]))
    {
        if($_GET["success"] == 1)
        {
            ?>
            <div class="container">
                <div class="alert alert-success" role="alert">
                    Le client a bien été modifié !
                </div>
            </div>
        <?php
        }
    }?>
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
                    <td><?= $client["CLI_NOM"] . " " . $client["CLI_PRENOM"] ?> <a href="detailsClient.php?cid=<?= $client["CLI_ID"] ?>"> > Détails</a></td>
                    <td>
                        <?php
                        $typeFamille = "Grande";
                        if ($client["CLI_TAILLEFAMILLE"] == 2)
                            $typeFamille = "Moyenne";
                        else if ($client["CLI_TAILLEFAMILLE"] == 1)
                            $typeFamille = "Petite";
                        echo $typeFamille;
                        ?>
                    </td>
                    <td><?= $client["CLI_DATE"] ?> <button></td>
                    <td><?= $client["CLI_AGE"] ?></td>
                    <td>
                        <?php
                        $now = time(); //Aujourd'hui
                        $date = strtotime($client["CLI_DATE"]); //La date limite de distribution du client

                        $datediff = round(($date - $now) / (60 * 60 * 24)); //Calcul du nombre de jours qui sépare les deux dates

                        //Affichage de la réponse en fonction de la différence de jour
                        if ($datediff < 0)
                            echo "Dépassé";
                        else if ($datediff <= 7)
                            echo "<img src='img/prouge.png' width='30'>";
                        else if ($datediff <= 14)
                            echo "<img src='img/porange.png' width='30'>";
                        else if ($datediff > 14)
                            echo "<img src='img/pverte.png' width='30'>";
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>