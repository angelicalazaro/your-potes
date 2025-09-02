# Your-Potes 🐶 🐱 🐰

Une application web de gestion de profils d'animaux construite en PHP et CSS purs à des fins d'apprentissage.

## 📋 Aperçu du Projet

**Your-Potes** est un projet d'apprentissage conçu pour maîtriser les compétences fondamentales du développement web en utilisant PHP et CSS purs. L'application permet aux utilisateurs de créer des profils et de gérer leurs animaux en les ajoutant avec leurs informations d'espèces dans une base de données MySQL.

### Objectifs d'Apprentissage

- Maîtriser le développement PHP pur sans frameworks
- Apprendre le stylage CSS et le design responsive
- Comprendre la gestion des bases de données MySQL
- Pratiquer la configuration de serveur WAMP/XAMPP
- Implémenter l'authentification utilisateur et la gestion de sessions

## ✨ Fonctionnalités

- **Authentification Utilisateur** : Inscription, connexion et déconnexion
- **Profils Utilisateur** : Créer et gérer des profils personnels
- **Gestion des Animaux** : Ajouter des animaux avec noms, descriptions et espèces
- **Galerie d'Animaux** : Parcourir tous les animaux enregistrés
- **Détails des Animaux** : Voir les informations détaillées de chaque animal
- **Design Responsive** : Style CSS propre et moderne

## 🛠️ Technologies Utilisées

- **Backend** : PHP pur
- **Frontend** : CSS pur, HTML
- **Base de Données** : MariaBD
- **Serveur** : WAMP/XAMPP
- **Gestion de Sessions** : Sessions PHP

## 📁 Structure du Projet

```
your-potes/
├── assets/
│   ├── debug.php
│   └── images/
├── auth/
│   ├── login.php
│   ├── logout.php
│   ├── signUp.php
│   └── userProfile.php
├── config/
│   ├── config.php
│   ├── config_exemple.php
│   └── connect_db.php
├── includes/
│   ├── footer.php
│   ├── header.php
│   └── session_manager.php
├── style/
│   ├── footer.css
│   ├── forms.css
│   ├── globals.css
│   ├── header.css
│   └── index.css
├── addPets.php
├── detailPets.php
└── index.php
```

## 🚀 Installation

### Prérequis

- Serveur WAMP ou XAMPP
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur

### Étapes d'Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/yourusername/your-potes.git
   cd your-potes
   ```

2. **Déplacer vers le répertoire serveur**
   ```bash
   # Pour XAMPP
   mv your-potes /Applications/XAMPP/xamppfiles/htdocs/
   
   # Pour WAMP
   mv your-potes C:/wamp64/www/
   ```

3. **Configurer la connexion à la base de données**
   ```bash
   cp config/config_exemple.php config/config.php
   ```
   
   Modifiez `config/config.php` avec vos identifiants de base de données :
   ```php
   <?php
   $host_bdd = "localhost";
   $user_bdd = "root";
   $pwd_bdd = "";
   $db_name = "your_potes_db";
   ?>
   ```

4. **Créer la base de données MySQL**
   ```sql
   CREATE DATABASE your_potes_db;
   USE your_potes_db;
   
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) UNIQUE NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       password VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   
   CREATE TABLE pets (
       id INT AUTO_INCREMENT PRIMARY KEY,
       pet_name VARCHAR(100) NOT NULL,
       description TEXT NOT NULL,
       user_id INT,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (user_id) REFERENCES users(id)
   );
   ```

5. **Démarrer votre serveur**
   - Démarrer XAMPP/WAMP
   - Naviguer vers `http://localhost/your-potes`

## 📖 Utilisation

### Pour Commencer

1. **Créer un compte** : S'inscrire avec un nouveau compte utilisateur
2. **Se connecter** : Se connecter avec vos identifiants
3. **Ajouter des animaux** : Cliquer sur "Ajouter un pote" pour ajouter vos animaux
4. **Parcourir les animaux** : Voir tous les animaux sur la page d'accueil
5. **Voir les détails** : Cliquer sur n'importe quel animal pour voir les informations détaillées

### Pages Principales

- **Page d'Accueil** (`index.php`) : Affiche tous les animaux et la navigation
- **Ajouter un Animal** (`addPets.php`) : Formulaire pour ajouter de nouveaux animaux
- **Détails Animal** (`detailPets.php`) : Vue détaillée des animaux individuels
- **Authentification** (`auth/`) : Connexion, inscription et gestion de profil

## 🔧 Configuration

### Configuration Base de Données

Mettez à jour `config/config.php` avec vos paramètres de base de données spécifiques :

```php
<?php
$host_bdd = "votre_host";
$user_bdd = "votre_nom_utilisateur";
$pwd_bdd = "votre_mot_de_passe";
$db_name = "votre_nom_base_donnees";
?>
```

### Configuration Sessions

La gestion de sessions est gérée dans `includes/session_manager.php` avec les fonctions pour :
- `isLoggedIn()` : Vérifier le statut d'authentification
- `startSession()` : Initialiser la session utilisateur  
- `clean_input()` : Nettoyer les entrées utilisateur

## 🛡️ Fonctionnalités de Sécurité

- Validation et nettoyage des entrées
- Requêtes SQL préparées pour éviter les injections
- Hachage des mots de passe utilisateur
- Authentification basée sur les sessions
- Protection CSRF sur les formulaires

## 📚 Ressources d'Apprentissage

Ce projet couvre les concepts essentiels du développement web :

- **Fondamentaux PHP** : Variables, fonctions, classes, gestion d'erreurs
- **Opérations Base de Données** : Opérations CRUD, requêtes préparées, PDO
- **Sécurité Web** : Validation d'entrées, prévention injection SQL, authentification
- **Design Frontend** : Layouts CSS, design responsive, stylage de formulaires
- **Gestion Serveur** : Configuration Apache, hôtes virtuels, débogage

## 🤝 Contribution

Ceci est un projet d'apprentissage, mais les contributions sont les bienvenues ! Veuillez :

1. Forker le dépôt
2. Créer une branche de fonctionnalité
3. Effectuer vos modifications
4. Tester minutieusement
5. Soumettre une pull request

## 📄 Licence

Ce projet est open source et disponible sous la [Licence MIT](LICENSE).

## 👥 Auteurs

- Votre Nom - Travail initial

## 🙏 Remerciements

- Construit pour apprendre PHP et MySQL purs
- Inspiré par le besoin de comprendre les fondamentaux du web
- Merci à la communauté PHP et de développement web

---

**Note** : Ceci est un projet d'apprentissage axé sur la compréhension des concepts fondamentaux du développement web utilisant PHP et CSS purs sans frameworks modernes.