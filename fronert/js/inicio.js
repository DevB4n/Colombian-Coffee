document.addEventListener('DOMContentLoaded', function() {

    const video = document.getElementById('videoBackground');
    const overlay = document.getElementById('overlay');
    const btnEnter = document.getElementById('btnEnter');
    const loginOptions = document.getElementById('loginOptions');
    const userLogin = document.getElementById('userLogin');
    const adminLogin = document.getElementById('adminLogin');
    const userModal = document.getElementById('userModal');
    const adminModal = document.getElementById('adminModal');
    const registerModal = document.getElementById('registerModal');
    const closeUserModal = document.getElementById('closeUserModal');
    const closeAdminModal = document.getElementById('closeAdminModal');
    const closeRegisterModal = document.getElementById('closeRegisterModal');
    const registerLink = document.getElementById('registerLink');

    const ADMIN_USERNAME = 'yarith1102@gmail.com';
    const ADMIN_PASSWORD = '123456';

    // Usuarios almacenados en memoria
    let USERS = [
        { username: 'meliza', password: '12345', firstName: 'Meliza', lastName: 'Pérez' }
    ];

    video.play();

    overlay.style.opacity = '1';

    setTimeout(function () {
        btnEnter.style.display = 'block';
        setTimeout(function () {
            btnEnter.style.opacity = '1';
        }, 100);
    }, 6000);

    video.addEventListener('ended', function () {
        if (loginOptions.style.display !== 'flex') {
            showLoginOptions();
        }
    });

    btnEnter.addEventListener('click', showLoginOptions);

    function showLoginOptions() {
        btnEnter.style.display = 'none';
        loginOptions.style.display = 'flex';
        setTimeout(function () {
            loginOptions.style.opacity = '1';
        }, 100);
    }

    userLogin.addEventListener('click', function () {
        userModal.style.display = 'flex';
    });

    adminLogin.addEventListener('click', function () {
        adminModal.style.display = 'flex';
    });

    registerLink.addEventListener('click', function (e) {
        e.preventDefault();
        userModal.style.display = 'none';
        registerModal.style.display = 'flex';
    });

    closeUserModal.addEventListener('click', function () {
        userModal.style.display = 'none';
    });

    closeAdminModal.addEventListener('click', function () {
        adminModal.style.display = 'none';
    });

    closeRegisterModal.addEventListener('click', function () {
        registerModal.style.display = 'none';
    });

    window.addEventListener('click', function (e) {
        if (e.target === userModal) userModal.style.display = 'none';
        if (e.target === adminModal) adminModal.style.display = 'none';
        if (e.target === registerModal) registerModal.style.display = 'none';
    });

    // Login de usuario con validación local
    document.getElementById('userForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        const submitBtn = this.querySelector('.submit-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Cargando...';
        submitBtn.disabled = true;

        setTimeout(() => {
            const user = USERS.find(u => u.username === username && u.password === password);
            if (user) {
                localStorage.setItem('userToken', 'fake-token-' + user.username);
                localStorage.setItem('userData', JSON.stringify(user));
                window.location.href = "/dashboard"
            } else {
                alert('Credenciales incorrectas');
            }

            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 1000);
    });

    // Validación de administrador
    document.getElementById('adminForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const adminUsername = document.getElementById('adminUsername').value.trim();
        const adminPassword = document.getElementById('adminPassword').value.trim();

        if (adminUsername === ADMIN_USERNAME && adminPassword === ADMIN_PASSWORD) {
            localStorage.setItem('isAdmin', true);
            adminModal.style.display = 'none';
            window.location.href = "/admin/dashboard"
        } else {
            alert('Credenciales de administrador incorrectas');
        }
    });

    // Registro de nuevo usuario (solo en memoria)
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const newUsername = document.getElementById('newUsername').value.trim();
        const email = document.getElementById('email').value.trim();
        const newPassword = document.getElementById('newPassword').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();

        const submitBtn = this.querySelector('.submit-btn');
        const originalText = submitBtn.textContent;

        if (newPassword !== confirmPassword) {
            alert('Las contraseñas no coinciden');
            return;
        }

        if (USERS.some(u => u.username === newUsername)) {
            alert('El nombre de usuario ya está en uso');
            return;
        }

        submitBtn.textContent = 'Cargando...';
        submitBtn.disabled = true;

        setTimeout(() => {
            USERS.push({
                username: newUsername,
                password: newPassword,
                firstName: newUsername,
                lastName: ''
            });

            alert('¡Registro exitoso! Ahora puedes iniciar sesión con tus credenciales.');

            this.reset();
            registerModal.style.display = 'none';
            userModal.style.display = 'flex';

            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 1500);
    });
});
    