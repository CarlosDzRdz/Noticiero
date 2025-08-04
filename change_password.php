<?php
// change_password.php - Cambiar contraseña del usuario
require_once 'config.php';

// Debug - agregar logs de error
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes estar logueado para cambiar la contraseña']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug - log de datos recibidos
    error_log("POST data recibida: " . print_r($_POST, true));
    
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validar campos vacíos
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }
    
    // Validar que las nuevas contraseñas coincidan
    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Las nuevas contraseñas no coinciden']);
        exit;
    }
    
    // Validar longitud de la nueva contraseña
    if (strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'La nueva contraseña debe tener al menos 6 caracteres']);
        exit;
    }
    
    try {
        $pdo = conectarDB();
        
        // Debug - verificar conexión
        if (!$pdo) {
            throw new Exception("No se pudo conectar a la base de datos");
        }
        
        // Obtener la contraseña actual del usuario
        $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            exit;
        }
        
        // Verificar que la contraseña actual sea correcta
        if (!password_verify($current_password, $usuario['password'])) {
            echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta']);
            exit;
        }
        
        // Verificar que la nueva contraseña sea diferente a la actual
        if (password_verify($new_password, $usuario['password'])) {
            echo json_encode(['success' => false, 'message' => 'La nueva contraseña debe ser diferente a la actual']);
            exit;
        }
        
        // Encriptar la nueva contraseña
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Actualizar la contraseña en la base de datos
        $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $result = $stmt->execute([$new_password_hash, $_SESSION['usuario_id']]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Contraseña cambiada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña']);
        }
        
    } catch (PDOException $e) {
        // Debug - log del error
        error_log("Error PDO: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Debug - log del error
        error_log("Error general: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>