<?php

require_once __DIR__ . "/../includes/session_manager.php";
require_once __DIR__ . "/../config/connect_db.php";

requireLogin();

$usernameErr = $emailErr = $passwordErr = $generalErr = "";
$username = $email = $password_hashed = "";
$current_user = getCurrentUser();
$user_id = $current_user['id'];

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

// update = how to manage change the pw ?
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $new_username_input = $_POST['username'] ?? "";
    $new_password_input = $_POST['password_hash'] ?? "";

    if(empty($new_username_input)) {
        $usernameErr = "Nom d'utilisateur obligatoire";
    } else {
        $new_username_input = clean_input($new_username_input);
        if (!preg_match("/^[a-zA-ZÀ-ÿ0-9' -]+$/u", $new_username_input)) {
            $usernameErr = "Format invalide";
        }
    }

    if(!empty($new_password_input)) {
        if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?])[\w!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]{8,}$/", $new_password_input)) {
            $passwordErr = "Format de mot de passe invalide.";
        } else {
            $new_password_hash = password_hash($new_password_input, PASSWORD_DEFAULT);
        }
    }
} 

// "get"
$pdo = connectDb();
$sql = "SELECT * FROM users WHERE id = " . $user_id;
$resultPdoStatement = $pdo->query($sql, PDO::FETCH_ASSOC);
$user_data = $resultPdoStatement->fetchAll();
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
    <h3>Modifier mon profil</h3>
    <form action="" method="post" class="forms">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <?php 
        $user_labels = [
            'username' => 'Nom d\'utilisateur',

        ]; ?>
    </form>
    <h1>Ceci est la page profil, hello !</h1> 
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>