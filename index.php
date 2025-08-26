
<?php
    include("./includes/header.php");
    require_once "./connect_db.php";
    $sql = "SELECT * FROM pets ORDER BY id ASC";
    $pdo = connectDb();
    $resultPdoStatement = $pdo->query($sql, PDO::FETCH_ASSOC);
    $pets = $resultPdoStatement->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes potes</title>
    <link rel="stylesheet" href="../style/index.css" type="text/css">

</head>
<body>
    <div class="index_subtitle">
        <p>L'amicale de potes aux 4 pattes 🐶 🐱 🐰</p><br>
        <p>Comment ca marche ?</p>
        <img src="./assets/images/arrow.png"/>
    </div>
    <section class="cards_bg">
        <div class="card">
            <div class="container">
                <img src="./assets/images/profile_photo.png">
                <p>1. Cree ton compte</p>
            </div>
        </div> 
        <div class="card">
            <div class="container">
                <img src="./assets/images/paw.png">
                <p>2. Ajoute ton pote</p>
            </div>
        </div> 
        <div class="card">
            <div class="container">
                <img src="./assets/images/ok-girl.png">
                <p>C'est OK ! Maintenant ton pote est en ligne.</p>
            </div>
        </div> 
    </section>  
    <h2>Tous les potes</h2>
    <?php
        foreach($pets as $pet) { ?> 
            <div>
            <tr> 
                <td><?=$pet['pet_name'];?></td><br>
                <td><?=$pet['description'];?></td><br>
                <button class="action-btn"><a href="./detailPets.php?id=<?=$pet['id'];?>">Connaitre ce pote</a></button>
            </tr>
            </div>
        
    <?php } ?>
    <br>
    <button class="action-btn"><a href="./addPets.php">Ajouter un pote 🐶 🐱 🐰</a></button><br>
</body>
</html>