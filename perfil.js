// perfil.js - JavaScript para la página de perfil

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'success') {
    const alertContainer = document.getElementById('alertContainer');
    const alertHtml = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    alertContainer.innerHTML = alertHtml;
    
    // Auto ocultar después de 5 segundos
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

// Función para evaluar la fuerza de la contraseña
function evaluarFuerzaPassword(password) {
    let fuerza = 0;
    const strengthBar = document.getElementById('strengthBar');
    
    if (password.length >= 6) fuerza += 1;
    if (password.length >= 8) fuerza += 1;
    if (/[A-Z]/.test(password)) fuerza += 1;
    if (/[a-z]/.test(password)) fuerza += 1;
    if (/[0-9]/.test(password)) fuerza += 1;
    if (/[^A-Za-z0-9]/.test(password)) fuerza += 1;
    
    // Remover clases anteriores
    strengthBar.classList.remove('strength-weak', 'strength-medium', 'strength-strong');
    
    if (fuerza <= 2) {
        strengthBar.classList.add('strength-weak');
    } else if (fuerza <= 4) {
        strengthBar.classList.add('strength-medium');
    } else {
        strengthBar.classList.add('strength-strong');
    }
}

// Función para limpiar el formulario
function limpiarFormulario() {
    document.getElementById('changePasswordForm').reset();
    document.getElementById('strengthBar').classList.remove('strength-weak', 'strength-medium', 'strength-strong');
    document.getElementById('alertContainer').innerHTML = '';
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
                window.location.href = 'index.php';
            } else {
                mostrarAlerta('Error al cerrar sesión', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'danger');
        });
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const newPasswordInput = document.getElementById('new_password');
    const changePasswordForm = document.getElementById('changePasswordForm');
    
    // Evaluar fuerza de contraseña en tiempo real
    newPasswordInput.addEventListener('input', function() {
        evaluarFuerzaPassword(this.value);
    });
    
    // Manejar envío del formulario
    changePasswordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        // Validaciones del lado del cliente
        if (currentPassword.trim() === '') {
            mostrarAlerta('La contraseña actual es obligatoria', 'warning');
            return;
        }
        
        if (newPassword.length < 6) {
            mostrarAlerta('La nueva contraseña debe tener al menos 6 caracteres', 'warning');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            mostrarAlerta('Las nuevas contraseñas no coinciden', 'warning');
            return;
        }
        
        if (currentPassword === newPassword) {
            mostrarAlerta('La nueva contraseña debe ser diferente a la actual', 'warning');
            return;
        }
        
        // Deshabilitar botón mientras se procesa
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Cambiando...';
        
        const formData = new FormData(this);
        
        fetch('change_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('Contraseña cambiada exitosamente', 'success');
                limpiarFormulario();
            } else {
                mostrarAlerta('Error: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'danger');
        })
        .finally(() => {
            // Rehabilitar botón
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
    
    // Validación en tiempo real de confirmación de contraseña
    document.getElementById('confirm_password').addEventListener('input', function() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword && newPassword !== confirmPassword) {
            this.setCustomValidity('Las contraseñas no coinciden');
        } else {
            this.setCustomValidity('');
        }
    });
});