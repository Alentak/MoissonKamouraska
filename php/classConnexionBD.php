<?php
    //Classe permettant la connexion à la base de données

    class ConnexionBD
    {
        private static $bdHost = 'localhost';
        private static $bdName = 'inventaire';
        private static $bdUsername = 'root';
        private static $bdPassword = 'P@ssw0rd123';

        public static $connection;

        public static function getConnexion()
        {
            try
            {
                self::$connection = new PDO('mysql:host=' . self::$bdHost . ';dbname=' . self::$bdName . ';charset=utf8', self::$bdUsername, self::$bdPassword);
            }
            catch (PDOException $e)
            {
                    die('Erreur : ' . $e->getMessage());
            }
            return self::$connection;
        }
    }

?>