<?php

if (!function_exists('isLoggedIn')) {
    die('Erreur : session_manager non inclus');
}
//  bouton page Mes animaux
?>

<nav>
    <a href="../index.php" class="header_logo">
        <img src="/assets/images/logo.svg"/>
        <h2>Mate mon pote</h2>
    </a>
    <?php if(isLoggedIn() === true): ?> 
        <?php $user = getCurrentUser(); ?>
        <span>Hello ! <?php echo htmlspecialchars($user['username']); ?></span>
        <a href="/auth/logout.php" class="action-btn">Déconnexion</a>
        <a href="/auth/userProfile.php" class="action-btn">Mon Profil</a> 
    <?php else: ?>
    <div class="btn_container">
        <a class="home_login_btn" href="../auth/login.php">Connexion</a>
        <a class="home_login_btn" href="../auth/signUp.php">Inscription</a>
    </div>
    <?php endif ?>
</nav>
