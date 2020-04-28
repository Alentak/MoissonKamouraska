<?php

class clientAutre
{

    private $_NomPrenom;
    private $_DateNaissance;
    private $_Lien;


    public function __get($name)
    {
        return $this->$name;
    }


    public function __set($name, $valeur)
    {
        $this->$name = $valeur;
    }


    public function __construct($nomPrenom, $dateNaissance, $lien)
    {
        $this->__set("_NomPrenom", $nomPrenom);
        $this->__set("_DateNaissance", $dateNaissance);
        $this->__set("_Lien", $lien);
    }

    public static function SelectDernierClient()
    {
        $connection = ConnexionBD::getConnexion();

        $requete = $connection->query("SELECT MAX(CLI_ID) FROM t_client");

        $resultat = $requete->fetchAll();

        return $resultat;
    }


    public function AjouterClientAutre($idClient)
    {
        $connection = ConnexionBD::getConnexion();

        $requete = $connection->prepare("INSERT INTO `t_clientautre` (`CLI_ID`, `CLIA_NOMPRENOM`, `CLIA_DDN`, `CLIA_LIEN`) value (:idClient, :nomPrenom, :ddn, :lien)");

        $requete->bindValue(":idClient", $idClient);
        $requete->bindValue(":nomPrenom", $this->_NomPrenom);
        $requete->bindValue(":ddn", $this->_DateNaissance);
        $requete->bindValue(":lien", $this->_Lien);

        $requete->execute();
    }

}



?>