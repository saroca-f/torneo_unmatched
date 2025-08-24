<?php
	$dbPath = '/data/users.db';

    if (!isset($_COOKIE['id'])) {
        echo "<script>window.location.href='./login.html';
        window.open('../register_error.html?msg=Usuario+no+encontrado', 'Error', 'width=400,height=200');</script>";
        exit;
    }
    else {
        // Eliminar la cookie estableciendo su tiempo de expiración en el pasado
        setcookie('id', '', time() - 3600, "/");
        // Redirigir al usuario a la página de inicio de sesión
        echo "<script>window.location.href='./login.html';
        window.open('../register_error.html?msg=Sesión+cerrada+exitosamente', 'Info', 'width=400,height=200');</script>";
        exit;
    }
?>