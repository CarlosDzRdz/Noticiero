<?php
// config.php - Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Usuario por defecto de WAMP
define('DB_PASS', ''); // Contraseña vacía por defecto en WAMP
define('DB_NAME', 'news_tech');

// Función para conectar a la base de datos
function conectarDB() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>