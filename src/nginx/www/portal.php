<?php
	$dbPath = '/data/users.db';

	if (!isset($_COOKIE['id'])) {
		echo "<script>window.location.href='./login.html';
		window.open('../register_error.html?msg=Usuario+no+encontrado', 'Error', 'width=400,height=200');</script>";
		exit;
	}

	$userId = intval($_COOKIE['id']);
	try {
		$db = new PDO('sqlite:' . $dbPath);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Buscar usuario por ID
		$stmt = $db->prepare("SELECT username, avatar FROM users WHERE id = :id");
		$stmt->bindValue(':id', $userId, PDO::PARAM_INT);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		$username = $user['username'];
		if ($user['avatar'] === 'default.png') {
			$avatar = './img_src/default.png'; // Ruta por defecto si no hay avatar
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
	<title>Portal</title>
	<link rel="stylesheet" href="portal.css">
</head>
<body>
	<!-- Menú lateral -->
	<div class="sidebar" id="sidebar">
		<button class="toggle-btn" onclick="toggleSidebar()">
			<img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar"> <!-- tu avatar -->
		</button>
		<span class="username"><?php echo htmlspecialchars($username); ?></span>
		<nav class="menu" alt="Menú lateral">
			<a href="perfil.php">Configuración de perfil</a><br>
			<a href="equipo.php">Equipo</a><br>
			<a href="deslogueo.php">Desloguearse</a>
		</nav>
	</div>

	<main>
		<h1>Bienvenido al Portal</h1>
		<p>Aquí va el contenido de tu aplicación.</p>
	</main>

	<script>
		function toggleSidebar() {
			document.getElementById("sidebar").classList.toggle("collapsed");
		}
	</script>
</body>
</html>
