<?php
// auth.php - Sistema de autenticación y roles
session_start();

// Configuración de base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "news_tech";

// Conexión a la base de datos
function conectarBD() {
    global $host, $user, $pass, $db;
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Función para validar login
function validarLogin($email, $password) {
    $pdo = conectarBD();
    $stmt = $pdo->prepare("SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        $_SESSION['loggedin'] = true;
        
        // Log de acceso
        logAcceso($usuario['id'], 'login', 'Inicio de sesión exitoso');
        return true;
    }
    return false;
}

// Función para verificar si el usuario está logueado
function estaLogueado() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

// Función para verificar si el usuario es admin
function esAdmin() {
    return estaLogueado() && $_SESSION['usuario_rol'] === 'admin';
}

// Función para requerir login
function requiereLogin() {
    if (!estaLogueado()) {
        header("Location: login.php");
        exit();
    }
}

// Función para requerir admin
function requiereAdmin() {
    if (!esAdmin()) {
        header("Location: acceso_denegado.php");
        exit();
    }
}

// Función para cerrar sesión
function cerrarSesion() {
    if (isset($_SESSION['usuario_id'])) {
        logAcceso($_SESSION['usuario_id'], 'logout', 'Cierre de sesión');
    }
    session_destroy();
    header("Location: login.php");
    exit();
}

// Función para obtener información del usuario actual
function obtenerUsuarioActual() {
    if (!estaLogueado()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['usuario_id'],
        'nombre' => $_SESSION['usuario_nombre'],
        'email' => $_SESSION['usuario_email'],
        'rol' => $_SESSION['usuario_rol']
    ];
}

// Función para registrar logs de acceso
function logAcceso($usuario_id, $accion, $detalles = '') {
    $pdo = conectarBD();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt = $pdo->prepare("INSERT INTO logs_acceso (usuario_id, accion, detalles, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $accion, $detalles, $ip]);
}

// Función para obtener todos los usuarios (solo admin)
function obtenerTodosUsuarios() {
    $pdo = conectarBD();
    $stmt = $pdo->prepare("SELECT id, nombre, email, rol FROM usuarios ORDER BY nombre");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener usuario por ID
function obtenerUsuarioPorId($id) {
    $pdo = conectarBD();
    $stmt = $pdo->prepare("SELECT id, nombre, email, rol FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para actualizar usuario
function actualizarUsuario($id, $nombre, $email, $password = null, $rol = null) {
    $pdo = conectarBD();
    
    if ($password && $rol) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol = ? WHERE id = ?");
        $result = $stmt->execute([$nombre, $email, $password, $rol, $id]);
    } elseif ($password) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id = ?");
        $result = $stmt->execute([$nombre, $email, $password, $id]);
    } elseif ($rol) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id = ?");
        $result = $stmt->execute([$nombre, $email, $rol, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        $result = $stmt->execute([$nombre, $email, $id]);
    }
    
    if ($result) {
        logAcceso($_SESSION['usuario_id'], 'update_user', "Usuario ID $id actualizado");
    }
    
    return $result;
}

// Función para eliminar usuario
function eliminarUsuario($id) {
    $pdo = conectarBD();
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $result = $stmt->execute([$id]);
    
    if ($result) {
        logAcceso($_SESSION['usuario_id'], 'delete_user', "Usuario ID $id eliminado");
    }
    
    return $result;
}

// Función para crear usuario
function crearUsuario($nombre, $email, $password, $rol = 'user') {
    $pdo = conectarBD();
    
    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetchColumn() > 0) {
        return false; // Email ya existe
    }
    
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$nombre, $email, $password, $rol]);
    
    if ($result) {
        logAcceso($_SESSION['usuario_id'], 'create_user', "Usuario creado: $email");
    }
    
    return $result;
}

// Función para obtener logs de acceso
function obtenerLogs($limite = 50) {
    $pdo = conectarBD();
    $stmt = $pdo->prepare("
        SELECT l.*, u.nombre, u.email 
        FROM logs_acceso l 
        LEFT JOIN usuarios u ON l.usuario_id = u.id 
        ORDER BY l.created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$limite]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>