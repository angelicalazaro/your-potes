<?php

require_once "./connect_db.php";
// initialisation des variables
$nameErr = $descriptionErr = "" ;
$name = $description = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // RÉCUPÉRATION DONNÉES
    $name_input = $_POST["name"] ?? "";
    $description_input = $_POST["description"] ?? "";

    if (empty($name_input)) {
        $nameErr = "Introduit un nom, champ obligatoire";
    } else {
        $name = clean_input($name_input);
        if (!preg_match("/^[a-zA-ZÀ-ÿ0-9' -]+$/u", $name)) {
            $nameErr = "Format invalide";
        }
    }
    if (empty($description_input)) {
        $descriptionErr = "Rentre une description, champ obligatoire";
    } else {
        $description = clean_input($description_input);
        if (!preg_match("/^[\p{L}\p{N}\p{P}\p{S}\p{Zs}]+$/u", $description)) {
            $descriptionErr = "Format invalide";
        }
    }

    // si aucune erreur, insert en bdd :
    if (empty($nameErr) && empty($descriptionErr)) {
        // etablir connexion bdd :
        try {
            $pdo = connectDb();
            $sql = "INSERT INTO pets (pet_name, description) VALUES (?, ?)";
            $reqPreparee = $pdo->prepare($sql);
            $result = $reqPreparee->execute([$name, $description]);
            if ($result) {
                header("location: index.php");
                exit();
            } else {
                $nameErr = "Erreur lors de l'enregistrement";
            }
        } catch (PDOException $e) {
            $nameErr = "Erreur de base de donnees";
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
    <title>Ajoute ton pote de 4 pattes !</title>
    <link rel="stylesheet" href="/style/index.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
    <link rel="stylesheet" href="/style/globals.css" type="text/css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <h1>Ajoute ton pote aux 4 pattes ICI 🐶 🐱 🐰</h1>
    <form 
        action="addPets.php" 
        enctype="multipart/form-data" 
        method="POST"
        class="forms">
            <label for="name">Quel est son nom :</label> 
            <input 
                type="text" 
                name="name">
                <br>
            <?php if (!empty($nameErr)): ?>
                <span class="error_message"><?= $nameErr ?></span><br>
            <?php endif; ?>
            <label for="description">Comment tu lui decris ?</label> 
            <input 
                type="text" 
                name="description"
            >
                <br>
            <?php if (!empty($descriptionErr)): ?>
                <span class="error_message"><?= $descriptionErr ?></span><br>
            <?php endif; ?>
            <!-- Tu as des photos ? Vas y : <input type="file" name="image" accept="image/*"> -->
            <button type="submit" class="action-btn">Ajouter</button>
    </form>
    <p><a href="index.php" class="action-btn">Retour a la liste de potes 🐶 🐱 🐰</a></p>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>