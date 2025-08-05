<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar contraseña</title>
    <link rel="stylesheet" href="/Noticiero/CSS/estilos_contra.css"> <!-- Asegúrate que la ruta sea correcta -->
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $token = $_POST["token"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $pdo = conectarDB();

            $stmt = $pdo->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
            $stmt->execute([$password, $token]);

            if ($stmt->rowCount() > 0) {
                echo '<h3 class="success-msg">Contraseña actualizada con éxito.</h3>';
            } else {
                echo '<p class="error-msg">Error al actualizar la contraseña o el token ya expiró.</p>';
            }
        }
        ?>
        <div class="forgot-password">
            <a href="index.php">Volver al inicio</a>
        </div>
    </div>
</body>
</html>
