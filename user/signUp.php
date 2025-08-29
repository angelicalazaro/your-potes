<?php

require_once __DIR__ . "/../connect_db.php";

$usernameErr = $emailErr = $passwordErr = $generalErr = "";
$username = $email = $password_hashed = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = $_POST["username"] ?? "";
    $email_input = $_POST["email"] ?? "";
    $password_input = $_POST["password_hash"] ?? "";

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
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?])[\w!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]{8,}$/", $password_input)) {
            $passwordErr = "Minimun 8 caracteres : au moins une lettre, un chiffre et un caractere special";
        } else {
            $password_hashed = password_hash($password_input, PASSWORD_DEFAULT);
        }
    }

    if(empty($usernameErr) && empty($emailErr) && empty($passwordErr)) {
        try {
            $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
            $reqPreparee = $pdo->prepare($sql);
            $result = $reqPreparee->execute([$username, $email, $password_hashed]);
            if ($result) {
                header("location: userProfile.php");
                $successMessage = "Ton profil a bien ete cree";
            } else {
                $passwordErr = "Erreur lors de l'enregistrement";
            }
        } catch (PDOException $e) {
            $generalErr = "Erreur de base de donnees";
            echo $e->getMessage();
        }
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription a Mate mon pote</title>
    <link rel="stylesheet" href="/../style/index.css" type="text/css">
    <link rel="stylesheet" href="/../style/header.css" type="text/css">
    <link rel="stylesheet" href="/../style/footer.css" type="text/css">
    <link rel="stylesheet" href="/../style/globals.css" type="text/css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <h1>Inscription</h1>

    <form 
    action="signUp.php" 
    method="POST"
    class="forms">
        <label for="username">Comment tu t'appelles ? :</label> 
        <input 
            type="text" 
            id="username"
            name="username"
            value="<?php echo htmlspecialchars($username ?? '');?>"
        >
            <br>
        <?php if (!empty($usernameErr)): ?>
            <span class="error_message"><?php echo $usernameErr; ?></span><br>
        <?php endif; ?>
        <label for="email">Ton adresse email :</label> 
        <input 
            type="email"
            id="email" 
            name="email"
            value="<?php echo htmlspecialchars($email ?? ''); ?>"
        >
            <br>
        <?php if (!empty($emailErr)): ?>
            <span class="error_message"><?php echo $emailErr; ?></span><br>
        <?php endif; ?>
        <label for="password_hash">Choisi un mot de passe : </label>
        <input  
            type="password"
            id="password_hash"
            name="password_hash"
        >
        <br>
        <?php if (!empty($passwordErr)): ?>
            <span class="error_message"><?php echo $passwordErr; ?></span><br>
        <?php endif ?>
        <!-- Tu as des photos ? Vas y : <input type="file" name="image" accept="image/*"> -->
        <button type="submit" class="action-btn">M'inscrire</button>
    </form>
    <p><a href="index.php" class="action-btn">Retour a la liste de potes 🐶 🐱 🐰</a></p>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>