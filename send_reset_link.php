<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Enlace de recuperación</title>
    <link rel="stylesheet" href="/Noticiero/CSS/estilos_contra.css">
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"];
            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $pdo = conectarDB();

            $sql = "UPDATE usuarios SET reset_token = ?, reset_expiry = ? WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$token, $expiry, $email]);

            if ($stmt->rowCount() > 0) {
                $link = "http://localhost/Noticiero/reset_password.php?token=$token";
                echo "<h3>Enlace de recuperación generado con éxito:</h3>";
                echo "<a href='$link'>$link</a>";
            } else {
                echo "<p style='color:red;'>Correo no encontrado.</p>";
            }
        }
        ?>
        <div class="forgot-password">
            <a href="forgot_password.php">Volver</a>
        </div>
    </div>
</body>
</html>
