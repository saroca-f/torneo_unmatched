<?php
	$dbPath = '/data/users.db';

    if (!isset($_COOKIE['id'])) {
        echo "<script>window.location.href='./login.html';
        window.open('../register_error.html?msg=Usuario+no+encontrado', 'Error', 'width=400,height=200');</script>";
        exit;
    }

    $userId = intval($_COOKIE['id']);
    $currentPassword = trim($_POST['password']);
    $newPassword = trim($_POST['newpassword']);

    try {
        $db = new PDO('sqlite:' . $dbPath);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($currentPassword, $user['password'])) {
            setcookie('id', '', time() - 3600, "/");
            header("Location: ./login.html?error=wrong_password");
            exit;
        }

        if (strlen($newPassword) < 8)
        {
            echo "<script>window.location.href='./perfil.php';
            window.open('./register_error.html?msg=La+nueva+contraseña+debe+tener+al+menos+8+caracteres', 'Error', 'width=400,height=200');</script>";
            exit;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':id', $userId);
        $stmt->execute();

        echo "<script>window.location.href='./perfil.php';
        window.open('./register_error.html?msg=Contraseña+actualizada+con+éxito', 'Éxito', 'width=400,height=200');</script>";
    }
    catch (PDOException $e){
        die("Error de base de datos: " . $e->getMessage());
    }
?>