<?php
// si la variable session_started n'existe pas, initialiser la session et mettre la variable a true (la faire exister)
if (!isset($session_started)) {
    session_start();
    $session_started = true;
}

// passer a fichier functions.php, appeler avec require
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}
?>

<nav>
    <a href="../index.php" class="header_logo">
        <img src="/assets/images/logo.svg"/>
        <h2>Mate mon pote</h2>
    </a>
    <?php if(isLoggedIn() === true): ?> 
        <span>Hello ! <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="/user/logout.php" class="action-btn">Déconnexion</a>
        <a href="/user/userProfile.php" class="action-btn">Mon Profil</a> 
    <?php else: ?>
    <div class="btn_container">
        <a class="home_login_btn" href="../user/login.php">Connexion</a>
        <a class="home_login_btn" href="../user/signUp.php">Inscription</a>
    </div>
    <?php endif ?>
</nav>
