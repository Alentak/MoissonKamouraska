<?php
include('classConnexionBD.php');

if(isset($_POST['Valider']))
{
    $tableau = $_POST["donnees"];
    
    $clientAutre1 = $_POST["autre1"];
    $clientAutre2 = $_POST["autre2"];
    $clientAutre3 = $_POST["autre3"];
    $clientAutre4 = $_POST["autre4"];

    $i = true;
    
    foreach($tableau as $tab)
        if(empty($tab))
        {
            $i = false;

                if($tab == 0)
                {
                    $i = true;
                }
        }

    if($i == true)
    {
        //client
        $date = $tableau["date"];
        $nomBeneficiaire = $tableau["nomBeneficiaire"];
        $prenomBeneficiaire = $tableau['prenomBeneficiaire'];
        $age = $tableau["ageBeneficiaire"];
        $adresse = $tableau["adresse"];
        $ville = $tableau["ville"];
        $codePostal = $tableau["codePostal"];
        $tel = $tableau["tel"];
        $nombreAdulte = $tableau["nombreAdulte"];
        $nombreEnfant = $tableau["nombreEnfant"];
        $tailleFamille = $tableau["tailleFamille"];

        //Revenus
        $aideSociale = $tableau["aideSociale"];
        $chomage = $tableau["chomageCSST"];
        $pretBourse = $tableau["pretBourse"];
        $pension = $tableau["pension"];
        $revenusAutres = $tableau["revenusAutres"];
        $revenusTotal = $tableau["revenusTotal"];
        $reference = $tableau["refererPar"];

        //Depenses
        $loyer = $tableau["loyer"];
        $electricite = $tableau["electriciteChauffage"];
        $assurances = $tableau["assurances"];
        $telDepense = $tableau["telDepense"];
        $depensesAutres = $tableau["depensesAutres"];
        $depensesTotal = $tableau["depensesTotal"];

        //les btn radios ne sont pas présent s'ils n'ont pas été coché. Pk ? jsp
        
        $aideAlimentaire = $tableau["AideAlimentaire"];
        $benevole = $tableau["Benevole"];
        $signature = $tableau["signature"];

        $dateSignature = $tableau["dateSignature"];

        $result1 = verifierClientAutre($clientAutre1);
        $result2 = verifierClientAutre($clientAutre2);
        $result3 = verifierClientAutre($clientAutre3);
        $result4 = verifierClientAutre($clientAutre4);

        if($result1 == 0 || $result2 == 0 || $result3 == 0 || $result4 == 0)
        {
            header("Location: ../detailsClient.php?erreur=2");
        }
        else
        {
            $client = new Client($date, $nomBeneficiaire, $prenomBeneficiaire, $age, $adresse, $ville, $codePostal, $tel, $nombreAdulte, $nombreEnfant, $tailleFamille, $aideSociale, $chomage, $pretBourse, $pension, $revenusAutres, $revenusTotal, $reference, $loyer, $electricite, $assurances, $telDepense, $depensesAutres, $depensesTotal, $aideAlimentaire, $benevole, $signature, $dateSignature);
            
            $idClient = $_GET["cid"];
            $client->__set("_IdClient", $idClient);

            $client->ModifierClient();

            //Récup les membre de la famille du client pour les modifier
            $autres = $client->GetClientsAutre();

            if($result1 == 1)
                modifierAutre($clientAutre1, $autres[0]["CLIA_ID"]);

            if($result2 == 1)
                modifierAutre($clientAutre2, $autres[1]["CLIA_ID"]);
            
            if($result3 == 1)
                modifierAutre($clientAutre3, $autres[2]["CLIA_ID"]);
            
            if($result4 == 1)
                modifierAutre($clientAutre4, $autres[3]["CLIA_ID"]);

            header("Location: ../listeModifClient.php?success=1");
        }
    }
    else
    {
        header("Location: ../detailsClient.php?erreur=1");
    }
}

function verifierClientAutre($tableau)
{
    $a = false;
    $b = true;

    //Ce champ n'est pas obligatoire, donc on vérifie si on a cherché à remplir un 'clientAutre'.
    foreach($tableau as $row)
    {
        if(!empty($row))
        {
            $a = true;
        }
    }

    //Si oui, on verifie que tout les champs sont complétés et on ajoute.
    if($a == true)
    {
        foreach($tableau as $row)
        {
            if(empty($row))
            {
                $b = false;
            }
        }

        if($b == true)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    else
    {
        return 2;
    }
}

function ajouterAutre($tableau)
{
    $resultat = clientAutre::SelectDernierClient();

    foreach($resultat as $result)
    {
        $idClient = $result["MAX(CLI_ID)"];
    }

    $nomPrenom = $tableau["nomPrenom"];
    $ddn = $tableau["ddn"];
    $lien = $tableau["lien"];

    $clientAutre = new clientAutre($nomPrenom, $ddn, $lien);

    $clientAutre->AjouterClientAutre($idClient);
}

function modifierAutre($tableau, $idAutre)
{
    $nomPrenom = $tableau["nomPrenom"];
    $ddn = $tableau["ddn"];
    $lien = $tableau["lien"];

    $clientAutre = new clientAutre($nomPrenom, $ddn, $lien);
    $clientAutre->__set("_IdClientAutre", $idAutre);

    $clientAutre->ModifierClientAutre();
}
?>