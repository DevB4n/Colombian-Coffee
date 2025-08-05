<?php

// Incluir el sistema de autenticación
require_once 'auth.php';

// Verificar si ya está autenticado y redirigir
if (isAuthenticated()) {
    $role = getUserRole();
    if ($role === 'admin') {
        header('Location: adminadd.php');
        exit();
    } else {
        header('Location: coneccion.php');
        exit();
    }
}

// Mostrar errores si existen
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>☕ Catálogo de Café - Iniciar Sesión</title>
    <link rel="stylesheet" href="../../frontend/css/pagina_principal.css">
    <style>
        .error-alert {
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .form-section {
            margin-bottom: 20px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .loading {
            display: none;
            text-align: center;
            margin: 10px 0;
        }
        
        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #8B4513;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-left: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <video id="videoBackground" muted loop autoplay>
            <source src="img/Comerciales Colcafé - Consiéntete, date gusto con un Colcafé Clásico.mp4" type="video/mp4">
        </video>
        
        <div class="overlay" id="overlay">
            <div class="header">
                <h1>☕ Catálogo de Café</h1>
                <p>Descubre las mejores variedades de café colombiano</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-alert" id="errorAlert">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <button class="btn-enter" id="btnEnter">ENTRAR</button>
            
            <div class="login-options" id="loginOptions" style="display: none;">
                <button class="login-btn" id="userLogin">Ingresar como Usuario</button>
                <button class="login-btn" id="adminLogin">Ingresar como Administrador</button>
            </div>
        </div>

        <!-- Modal de Usuario -->
        <div class="modal" id="userModal">
            <div class="modal-content">
                <span class="close-btn" id="closeUserModal">&times;</span>
                <h2>👤 Ingreso de Usuario</h2>
                
                <form id="userForm" action="auth.php" method="POST">
                    <input type="hidden" name="login_type" value="user">
                    
                    <div class="form-section">
                        <div class="form-group">
                            <label for="username">👤 Usuario:</label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Ingresa tu usuario"
                                   required 
                                   autocomplete="username">
                        </div>
                        <div class="form-group">
                            <label for="password">🔒 Contraseña:</label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Ingresa tu contraseña"
                                   required 
                                   autocomplete="current-password">
                        </div>
                    </div>
                    
                    <div class="loading" id="userLoading">
                        <span>Iniciando sesión...</span>
                        <div class="spinner"></div>
                    </div>
                    
                    <button type="submit" class="submit-btn" id="userSubmitBtn">
                        🚀 Ingresar como Usuario
                    </button>
                    
                    <p>¿No tienes cuenta? <a href="#" id="registerLink">Regístrate aquí</a></p>
                    
                    <div class="demo-credentials" style="margin-top: 15px; padding: 10px; background: rgba(0,0,0,0.1); border-radius: 5px; font-size: 0.9em;">
                        <strong>💡 Credenciales de prueba:</strong><br>
                        Usuario: <code>usuario1</code> | Contraseña: <code>password123</code><br>
                        Usuario: <code>cliente</code> | Contraseña: <code>cliente123</code>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal de Administrador -->
        <div class="modal" id="adminModal">
            <div class="modal-content">
                <span class="close-btn" id="closeAdminModal">&times;</span>
                <h2>🔧 Ingreso de Administrador</h2>
                
                <form id="adminForm" action="auth.php" method="POST">
                    <input type="hidden" name="login_type" value="admin">
                    
                    <div class="form-section">
                        <div class="form-group">
                            <label for="adminUsername">👨‍💼 Usuario Administrador:</label>
                            <input type="text" 
                                   id="adminUsername" 
                                   name="username" 
                                   placeholder="Ingresa tu usuario de admin"
                                   required 
                                   autocomplete="username">
                        </div>
                        <div class="form-group">
                            <label for="adminPassword">🔐 Contraseña:</label>
                            <input type="password" 
                                   id="adminPassword" 
                                   name="password" 
                                   placeholder="Ingresa tu contraseña de admin"
                                   required 
                                   autocomplete="current-password">
                        </div>
                    </div>
                    
                    <div class="loading" id="adminLoading">
                        <span>Verificando credenciales...</span>
                        <div class="spinner"></div>
                    </div>
                    
                    <button type="submit" class="submit-btn" id="adminSubmitBtn">
                        🛠️ Ingresar como Administrador
                    </button>
                    
                    <div class="demo-credentials" style="margin-top: 15px; padding: 10px; background: rgba(0,0,0,0.1); border-radius: 5px; font-size: 0.9em;">
                        <strong>💡 Credenciales de prueba:</strong><br>
                        Admin: <code>admin</code> | Contraseña: <code>admin123</code><br>
                        Admin: <code>Adrian@gmail.com</code> | Contraseña: <code>soylacontra</code>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal de Registro -->
        <div class="modal" id="registerModal">
            <div class="modal-content">
                <span class="close-btn" id="closeRegisterModal">&times;</span>
                <h2>📝 Registro de Usuario</h2>
                
                <form id="registerForm" action="auth.php" method="POST">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="form-section">
                        <div class="form-group">
                            <label for="newUsername">👤 Nombre de Usuario:</label>
                            <input type="text" 
                                   id="newUsername" 
                                   name="new_username" 
                                   placeholder="Elige un nombre de usuario"
                                   required 
                                   minlength="3"
                                   autocomplete="username">
                        </div>
                        <div class="form-group">
                            <label for="email">📧 Correo Electrónico:</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   placeholder="tu@email.com"
                                   required 
                                   autocomplete="email">
                        </div>
                        <div class="form-group">
                            <label for="newPassword">🔒 Contraseña:</label>
                            <input type="password" 
                                   id="newPassword" 
                                   name="new_password" 
                                   placeholder="Mínimo 8 caracteres"
                                   required 
                                   minlength="8"
                                   autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">🔒 Confirmar Contraseña:</label>
                            <input type="password" 
                                   id="confirmPassword" 
                                   name="confirm_password" 
                                   placeholder="Repite tu contraseña"
                                   required 
                                   minlength="8"
                                   autocomplete="new-password">
                        </div>
                    </div>
                    
                    <div class="loading" id="registerLoading">
                        <span>Creando cuenta...</span>
                        <div class="spinner"></div>
                    </div>
                    
                    <button type="submit" class="submit-btn" id="registerSubmitBtn">
                        ✨ Crear Cuenta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        const btnEnter = document.getElementById('btnEnter');
        const loginOptions = document.getElementById('loginOptions');
        const userLogin = document.getElementById('userLogin');
        const adminLogin = document.getElementById('adminLogin');
        
        // Modales
        const userModal = document.getElementById('userModal');
        const adminModal = document.getElementById('adminModal');
        const registerModal = document.getElementById('registerModal');
        
        // Botones de cerrar
        const closeUserModal = document.getElementById('closeUserModal');
        const closeAdminModal = document.getElementById('closeAdminModal');
        const closeRegisterModal = document.getElementById('closeRegisterModal');
        
        // Enlaces
        const registerLink = document.getElementById('registerLink');
        
        // Formularios
        const userForm = document.getElementById('userForm');
        const adminForm = document.getElementById('adminForm');
        const registerForm = document.getElementById('registerForm');

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            initializeEvents();
            
            // Auto-ocultar alertas de error después de 5 segundos
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => errorAlert.remove(), 300);
                }, 5000);
            }
        });

        function initializeEvents() {
            // Mostrar opciones de login
            if (btnEnter) {
                btnEnter.addEventListener('click', showLoginOptions);
            }

            // Mostrar modales de login
            if (userLogin) {
                userLogin.addEventListener('click', () => showModal(userModal));
            }
            
            if (adminLogin) {
                adminLogin.addEventListener('click', () => showModal(adminModal));
            }

            // Cerrar modales
            if (closeUserModal) {
                closeUserModal.addEventListener('click', () => hideModal(userModal));
            }
            
            if (closeAdminModal) {
                closeAdminModal.addEventListener('click', () => hideModal(adminModal));
            }
            
            if (closeRegisterModal) {
                closeRegisterModal.addEventListener('click', () => hideModal(registerModal));
            }

            // Mostrar modal de registro
            if (registerLink) {
                registerLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    hideModal(userModal);
                    showModal(registerModal);
                });
            }

            // Manejar envío de formularios
            if (userForm) {
                userForm.addEventListener('submit', handleFormSubmit);
            }
            
            if (adminForm) {
                adminForm.addEventListener('submit', handleFormSubmit);
            }
            
            if (registerForm) {
                registerForm.addEventListener('submit', handleRegisterSubmit);
            }

            // Cerrar modales al hacer clic fuera
            window.addEventListener('click', function(e) {
                if (e.target.classList.contains('modal')) {
                    hideModal(e.target);
                }
            });

            // Cerrar modales con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideAllModals();
                }
            });
        }

        function showLoginOptions() {
            loginOptions.style.display = 'flex';
            btnEnter.style.display = 'none';
            
            // Animación suave
            loginOptions.style.opacity = '0';
            setTimeout(() => {
                loginOptions.style.opacity = '1';
            }, 100);
        }

        function showModal(modal) {
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
                
                // Focus en el primer input
                const firstInput = modal.querySelector('input[type="text"], input[type="email"]');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        }

        function hideModal(modal) {
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                
                // Limpiar formularios
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                }
                
                // Ocultar loading
                const loading = modal.querySelector('.loading');
                if (loading) {
                    loading.style.display = 'none';
                }
            }
        }

        function hideAllModals() {
            const modals = [userModal, adminModal, registerModal];
            modals.forEach(modal => hideModal(modal));
        }

        function handleFormSubmit(e) {
            const form = e.target;
            const formType = form.id.includes('user') ? 'user' : 'admin';
            const submitBtn = document.getElementById(formType + 'SubmitBtn');
            const loading = document.getElementById(formType + 'Loading');
            
            // Mostrar loading
            if (loading) {
                loading.style.display = 'block';
            }
            
            // Deshabilitar botón
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = formType === 'user' ? 
                    '⏳ Iniciando sesión...' : 
                    '⏳ Verificando...';
            }
            
            // El formulario se enviará normalmente al servidor
        }

        function handleRegisterSubmit(e) {
            const password = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                showNotification('Las contraseñas no coinciden', 'error');
                return false;
            }
            
            const submitBtn = document.getElementById('registerSubmitBtn');
            const loading = document.getElementById('registerLoading');
            
            // Mostrar loading
            if (loading) {
                loading.style.display = 'block';
            }
            
            // Deshabilitar botón
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '⏳ Creando cuenta...';
            }
        }

        function showNotification(message, type = 'info') {
            // Crear notificación
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
            // Estilos
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                color: white;
                font-weight: bold;
                z-index: 10000;
                max-width: 300px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                animation: slideInRight 0.3s ease;
            `;
            
            // Color según tipo
            switch(type) {
                case 'success':
                    notification.style.background = 'linear-gradient(45deg, #4CAF50, #45a049)';
                    break;
                case 'error':
                    notification.style.background = 'linear-gradient(45deg, #f44336, #d32f2f)';
                    break;
                default:
                    notification.style.background = 'linear-gradient(45deg, #2196F3, #1976D2)';
            }
            
            document.body.appendChild(notification);
            
            // Remover después de 4 segundos
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }

        // Configurar video de fondo
        const videoBackground = document.getElementById('videoBackground');
        if (videoBackground) {
            videoBackground.play().catch(function(error) {
                console.log('No se pudo reproducir el video:', error);
                videoBackground.style.display = 'none';
            });
        }
    </script>

    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .demo-credentials {
            border-left: 3px solid #FFD700;
        }
        
        .demo-credentials code {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</body>
</html>