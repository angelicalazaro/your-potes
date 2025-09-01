<?php
    require_once "./config/connect_db.php";
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
    <link rel="icon" type="image/svg+xml" href="/assets/images/logo.svg">
    <link rel="stylesheet" href="../style/index.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <div class="index_subtitle">
        <h2>L'amicale de potes aux 4 pattes 🐶 🐱 🐰</h2><br>
        <p>Comment ca marche ?</p>
        <img src="./assets/images/arrow.png"/>
    </div>
    <main>
        <section class="main_container">
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
            <section class="pets_container">
                <div>
                    <img src="./assets/images/dogHome.png" />
                    <img src="./assets/images/catHome.png" />
                </div>
            </section> 
        </section>
        <section class="pets_home_list">
            <h3>Tous les potes</h3>
            <div class="pets_cards">
                <?php
                    foreach($pets as $pet) { ?> 
                        <div class="pet_card">
                            <h4><?=$pet['pet_name'];?></h4>
                            <p><?=$pet['description'];?></p>
                            <a class="action-btn" href="./detailPets.php?id=<?=$pet['id'];?>">Connaitre ce pote</a>
                        </div>
                <?php } ?>
            </div>
        </section>
        <br>
    </main>
    <div class="btn-container">
        <a class="action-btn" href="./addPets.php">Ajouter un pote 🐶 🐱 🐰</a>
    </div>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>