<?php
require "../includes/session_manager.php";
require "../config/connect_db.php";

if (!isLoggedIn()) {
    header('Location: /../auth/login.php');
    exit();
}

$tmp_dir = "../uploads/tmp";
if (is_dir($tmp_dir)) {
    $files = glob($tmp_dir . "/*");
    $now = time();
    foreach ($files as $file) {
        if (is_file($file) && ($now - filemtime($file)) > 3600) {
            unlink($file); // supprime les fichiers vieux de plus d'1h
        }
    }
} else {
    mkdir($tmp_dir, 0755, true); 
}

$title = "Ajouter un pote";
$nameErr = $descriptionErr = $speciesErr = $imageErr = "";
$name = $description = $species = "";
$new_image_name = null;

$pdo = connectDb();
$speciesList = $pdo->query("SELECT id, sp_name FROM species ORDER BY sp_name")->fetchAll(PDO::FETCH_ASSOC);

$step = $_POST['step'] ?? 1;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($step == 1) {
        $name_input = $_POST["name"] ?? "";
        $description_input = $_POST["description"] ?? "";
        $species_input = $_POST["species_id"] ?? "";

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

        if (empty($species_input)) {
            $speciesErr = "Tu dois choisir une espece de pote";
        } else {
            $species_input = (int)$species_input;
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM species WHERE id = ?");
            $stmt->execute([$species_input]);
            if ($stmt->fetchColumn() == 0) {
                $speciesErr = "Espece invalide, choisis une espece dans la liste";
            } else {
                $species = $species_input;
            }
        }

        if (!empty($_FILES["image_path"]["tmp_name"])) {
            $allowed_types = ['image/jpeg', 'image/png'];
            if (!in_array($_FILES["image_path"]["type"], $allowed_types)) {
                $imageErr = "Format invalide. Seules les images JPEG et PNG sont autorisees";
            } elseif ($_FILES["image_path"]["size"] > 5242880) {
                $imageErr = "Image trop lourde, max 5MB";
            } else {
                $ext = pathinfo($_FILES["image_path"]["name"], PATHINFO_EXTENSION);
                $new_image_name = uniqid("pote_") . "." . $ext;
                move_uploaded_file($_FILES["image_path"]["tmp_name"], $tmp_dir . '/' . $new_image_name);
            }
        }

        if (empty($nameErr) && empty($descriptionErr) && empty($speciesErr) && empty($imageErr)) {
            $step = 2;
        }
    }

    if ($step == 3) {
        $current_user = getCurrentUser();
        $user_id = $current_user['id'];

        $name = $_POST['name'];
        $description = $_POST['description'];
        $species = $_POST['species_id'];
        $new_image_name = $_POST['image_tmp'];

        $final_dir = "../uploads/pets-photos";
        if (!is_dir($final_dir)) mkdir($final_dir, 0755, true);

        rename($tmp_dir . '/' . $new_image_name, $final_dir . '/' . $new_image_name);

        try {
            $sql = "INSERT INTO pets (user_id, pet_name, description, species_id, image_path) VALUES (?, ?, ?, ?, ?)";
            $reqPreparee = $pdo->prepare($sql);
            $result = $reqPreparee->execute([$user_id, $name, $description, $species, $new_image_name]);

            if ($result) {
                echo "<div class='success_message'>Ton pote a bien été ajouté ! Redirection vers Tes Potes dans 2 seg</div>";
                echo "<meta http-equiv='refresh' content='2;url=../auth/myPets.php'>";
                exit();
            } else {
                $nameErr = "Erreur lors de l'enregistrement";
            }
        } catch (PDOException $e) {
            $nameErr = "Erreur de base de données";
            echo $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/../includes/head.php'; ?>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<h1>Ajoute ton pote aux 4 pattes ICI 🐶 🐱 🐰</h1>
<main>

<?php if ($step === 1) { ?>
<form action="addPets.php" enctype="multipart/form-data" method="POST" class="forms">
    <input type="hidden" name="step" value="1">
    <div class="form_group">
        <label for="name">Quel est son nom :</label> 
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <?php if (!empty($nameErr)){ ?>
            <span class="error_message"><?php echo $nameErr; ?></span>
        <?php } ?>
    </div>

    <div class="form_group">
        <label for="description">Comment tu lui decris ?</label> 
        <input type="text" id="description" name="description" placeholder="Décris ton poto..." value="<?php echo htmlspecialchars($description); ?>">
        <?php if (!empty($descriptionErr)) { ?>
            <span class="error_message"><?php echo $descriptionErr; ?></span>
        <?php } ?>
    </div>

    <div class="form_group">
        <label for="species_id">Quelle est son espece ? </label>
        <select name="species_id" id="species_id">
            <option value="">Choisis une espece</option>
            <?php foreach ($speciesList as $spName) { ?>
                <option value="<?php echo $spName['id']; ?>" <?php echo ($species == $spName['id']) ? 'selected' : ''; ?>>
                    <?php echo $spName['sp_name']; ?>
                </option>
            <?php } ?>
        </select>
        <?php if (!empty($speciesErr)) { ?>
            <span class="error_message"><?php echo $speciesErr; ?></span>
        <?php } ?>
    </div>

    <div class="form_group">
        <label for="image_path">Tu as des photos ? Vas y :</label>
        <div class="file_uploader_container">
            <div class="file_upload_area">
                <p>Clique ICI pour deposer ton image</p>
                <p class="upload_types">JPEG, PNG | Max 5MB</p>
                <input type="file" name="image_path" accept="image/png, image/jpeg" class="file-input">
            </div>
        </div>
    </div>

    <button type="submit" class="action-btn">Aperçu</button>
</form>
<?php } ?>

<?php if ($step === 2) { ?>
<div class="pet_card">
    <img src="<?php echo $tmp_dir . '/' . $new_image_name; ?>" alt="Apercu du pote">
    <h4><?php echo htmlspecialchars($name); ?></h4>
    <p><?php echo htmlspecialchars($description); ?></p>
</div>

<form action="addPets.php" method="POST">
    <input type="hidden" name="step" value="3">
    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
    <input type="hidden" name="description" value="<?php echo htmlspecialchars($description); ?>">
    <input type="hidden" name="species_id" value="<?php echo $species; ?>">
    <input type="hidden" name="image_tmp" value="<?php echo $new_image_name; ?>">
    <button type="submit" class="action-btn">Confirmer</button>
</form>

<form action="addPets.php" method="POST">
    <input type="hidden" name="step" value="1">
    <button type="submit" class="action-btn">Annuler</button>
</form>
<?php } ?>

<p><a href="../index.php" class="action-btn">Retour a la liste de potes 🐶 🐱 🐰</a></p>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
