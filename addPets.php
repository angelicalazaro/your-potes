<?php

require_once "./config.php";

$nameErr = $descriptionErr = $imgErr = "";
$name = $description = 

// https://tryphp.w3schools.com/showphp.php?filename=demo_form_validation_complete

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajoute ton pote de 4 pattes !</title>
</head>
<body>
    <h1>Ajoute ton pote de 4 pattes ICI</h1>
    <form action="addPets.php" enctype="multipart/form-data" method="POST">
        Quel est son nom : <input type="text" name="name"><br>
        Comment tu lui decris ? <input type="text" name="description"><br>
        Tu as des photos ? Vas y : <input type="file" name="image" accept="image/*">
         <input type="submit">
    </form>
</body>
</html>