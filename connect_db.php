<?php
require_once "./config.php";

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

function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$pdo = connectDb();
?>