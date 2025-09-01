<?php

session_start();

require_once __DIR__ . "/../config/connect_db.php";

$usernameErr = $emailErr = $passwordErr = $generalErr = "";
$username = $email = $password_hashed = "";

// delete
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

// update
if (isset($_POST['id']) && isset($_POST['modifier'])) {

    $username_input = $_POST["username"] ?? "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <link rel="stylesheet" href="/../style/index.css" type="text/css">
    <link rel="stylesheet" href="/../style/header.css" type="text/css">
    <link rel="stylesheet" href="/../style/footer.css" type="text/css">
    <link rel="stylesheet" href="/../style/globals.css" type="text/css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <h1>Ceci est la page profil, hello !</h1> 
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>