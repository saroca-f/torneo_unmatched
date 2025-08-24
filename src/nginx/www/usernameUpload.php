<?php
    $dbPath = '/data/users.db';

    $userId = intval($_COOKIE['id']);
    if (!isset($_COOKIE['id'])) {
        echo "<script>window.location.href='./login.html';
        window.open('../register_error.html?msg=Usuario+no+encontrado', 'Error', 'width=400,height=200');</script>";
        exit;
    }

    $newName = trim($_POST['name']);

    try 
    {
		$db = new PDO('sqlite:' . $dbPath);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->bindValue(':username', $newName, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "<script>window.location.href='./perfil.php';
            window.open('../register_error.html?msg=El+nombre+de+usuario+ya+existe.', 'Error', 'width=400,height=200');</script>";
            exit;
        }

		// Buscar usuario por ID
		$stmt = $db->prepare("SELECT username, avatar FROM users WHERE id = :id");
		$stmt->bindValue(':id', $userId, PDO::PARAM_INT);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		$currentAvatar = $user['avatar'];
        $currentUsername = $user['username'];

        $avatarDir = './img_src/userAvatars/';
        
        if ($newName !== $currentUsername && $currentAvatar !== 'default.png') {
            $ext = pathinfo($currentAvatar, PATHINFO_EXTENSION);
            $newAvatarName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $newName) . '.' . $ext;
            rename($avatarDir . $currentAvatar, $avatarDir . $newAvatarName);
            $currentAvatar = $newAvatarName;
        }

        $stmt = $db->prepare("UPDATE users SET username = :username, avatar = :avatar WHERE id = :id");
        $stmt->bindValue(':username', $newName, PDO::PARAM_STR);
        $stmt->bindValue(':avatar', $currentAvatar, PDO::PARAM_STR);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ./perfil.php");
	}
    catch (PDOException $e) {
        die("Error de base de datos: " . $e->getMessage());
    }
?>
