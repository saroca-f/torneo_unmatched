<?php
$dbPath = '/data/users.db';

// Verificar si la base de datos existe
if(!file_exists($dbPath)) {
    echo "<script>window.location.href='../login.html';
    window.open('../register_error.html?msg=Usuario+no+encontrado', 'Error', 'width=400,height=200');
    </script>";
    exit;
}

// Conectar a SQLite
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

// Recibir datos del formulario
$username = trim($_POST['user']);
$password = $_POST['password'];

// Verificar si el usuario existe
$stmt = $db->prepare("SELECT * FROM users Where username = :username");
$stmt->bindValue(':username', $username);
$stmt->execute();

$userExist = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$userExist) {
    echo "<script>window.location.href='../login.html';
    window.open('../register_error.html?msg=Usuario+no+encontrado', 'Error', 'width=400,height=200');</script>";
    exit;
}

// Verificar la contraseña
if (!password_verify($password, $userExist['password'])) {
    echo "<script>window.location.href='../login.html';
    window.open('../register_error.html?msg=Contraseña+incorrecta', 'Error', 'width=400,height=200');</script>";
    exit;
}

// Si todo es correcto, redirigir al usuario
setcookie("id", $userExist['id'], time() + (86400 * 30), "/"); // Cookie válida por 30 días
echo "<script>window.location.href='../portal.php';</script>";
?>