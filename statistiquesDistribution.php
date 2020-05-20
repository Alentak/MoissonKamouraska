<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="lib/bootstrap.min.css" />
    <link rel="stylesheet" href="css/statistiquesArrivage.css">
    <link rel="stylesheet" type="text/css" href="css/statistiquesArrivagepdf.css" media="print">
    <script src="lib/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap.min.js"></script>
    <script src="lib/jquery.canvasjs.min.js"></script>
    <script src="js/statistiquesArrivage.js"></script>
    <title>Historique distribution</title>
</head>
<body>
    <header>
        <div>
            <a href="index.html"><img src="img/Logo_MoissonKam.png" alt="logo"></a>
            <h1>Historique distribution</h1>
        </div>
    </header>

    <?php include "php/getStatistiquesDistribution.php"; 
    

    
    ?>

    <div id="container">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Nom du client</th>
                    <th>Poids de denrées</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="tb">
                <?php
                    foreach($resultat as $result) : ?>

                        <tr>
                            <td><?php echo $result['CLI_NOM'] . " " . $result['CLI_PRENOM']; ?></td>
                            <td><?php echo $result['PoidsTotal']; ?></td>
                            <td><?php echo $result['HIS_Date']; ?></td>
                        </tr>

                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>



</body>
</html>