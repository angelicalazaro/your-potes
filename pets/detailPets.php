<?php

require "../includes/session_manager.php";
require_once "../config/connect_db.php";

$nameErr = $descriptionErr = "";
$name = $description = "";
$erreur = "";

// get de potes
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    die("ID invalide");
}
$sql = "SELECT pets.*, users.username as owner_name 
        FROM pets 
        JOIN users ON pets.user_id = users.id
        WHERE pets.id = :id
        ORDER BY pets.id ASC";
$reqPreparee = $pdo->prepare($sql);
$reqPreparee->bindValue(':id', $id, PDO::PARAM_INT);
$reqPreparee->execute();
$pet = $reqPreparee->fetch(PDO::FETCH_ASSOC);

$can_edit = false;
if (isLoggedIn()) {
    $current_user = getCurrentUser();
    $can_edit = ($pet['user_id'] === $current_user['id']);
}

if (isset($_POST['id']) && isset($_POST['supprimer'])) {
    // delete
    $sql = "DELETE FROM pets WHERE id=:id";
    $reqPreparee = $pdo->prepare($sql);
    $result = $reqPreparee->execute(['id' => $_POST['id']]);
    if ($result) {
        header("location: ../index.php");
        exit();
    } else {
        $erreur = "Impossibilite de supprimer ce pote";
    }
}

// update
if (isset($_POST['id']) && isset($_POST['modifier'])) {

    $name_input = $_POST["pet_name"] ?? "";
    $description_input = $_POST["description"] ?? "";

    if (empty($name_input)) {
        $nameErr = "Introduit un nom, c'est un champ obligatoire";
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
            $descriptionErr = "Format invalide, rentre une bonne description";
        }
    }
    // image actuelle conservee par default
    $new_image_path = $pet['image_path']; 

    // si nouvelle image uploadée :
    if (!empty($_FILES["new_image"]["tmp_name"])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES["new_image"]["type"], $allowed_types) && $_FILES["new_image"]["size"] <= 5242880) {
            
            $file_basename = pathinfo($_FILES["new_image"]["name"], PATHINFO_FILENAME);
            $file_extension = pathinfo($_FILES["new_image"]["name"], PATHINFO_EXTENSION);
            $new_image_name = $file_basename . '_' . date("Ymd_His") . '.' . $file_extension;
            
            $target_directory = "../uploads/pets-photos/";
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0755, true);
            }
            $target_path = $target_directory . $new_image_name;
            
            if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_path)) {
                // Supprimer ancienne image
                if (!empty($pet['image_path']) && file_exists($target_directory . $pet['image_path'])) {
                    unlink($target_directory . $pet['image_path']);
                }
                $new_image_path = $new_image_name;
            }
        }
    }

    // si supprimer image
    if (isset($_POST['delete_image']) && $_POST['delete_image'] == '1') {
        if (!empty($pet['image_path']) && file_exists("../uploads/pets-photos/" . $pet['image_path'])) {
            unlink("../uploads/pets-photos/" . $pet['image_path']);
        }
        $new_image_path = null;
    }

    if (empty($nameErr) && empty($descriptionErr)) {
        try {
            $sql = "UPDATE pets SET pet_name=:pet_name, description=:description, image_path=:image_path WHERE id=:id";
            $reqPreparee = $pdo->prepare($sql);
            $result = $reqPreparee->execute([
                'id' => $_POST['id'],
                'pet_name' => $name,   
                'description' => $description,
                'image_path' => $new_image_path
            ]);
            if ($result) {
                $successMsg = "Modifications enregistrées avec succès !";
            } else {
                $erreur = "Impossibilite de modifier ce pote";
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
    <title>Ton pote</title>
    <link rel="stylesheet" href="/style/index.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
    <link rel="stylesheet" href="/style/globals.css" type="text/css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <div class="pet-image-container">
        <?php if (!empty($pet["image_path"])) { ?>
            <img src="../uploads/pets-photos/<?= htmlspecialchars($pet['image_path']) ?>"
                 alt="Photo de <?= htmlspecialchars($pet['pet_name']) ?>"
                 class="pet-detail-image">
        <?php } else { ?>
            <div class="no-image-placeholder">
                <p>Aucune photo pour ce pote</p>
            </div>
        <?php } ?>
    </div>

    <?php if (!empty($erreur)) { ?>
        <div class="error_message">
            <?php echo htmlspecialchars($erreur); ?>
        </div> 
    <?php } ?>

    <?php if (!empty($successMsg)) { ?>
        <div class="success_message">
            <?php echo htmlspecialchars($successMsg); ?>
        </div> 
    <?php } ?>
    <?php if ($can_edit) { ?>
        <h3>Modifier mon pote</h3>
        <form action="" method="post" enctype="multipart/form-data" class="forms">
            <input type="hidden" name="id" value="<?= $pet['id']; ?>">

            <?php
            $labels = [
                'pet_name' => 'Nom :',
                'description' => 'Description : ',
                'image_path' => 'Photo : ',
                'created_at' => 'Ajoute le : ',
                'update_at' => 'Modifie le : '
            ];
            ?>

            <?php foreach($labels as $column => $label) { ?>
            <div class="form-row">
                <label><?= $label ?></label>
                
                <?php if (in_array($column, ['pet_name','description'])) { ?>
                    <input 
                        type="text" 
                        name="<?= $column ?>" 
                        value="<?= ($pet[$column]) ?>"
                    >
                    <?php if ($column === 'pet_name' && !empty($nameErr)) { ?>
                        <span class="error_message"><?= $nameErr ?></span>
                    <?php } ?>
                    <?php if ($column === 'description' && !empty($descriptionErr)) { ?>
                        <span class="error_message"><?= $descriptionErr ?></span>
                    <?php } ?>
                    
                <?php } elseif ($column === 'image_path') { ?>
                    <?php if (!empty($pet["image_path"])) { ?>
                        <label>
                            <input type="checkbox" name="delete_image" value="1">
                            Supprimer l'image actuelle
                        </label><br>
                    <?php } ?>
                    <label>Nouvelle image (optionnel) :</label>
                    <input type="file" name="new_image" accept="image/*">
                    
                <?php } else { ?>
                    <span><?= ($pet[$column]) ?></span>
                <?php } ?>
            </div>
            <?php } ?>

            <input type="submit" name="modifier" value="Modifier" class="action-btn">
            <input type="submit" name="supprimer" value="Supprimer mon pote" class="action-btn">
        </form>
    <?php } else { ?>
        <h3>Detail du pote</h3>
        <div class="read_only">
            <p><strong>Nom : </strong><?php echo ($pet['pet_name']); ?></p>
            <p><strong>Description : </strong><?php echo ($pet['description']) ?></p>
            <p><strong>Parent du pote : </strong><?php echo ($pet['owner_name']) ?></p>
            <p><strong>Ajoute le : </strong><?php echo ($pet['created_at'])?></p>
            <?php if (!isLoggedIn()) { ?>
                <p><a href="../auth/login.php" class="action-btn">Connecte toi pour ajouter tes propres potes</a></p>
                <p>OU</p>
                <p><a href="../auth/login.php" class="action-btn">Inscris toi pour creer ton compte</a></p>
            <?php } ?>
        </div>
    <?php } ?>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>