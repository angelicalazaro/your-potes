<?php

require_once "./connect_db.php";

// delete
if (isset($_POST['id']) && isset($_POST['supprimer'])) {
    $pdo = connectDb();
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
    $pdo = connectDb();
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
        header("location: index.php");
        exit();
    } else {
        $erreur = "Impossibilite de modifier ce pote";
    }
}

// Utiliser GET afficher
if (isset($_GET['id'])) {
    $pdo = connectDb();
    $sql = "SELECT * FROM pets WHERE id=:id";
    $reqPreparee = $pdo->prepare($sql);
     // passer des parametres a execute (c'est une fonction)
    $result = $reqPreparee->execute(['id' => $_GET['id']]);
    $pet = $reqPreparee->fetch(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ton pote</title>
</head>
<body>
    <h1>Ton pote et ses details ICI</h1>
    <?php
    // tableau associatif pour traduire les entres bdd en etiquettes front :
    $labels = [
        'pet_name'=> 'Nom :',
        'description'=> 'Description : ',
        'created_at'=> 'Ajoute le : '
    ];
    foreach($labels as $column => $label) {  ?>
    <table>
    <tr>
        <!-- montrer que les elements de labels -->
      <td><h2><?=$label?></h2></td>
      <td><?=$pet[$column]?></td>
    </tr>
    </table>
    <?php }
    ?>
    <div><h3>Supprimer mon pote</h3>
    <form action="" method="post">
        <input type="submit" name="supprimer" value="Supprimer">
        <!-- champ cache pour envoyer cette demande au serveur -->
        <input type="hidden" name="id" value="<?=$pet['id'];?>" >
    </form> 
    <div><h3>Modifier mon pote</h3>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?=$pet['id'];?>" >
       Modifier le nom : 
        <input type="text" name="pet_name" value="<?=$pet['pet_name'];?>" >
        <input type="submit" name="modifier" value="Modifier">
    </form> 
</body>
</html>