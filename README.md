# Your-Potes 🐶 🐱 🐰

A pet profile management web application built with pure PHP and CSS for learning purposes.

## 📋 Project Overview

**Your-Potes** is a learning project designed to master fundamental web development skills using pure PHP and CSS. The application allows users to create profiles and manage their pets by adding them with their species information to a MySQL database.

### Learning Objectives

- Master pure PHP development without frameworks
- Learn CSS styling and responsive design
- Understand MySQL database management
- Practice WAMP/XAMPP server configuration
- Implement user authentication and session management

## ✨ Features

- **User Authentication**: Sign up, login, and logout functionality
- **User Profiles**: Create and manage personal profiles
- **Pet Management**: Add pets with names, descriptions, and species
- **Pet Gallery**: Browse all registered pets
- **Pet Details**: View detailed information about individual pets
- **Responsive Design**: Clean and modern CSS styling

## 🛠️ Technologies Used

- **Backend**: Pure PHP
- **Frontend**: Pure CSS, HTML
- **Database**: MariaDB
- **Server**: WAMP/XAMPP
- **Session Management**: PHP Sessions

## 📁 Project Structure

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

### Prerequisites

- WAMP or XAMPP server
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/your-potes.git
   cd your-potes
   ```

2. **Move to server directory**
   ```bash
   # For XAMPP
   mv your-potes /Applications/XAMPP/xamppfiles/htdocs/
   
   # For WAMP
   mv your-potes C:/wamp64/www/
   ```

3. **Configure database connection**
   ```bash
   cp config/config_exemple.php config/config.php
   ```
   
   Edit `config/config.php` with your database credentials:
   ```php
   <?php
   $host_bdd = "localhost";
   $user_bdd = "root";
   $pwd_bdd = "";
   $db_name = "your_potes_db";
   ?>
   ```

4. **Create MySQL database**
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

5. **Start your server**
   - Start XAMPP/WAMP
   - Navigate to `http://localhost/your-potes`

## 📖 Usage

### Getting Started

1. **Create an account**: Register a new user account
2. **Login**: Sign in with your credentials
3. **Add pets**: Click "Ajouter un pote" to add your pets
4. **Browse pets**: View all pets on the homepage
5. **View details**: Click on any pet to see detailed information

### Main Pages

- **Homepage** (`index.php`): Display all pets and navigation
- **Add Pet** (`addPets.php`): Form to add new pets
- **Pet Details** (`detailPets.php`): Detailed view of individual pets
- **User Authentication** (`auth/`): Login, signup, and profile management

## 🔧 Configuration

### Database Configuration

Update `config/config.php` with your specific database settings:

```php
<?php
$host_bdd = "your_host";
$user_bdd = "your_username";
$pwd_bdd = "your_password";
$db_name = "your_database_name";
?>
```

### Session Configuration

Session management is handled in `includes/session_manager.php` with functions for:
- `isLoggedIn()`: Check authentication status
- `startSession()`: Initialize user session
- `clean_input()`: Sanitize user input

## 🛡️ Security Features

- Input validation and sanitization
- Prepared SQL statements to prevent injection
- Password hashing for user accounts
- Session-based authentication
- CSRF protection on forms

## 📚 Learning Resources

This project covers essential web development concepts:

- **PHP Fundamentals**: Variables, functions, classes, error handling
- **Database Operations**: CRUD operations, prepared statements, PDO
- **Web Security**: Input validation, SQL injection prevention, authentication
- **Frontend Design**: CSS layouts, responsive design, form styling
- **Server Management**: Apache configuration, virtual hosts, debugging

## 🤝 Contributing

This is a learning project, but contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 👥 Authors

- Your Name - Initial work

## 🙏 Acknowledgments

- Built for learning pure PHP and MySQL
- Inspired by the need to understand web fundamentals
- Thanks to the PHP and web development community

---

**Note**: This is a learning project focused on understanding core web development concepts using pure PHP and CSS without modern frameworks.