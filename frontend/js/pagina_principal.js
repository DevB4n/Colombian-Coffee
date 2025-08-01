// Variables globales
const btnEnter = document.getElementById('btnEnter');
const loginOptions = document.getElementById('loginOptions');
const overlay = document.getElementById('overlay');
const cafeCatalog = document.getElementById('cafeCatalog');
const btnBack = document.getElementById('btnBack');

// Botones de login
const userLogin = document.getElementById('userLogin');
const adminLogin = document.getElementById('adminLogin');
const showCatalog = document.getElementById('showCatalog');

// Modales
const userModal = document.getElementById('userModal');
const adminModal = document.getElementById('adminModal');
const registerModal = document.getElementById('registerModal');

// Botones de cerrar modal
const closeUserModal = document.getElementById('closeUserModal');
const closeAdminModal = document.getElementById('closeAdminModal');
const closeRegisterModal = document.getElementById('closeRegisterModal');

// Enlaces y formularios
const registerLink = document.getElementById('registerLink');
const userForm = document.getElementById('userForm');
const adminForm = document.getElementById('adminForm');
const registerForm = document.getElementById('registerForm');

// Video de fondo
const videoBackground = document.getElementById('videoBackground');

// Inicialización cuando se carga el DOM
document.addEventListener('DOMContentLoaded', function() {
    // Configurar video de fondo
    if (videoBackground) {
        videoBackground.play().catch(function(error) {
            console.log('No se pudo reproducir el video automáticamente:', error);
        });
    }
    
    // Inicializar eventos
    initializeEvents();
});


// Función para inicializar todos los eventos
function initializeEvents() {
    // Evento del botón ENTRAR
    if (btnEnter) {
        btnEnter.addEventListener('click', function() {
            showLoginOptions();
        });
    }

    // Eventos de los botones de login
    if (userLogin) {
        userLogin.addEventListener('click', function() {
            showModal(userModal);
        });
    }

    if (adminLogin) {
        adminLogin.addEventListener('click', function() {
            showModal(adminModal);
        });
    }

    if (showCatalog) {
        showCatalog.addEventListener('click', function() {
            showCoffeeCatalog();
        });
    }

    // Evento del botón volver
    if (btnBack) {
        btnBack.addEventListener('click', function() {
            showMainScreen();
        });
    }

    // Eventos para cerrar modales
    if (closeUserModal) {
        closeUserModal.addEventListener('click', function() {
            hideModal(userModal);
        });
    }

    if (closeAdminModal) {
        closeAdminModal.addEventListener('click', function() {
            hideModal(adminModal);
        });
    }

    if (closeRegisterModal) {
        closeRegisterModal.addEventListener('click', function() {
            hideModal(registerModal);
        });
    }

    // Evento para mostrar modal de registro
    if (registerLink) {
        registerLink.addEventListener('click', function(e) {
            e.preventDefault();
            hideModal(userModal);
            showModal(registerModal);
        });
    }

    // Eventos de formularios
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleUserLogin();
        });
    }

    if (adminForm) {
        adminForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleAdminLogin();
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleUserRegistration();
        });
    }

    // Cerrar modales al hacer clic fuera de ellos
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            hideModal(e.target);
        }
    });

    // Evento ESC para cerrar modales
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideAllModals();
        }
    });
}

// Funciones de navegación
function showLoginOptions() {
    if (loginOptions) {
        loginOptions.style.display = 'flex';
        btnEnter.style.display = 'none';
        
        // Animación suave
        loginOptions.style.opacity = '0';
        setTimeout(() => {
            loginOptions.style.opacity = '1';
        }, 100);
    }
}

function showMainScreen() {
    if (overlay && cafeCatalog) {
        cafeCatalog.style.display = 'none';
        overlay.style.display = 'flex';
        
        // Resetear estado inicial
        if (loginOptions) {
            loginOptions.style.display = 'none';
        }
        if (btnEnter) {
            btnEnter.style.display = 'block';
        }
    }
}

function showCoffeeCatalog() {
    if (overlay && cafeCatalog) {
        overlay.style.display = 'none';
        cafeCatalog.style.display = 'block';
        
        // Scroll suave al inicio
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Funciones de modales
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
    }
}

function hideAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        hideModal(modal);
    });
}

