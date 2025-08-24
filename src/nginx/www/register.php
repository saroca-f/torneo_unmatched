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
        password TEXT,
        avatar TEXT DEFAULT 'default.png'
    )");

    // Recibir datos del formulario
    $username = trim($_POST['user']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Politica de usuario y contraseña (Trabajar mas adelante)
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();

    $count = $stmt->fetchColumn();
    if($count > 0) {
        echo "<script>window.location.href='../register.html'; window.open('../register_error.html?msg=El+nombre+de+usuario+ya+existe', 'Error', 'width=400,height=200');</script>";
        exit;
    }

    if (strlen($password) < 8) {
        echo "<script>window.location.href='../register.html';
        window.open('../register_error.html?msg=La+contraseña+debe+tener+al+menos+8+caracteres', 'Error', 'width=400,height=200');
        </script>";
        exit;
    }

    // Encriptar contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->execute();

    echo "<script>
        window.location.href='../login.html';
        window.open('../register_error.html?msg=Usuario+creado+con+éxito', 'Error', 'width=400,height=200');
        </script>";
}
catch (PDOException $e) 
{
    echo "Error: " . $e->getMessage();
}
?>