<?php

require_once __DIR__ . "/../includes/session_manager.php";
require_once __DIR__ . "/../config/connect_db.php";

requireLogin();

$title = "Mes potes";
$current_user = getCurrentUser();
$user_id = $current_user['id'];
$pdo = connectDb();
$sql = "SELECT * FROM pets WHERE user_id = " . $user_id .   
        " ORDER BY created_at DESC";
$resultPdoStatement = $pdo->query($sql, PDO::FETCH_ASSOC);
$myPets = $resultPdoStatement->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/../includes/head.php'; ?>
<body>
    <?php include __DIR__ . '/../includes/header.php';?>
    <main>    
    <h1>Mes potes</h1>
        <?php if (count($myPets) === 0) { ?>
            <div class="empty-pets">
                <h2>Tu n'as pas encore de potes inscrits</h2>
                <p>Ajoute ton premier compagnon !</p>
                <a href="../pets/addPets.php" class="action-btn">Ajouter mon premier pote</a>
            </div>
        <?php } else { ?>
            <table class="my_pets_table">
                <thead>
                    <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Espece</th>
                    <th>Ajoute le</th>
                    <th>Modifie le</th>
                </thead>
                <tbody> 
                    <?php foreach ($myPets as $pet) { ?>  
                    <tr>
                        <td><?php echo ($pet['pet_name']); ?></td>   
                        <td><?php echo ($pet['description']); ?></td>   
                        <td><?php echo ($pet['species_id']); ?></td>   
                        <td><?php echo ($pet['created_at']); ?></td>   
                        <td><?php echo ($pet['update_at']); ?></td> 
                        <th>
                            <a href="../pets/detailPets.php?id=<?= $pet['id'] ?>" class="action-btn">Modifier / Supprimer</a>
                        </th>
                    </tr>  
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
