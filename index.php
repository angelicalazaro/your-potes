<?php

require_once 'includes/session_manager.php';
require_once './config/connect_db.php';
$title = "Mate mon pote - Accueil";

$sql = "SELECT pets.*, users.username as owner_name, species.sp_name 
        FROM pets 
        JOIN users ON pets.user_id = users.id
        LEFT JOIN species ON pets.species_id = species.id
        ORDER BY pets.id ASC";
$resultPdoStatement = $pdo->query($sql, PDO::FETCH_ASSOC);
$pets = $resultPdoStatement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/includes/head.php'; ?>
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
                            <div class="pet_image_container">
                                <?php if (!empty($pet['image_path'])) { ?>
                                    <img src="/uploads/pets-photos/<?= ($pet['image_path']) ?>"
                                         alt="Photo de : <?= ($pet['pet_name']) ?>"
                                         class="pet_card_image">
                                <?php } else { ?>
                                    <div class="pet_no_image">
                                        <!-- Ajouter une photo par default  -->
                                        <span>Pote sans p(h)oto</span>
                                    </div>
                                <?php } ?>
                            </div>

                            <h4><strong>Nom : </strong><?php echo $pet['pet_name']; ?></h4>
                            <p><?php echo $pet['description'];?></p>
                            <p><strong>Espece : </strong><?php echo $pet['sp_name'];?></p>
                            <p><strong>Parent : </strong><?php echo $pet['owner_name'];?></p>
                            <a class="action-btn" href="/pets/detailPets.php?id=<?=$pet['id'];?>">Connaitre ce pote</a>
                        </div>
                <?php } ?>
            </div>
        </section>
        <br>
    </main>
    <div class="btn-container">
        <a class="action-btn" href="/pets/addPets.php">Ajouter un pote 🐶 🐱 🐰</a>
    </div>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>