<?php
require "../includes/session_manager.php";
require "../config/connect_db.php";

if (!isLoggedIn()) {
    header('Location: /../auth/login.php');
    exit();
}
// initialisation des variables
$title = "Ajouter un pote";
$nameErr = "";
$descriptionErr = "";
$speciesErr = "";
$imageErr = ""; 


$name = "";
$description = "";
$species = "";
$new_image_name = null;

$pdo = connectDb();
$species_sql = "SELECT id, sp_name FROM species ORDER BY sp_name";
$requeteSpecies = $pdo->query($species_sql);
$speciesList = $requeteSpecies->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name_input = $_POST["name"] ?? "";
    $description_input = $_POST["description"] ?? "";
    $species_input = $_POST["species_id"] ?? "";
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
    if (empty($species_input)) {
        $speciesErr = "Tu dois choisir une espece de pote";
    } else {
        $species_input = (int)$species_input;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM species WHERE id = ?");
        $stmt->execute([$species_input]);

        if($stmt->fetchColumn() == 0) {
            $speciesErr = "Espece invalide, choisis une espece dans la liste";
        } else {
            $species = $species_input;
        }
    }
    if (!empty($_FILES["image_path"]["tmp_name"])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES["image_path"]["type"], $allowed_types)) {
            $imageErr = "Format invalide. Seules les images JPEG et PNG sont autorisees";
            return;
        }
        elseif($_FILES["image_path"]["size"] > 5242880) {
            $imageErr = "Image trop lourde, max 5MB";
            return;
        }
        else {
            $file_basename = pathinfo($_FILES["image_path"]["name"], PATHINFO_FILENAME);
            $file_extension = pathinfo($_FILES["image_path"]["name"], PATHINFO_EXTENSION);
            $new_image_name = $file_basename . '_' . date("Ymd_His") . '.' . $file_extension;

            $target_directory = "../uploads/pets-photos";
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0755, true);
            }
            $target_path = $target_directory . '/' . $new_image_name;
        }
    }
    // si aucune erreur, insert en bdd :
    if (empty($nameErr) && empty($descriptionErr) && empty($speciesErr) && empty($imageErr)) {

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
            $sql = "INSERT INTO pets (user_id, pet_name, description, species_id, image_path) VALUES (?, ?, ?, ?, ?)";
            $reqPreparee = $pdo->prepare($sql);
            $result = $reqPreparee->execute([$user_id, $name, $description, $species, $new_image_name]);
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
<?php include __DIR__ . '/../includes/head.php'; ?>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <h1>Ajoute ton pote aux 4 pattes ICI 🐶 🐱 🐰</h1>
    <main>
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
                <label for="species_id">Quelle est son espece ? </label>
                <select name="species_id" id="species_id">
                    <option value="">Choisis une espece</option>
                    <?php foreach ($speciesList as $spName) { ?>
                        <option
                          value="<?php echo $spName['id']; ?>"
                          <?php echo (isset($species_input) && (string)$species_input === (string)$spName['id']) ? 'selected' : ''; ?>
                        >
                        <?php echo $spName['sp_name']; ?>
                    </option>
                    <?php } ?>
                </select>
                <?php if (!empty($speciesErr)) { ?>
                    <span class="error_message"><?php echo $speciesErr; ?></span><br>
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
            </div>
                <button type="submit" class="action-btn">Ajouter</button>
        </form>
    <p><a href="../index.php" class="action-btn">Retour a la liste de potes 🐶 🐱 🐰</a></p>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>