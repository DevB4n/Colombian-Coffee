<?php
$apiUrl = 'http://localhost:8081/caracteristicas_cafe';
$username = "Adrian@gmail.com";
$password = "soylacontra";
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

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
            </div>
        </div>

        <!-- Catálogo de Café -->
        <div class="cafe-catalog" id="cafeCatalog">
            <div class="catalog-header">
                <h2>🌱 Variedades de Café Colombiano</h2>
                <button class="btn-back" id="btnBack">← Volver al Inicio</button>
            </div>

            <!-- Sección de Información del Café -->
            <div class="coffee-info-section">
                <div class="info-hero">
                    <div class="info-hero-content">
                        <h3>☕ El Fascinante Mundo del Café Colombiano</h3>
                        <p>Colombia es el tercer productor mundial de café y el primero en café arábica suave. Nuestras tierras privilegiadas entre los trópicos ofrecen condiciones únicas para cultivar algunos de los mejores cafés del mundo.</p>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">🌍</div>
                        <div class="stat-number">32</div>
                        <div class="stat-label">Departamentos Cafeteros</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👨‍🌾</div>
                        <div class="stat-number">540,000</div>
                        <div class="stat-label">Familias Cafeteras</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">☕</div>
                        <div class="stat-number">12M</div>
                        <div class="stat-label">Sacos Anuales</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🏔️</div>
                        <div class="stat-number">1,200-2,000</div>
                        <div class="stat-label">Metros de Altitud</div>
                    </div>
                </div>
                <div class="coffee-map-section" style="margin-bottom: 40px;">
                    <h4 class="map-title" style="color: #FFD700; text-align: center; font-size: 1.8rem; margin-bottom: 20px;">
                        📍 Regiones donde se cultiva nuestro café
                    </h4>
                    <div id="map" style="height: 450px; border-radius: 15px; overflow: hidden; border: 2px solid #D2691E;"></div>
                </div>


                <div class="curiosities-section">
                    <h4>🤔 ¿Sabías que...?</h4>
                    <div class="curiosities-grid">
                        <div class="curiosity-card">
                            <div class="curiosity-emoji">🌱</div>
                            <h5>Proceso Único</h5>
                            <p>El café colombiano se procesa por el método húmedo, lo que le da su característico sabor suave y aromático.</p>
                        </div>
                        <div class="curiosity-card">
                            <div class="curiosity-emoji">🌧️</div>
                            <h5>Dos Cosechas</h5>
                            <p>Colombia tiene dos temporadas de cosecha al año: la principal (octubre-enero) y la mitaca (abril-junio).</p>
                        </div>
                        <div class="curiosity-card">
                            <div class="curiosity-emoji">🏆</div>
                            <h5>Calidad Premium</h5>
                            <p>Solo el café que cumple estrictos estándares de calidad puede usar el sello "Café de Colombia".</p>
                        </div>
                        <div class="curiosity-card">
                            <div class="curiosity-emoji">🌡️</div>
                            <h5>Clima Perfecto</h5>
                            <p>La temperatura promedio de 20°C y las lluvias regulares crean condiciones ideales para el cultivo.</p>
                        </div>
                        <div class="curiosity-card">
                            <div class="curiosity-emoji">🧬</div>
                            <h5>Variedades Únicas</h5>
                            <p>Colombia cultiva principalmente Arábica, con variedades como Típica, Borbón, Caturra, Castillo y Geisha.</p>
                        </div>
                        <div class="curiosity-card">
                            <div class="curiosity-emoji">🌿</div>
                            <h5>Café Sostenible</h5>
                            <p>El 80% de los cafetales colombianos están bajo sombra, preservando la biodiversidad y el ecosistema.</p>
                        </div>
                    </div>
                </div>

                <div class="regions-highlight">
                    <h4>🗺️ Regiones Cafeteras Principales</h4>
                    <div class="regions-grid">
                        <div class="region-card">
                            <h5>🏔️ Eje Cafetero</h5>
                            <p><strong>Caldas, Quindío, Risaralda:</strong> Corazón de la cultura cafetera colombiana. Café con cuerpo medio y acidez brillante.</p>
                        </div>
                        <div class="region-card">
                            <h5>🌋 Huila</h5>
                            <p><strong>Región Sur:</strong> Cafés con notas frutales y florales, cultivados en suelos volcánicos ricos en minerales.</p>
                        </div>
                        <div class="region-card">
                            <h5>🏞️ Nariño</h5>
                            <p><strong>Frontera con Ecuador:</strong> Cafés de altura con acidez vibrante y perfiles complejos de sabor.</p>
                        </div>
                        <div class="region-card">
                            <h5>⛰️ Antioquia</h5>
                            <p><strong>Región Norte:</strong> Cafés balanceados con buen cuerpo y notas achocolatadas.</p>
                        </div>
                    </div>
                </div>

                <div class="quality-indicators">
                      <h4>Indicadores de calidad</h4>
                      <div class="quality-grid">

                        <div class="quality-item" data-region="Huila" data-info="Café suave, con notas dulces y cuerpo medio.">
                          <div class="quality-icon">🌱</div>
                          <div class="quality-content">
                            <h6>Altura ideal</h6>
                            <p>Entre 1200 y 1800 msnm</p>
                          </div>
                        </div>

                        <div class="quality-item" data-region="Nariño" data-info="Café con notas cítricas y dulces gracias a la altura.">
                          <div class="quality-icon">🌤️</div>
                          <div class="quality-content">
                            <h6>Clima templado</h6>
                            <p>18°C a 22°C</p>
                          </div>
                        </div>

                        <div class="quality-item" data-region="Antioquia" data-info="Café con cuerpo medio y sabor achocolatado.">
                          <div class="quality-icon">🌾</div>
                          <div class="quality-content">
                            <h6>Tipo de suelo</h6>
                            <p>Volcánico y fértil</p>
                          </div>
                        </div>

                      </div> 
                    </div>

            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <h3>⚠️ Error al cargar datos</h3>
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php else: ?>
                <div class="catalog-title-section">
                    <h3>🌟 Nuestras Variedades Destacadas</h3>
                    <p>Explora las características únicas de cada variedad de café colombiano</p>
                </div>
                
                <!-- 🔥 AQUÍ ES DONDE REEMPLAZAS LA SECCIÓN ANTIGUA 🔥 -->
                <!-- ELIMINA ESTAS LÍNEAS:
                <div class="search-variety" style="text-align: center; margin-bottom: 30px;">
                    <input type="text" id="searchInput" placeholder="Buscar variedad de café (ej. Castillo) 🔎" style="padding: 12px 20px; border-radius: 20px; border: 2px solid #D2691E; font-size: 1rem; width: 80%; max-width: 500px;">
                </div>
                -->
                
                <!-- Y AGREGA ESTA NUEVA SECCIÓN DE BÚSQUEDA Y FILTROS -->
                <div class="search-filter-section" style="max-width: 1200px; margin: 0 auto 40px; padding: 20px;">
                    <div class="search-filter-container" style="background: linear-gradient(135deg, rgba(139, 69, 19, 0.9), rgba(210, 105, 30, 0.9)); border-radius: 20px; padding: 30px; backdrop-filter: blur(10px); border: 2px solid rgba(255, 215, 0, 0.3);">
                        
                        <!-- Título de la sección -->
                        <h3 style="color: #FFD700; text-align: center; font-size: 1.8rem; margin-bottom: 25px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                            🔎 Buscar y Filtrar Variedades
                        </h3>
                        
                        <!-- Buscador principal -->
                        <div class="search-main" style="margin-bottom: 25px; text-align: center;">
                            <input type="text" 
                                   id="searchInput" 
                                   placeholder="Buscar por nombre de variedad (ej. Castillo, Caturra) 🔍" 
                                   style="padding: 15px 25px; border-radius: 25px; border: 2px solid #FFD700; font-size: 1.1rem; width: 90%; max-width: 600px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                        </div>
                        
                        <!-- Filtros por características -->
                        <div class="filters-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
                            
                            <!-- Filtro por Calidad -->
                            <div class="filter-group">
                                <label style="color: #FFD700; font-weight: bold; display: block; margin-bottom: 8px; font-size: 1rem;">
                                    ⭐ Calidad del Grano:
                                </label>
                                <select id="filterCalidad" style="width: 100%; padding: 10px 15px; border-radius: 15px; border: 2px solid #D2691E; background: rgba(255, 255, 255, 0.95); font-size: 0.95rem;">
                                    <option value="">Todas las calidades</option>
                                    <option value="bueno">Bueno</option>
                                    <option value="regular">Regular</option>
                                    <option value="excelente">Excelente</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Región -->
                            <div class="filter-group">
                                <label style="color: #FFD700; font-weight: bold; display: block; margin-bottom: 8px; font-size: 1rem;">
                                    📍 Región:
                                </label>
                                <select id="filterRegion" style="width: 100%; padding: 10px 15px; border-radius: 15px; border: 2px solid #D2691E; background: rgba(255, 255, 255, 0.95); font-size: 0.95rem;">
                                    <option value="">Todas las regiones</option>
                                    <option value="huila">Huila</option>
                                    <option value="nariño">Nariño</option>
                                    <option value="antioquia">Antioquia</option>
                                    <option value="eje cafetero">Eje Cafetero</option>
                                    <option value="santander">Santander</option>
                                    <option value="cauca">Cauca</option>
                                    <option value="tolima">Tolima</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Color del Grano -->
                            <div class="filter-group">
                                <label style="color: #FFD700; font-weight: bold; display: block; margin-bottom: 8px; font-size: 1rem;">
                                    🎨 Color del Grano:
                                </label>
                                <select id="filterColorGrano" style="width: 100%; padding: 10px 15px; border-radius: 15px; border: 2px solid #D2691E; background: rgba(255, 255, 255, 0.95); font-size: 0.95rem;">
                                    <option value="">Todos los colores</option>
                                    <option value="verde">Verde</option>
                                    <option value="amarillo">Amarillo</option>
                                    <option value="marrón">Marrón</option>
                                    <option value="rojizo">Rojizo</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Tamaño del Grano -->
                            <div class="filter-group">
                                <label style="color: #FFD700; font-weight: bold; display: block; margin-bottom: 8px; font-size: 1rem;">
                                    📏 Tamaño del Grano:
                                </label>
                                <select id="filterTamanoGrano" style="width: 100%; padding: 10px 15px; border-radius: 15px; border: 2px solid #D2691E; background: rgba(255, 255, 255, 0.95); font-size: 0.95rem;">
                                    <option value="">Todos los tamaños</option>
                                    <option value="pequeño">Pequeño (< 5mm)</option>
                                    <option value="mediano">Mediano (5-7mm)</option>
                                    <option value="grande">Grande (> 7mm)</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Resistencia -->
                            <div class="filter-group">
                                <label style="color: #FFD700; font-weight: bold; display: block; margin-bottom: 8px; font-size: 1rem;">
                                    🛡️ Resistencia:
                                </label>
                                <select id="filterResistencia" style="width: 100%; padding: 10px 15px; border-radius: 15px; border: 2px solid #D2691E; background: rgba(255, 255, 255, 0.95); font-size: 0.95rem;">
                                    <option value="">Todos los tipos</option>
                                    <option value="roya">Resistente a Roya</option>
                                    <option value="plagas">Resistente a Plagas</option>
                                    <option value="sequía">Resistente a Sequía</option>
                                    <option value="alta">Alta Resistencia</option>
                                    <option value="media">Media Resistencia</option>
                                    <option value="baja">Baja Resistencia</option>
                                </select>
                            </div>
                            
                            <!-- Filtro por Altitud -->
                            <div class="filter-group">
                                <label style="color: #FFD700; font-weight: bold; display: block; margin-bottom: 8px; font-size: 1rem;">
                                    🏔️ Altitud Óptima:
                                </label>
                                <select id="filterAltitud" style="width: 100%; padding: 10px 15px; border-radius: 15px; border: 2px solid #D2691E; background: rgba(255, 255, 255, 0.95); font-size: 0.95rem;">
                                    <option value="">Todas las altitudes</option>
                                    <option value="baja">Baja (< 1200 msnm)</option>
                                    <option value="media">Media (1200-1600 msnm)</option>
                                    <option value="alta">Alta (> 1600 msnm)</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="filter-actions" style="text-align: center; margin-top: 20px;">
                            <button id="clearFilters" 
                                    style="background: linear-gradient(45deg, #6B4423, #8B4513); color: white; border: none; padding: 12px 25px; border-radius: 20px; cursor: pointer; font-size: 1rem; margin: 0 10px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                🔄 Limpiar Filtros
                            </button>
                            <span id="resultsCount" 
                                  style="color: #FFD700; font-weight: bold; font-size: 1.1rem; margin-left: 20px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                                Mostrando todas las variedades
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="cafe-grid">
                    <?php foreach ($variedades as $cafe): ?>
                        <div class="cafe-card" data-name="<?php echo htmlspecialchars($cafe['grano']['planta']['nombre_variedad']); ?>">
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

            <!-- Footer del catálogo -->
            <div class="catalog-footer">
                <div class="footer-content">
                    <h4>☕ Café de Colombia - Patrimonio Cultural de la Humanidad</h4>
                    <p>El Paisaje Cultural Cafetero de Colombia fue declarado Patrimonio de la Humanidad por la UNESCO en 2011, reconociendo la tradición, el esfuerzo y la dedicación de nuestros caficultores.</p>
                    <div class="footer-stats">
                        <div class="footer-stat">
                            <strong>UNESCO 2011</strong>
                            <span>Patrimonio Mundial</span>
                        </div>
                        <div class="footer-stat">
                            <strong>100+ años</strong>
                            <span>Tradición Cafetera</span>
                        </div>
                        <div class="footer-stat">
                            <strong>Juan Valdez</strong>
                            <span>Embajador Mundial</span>
                        </div>
                    </div>
                </div>
            </div>
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
    <script>
  const map = L.map('map').setView([4.5, -74.2], 6); // Centro de Colombia

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Definir el emoji como ícono HTML
  const iconEmoji = (emoji = "📍") => L.divIcon({
    html: `<div style="font-size: 1.8rem;">${emoji}</div>`,
    className: '',
    iconSize: [24, 24]
  });

  // Zonas cafeteras
  const zonasCafeteras = [
    {
      nombre: "Huila",
      tipoCafe: "Café suave y balanceado",
      coords: [2.5359, -75.5277]
    },
    {
      nombre: "Nariño",
      tipoCafe: "Notas cítricas y dulces",
      coords: [1.2891, -77.3579]
    },
    {
      nombre: "Antioquia",
      tipoCafe: "Cuerpo medio, notas a chocolate",
      coords: [6.2518, -75.5636]
    },
    {
      nombre: "Santander",
      tipoCafe: "Aroma intenso, acidez media",
      coords: [7.1254, -73.1198]
    },
    {
      nombre: "Cauca",
      tipoCafe: "Dulce, floral y frutal",
      coords: [2.4448, -76.6147]
    },
    {
      nombre: "Tolima",
      tipoCafe: "Acidez media y buen cuerpo",
      coords: [4.4389, -75.2322]
    }
  ];

  // Agregar cada marcador con emoji
  zonasCafeteras.forEach(zona => {
    L.marker(zona.coords, { icon: iconEmoji("📍") })
      .addTo(map)
      .bindPopup(`<strong>${zona.nombre}</strong><br>${zona.tipoCafe}`);
  });
</script>

    
    <script src="../../frontend/js/pagina_principal.js"></script>
</body>
</html>