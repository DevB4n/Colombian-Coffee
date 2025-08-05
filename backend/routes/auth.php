<?php
session_start();

// Configuración de la base de datos (ajusta según tu configuración)
define('DB_HOST', 'localhost');
define('DB_NAME', 'cafe_catalog');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración de la API
define('API_USERNAME', 'Adrian@gmail.com');
define('API_PASSWORD', 'soylacontra');

// Usuarios predefinidos para demostración
$users = [
    // Usuarios normales
    'usuario1' => [
        'password' => 'password123',
        'role' => 'user',
        'email' => 'usuario1@example.com'
    ],
    'cliente' => [
        'password' => 'cliente123',
        'role' => 'user',
        'email' => 'cliente@example.com'
    ],
    // Administradores
    'admin' => [
        'password' => 'admin123',
        'role' => 'admin',
        'email' => 'admin@example.com'
    ],
    'Adrian@gmail.com' => [
        'password' => 'soylacontra',
        'role' => 'admin',
        'email' => 'Adrian@gmail.com'
    ]
];

// Función para conectar a la base de datos (opcional, para usuarios registrados)
function getDbConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        // Si no hay base de datos, usar usuarios predefinidos
        return null;
    }
}

// Función para verificar si el usuario está autenticado
function isAuthenticated() {
    return isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] === true;
}

// Función para obtener el rol del usuario
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

// Función para obtener información del usuario
function getUserInfo() {
    return [
        'username' => $_SESSION['username'] ?? null,
        'role' => $_SESSION['user_role'] ?? null,
        'email' => $_SESSION['user_email'] ?? null
    ];
}

// Función para obtener credenciales de la API
function getApiCredentials() {
    return [
        'username' => API_USERNAME,
        'password' => API_PASSWORD
    ];
}

// Función para redirigir según el rol
function redirectByRole() {
    $role = getUserRole();
    if ($role === 'admin') {
        header('Location: adminadd.php');
        exit();
    } else {
        header('Location: coneccion.php');
        exit();
    }
}

// Función para validar credenciales
function validateCredentials($username, $password) {
    global $users;
    
    // Primero verificar en usuarios predefinidos
    if (isset($users[$username])) {
        if ($users[$username]['password'] === $password) {
            return [
                'valid' => true,
                'role' => $users[$username]['role'],
                'email' => $users[$username]['email']
            ];
        }
    }
    
    // Si hay base de datos, verificar también ahí
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                return [
                    'valid' => true,
                    'role' => $user['role'] ?? 'user',
                    'email' => $user['email']
                ];
            }
        } catch(PDOException $e) {
            error_log("Error de base de datos: " . $e->getMessage());
        }
    }
    
    return ['valid' => false];
}

// Función para registrar un nuevo usuario
function registerUser($username, $email, $password) {
    global $users;
    
    // Verificar si el usuario ya existe
    if (isset($users[$username])) {
        return ['success' => false, 'message' => 'El usuario ya existe'];
    }
    
    // Verificar si el email ya existe
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }
    }
    
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            // Verificar si el usuario o email ya existe en la base de datos
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'Usuario o email ya existe'];
            }
            
            // Insertar nuevo usuario
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
            $stmt->execute([$username, $email, $hashedPassword]);
            
            return ['success' => true, 'message' => 'Usuario registrado exitosamente'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Error al registrar usuario'];
        }
    }
    
    return ['success' => false, 'message' => 'Servicio de registro no disponible'];
}

// Procesar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Manejar registro de usuario
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $username = trim($_POST['new_username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validaciones
        if (empty($username) || empty($email) || empty($password)) {
            header('Location: index.php?error=' . urlencode('Todos los campos son obligatorios'));
            exit();
        }
        
        if (strlen($username) < 3) {
            header('Location: index.php?error=' . urlencode('El nombre de usuario debe tener al menos 3 caracteres'));
            exit();
        }
        
        if (strlen($password) < 8) {
            header('Location: index.php?error=' . urlencode('La contraseña debe tener al menos 8 caracteres'));
            exit();
        }
        
        if ($password !== $confirmPassword) {
            header('Location: index.php?error=' . urlencode('Las contraseñas no coinciden'));
            exit();
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: index.php?error=' . urlencode('Email inválido'));
            exit();
        }
        
        // Intentar registrar usuario
        $result = registerUser($username, $email, $password);
        
        if ($result['success']) {
            header('Location: index.php?success=' . urlencode($result['message']));
            exit();
        } else {
            header('Location: index.php?error=' . urlencode($result['message']));
            exit();
        }
    }
    
    // Manejar login
    if (isset($_POST['login_type'])) {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $loginType = $_POST['login_type'];
        
        if (empty($username) || empty($password)) {
            header('Location: index.php?error=' . urlencode('Usuario y contraseña son obligatorios'));
            exit();
        }
        
        // Validar credenciales
        $validation = validateCredentials($username, $password);
        
        if (!$validation['valid']) {
            header('Location: index.php?error=' . urlencode('Credenciales incorrectas'));
            exit();
        }
        
        // Verificar que el tipo de login coincida con el rol
        if ($loginType === 'admin' && $validation['role'] !== 'admin') {
            header('Location: index.php?error=' . urlencode('No tienes permisos de administrador'));
            exit();
        }
        
        if ($loginType === 'user' && $validation['role'] === 'admin') {
            header('Location: index.php?message=' . urlencode('Redirigiendo al panel de administrador'));
            // Permitir que admin acceda como usuario también, pero redirigir a admin
        }
        
        // Establecer sesión
        $_SESSION['user_authenticated'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_role'] = $validation['role'];
        $_SESSION['user_email'] = $validation['email'];
        $_SESSION['login_time'] = time();
        
        // Redirigir según el rol
        redirectByRole();
    }
}

// Manejar logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php?message=' . urlencode('Sesión cerrada exitosamente'));
    exit();
}

// Función para crear tabla de usuarios (ejecutar una vez)
function createUsersTable() {
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('user', 'admin') DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $pdo->exec($sql);
            return true;
        } catch(PDOException $e) {
            error_log("Error creando tabla: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

// Crear tabla si no existe (solo para desarrollo)
if (isset($_GET['create_table'])) {
    if (createUsersTable()) {
        echo "Tabla de usuarios creada exitosamente";
    } else {
        echo "Error al crear tabla de usuarios";
    }
    exit();
}

// Función para obtener estadísticas (solo para admin)
function getLoginStats() {
    if (getUserRole() !== 'admin') {
        return null;
    }
    
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
            $totalUsers = $stmt->fetchColumn();
            
            $stmt = $pdo->query("SELECT COUNT(*) as admin_users FROM users WHERE role = 'admin'");
            $adminUsers = $stmt->fetchColumn();
            
            return [
                'total_users' => $totalUsers,
                'admin_users' => $adminUsers,
                'regular_users' => $totalUsers - $adminUsers
            ];
        } catch(PDOException $e) {
            return null;
        }
    }
    
    return null;
}

// API para JavaScript (opcional)
if (isset($_GET['api'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['api']) {
        case 'check_auth':
            echo json_encode([
                'authenticated' => isAuthenticated(),
                'user' => getUserInfo()
            ]);
            break;
            
        case 'stats':
            if (getUserRole() === 'admin') {
                echo json_encode(getLoginStats());
            } else {
                http_response_code(403);
                echo json_encode(['error' => 'Acceso denegado']);
            }
            break;
            
        default:
            echo json_encode(['error' => 'API endpoint no encontrado']);
    }
    exit();
}
?>