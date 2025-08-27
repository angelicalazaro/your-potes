<?php

require_once "./connect_db.php";
// initialisation des variables
$nameErr = $descriptionErr = "" ;
$name = $description = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // RÉCUPÉRATION DONNÉES
    // les variables avec _input sont initialisees avec la variable POST donc pas besoin de les appeler apres dans les conditions 
    $name_input = $_POST["name"] ?? "";
    $description_input = $_POST["description"] ?? "";

    if (empty($name_input)) {
        $nameErr = "Introduit un nom, champ obligatoire";
    } else {
        $name = clean_input($name_input);
        // verifier d'autre facon
        if (!ctype_alpha(str_replace(' ', '', $name))) {
            $nameErr = "Format invalide";
        }
    }
    if (empty($description_input)) {
        $descriptionErr = "Rentre une description valide s'il te plait";
    } else {
        $description = clean_input($description_input);
        // lettres et espaces seulement -> chercher une autre methode
        if (!ctype_alpha(str_replace(' ', '', $description))) {
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
    <link rel="stylesheet" href="/style/errorMessages.css" type="text/css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <h1>Ajoute ton pote aux 4 pattes ICI 🐶 🐱 🐰</h1>
    <form action="addPets.php" enctype="multipart/form-data" method="POST">
        Quel est son nom : <input type="text" name="name"><br>
         <?php if (!empty($nameErr)): ?>
        <span style="color: red;"><?= $nameErr ?></span><br>
    <?php endif; ?>
        Comment tu lui decris ? <input type="text" name="description"><br>
        <?php if (!empty($descriptionErr)): ?>
        <span style="color: red;"><?= $descriptionErr ?></span><br>
    <?php endif; ?>
        <!-- Tu as des photos ? Vas y : <input type="file" name="image" accept="image/*"> -->
         <input name="button" type="submit">
    </form>
    <p><a href="index.php">Retour a la liste de potes 🐶 🐱 🐰</a></p>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>