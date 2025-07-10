<?php
require_once 'config.php';
require_once 'auth.php';

// Verificar que sea admin
requiereAdmin();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    switch ($accion) {
        case 'eliminar_usuario':
            if (isset($_POST['usuario_id'])) {
                $id = intval($_POST['usuario_id']);
                if ($id != $_SESSION['usuario_id']) { // No puede eliminarse a sí mismo
                    if (eliminarUsuario($id)) {
                        $mensaje = "Usuario eliminado exitosamente.";
                        $tipo_mensaje = "success";
                    } else {
                        $mensaje = "Error al eliminar el usuario.";
                        $tipo_mensaje = "danger";
                    }
                } else {
                    $mensaje = "No puedes eliminar tu propia cuenta.";
                    $tipo_mensaje = "warning";
                }
            }
            break;
            
        case 'crear_usuario':
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $rol = $_POST['rol'];
            
            if (empty($nombre) || empty($email) || empty($password)) {
                $mensaje = "Todos los campos son obligatorios.";
                $tipo_mensaje = "danger";
            } else {
                if (crearUsuario($nombre, $email, $password, $rol)) {
                    $mensaje = "Usuario creado exitosamente.";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al crear el usuario. El email podría ya existir.";
                    $tipo_mensaje = "danger";
                }
            }
            break;
            
        case 'actualizar_usuario':
            $id = intval($_POST['usuario_id']);
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $rol = $_POST['rol'];
            
            if (empty($nombre) || empty($email)) {
                $mensaje = "El nombre y email son obligatorios.";
                $tipo_mensaje = "danger";
            } else {
                // Si es el propio admin, no cambiar el rol
                if ($id == $_SESSION['usuario_id']) {
                    $rol = null;
                }
                
                if (actualizarUsuario($id, $nombre, $email, !empty($password) ? $password : null, $rol)) {
                    $mensaje = "Usuario actualizado exitosamente.";
                    $tipo_mensaje = "success";
                    
                    // Si actualizó su propia información, actualizar la sesión
                    if ($id == $_SESSION['usuario_id']) {
                        $_SESSION['usuario_nombre'] = $nombre;
                        $_SESSION['usuario_email'] = $email;
                    }
                } else {
                    $mensaje = "Error al actualizar el usuario.";
                    $tipo_mensaje = "danger";
                }
            }
            break;
    }
}

// Obtener usuarios
$usuarios = obtenerTodosUsuarios();

// Obtener logs recientes
$logs = obtenerLogs(20);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Noticiero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1rem 1.5rem;
        }
        .stats-card {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .stats-card h3 {
            margin: 0;
            font-size: 2.5rem;
        }
        .btn-action {
            margin: 0.2rem;
        }
        .log-item {
            border-left: 4px solid #007bff;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
            background: white;
            border-radius: 0 10px 10px 0;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-newspaper"></i> Noticiero - Admin
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php"><i class="fas fa-home"></i> Ver Sitio</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Mensajes -->
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?= count($usuarios) ?></h3>
                    <p><i class="fas fa-users"></i> Usuarios Totales</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                    <h3><?= count(array_filter($usuarios, function($u) { return $u['rol'] == 'admin'; })) ?></h3>
                    <p><i class="fas fa-user-shield"></i> Administradores</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #3742fa 0%, #2f3542 100%);">
                    <h3><?= count(array_filter($usuarios, function($u) { return $u['rol'] == 'user'; })) ?></h3>
                    <p><i class="fas fa-user"></i> Usuarios Regulares</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);">
                    <h3><?= count($logs) ?></h3>
                    <p><i class="fas fa-history"></i> Logs Recientes</p>
                </div>
            </div>
        </div>

        <!-- Gestión de Usuarios -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-users-cog"></i> Gestión de Usuarios</h5>
                <button class="btn btn-light btn-sm float-end" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
                    <i class="fas fa-plus"></i> Crear Usuario
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= $usuario['id'] ?></td>
                                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $usuario['rol'] == 'admin' ? 'danger' : 'primary' ?>">
                                            <?= $usuario['rol'] == 'admin' ? 'Admin' : 'Usuario' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btn-action" 
                                                onclick="editarUsuario(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>', '<?= htmlspecialchars($usuario['email']) ?>', '<?= $usuario['rol'] ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                                            <button class="btn btn-sm btn-danger btn-action" 
                                                    onclick="eliminarUsuario(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Logs de Actividad -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> Logs de Actividad Reciente</h5>
            </div>
            <div class="card-body">
                <?php foreach ($logs as $log): ?>
                    <div class="log-item">
                        <strong><?= htmlspecialchars($log['nombre'] ?? 'Usuario desconocido') ?></strong>
                        <span class="text-muted">(<?= htmlspecialchars($log['email'] ?? 'N/A') ?>)</span>
                        - <?= ucfirst($log['accion']) ?>
                        <?php if ($log['detalles']): ?>
                            <br><small class="text-muted"><?= htmlspecialchars($log['detalles']) ?></small>
                        <?php endif; ?>
                        <br><small class="text-muted">
                            <i class="fas fa-clock"></i> <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($log['ip_address']) ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="crearUsuarioModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="crear_usuario">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select class="form-select" name="rol" required>
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="editarUsuarioModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="actualizar_usuario">
                        <input type="hidden" name="usuario_id" id="edit_usuario_id">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña (opcional)</label>
                            <input type="password" class="form-control" name="password" id="edit_password">
                            <div class="form-text">Deja vacío si no quieres cambiar la contraseña</div>
                        </div>
                        <div class="mb-3" id="rol_container">
                            <label class="form-label">Rol</label>
                            <select class="form-select" name="rol" id="edit_rol">
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Eliminación -->
    <div class="modal fade" id="eliminarUsuarioModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="eliminar_usuario">
                        <input type="hidden" name="usuario_id" id="delete_usuario_id">
                        <p>¿Estás seguro de que quieres eliminar al usuario <strong id="delete_usuario_nombre"></strong>?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Esta acción no se puede deshacer.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editarUsuario(id, nombre, email, rol) {
            document.getElementById('edit_usuario_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_rol').value = rol;
            
            // Si es el propio usuario, ocultar selector de rol
            if (id == <?= $_SESSION['usuario_id'] ?>) {
                document.getElementById('rol_container').style.display = 'none';
            } else {
                document.getElementById('rol_container').style.display = 'block';
            }
            
            new bootstrap.Modal(document.getElementById('editarUsuarioModal')).show();
        }
        
        function eliminarUsuario(id, nombre) {
            document.getElementById('delete_usuario_id').value = id;
            document.getElementById('delete_usuario_nombre').textContent = nombre;
            new bootstrap.Modal(document.getElementById('eliminarUsuarioModal')).show();
        }
    </script>
</body>
</html>