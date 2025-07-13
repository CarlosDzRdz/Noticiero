<?php
// editar_usuario.php
require_once 'config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$pdo = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT id, nombre, email, rol FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "<script>alert('Usuario no encontrado'); window.location.href='admin_dashboard.php';</script>";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $rol = $_POST['rol'];
    $cambiar_contra = !empty($_POST['password']);

    try {
        $pdo = conectarDB();

        if ($cambiar_contra) {
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, rol = ?, password = ? WHERE id = ?");
            $stmt->execute([$nombre, $email, $rol, $password_hash, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id = ?");
            $stmt->execute([$nombre, $email, $rol, $id]);
        }

        echo "<script>alert('Usuario actualizado'); window.location.href='admin_dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al actualizar'); window.location.href='admin_dashboard.php';</script>";
    }
    exit;
} else {
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="css/estilos_admin.css">
</head>
<body>
    <div class="admin-container">
        <div class="top-nav">
            <h1>✏️ Editar Usuario</h1>
            <a href="admin_dashboard.php" class="nav-link">← Volver al panel</a>
        </div>

        <form method="POST" action="editar_usuario.php">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            
            <label for="rol">Rol del usuario:</label>
            <select id="rol" name="rol">
                <option value="user" <?php echo $usuario['rol'] === 'user' ? 'selected' : ''; ?>>👤 Usuario</option>
                <option value="admin" <?php echo $usuario['rol'] === 'admin' ? 'selected' : ''; ?>>🛡️ Administrador</option>
            </select>
            
            <label for="password">Nueva contraseña (opcional):</label>
            <input type="password" id="password" name="password" placeholder="Dejar vacío para mantener la actual">
            
            <button type="submit" style="background: var(--success-color); color: white; padding: 12px 24px; font-size: 1rem;">
                💾 Guardar cambios
            </button>
        </form>
    </div>
</body>
</html>
