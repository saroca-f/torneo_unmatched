<?php
    $dbPath = '/data/users.db';

    if (!isset($_COOKIE['id'])) {
        echo "<script>window.location.href='./login.html';
        window.open('./register_error.html?msg=Usuario+no+encontrado', 'Error', 'width=400,height=200');</script>";
        exit;
    }

    try {
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Buscar usuario por ID
        $userId = intval($_COOKIE['id']);

        $stmt = $db->prepare("SELECT username, avatar, id FROM users WHERE id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $targetDir = __DIR__ . "/img_src/userAvatars/";

        $oldAvatar = $targetDir . $user['avatar'];

        if ($user['avatar'] && file_exists($oldAvatar) && $user['avatar'] !== 'default.png') {
            unlink($oldAvatar);
        }

        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) 
        {
            $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
            if(!in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'gif'])) {
                echo "<script>window.open('./register_error.html?msg=Tipo+de+archivo+no+permitido.+Solo+png,+jpg,+jpeg,+gif.', 'Error', 'width=400,height=200');
                    window.location.href='perfil.php';</script>";
                exit;
            }
            
            $newFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $user['username']) . '.' . strtolower($ext);
            $targetFile = $targetDir . $newFileName;
            // Mover el archivo subido al directorio de destino
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $targetFile))
            {
                // Actualizar la base de datos con el nuevo nombre de avatar
                $stmt = $db->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
                $stmt->bindValue(':avatar', $newFileName, PDO::PARAM_STR);
                $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                echo "<script>window.open('./register_error.html?msg=Avatar+actualizado+exitosamente.', 'Error', 'width=400,height=200');
                    window.location.href='perfil.php';</script>";
            } 
            else {
                echo "<script>window.open('./register_error.html?msg=Error+al+subir+el+archivo.', 'Error', 'width=400,height=200');</script>";
            }
        } 
        else 
        {
            echo "<script>alert('No se ha seleccionado ningún archivo o ha ocurrido un error.'); window.location.href='perfil.php';</script>";
        }
    }
    catch (PDOException $e) {
        die("Error de base de datos: " . $e->getMessage());
    }
?>