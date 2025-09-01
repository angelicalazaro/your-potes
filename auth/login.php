<?php

require_once __DIR__ . "/../includes/session_manager.php";
require_once __DIR__ . "/../config/connect_db.php";

redirectIfLogged('/auth/userProfile.php');

$usernameErr = $passwordErr = $generalErr = "";
$username = $password_hashed = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    if (empty($_POST['username'])) {
        $usernameErr = "Nom obligatoire";
    }
    if (empty($_POST['password'])) {
        $passwordErr = "Mot de passe obligatoire";
    }
    
    if (empty($usernameErr) && empty($passwordErr)) {
        $username = htmlspecialchars(trim($_POST['username']));
        $password = $_POST['password'];

        $reqPreparee = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $reqPreparee->execute([$username]);
        $user = $reqPreparee->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("location: userProfile.php");
            exit;
        } else {
            $_SESSION['error'] = "Identifiants incorrects";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/../style/index.css" type="text/css">
    <link rel="stylesheet" href="/../style/header.css" type="text/css">
    <link rel="stylesheet" href="/../style/footer.css" type="text/css">
    <link rel="stylesheet" href="/../style/globals.css" type="text/css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <h2>Connexion</h2>
    
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='error_message'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    ?>
    
    <form action="login.php" method="POST" class="forms"> 
        <label for="username">Prénom : </label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username ?? '') ?>">
        <?php if (!empty($usernameErr)): ?>
            <span class="error_message"><?php echo $usernameErr; ?></span>
        <?php endif; ?>
        <br>
        
        <label for="password">Mot de passe : </label>
        <input type="password" id="password" name="password"> 
        <?php if (!empty($passwordErr)): ?>
            <span class="error_message"><?php echo $passwordErr; ?></span>
        <?php endif; ?>
        <br>
        
        <button type="submit" name="login" class="action-btn">Se connecter</button>
    </form>
</body>
</html>