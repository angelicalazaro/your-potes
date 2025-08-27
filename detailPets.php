<?php

require_once "./connect_db.php";

$erreur = "";

if (isset($_POST['id']) && isset($_POST['supprimer'])) {
// delete
    $sql = "DELETE FROM pets WHERE id=:id";
    $reqPreparee = $pdo->prepare($sql);
    // passer des parametres a execute (c'est une fonction)
    $result = $reqPreparee->execute(['id' => $_POST['id']]);
    // si la requete est executee
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
    $sql = "UPDATE pets SET pet_name=:pet_name WHERE id=:id";
    $reqPreparee = $pdo->prepare($sql);
    // passer des parametres a execute (c'est une fonction)
    $result = $reqPreparee->execute(
        [
            'id'=>$_POST['id'],
            'pet_name'=>$_POST['pet_name']   
        ]
        // ['description'=>$_POST['description']]
    );
    // si la requete est executee
    if ($result) {
        // redirection a Home
        header("location: index.php?message=ok");
        exit();
    } else {
        $erreur = "Impossibilite de modifier ce pote";
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
    <link rel="stylesheet" href="/style/errorMessages.css" type="text/css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <h1>Ton pote et ses details ICI</h1>

    <?php if (!empty($erreur)): ?>
        <div class="error_message">
            <?php echo htmlspecialchars($erreur); ?>
        </div> 
    <?php endif; ?>

    <?php
    // tableau associatif pour traduire les entres bdd en etiquettes front :
    $labels = [
        'pet_name'=> 'Nom :',
        'description'=> 'Description : ',
        'created_at'=> 'Ajoute le : '
    ];
    ?>
    <table>
    <?php
    foreach($labels as $column => $label) {  ?>
    <tr>
        <!-- montrer que les elements de labels -->
      <td><h2><?php echo $label; ?></h2></td>
      <td><?php echo $pet[$column]; ?></td>
    </tr>
    
    <?php }
    ?>
    </table>
    <h3>Supprimer mon pote</h3>
        <form action="" method="post">
            <input type="submit" name="supprimer" value="Supprimer">
            <!-- champ cache pour envoyer cette demande au serveur -->
            <input type="hidden" name="id" value="<?=$pet['id'];?>" >
        </form> 
    <h3>Modifier mon pote</h3>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?=$pet['id'];?>" >
                Modifier le nom : 
            <input type="text" name="pet_name" value="<?=$pet['pet_name'];?>" >
            <input type="submit" name="modifier" value="Modifier">
        </form> 
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>