<?php
require "../includes/session_manager.php";
require "../config/connect_db.php";

if (!isLoggedIn()) {
    header('Location: /../auth/login.php');
    exit();
}
// initialisation des variables
$nameErr = "";
$descriptionErr = "";
$imageErr = ""; 

$name = "";
$description = "";
$new_image_name = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name_input = $_POST["name"] ?? "";
    $description_input = $_POST["description"] ?? "";
    $image_input = $_POST["image_path"] ?? "";

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
        // verif côté serveur
        $description = clean_input($description_input);
        if (!preg_match("/^[\p{L}\p{N}\p{P}\p{S}\p{Zs}]+$/u", $description)) {
            $descriptionErr = "Format invalide";
        }
    }
    if (!empty($_FILES["image_path"]["tmp_name"])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES["image_path"]["type"], $allowed_types)) {
            $imageErr = "Format invalide. Seules les images JPEG et PNG sont autorisees";
        }
        elseif($_FILES["image_path"]["size"] > 5242880) {
            $imageErr = "Image trop lourde, max 5MB";
        }
        else {
            $file_basename = pathinfo($_FILES["image_path"]["name"], PATHINFO_FILENAME);
            $file_extension = pathinfo($_FILES["image_path"]["name"], PATHINFO_EXTENSION);
            $new_image_name = $file_basename . '_' . date("Ymd_His") . '.' . $file_extension;

            $target_directory = "../uploads";
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0755, true);
            }
            $target_path = $target_directory . '/' . $new_image_name;
        }
    }
    // si aucune erreur, insert en bdd :
    if (empty($nameErr) && empty($descriptionErr) && empty($imageErr)) {

        if ($new_image_name && !move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_path)) {
            $imageErr = "Erreur de telechargement fichier";
        }
        // etablir connexion bdd :
        try {
            $current_user = getCurrentUser();
            $user_id = $current_user['id'];

            if (!$user_id) {
                die("Utilisateur non identifie");
            }

            $pdo = connectDb();
            // ajouter user_id connecté
            $sql = "INSERT INTO pets (user_id, pet_name, description, image_path) VALUES (?, ?, ?, ?)";
            $reqPreparee = $pdo->prepare($sql);
            $result = $reqPreparee->execute([$user_id, $name, $description, $new_image_name]);
            if ($result) {
                header("location: ./addPets.php");
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
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <h1>Ajoute ton pote aux 4 pattes ICI 🐶 🐱 🐰</h1>
    <form 
        action="addPets.php" 
        enctype="multipart/form-data" 
        method="POST"
        class="forms">
        <div class="form_group">
            <label for="name">Quel est son nom :</label> 
            <input 
                type="text" 
                id="name"
                name="name">
                <br>
            <?php if (!empty($nameErr)){ ?>
                <span class="error_message"><?php echo $nameErr; ?></span><br>
            <?php } ?>
        </div>
        <div class="form_group">
            <label for="description">Comment tu lui decris ?</label> 
            <input 
                type="text" 
                id="description"
                name="description"
                placeholder="Décris ton poto..."
            >
                <br>
            <?php if (!empty($descriptionErr)) { ?>
                <span class="error_message"><?php echo $descriptionErr; ?></span><br>
            <?php } ?>
        </div>
        <div class="form_group">
            <label for="image_path"> Tu as des photos ? Vas y : </label>
            <div class="file_uploader_container">
                <div class="file_upload_area">
                    <p>Clique ou depose ton image ici</p>
                    <p class="upload_types">JPEG, PNG | Max 5MB</p>
                    <input type="file" name="image_path" accept="image/*" class="file-input">
                </div>
            </div>
            <button type="submit" class="action-btn">Ajouter</button>
    </form>
    <p><a href="index.php" class="action-btn">Retour a la liste de potes 🐶 🐱 🐰</a></p>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>