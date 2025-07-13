// login.js - Versión actualizada
function abrirModal() {
    document.getElementById('loginModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('loginModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Cerrar modal al hacer clic en el overlay
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginModal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModal();
        cerrarModalRegistro();
    }
});

// Manejar envío del formulario de login
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
            alert('Login exitoso');
            cerrarModal();

            if (data.rol === 'admin') {
                window.location.href = 'admin_dashboard.php';
            } else {
                window.location.reload();
            }
        } else {
            alert('Error: ' + data.message);
        }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    });
});

// Crear nuevo usuario
function abrirModalRegistro() {
    cerrarModal();
    document.getElementById('registerModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function cerrarModalRegistro() {
    document.getElementById('registerModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Cerrar al hacer clic fuera
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('registerModal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModalRegistro();
    });

    // Submit del registro
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('reg_email').value;
        const password = document.getElementById('reg_password').value;
        const confirm = document.getElementById('reg_confirm').value;

        if (password !== confirm) {
            alert("Las contraseñas no coinciden.");
            return;
        }

        if (password.length < 6) {
            alert("La contraseña debe tener al menos 6 caracteres.");
            return;
        }

        const formData = new FormData(this);

        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Cuenta creada exitosamente. Ahora inicia sesión.');
                cerrarModalRegistro();
                abrirModal();
                // Limpiar formulario
                document.getElementById('registerForm').reset();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error de conexión');
        });
    });
});

function regresarAlLogin() {
    cerrarModalRegistro();
    abrirModal();
}

// Función para cerrar sesión
function cerrarSesion() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        fetch('logout.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Sesión cerrada exitosamente');
                window.location.reload();
            } else {
                alert('Error al cerrar sesión');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
}