<?php
session_start(); // Start the session

$servername = gethostname();
$db_username = "showdt4k_rohit_test";
$db_password = "Rohit@4077175";
$dbname = "showdt4k_game_db";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_username = trim($_POST['username']);
    $form_password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    try {
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new PDO($dsn, $db_username, $db_password, $options);

        // Check if the 'users' table exists, create it if it does not
        $query = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $pdo->exec($query);

        // Insert the new user into the 'users' table
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute(['username' => $form_username, 'password' => $form_password]);

        // Set success message in session and redirect to index.php
        $_SESSION['message'] = "Registration successful";
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = addslashes($e->getMessage());
        header("Location: register_html.php");
        exit();
    }
}
?>
