<?php
// login.php actualizado con roles
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    try {
        $pdo = conectarDB();
        $stmt = $pdo->prepare("SELECT id, email, password, nombre, rol FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'] ?: explode('@', $usuario['email'])[0];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            echo json_encode([
                'success' => true,
                'message' => 'Login exitoso',
                'usuario' => $_SESSION['usuario_nombre'],
                'rol' => $_SESSION['usuario_rol']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
