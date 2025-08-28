<?php

require_once __DIR__ . "/../connect_db.php";

$usernameErr = $emailErr = $passwordErr = "";
$username = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = $_POST["username"] ?? "";
    $email_input = $_POST["email"] ?? "";
    $password_input = $_POST["password"] ?? "";

    if(empty($username_input)) {
        $usernameErr = "Introduit ton nom, champ obligatoire";
    } else {
        $username = clean_input($username_input);
        if (!preg_match("/^[a-zA-ZÀ-ÿ0-9' -]+$/u", $username)) {
            $usernameErr = "Format invalide";
        }
    }
    if (empty($email_input)) {
        $emailErr = "Introduit un email, champ obligatoire";
    } else {
        $email = clean_input($email_input);
        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
            $emailErr = "Indroduit une adresse mail valide";
        }
    }
    if (empty($password_input)) {
        $passwordErr = "Introduit un mot de passe, champ obligatoire";
    } else {
        if (strlen)
    }
    
}