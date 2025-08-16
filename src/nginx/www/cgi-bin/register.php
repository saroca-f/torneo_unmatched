<?php
// Ruta a la base de datos (asegúrate que es accesible desde PHP)
$dbPath = '/data/users.db';

// Conectar a SQLite
try 
{
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear tabla si no existe
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE,
        password TEXT
    )");

    // Recibir datos del formulario
    $username = trim($_POST['user']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Politica de usuario y contraseña (Trabajar mas adelante)
    if ($password !== $confirm) {
        die("Las contraseñas no coinciden");
    }
    if (empty($username) || empty($password)) {
        die("Rellena todos los campos");
    }

    // Encriptar contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->execute();

    echo "Usuario creado con éxito";
}
catch (PDOException $e) 
{
    echo "Error: " . $e->getMessage();
}
?>