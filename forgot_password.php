<!-- forgot_password.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="/Noticiero/CSS/estilos_contra.css">
</head>
<body>
    <div class="container">
        <h1>¿Olvidaste tu contraseña?</h1>
        <form method="POST" action="send_reset_link.php">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="email" required>
            <button type="submit">Enviar enlace de recuperación</button>
        </form>

        <div class="forgot-password">
            <a href="index.php">Volver al inicio</a>
        </div>
    </div>
</body>
</html>
