<?php
// index.php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewTechs</title>
   <link rel="stylesheet" href="/Noticiero/css/estilos_index.css">

    <!--Tipografia titular-->
    <link href="https://db.onlinewebfonts.com/c/a294b78eedf270c41a9489c97c72f429?family=Respira+Black" rel="stylesheet">
    <!--Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!--Icono navegador-->
    <link rel="icon" type="image/png" href="../Imagenes/letra-t.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="wrapper"> 
        <header id="cabezera_index">
        <a href="index.php">
            <img id="logo" src="/Noticiero/Imagenes/letra-t.png" alt="Logo News Tech" width="50">
        </a>
        
        <div id="titulo">
            News Tech
        </div>

        <div class="botones">
    <div id="suscribirse">
        <button id="suscripsion" type="button" class="btn btn-info" onclick="suscribirseConBroma()">
            Suscribirse
        </button>
    </div>
</div>

<script>
    function suscribirseConBroma() {
        alert("✅ Suscripción completada con éxito.\n💸 Cargo automático de $1,000 diarios activado.\n🙃 Cancelaciones solo por carta escrita, enviada por paloma mensajera.\nGracias por tu generosa donación.");
        window.location.href = '#';
    }
</script>

            <div id="boton_login">
                <?php if (isset($_SESSION['usuario_nombre'])): ?>
                    <!-- Usuario logueado -->
                    <div class="dropdown">
                        <button id="usuario-logueado" type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="cerrarSesion()">Cerrar Sesión</a></li>
                             <li><a class="dropdown-item" href="perfil.php">Mi Perfil</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- Usuario no logueado -->
                    <button id="iniciar-secion" type="button" class="btn btn-light" onclick="abrirModal()">
                        Iniciar Sesión
                    </button>
                <?php endif; ?>
            </div>
        </div>
        </header>

        <div id="menu">
            <div class="menu-categorias">
                <a href="#categoria-entertainment" class="btn-categoria" data-categoria="entertainment">
                    <span>Arts & Lifestyle</span>
                </a>
                <a href="#categoria-business" class="btn-categoria" data-categoria="business">
                    <span>Business</span>
                </a>
                <a href="#categoria-health" class="btn-categoria" data-categoria="health">
                    <span>Health</span>
                </a>
                <a href="#categoria-science" class="btn-categoria" data-categoria="science">
                    <span>Science</span>
                </a>
                <a href="#categoria-sports" class="btn-categoria" data-categoria="sports">
                    <span>Sports</span>
                </a>
                <a href="#categoria-technology" class="btn-categoria" data-categoria="technology">
                    <span>Technology</span>
                </a>
            </div>
        </div>

        <div class="container_news">
            <div id="noticias">
                <script src="script.js"></script>
            </div>
        
            <div class="news_column">
                <div id="ultimas_noticias">
                    <h2>¡Últimas Noticias!</h2>
                    <br>
                    <div>
                        <h5>Fallecen 3 al ser asesinados</h5>
                        <p>Esta tragica mañana 3 personas fueron encontradas sin vida; Lorem ipsum dolor sit amet consectetur, adipisicing elit. Iste laudantium ipsum qui tenetur doloremque dolorem exercitationem, sit architecto quae voluptatibus veniam ea repellendus recusandae modi, obcaecati enim quaerat doloribus! Commodi.</p>
                        <img src="" alt="" width="100%"  height="auto">
                    </div>
                </div>
            
                <div id="ads">
                    <script src="ads.js"></script>
                </div>
            </div>
        </div>
    </div>

    <!-- Log in -->
    <div id="loginModal" class="modal-overlay">
        <div class="login-card">
            <button class="close-btn" onclick="cerrarModal()">&times;</button>
            <h2>Login</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="login-btn">Iniciar sesión</button>

                <div class="forgot-password">
                    <a href="#" onclick="alert('Función próximamente')">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="create-account">
                    New user? <a href="#" onclick="abrirModalRegistro()">Crea tu cuenta aquí</a>
                </div>
            </form>
        </div>
    </div>

    <script src="login.js"></script>
    <!-- fin login -->

    <!-- Register Modal -->
    <div id="registerModal" class="modal-overlay">
        <div class="login-card">
            <button class="close-btn" onclick="cerrarModalRegistro()">&times;</button>
            <button class="back-btn" onclick="regresarAlLogin()" title="Volver al login">←</button>
            <h2>Crear cuenta</h2>
            <form id="registerForm">
                <div class="form-group">
                    <label for="reg_email">Email:</label>
                    <input type="email" id="reg_email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="reg_password">Contraseña:</label>
                    <input type="password" id="reg_password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="reg_confirm">Confirmar contraseña:</label>
                    <input type="password" id="reg_confirm" required>
                </div>

                <button type="submit" class="login-btn">Registrarse</button>
            </form>
        </div>
    </div>
    <!-- Fin Register Modal -->
    
    <footer id="pie_pagina">
        <div id="footer-content">
            <!-- Header del footer con logo -->
            <div class="footer-header">
                <img src="/Noticiero/Imagenes/letra-t-inversa.png" alt="Logo News Tech" class="footer-logo">
                <div>
                    <h2 class="footer-brand">News Tech</h2>
                    <p class="footer-tagline">Innovación y tecnología al alcance de todos</p>
                </div>
            </div>
        
            <!-- Grid de enlaces del footer -->
            <div class="footer-links">
                <div class="footer-section">
                    <h4>Información General</h4>
                    <a href="#">Acerca de News Tech</a>
                    <a href="#">Contacto</a>
                    <a href="#">Suscríbete para acceso ilimitado</a>
                    <a href="#">Mapa del sitio</a>
                    <a href="#">Política de privacidad</a>
                    <a href="#">Términos y condiciones</a>
                </div>
            
                <div class="footer-section">
                    <h4>Servicios y Recursos</h4>
                    <a href="#">eNewspaper</a>
                    <a href="#">Newsletter</a>
                    <a href="#">Archivo de noticias</a>
                    <a href="#">Cupones</a>
                    <a href="#">Buscar/Publicar empleos</a>
                    <a href="#">Colocar un anuncio</a>
                </div>
            
                <div class="footer-section">
                    <h4>Entretenimiento</h4>
                    <a href="#">Crucigramas</a>
                    <a href="#">Recetas</a>
                    <a href="#">Tienda News Tech</a>
                    <a href="#">Eventos</a>
                    <a href="#">Concursos</a>
                    <a href="#">Cultura y arte</a>
                </div>
            
                <div class="footer-section social-media">
                    <h4>Síguenos</h4>
                    <p>Mantente conectado con las últimas noticias</p>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook">
                            <img src="Noticiero/Imagenes/facebook-icon.png" alt="Facebook">
                        </a>
                        <a href="#" aria-label="Twitter">
                            <img src="twitter-icon.png" alt="Twitter">
                        </a>
                        <a href="#" aria-label="Instagram">
                            <img src="instagram-icon.png" alt="Instagram">
                        </a>
                        <a href="#" aria-label="YouTube">
                            <img src="youtube-icon.png" alt="YouTube">
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <img src="linkedin-icon.png" alt="LinkedIn">
                        </a>
                    </div>
                </div>
            </div>
        
            <!-- Información adicional -->
            <div class="footer-links">
                <div class="footer-section">
                    <h4>Carrera y Oportunidades</h4>
                    <a href="#">Carreras en News Tech</a>
                    <a href="#">Prácticas profesionales</a>
                    <a href="#">Freelance</a>
                    <a href="#">Colaboradores</a>
                </div>
            
                <div class="footer-section">
                    <h4>Soporte</h4>
                    <a href="#">Gestionar suscripción</a>
                    <a href="#">Centro de ayuda</a>
                    <a href="#">Reimpresiones y permisos</a>
                    <a href="#">Reportar un problema</a>
                </div>
            </div>
        
            <!-- Footer bottom -->
            <div class="footer-bottom">
                <p class="copyright">© 2025 News Tech. Todos los derechos reservados.</p>
                <p>Sitio web desarrollado con tecnología de vanguardia para brindar la mejor experiencia de usuario.</p>
            </div>
        </div>
    </footer>
</body>
</html>