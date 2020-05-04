<?php
include 'php/classConnexionBD.php';

$db = ConnexionBD::getConnexion();

$leClient;
if(isset($_GET["cid"]) && $_GET["cid"] != null && is_numeric($_GET["cid"]) && $_GET["cid"] > 0){
    $id = $_GET["cid"];
    //Récup le client sur lequel l'utilisateur a cliqué
    $leClient = $db->query("SELECT * FROM t_client WHERE CLI_ID = $id");
    if ($leClient->rowCount() > 0)
        $leClient = $leClient->fetch();
    else
        header('location:listeModifClient.php');
}else{
    header('location:listeModifClient.php');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="lib/bootstrap.min.css">
    <link rel="stylesheet" href="css/listeModifClient.css">
    <link rel="stylesheet" href="css/ajoutClient.css">
    <script src="lib/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap.min.js"></script>
    <title>Client - Détails</title>
</head>

<body>
    <header>
        <div>
            <a href="index.html"><img src="img/Logo_MoissonKam.png" alt="logo"></a>
        </div>
    </header>
    <div class="container">
        <form method="POST" action="php/ajoutClient.php">
            <h2>Détails sur le client</h2>
            <div class="form-group row">
                <label for="inputDate" class="col-md-1 col-form-label">Date</label>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="inputDate" name="donnees[date]" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputNomBeneficiaire" class="col-md-1 col-form-label font-weight-bold">Nom du bénéficiaire</label>
                <div class="col-md-3">
                    <input type="text" value="<?= $leClient["CLI_NOM"]?>" class="form-control" id="inputNomBeneficiaire" name="donnees[nomBeneficiaire]" autocomplete="off" required>
                </div>

                <label for="inputPrenomBeneficiaire" class="col-md-1 col-form-label font-weight-bold">Prénom</label>
                <div class="col-md-3">
                    <input type="text" value="<?= $leClient["CLI_PRENOM"]?>" class="form-control" id="inputPrenomBeneficiaire" name="donnees[prenomBeneficiaire]" autocomplete="off" required>
                </div>

                <label for="inputAgeBeneficiaire" class="col-md-1 col-form-label font-weight-bold">Age</label>
                <div class="col-md-3">
                    <input type="number" value="<?= $leClient["CLI_AGE"]?>" min="0" class="form-control" id="inputAgeBeneficiaire" name="donnees[ageBeneficiaire]" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputAdresse" class="col-md-1 col-form-label">Adresse</label>
                <div class="col-md-11">
                    <input type="text" class="form-control" value="<?= $leClient["CLI_ADRESSE"]?>" id="inputAdresse" name="donnees[adresse]" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputVille" class="col-md-1 col-form-label">Ville</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" value="<?= $leClient["CLI_VILLE"]?>" id="inputVille" name="donnees[ville]" autocomplete="off" required>
                </div>

                <label for="inputCodePostal" class="col-md-2 col-form-label">Code postal</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="<?= $leClient["CLI_CP"]?>" id="inputCodePostal" name="donnees[codePostal]" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputTel" class="col-md-1 col-form-label">Téléphone</label>
                <div class="col-md-6">
                    <input type="tel" class="form-control" value="<?= $leClient["CLI_TEL"]?>" id="inputVille" name="donnees[tel]" autocomplete="off" required>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label for="inputNomPrenomAutre" class="col-md-12 col-form-label font-weight-bold">Nom et prénom
                            des autres personnes</label>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control mb-1" id="inputNomPrenomAutre" name="autre1[nomPrenom]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control mb-1" id="inputNomPrenomAutre" name="autre2[nomPrenom]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control mb-1" id="inputNomPrenomAutre" name="autre3[nomPrenom]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control mb-1" id="inputNomPrenomAutre" name="autre4[nomPrenom]" autocomplete="off">
                        </div>
                    </div>
                </div>


                <div class="col-md-2 mb-3">
                    <div class="row">
                        <label for="inputNomPrenomAutre" class="col-md-12 col-form-label font-weight-bold">Date de
                            naissance</label>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="date" class="form-control mb-1" id="inputDDNAutre" name="autre1[ddn]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="date" class="form-control mb-1" id="inputDDNAutre" name="autre2[ddn]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="date" class="form-control mb-1" id="inputDDNAutre" name="autre3[ddn]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="date" class="form-control mb-1" id="inputDDNAutre" name="autre4[ddn]" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="row">
                        <label for="inputNomPrenomAutre" class="col-md-12 col-form-label font-weight-bold">Lien</label>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control mb-1" id="inputLienAutre" name="autre1[lien]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control mb-1" id="inputLienAutre" name="autre2[lien]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control mb-1" id="inputLienAutre" name="autre3[lien]" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control mb-1" id="inputLienAutre" name="autre4[lien]" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>



            <div class="form-group row">
                <label for="inputNbAdulte" class="col-md-2 col-form-label font-weight-bold">Nombre d'adulte</label>
                <div class="col-md-2">
                    <input type="number" class="form-control" value="<?= $leClient["CLI_NBADULTE"]?>" id="inputNbAdulte" name="donnees[nombreAdulte]" autocomplete="off" required>
                </div>

                <label for="inputNbEnfant" class="col-md-2 col-form-label font-weight-bold">Nombre d'enfant</label>
                <div class="col-md-2">
                    <input type="number" class="form-control" value="<?= $leClient["CLI_NBENFANT"]?>" id="inputNbEnfant" name="donnees[nombreEnfant]" autocomplete="off" required>
                </div>

                <label for="inputTypeFamille" class="col-md-2 col-form-label font-weight-bold">Taille de la
                    famille</label>
                <div class="col-md-2">
                    <select class="form-control" name="donnees[tailleFamille]" autocomplete="off" required>
                        <option>Petite famille</option>
                        <option>Moyenne famille</option>
                        <option>Grande famille</option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-6">
                    <div class="row">
                        <label class="col-md-12 col-form-label text-center mb-1 font-weight-bold">Revenus</label>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Aide sociale</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_AIDESOC"]?>" id="inputAideSociale" name="donnees[aideSociale]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Chômage / CSST</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_CHOMAGE"]?>" id="inputAideSociale" name="donnees[chomageCSST]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Prêts et bourses</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_PRETBOURSE"]?>" id="inputAideSociale" name="donnees[pretBourse]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Pension</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_PENSION"]?>" id="inputAideSociale" name="donnees[pension]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Autres</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_REVENUSAUTRES"]?>" id="inputAideSociale" name="donnees[revenusAutres]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Total</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_REVENUSTOTAL"]?>" id="inputAideSociale" name="donnees[revenusTotal]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Référé par</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_REFERENCE"]?>" id="inputAideSociale" name="donnees[refererPar]" autocomplete="off" required>
                        </div>
                    </div>
                </div>


                <div class="col-6">
                    <div class="row">
                        <label class="col-md-12 col-form-label mb-1 text-center font-weight-bold">Dépenses</label>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Loyer</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_LOYER"]?>" id="inputAideSociale" name="donnees[loyer]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Electricité /
                            chauffage</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_ELEC"]?>" id="inputAideSociale" name="donnees[electriciteChauffage]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Assurances</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_ASSURANCE"]?>" id="inputAideSociale" name="donnees[assurances]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Téléphone</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_DEPTEL"]?>" id="inputAideSociale" name="donnees[telDepense]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Autres</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_DEPAUTRE"]?>" id="inputAideSociale" name="donnees[depensesAutres]" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row">
                        <label for="inputAideSociale" class="col-md-4 col-form-label mb-1">Total</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?= $leClient["CLI_DEPTOTAL"]?>" id="inputAideSociale" name="donnees[depensesTotal]" autocomplete="off" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-check mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <p>Avez-vous de l'aide alimentaire d'autres organismes du milieu ?</p>
                    </div>
                    <div class="col-md-1">
                        <input class="form-check-input" type="radio" id="AideAlimentaire1" value="1" <?= $leClient["CLI_AIDEALIM"] ? "checked" : "" ?> name="donnees[AideAlimentaire]" autocomplete="off" required>
                        <label class="form-check-label" for="AideAlimentaire1">
                            Oui
                        </label>
                    </div>
                    <div class="col-md-1">
                        <input class="form-check-input" type="radio" id="AideAlimentaire2" value="0" <?= $leClient["CLI_AIDEALIM"] ? "" : "checked" ?> name="donnees[AideAlimentaire]" autocomplete="off" required>
                        <label class="form-check-label" for="AideAlimentaire2">
                            Non
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-check">
                <div class="row">
                    <div class="col-md-6">
                        <p>Intéressé(e) à être bénévole ?</p>
                    </div>
                    <div class="col-md-1">
                        <input class="form-check-input" type="radio" id="Benevole1" value="1" <?= $leClient["CLI_BENEVOLAT"] ? "checked" : "" ?> name="donnees[Benevole]" autocomplete="off" required>
                        <label class="form-check-label" for="Benevole1">
                            Oui
                        </label>
                    </div>
                    <div class="col-md-1">
                        <input class="form-check-input" type="radio" id="Benevole2" value="0" <?= $leClient["CLI_BENEVOLAT"] ? "" : "checked" ?> name="donnees[Benevole]" autocomplete="off" required>
                        <label class="form-check-label" for="Benevole2">
                            Non
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md">
                    <p><span class="font-weight-bold">Par cette signature, je confirme que les renseignements fournis sont exacts.</span><br>

                        Je suis informé que la loi protège l'organisme de distribution alimentaine, ainsi que Moisson Kamouraska et ses fournisseurs, de toute réclamation ou poursuite judiciaire. (Loi du bon samaritain) <br>

                        <span class="font-weight-bold">Par la présente, j'autorise la divulgation d'informations entre mon intervenant et Moisson Kamouraska. <br>

                            Si besoin, j'accepte que Moisson Kamouraska renouvelle mon accès à l'Epicerie Sociale. <br>

                            Je m'engage à respecter les règlements établis par Moisson Kamouraska. Exemple : La non-revente des denrées, avoir des sacs réutilisables et échanger dans le respect et la bonne humeur. <br>

                            Advenant le cas du non-respect, en tout temps Moisson Kamouraska peut mettre fin à mon aide.</span>
                    </p>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inputSignAccept" value="1" name="donnees[signature]" autocomplete="off" required>
                        <label class="form-check-label" for="inputSignAccept">J'accepte</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label for="inputDateAccept" class="col-md-2 col-form-label">Date</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" value="<?= date('d-m-Y', strtotime($leClient["CLI_DATESIGN"]))?>" id="inputDateAccept" name="donnees[dateSignature]" autocomplete="off" required>
                        </div>
                    </div>
                </div>
            </div>

            <input type="submit" class="btn btn-dark" value="Valider" name="Valider">

        </form>
    </div>
</body>

</html>