<pre>
<?php
    require_once "./connect_db.php";
    $sql = "SELECT * FROM pets";
    $pdo = connectDb();
    $resultPdoStatement = $pdo->query($sql, PDO::FETCH_ASSOC);
    $pets = $resultPdoStatement->fetchAll();

?>
</pre>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes potes</title>
</head>
<body>
    <h1>Bienvenue aux potes aux 4 pattes 🐶 🐱 🐰</h1>
    <h2>Tous les potes</h2>
    <?php
        foreach($pets as $pet) { ?> 
            <div>
            <tr> 
                <td><a href="./detailPets.php?id=<?=$pet['id'];?>"><?=$pet['pet_name'];?></a></td><br>
                <td><?=$pet['description'];?></td><br>
                <!-- <td>//<?=$pet['image'];?></td><br> -->
            </tr>
            </div>
        
    <?php } ?>
    <br>
    <button><a href="./addPets.php">Ajouter un pote 🐶 🐱 🐰</a></button><br>
</body>
</html>