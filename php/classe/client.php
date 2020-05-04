<?php

class Client{

    //client
    private $_IdClient;
    private $_Date;
    private $_NomBeneficicaire;
    private $_PrenomBeneficiaire;
    private $_AgeBeneficiaire;
    private $_Adresse;
    private $_Ville;
    private $_CodePostal;
    private $_Tel;
    private $_NombreAdulte;
    private $_NombreEnfant;
    private $_TailleFamille;
    
    //revenus
    private $_AideSociale;
    private $_Chomage;
    private $_PretBourse;
    private $_Pension;
    private $_RevenusAutres;
    private $_RevenusTotal;
    private $_Reference;

    //depenses
    private $_Loyer;
    private $_Electricite;
    private $_Assurance;
    private $_DepensesTel;
    private $_DepensesAutres;
    private $_DepensesTotal;


    private $_AideAlimentaire;
    private $_Benevolat;

    private $_Signature;
    private $_DateSignature;


    public function __get($name)
    {
        return $this->$name;
    }


    public function __set($name, $valeur)
    {
        $this->$name = $valeur;
    }

    
    public function __construct($date, $nomBeneficicaire, $prenomBeneficiaire, $ageBeneficiaire, $adresse, $ville, $codePostal, $tel, $nombreAdulte, $nombreEnfant, $tailleFamille, $aideSociale, $chomage, $pretBourse, $pension, $revenusAutres, $revenusTotal, $reference, $loyer, $electricite, $assurance, $depensesTel, $depensesAutres, $depensesTotal, $aideAlimentaire, $benevolat, $signature, $dateSignature)
    {
        $this->__set("_Date", $date);
        $this->__set("_NomBeneficicaire", $nomBeneficicaire);
        $this->__set("_PrenomBeneficiaire", $prenomBeneficiaire);
        $this->__set("_AgeBeneficiaire", $ageBeneficiaire);
        $this->__set("_Adresse", $adresse);
        $this->__set("_Ville", $ville);
        $this->__set("_CodePostal", $codePostal);
        $this->__set("_Tel", $tel);
        $this->__set("_NombreAdulte", $nombreAdulte);
        $this->__set("_NombreEnfant", $nombreEnfant);
        $this->__set("_TailleFamille", $tailleFamille);
        $this->__set("_AideSociale", $aideSociale);
        $this->__set("_Chomage", $chomage);
        $this->__set("_PretBourse", $pretBourse);
        $this->__set("_Pension", $pension);
        $this->__set("_RevenusAutres", $revenusAutres);
        $this->__set("_RevenusTotal", $revenusTotal);
        $this->__set("_Reference", $reference);
        $this->__set("_Loyer", $loyer);
        $this->__set("_Electricite", $electricite);
        $this->__set("_Assurance", $assurance);
        $this->__set("_DepensesTel", $depensesTel);
        $this->__set("_DepensesAutres", $depensesAutres);
        $this->__set("_DepensesTotal", $depensesTotal);
        $this->__set("_AideAlimentaire", $aideAlimentaire);
        $this->__set("_Benevolat", $benevolat);
        $this->__set("_Signature", $signature);
        $this->__set("_DateSignature", $dateSignature);
    }


    public function AjouterClient()
    {
        $connection = ConnexionBD::getConnexion();

        $requete = $connection->prepare("INSERT INTO `t_client` (`CLI_DATE`, `CLI_NOM`, `CLI_PRENOM`, `CLI_AGE`, `CLI_ADRESSE`, `CLI_VILLE`, `CLI_CP`, `CLI_TEL`, `CLI_NBADULTE`, `CLI_NBENFANT`, `CLI_TAILLEFAMILLE`, `CLI_AIDESOC`, `CLI_CHOMAGE`, `CLI_PRETBOURSE`, `CLI_REVENUSAUTRES`, `CLI_REVENUSTOTAL`, `CLI_REFERENCE`, `CLI_LOYER`, `CLI_ELEC`, `CLI_ASSURANCE`, `CLI_DEPTEL`, `CLI_DEPAUTRE`, `CLI_DEPTOTAL`, `CLI_AIDEALIM`, `CLI_BENEVOLAT`, `CLI_SIGNATURE`, `CLI_DATESIGN`) value(:date, :nomBeneficiaire, :prenomBeneficiaire, :ageBeneficiaire, :adresse, :ville, :codePostal, :tel, :nombreAdulte, :nombreEnfant, :tailleFamille, :aideSociale, :chomage, :pretBourse, :revenusAutres, :revenusTotal, :reference, :loyer, :electricite, :assurance, :depensesTel, :depensesAutres, :depensesTotal, :aideAlimentaire, :benevolat, :signature, :dateSignature)");

        $requete->bindValue(":date", $this->_Date);
        $requete->bindValue(":nomBeneficiaire", $this->_NomBeneficicaire);
        $requete->bindValue(":prenomBeneficiaire", $this->_PrenomBeneficiaire);
        $requete->bindValue(":ageBeneficiaire", $this->_AgeBeneficiaire);
        $requete->bindValue(":adresse", $this->_Adresse);
        $requete->bindValue(":ville", $this->_Ville);
        $requete->bindValue(":codePostal", $this->_CodePostal);
        $requete->bindValue(":tel", $this->_Tel);
        $requete->bindValue(":nombreAdulte", $this->_NombreAdulte);
        $requete->bindValue(":nombreEnfant", $this->_NombreEnfant);
        $requete->bindValue(":tailleFamille", $this->_TailleFamille);
        $requete->bindValue(":aideSociale", $this->_AideSociale);
        $requete->bindValue(":chomage", $this->_Chomage);
        $requete->bindValue(":pretBourse", $this->_PretBourse);
        $requete->bindValue(":revenusAutres", $this->_RevenusAutres);
        $requete->bindValue(":revenusTotal", $this->_RevenusTotal);
        $requete->bindValue(":reference", $this->_Reference);
        $requete->bindValue(":loyer", $this->_Loyer);
        $requete->bindValue(":electricite", $this->_Electricite);
        $requete->bindValue(":assurance", $this->_Assurance);
        $requete->bindValue(":depensesTel", $this->_DepensesTel);
        $requete->bindValue(":depensesAutres", $this->_DepensesAutres);
        $requete->bindValue(":depensesTotal", $this->_DepensesTotal);
        $requete->bindValue(":aideAlimentaire", $this->_AideAlimentaire);
        $requete->bindValue(":benevolat", $this->_Benevolat);
        $requete->bindValue(":signature", $this->_Signature);
        $requete->bindValue(":dateSignature", $this->_DateSignature);

        $requete->execute();

    }
}

?>