// Funciones de manejo de formularios
function handleUserLogin() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (validateLoginForm(username, password)) {
        // Aquí puedes agregar la lógica de autenticación real
        console.log('Intento de login de usuario:', { username, password });
        
        // Simulación de login exitoso
        showNotification('Ingreso exitoso como usuario', 'success');
        hideModal(userModal);
        showCoffeeCatalog();
    }
}   

function handleAdminLogin() {
    const adminUsername = document.getElementById('adminUsername').value;
    const adminPassword = document.getElementById('adminPassword').value;
    
    if (validateLoginForm(adminUsername, adminPassword)) {
        // Aquí puedes agregar la lógica de autenticación de admin
        console.log('Intento de login de admin:', { adminUsername, adminPassword });
        
        // Simulación de login exitoso
        showNotification('Ingreso exitoso como administrador', 'success');
        hideModal(adminModal);
        showCoffeeCatalog();
    }
}

function handleUserRegistration() {
    const newUsername = document.getElementById('newUsername').value;
    const email = document.getElementById('email').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (validateRegistrationForm(newUsername, email, newPassword, confirmPassword)) {
        // Aquí puedes agregar la lógica de registro real
        console.log('Intento de registro:', { newUsername, email, newPassword });
        
        // Simulación de registro exitoso
        showNotification('Registro exitoso. Ya puedes iniciar sesión.', 'success');
        hideModal(registerModal);
        showModal(userModal);
    }
}

// Funciones de validación
function validateLoginForm(username, password) {
    if (!username.trim()) {
        showNotification('Por favor ingresa tu usuario', 'error');
        return false;
    }
    
    if (!password.trim()) {
        showNotification('Por favor ingresa tu contraseña', 'error');
        return false;
    }
    
    if (password.length < 6) {
        showNotification('La contraseña debe tener al menos 6 caracteres', 'error');
        return false;
    }
    
    return true;
}

function validateRegistrationForm(username, email, password, confirmPassword) {
    if (!username.trim()) {
        showNotification('Por favor ingresa un nombre de usuario', 'error');
        return false;
    }
    
    if (username.length < 3) {
        showNotification('El nombre de usuario debe tener al menos 3 caracteres', 'error');
        return false;
    }
    
    if (!email.trim()) {
        showNotification('Por favor ingresa tu correo electrónico', 'error');
        return false;
    }
    
    if (!isValidEmail(email)) {
        showNotification('Por favor ingresa un correo electrónico válido', 'error');
        return false;
    }
    
    if (!password.trim()) {
        showNotification('Por favor ingresa una contraseña', 'error');
        return false;
    }
    
    if (password.length < 8) {
        showNotification('La contraseña debe tener al menos 8 caracteres', 'error');
        return false;
    }
    
    if (password !== confirmPassword) {
        showNotification('Las contraseñas no coinciden', 'error');
        return false;
    }
    
    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Sistema de notificaciones
function showNotification(message, type = 'info') {
    // Remover notificaciones existentes
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Crear nueva notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Estilos de la notificación
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
    
    // Colores según el tipo
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
    
    // Agregar al DOM
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

// Agregar estilos de animación para las notificaciones
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(styleSheet);

// Funciones utilitarias para las imágenes
function handleImageError(img) {
    img.onerror = null; // Prevenir bucle infinito
    img.src = 'https://via.placeholder.com/300x200?text=Imagen+no+disponible';
    img.alt = 'Imagen no disponible';
}

// Lazy loading para las imágenes (opcional)
function setupLazyLoading() {
    const images = document.querySelectorAll('.cafe-image, .planta-image');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
}

// Función para filtrar variedades (opcional para futuras mejoras)
function filterVarieties(filterType, filterValue) {
    const cards = document.querySelectorAll('.cafe-card');
    
    cards.forEach(card => {
        let shouldShow = true;
        
        if (filterType && filterValue) {
            const cardData = card.dataset[filterType];
            shouldShow = cardData && cardData.toLowerCase().includes(filterValue.toLowerCase());
        }
        
        card.style.display = shouldShow ? 'block' : 'none';
    });
}

// Función para buscar en el catálogo (opcional para futuras mejoras)
function searchCatalog(searchTerm) {
    const cards = document.querySelectorAll('.cafe-card');
    const term = searchTerm.toLowerCase();
    
    cards.forEach(card => {
        const cardText = card.textContent.toLowerCase();
        const shouldShow = cardText.includes(term);
        card.style.display = shouldShow ? 'block' : 'none';
    });
}

