<?php
// Example of the configuration file config.php
$host_bdd = "localhost"; // Host of the database
$user_bdd = "root"; // User of the database
$pwd_bdd = ""; // Database password
$db_name = "mini-crud";

function connectDb() {
    global $host_bdd, $user_bdd, $pwd_bdd;
        try {
        $pdo = new PDO(
            'mysql:host=' . $host_bdd . ';dbname=mini-crud', 
            $user_bdd, 
            $pwd_bdd);
            return $pdo;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
}
?>