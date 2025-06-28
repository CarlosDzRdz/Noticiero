<?php
// register.php - Procesar registro de usuarios
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validar campos vacíos
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email no válido']);
        exit;
    }
    
    // Validar longitud de contraseña
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        exit;
    }
    
    try {
        $pdo = conectarDB();
        
        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Este email ya está registrado']);
            exit;
        }
        
        // Encriptar contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Crear nombre de usuario basado en email
        $nombre = explode('@', $email)[0];
        
        // Insertar nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, password, nombre) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password_hash, $nombre]);
        
        echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>