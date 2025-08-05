<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
   <link rel="stylesheet" href="/Noticiero/CSS/estilos_contra.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $pdo = conectarDB();

            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE reset_token = ? AND reset_expiry > NOW()");
            $stmt->execute([$token]);
            $result = $stmt->fetchAll();

            if (count($result) === 1) {
                echo '
                <h1>Restablecer Contraseña</h1>
                <form method="POST" action="update_password.php">
                    <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                    <label for="password">Nueva contraseña:</label>
                    <input type="password" name="password" id="password" required>
                    <button type="submit">Actualizar</button>
                </form>';
            } else {
                echo '<p style="color:red;">Token inválido o expirado.</p>';
            }
        }
        ?>
        <div class="forgot-password">
            <a href="index.php">Volver al inicio</a>
        </div>
    </div>
</body>
</html>
