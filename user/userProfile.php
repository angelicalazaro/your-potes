<?php

require_once __DIR__ . "/../connect_db.php";

$usernameErr = $emailErr = $passwordErr = $generalErr = "";
$username = $email = $password_hashed = "";

if (isset($_POST['id']) && isset($_POST['supprimer'])) {
    $sql = "DELETE FROM users WHERE id=:id";
    $reqPreparee = $pdo->prepare($sql);
    $result = $reqPreparee->execute(['id' => $_POST['id']]);
    if ($result) {
        $successMessage = "Profil supprime avec succes";
    } else {
        $generalErr = "Impossible de supprimer le profil";
    }
}

if (isset($_POST['id']) && isset($_POST['modifier'])) {

    $username_input = $_POST["username"] ?? "";
}