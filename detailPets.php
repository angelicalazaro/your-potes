<?php

require "./includes/session_manager.php";
require_once "./config/connect_db.php";

$nameErr = $descriptionErr = "";
$name = $description = "";
$erreur = "";

if (isset($_POST['id']) && isset($_POST['supprimer'])) {
// delete
    $sql = "DELETE FROM pets WHERE id=:id";
    $reqPreparee = $pdo->prepare($sql);
    $result = $reqPreparee->execute(['id' => $_POST['id']]);
    if ($result) {
        // redirection a Home
        header("location: index.php");
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

    if (empty($nameErr) && empty($descriptionErr)) {
        try {
            $sql = "UPDATE pets SET pet_name=:pet_name, description=:description WHERE id=:id";
            $reqPreparee = $pdo->prepare($sql);
            $result = $reqPreparee->execute([
                'id'=>$_POST['id'],
                'pet_name'=>$name,   
                'description'=>$description
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

// "get" sans _$get : peut se faire dans une func a part ?
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
// verifier que l'id est un int
if ($id === false) {
    die("ID invalide");
}
$sql = "SELECT * FROM pets WHERE id = :id ORDER BY id DESC";
$reqPreparee = $pdo->prepare($sql);
$reqPreparee->bindValue(':id', $id, PDO::PARAM_INT);
$reqPreparee->execute();
$pet = $reqPreparee->fetch(PDO::FETCH_ASSOC);

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
    <?php include __DIR__ . '/includes/header.php'; ?>
    <h1>Ton pote et ses details ICI</h1>

    <?php if (!empty($erreur)): ?>
        <div class="error_message">
            <?php echo htmlspecialchars($erreur); ?>
        </div> 
    <?php endif; ?>

    <h3>Modifier mon pote</h3>
    <form action="" method="post" class="forms">
        <input type="hidden" name="id" value="<?= $pet['id']; ?>">

        <?php
        $labels = [
            'pet_name'=> 'Nom :',
            'description'=> 'Description : ',
            'created_at'=> 'Ajoute le : ',
            'update_at'=> 'Modifie le : '
        ];
        ?>

        <?php foreach($labels as $column => $label): ?>
        <div class="form-row">
            <label><?= $label ?></label>
            <?php if(in_array($column, ['pet_name','description'])): ?>
                <input 
                    type="text" 
                    name="<?= $column ?>" 
                    value="<?= htmlspecialchars($pet[$column]) ?>"
                >
                <?php if($column === 'pet_name' && !empty($nameErr)): ?>
                    <span class="error_message"><?= $nameErr ?></span>
                <?php endif; ?>
                <?php if($column === 'description' && !empty($descriptionErr)): ?>
                    <span class="error_message"><?= $descriptionErr ?></span>
                <?php endif; ?>
            <?php else: ?>
                <span><?= htmlspecialchars($pet[$column]) ?></span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <input type="submit" name="modifier" value="Modifier" class="action-btn">
        <input type="submit" name="supprimer" value="Supprimer mon pote" class="action-btn">
    </form>
<?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>