<?php
// admin_dashboard.php
require_once 'config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$pdo = conectarDB();
$stmt = $pdo->query("SELECT id, email, nombre, rol, fecha_registro FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/estilos_admin.css">
</head>
<body>
    <div class="admin-container">
        <div class="top-nav">
            <h1>Panel de Administración</h1>
            <a href="index.php" class="nav-link">← Volver al inicio</a>
        </div>
        
        <p style="text-align: center; color: var(--text-secondary); margin-bottom: 30px;">
            Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong> (Administrador)
        </p>

        <h2>👥 Gestión de Usuarios</h2>
        
        <!-- Barra de búsqueda y filtros -->
        <div class="search-filters">
            <div class="search-box">
                <label class="search-label">🔍 Buscar usuario:</label>
                <input type="text" id="searchInput" placeholder="Buscar por nombre o email..." onkeyup="filterTable()">
            </div>
            <div class="filter-box">
                <label class="search-label">🎯 Filtrar por rol:</label>
                <select id="roleFilter" onchange="filterTable()">
                    <option value="">Todos los roles</option>
                    <option value="admin">Administradores</option>
                    <option value="user">Usuarios</option>
                </select>
            </div>
        </div>
        
        <!-- Contenedor de tabla con scroll -->
        <div class="table-container">
            <table id="usersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <span class="role-badge <?php echo 'role-' . $usuario['rol']; ?>">
                                <?php echo $usuario['rol'] === 'admin' ? 'Administrador' : 'Usuario'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                        <td>
                            <div class="actions">
                                <form method="GET" action="editar_usuario.php" style="display:inline">
                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                    <button type="submit">✏️ Editar</button>
                                </form>
                                
                                <?php if ($usuario['id'] !== $_SESSION['usuario_id']): ?>
                                    <form method="POST" action="eliminar_usuario.php" style="display:inline">
                                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                        <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">🗑️ Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput');
            const roleFilter = document.getElementById('roleFilter');
            const table = document.getElementById('usersTable');
            const tr = table.getElementsByTagName('tr');
            
            const searchTerm = searchInput.value.toLowerCase();
            const roleFilterValue = roleFilter.value.toLowerCase();
            
            // Comenzar desde 1 para saltar el header
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let showRow = true;
                
                if (td.length > 0) {
                    const nombre = td[1].textContent.toLowerCase();
                    const email = td[2].textContent.toLowerCase();
                    const roleBadge = td[3].querySelector('.role-badge');
                    
                    // Filtro de búsqueda
                    const matchesSearch = nombre.includes(searchTerm) || email.includes(searchTerm);
                    
                    // Filtro de rol - usar las clases CSS para identificar el rol real
                    let matchesRole = true;
                    if (roleFilterValue) {
                        if (roleFilterValue === 'admin') {
                            matchesRole = roleBadge.classList.contains('role-admin');
                        } else if (roleFilterValue === 'user') {
                            matchesRole = roleBadge.classList.contains('role-user');
                        }
                    }
                    
                    showRow = matchesSearch && matchesRole;
                }
                
                tr[i].style.display = showRow ? '' : 'none';
            }
            
            // Mostrar mensaje si no hay resultados
            updateNoResultsMessage();
        }
        
        function updateNoResultsMessage() {
            const table = document.getElementById('usersTable');
            const tbody = table.querySelector('tbody');
            const rows = tbody.getElementsByTagName('tr');
            let visibleRows = 0;
            
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].style.display !== 'none') {
                    visibleRows++;
                }
            }
            
            // Remover mensaje anterior si existe
            const existingMessage = document.getElementById('noResultsMessage');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            // Agregar mensaje si no hay resultados
            if (visibleRows === 0) {
                const messageRow = document.createElement('tr');
                messageRow.id = 'noResultsMessage';
                messageRow.innerHTML = `
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                        📭 No se encontraron usuarios que coincidan con los criterios de búsqueda
                    </td>
                `;
                tbody.appendChild(messageRow);
            }
        }
        
        // Limpiar filtros al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('roleFilter').value = '';
        });
    </script>
</body>
</html>