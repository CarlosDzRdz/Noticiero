<?php
// eliminar_usuario.php
require_once 'config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    // Evitar que el admin se elimine a sí mismo
    if ($id === $_SESSION['usuario_id']) {
        echo "<script>alert('No puedes eliminarte a ti mismo'); window.location.href='admin_dashboard.php';</script>";
        exit;
    }

    try {
        $pdo = conectarDB();
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);

        echo "<script>alert('Usuario eliminado correctamente'); window.location.href='admin_dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar el usuario'); window.location.href='admin_dashboard.php';</script>";
    }
} else {
    header('Location: admin_dashboard.php');
    exit;
}
?>