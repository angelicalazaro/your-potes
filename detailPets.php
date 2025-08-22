<?php

require_once "./connect_db.php";
// Utiliser GET afficher

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $pdo = connectDb();
    $sql = "DELETE FROM pets WHERE id=:id";
    $reqPreparee = $pdo->prepare($sql);
    $result = $reqPreparee->execute
}

if ($_GET) {
    
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

</body>
</html>