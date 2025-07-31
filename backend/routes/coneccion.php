<?php
$apiUrl = 'http://localhost:8081/caracteristicas_cafe';
$username = "Adrian@gmail.com";
$password = "Hola@2020";
$variedades = [];
$error = null;

// Realizar la petición a la API
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error = "Error al obtener datos de la API: " . curl_error($ch);
} elseif ($response === false || empty($response)) {
    $error = "No se recibió respuesta de la API.";
} else {
    $variedades = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = "Error al decodificar JSON: " . json_last_error_msg();
    }
}
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Variedades de Café</title>
    <link rel="stylesheet" href="../../frontend/css/pagina_principal.css">
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
            
            <button class="btn-enter" id="btnEnter">ENTRAR</button>
            
            <div class="login-options" id="loginOptions">
                <button class="login-btn" id="userLogin">Ingresar como Usuario</button>
                <button class="login-btn" id="adminLogin">Ingresar como Administrador</button>
                <button class="login-btn" id="showCatalog">Ver Catálogo de Café</button>
            </div>
        </div>

        <!-- Catálogo de Café -->
        <div class="cafe-catalog" id="cafeCatalog">
            <div class="catalog-header">
                <h2>🌱 Variedades de Café Colombiano</h2>
                <button class="btn-back" id="btnBack">← Volver al Inicio</button>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <h3>⚠️ Error al cargar datos</h3>
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php else: ?>
                <div class="cafe-grid">
                    <?php foreach ($variedades as $cafe): ?>
                        <div class="cafe-card">
                            <!-- Imagen del grano -->
                            <div class="image-container">
                                <img src="<?php echo htmlspecialchars($cafe['grano']['imagen_url']); ?>" 
                                     alt="Grano <?php echo htmlspecialchars($cafe['grano']['planta']['nombre_variedad']); ?>" 
                                     class="cafe-image"
                                     onerror="this.src='https://via.placeholder.com/300x200?text=Imagen+no+disponible'">
                                <div class="image-overlay">
                                    <span class="variety-name"><?php echo htmlspecialchars($cafe['grano']['planta']['nombre_variedad']); ?></span>
                                </div>
                            </div>

                            <div class="card-content">
                                <div class="variedad-title">
                                    <?php echo htmlspecialchars($cafe['grano']['planta']['nombre_variedad']); ?>
                                    <span class="quality-badge quality-<?php echo strtolower($cafe['grano']['calidad']); ?>">
                                        <?php echo htmlspecialchars($cafe['grano']['calidad']); ?>
                                    </span>
                                </div>

                                <!-- Información del Grano -->
                                <div class="info-section">
                                    <div class="info-title">🌾 Características del Grano</div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="label">Tamaño:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['grano']['tamano_grano_mm']); ?> mm</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Color:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['grano']['color_grano']); ?></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Forma:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['grano']['forma_grano']); ?></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Sabor:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['sabor']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Imagen de la Planta -->
                                <div class="plant-image-container">
                                    <img src="<?php echo htmlspecialchars($cafe['grano']['planta']['imagen_url']); ?>" 
                                         alt="Planta <?php echo htmlspecialchars($cafe['grano']['planta']['nombre_variedad']); ?>" 
                                         class="planta-image"
                                         onerror="this.src='https://via.placeholder.com/300x180?text=Planta+no+disponible'">
                                </div>

                                <!-- Información de la Planta -->
                                <div class="info-section">
                                    <div class="info-title">🌱 Información de la Planta</div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="label">Especie:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['grano']['planta']['especie']); ?></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Altura:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['grano']['planta']['tamano_planta_cm']); ?> cm</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Color hoja:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['grano']['planta']['color_hoja']); ?></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Región:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['region']); ?></span>
                                        </div>
                                    </div>
                                    <div class="description">
                                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($cafe['grano']['planta']['descripcion']); ?></p>
                                    </div>
                                </div>

                                <!-- Datos de Cultivo -->
                                <div class="info-section">
                                    <div class="info-title">📊 Datos de Cultivo</div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="label">Altitud óptima:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['altitud_optima']); ?> msnm</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Densidad:</span>
                                            <span class="value"><?php echo number_format($cafe['datos_cafe']['densidad_plantacion']); ?> plantas/ha</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Resistencia:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['datos_cafe']['resistencia']); ?></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Nutrición:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['datos_cafe']['requerimiento_nutricion']); ?></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Crecimiento:</span>
                                            <span class="value"><?php echo htmlspecialchars($cafe['tiempo_crecimiento']['Desde_anhos']); ?> - <?php echo htmlspecialchars($cafe['tiempo_crecimiento']['Hasta_anhos']); ?> años</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Primera siembra:</span>
                                            <span class="value"><?php echo date('d/m/Y', strtotime($cafe['datos_cafe']['primera_siembra'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Modales originales del login -->
        <div class="modal" id="userModal">
            <div class="modal-content">
                <span class="close-btn" id="closeUserModal">&times;</span>
                <h2>Ingreso de Usuario</h2>
                <form id="userForm">
                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" id="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" required>
                    </div>
                    <button type="submit" class="submit-btn">Ingresar</button>
                    <p>¿No tienes cuenta? <a href="#" id="registerLink">Regístrate aquí</a></p>
                </form>
            </div>
        </div>
        
        <div class="modal" id="adminModal">
            <div class="modal-content">
                <span class="close-btn" id="closeAdminModal">&times;</span>
                <h2>Ingreso de Administrador</h2>
                <form id="adminForm">
                    <div class="form-group">
                        <label for="adminUsername">Usuario:</label>
                        <input type="text" id="adminUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="adminPassword">Contraseña:</label>
                        <input type="password" id="adminPassword" required>
                    </div>
                    <button type="submit" class="submit-btn">Ingresar</button>
                </form>
            </div>
        </div>
        
        <div class="modal" id="registerModal">
            <div class="modal-content">
                <span class="close-btn" id="closeRegisterModal">&times;</span>
                <h2>Registro de Usuario</h2>
                <form id="registerForm">
                    <div class="form-group">
                        <label for="newUsername">Usuario:</label>
                        <input type="text" id="newUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Contraseña:</label>
                        <input type="password" id="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirmar Contraseña:</label>
                        <input type="password" id="confirmPassword" required>
                    </div>
                    <button type="submit" class="submit-btn">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="../../frontend/js/pagina_principal.js"></script>
</body>
</html>