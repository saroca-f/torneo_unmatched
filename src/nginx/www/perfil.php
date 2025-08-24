<?php
    $dbPath = '/data/users.db';

    $userId = intval($_COOKIE['id']);
    if (!isset($_COOKIE['id'])) {
        echo "<script>window.location.href='./login.html';
        window.open('../register_error.html?msg=Usuario+no+encontrado', 'Error', 'width=400,height=200');</script>";
        exit;
    }
    try 
    {
		$db = new PDO('sqlite:' . $dbPath);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Buscar usuario por ID
		$stmt = $db->prepare("SELECT username, avatar FROM users WHERE id = :id");
		$stmt->bindValue(':id', $userId, PDO::PARAM_INT);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		$username = $user['username'];
        if ($user['avatar'] === 'default.png') {
			$avatar = './img_src/' . $user['avatar']; // Ruta por defecto si no hay avatar
		} 
		else {
			$avatar = './img_src/userAvatars/' . $user['avatar'];
		}
	}
    catch (PDOException $e) {
        die("Error de base de datos: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Perfil</title>
        <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="perfil.css">
    </head>
    <body>
        <div id ="menu">
            <div id="avatar">
                <h1>Cambiar avatar</h1>
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar">
                <form action="avatarUpload.php" method="post" enctype="multipart/form-data" class="upload-form">
                    <label for="archivo"></label>
                    <input type="file" name="archivo" id="archivo" required>
                    <button type="submit">Subir</button>
                </form>
            </div>
            <div id="username">
                <h1>Cambiar usuario</h1>
                <h2><?php echo htmlspecialchars($username); ?></h2>
                <form action="usernameUpload.php" method="post" enctype="multipart/form-data">
                    <label for="name">Nuevo nombre de usuario</label>
                    <input type="text" name="name" id="name" required>
                    <button type="submit">Actualizar</button>
                </form>
            </div>
            <div id ="password">
                <h1>Cambiar contraseña</h1>
            </div>
            <div id="back">
                <a href="portal.php"><img src="./img_src/salida.jpg" alt="back"></a>
            </div>
        </div>
    </body>
</html>
