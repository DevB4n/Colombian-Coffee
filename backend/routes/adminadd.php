<?php
require_once 'auth.php';

// Verificar autenticación
if (!isAuthenticated()) {
    header('Location: index.php?error=unauthorized');
    exit();
}

// Verificar que sea un administrador
if (getUserRole() !== 'admin') {
    header('Location: coneccion.php');
    exit();
}

// Obtener credenciales de API
$apiCredentials = getApiCredentials();
$apiUrl = 'http://localhost:8081/caracteristicas_cafe';
$api_username = $apiCredentials['username'];
$api_password = $apiCredentials['password'];
$variedades = [];
$error = null;

// Realizar la petición a la API
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_username:$api_password");
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
    <title>Catálogo de Variedades de Café - Administrador</title>
    <link rel="stylesheet" href="../../frontend/css/pagina_admi.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body>
    <div class="container">
        <video id="videoBackground" muted loop autoplay>
            <source src="../../frontend/img/Comerciales Colcafé - Consiéntete, date gusto con un Colcafé Clásico.mp4" type="video/mp4">
            <source src="https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4" type="video/mp4">
            Tu navegador no soporta el elemento de video.
        </video>

        <!-- Catálogo de Café -->
        <div class="cafe-catalog" id="cafeCatalog" style="display: block;">
            <div class="catalog-header">
                <h2>🌱 Variedades de Café Colombiano - Panel Administrador</h2>
                <div class="header-buttons">
                    <a href="auth.php?logout=1" class="btn-logout" style="background: linear-gradient(45deg, #6B4423, #8B4513); color: white; padding: 10px 20px; border-radius: 20px; text-decoration: none; margin-left: 10px;">Cerrar Sesión</a>
                </div>
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
                    <button class="btn-add-product" id="btnAddProduct">➕ Agregar Nuevo Producto</button>
                    <button class="btn-update" id="btnOpenUpdateModal">✏️ Actualizar Registro</button>
                    <button class="btn-delete" id="btnOpenDeleteModal">🗑️ Eliminar Variedad</button>
                </div>

                <!-- Sección de búsqueda y filtros -->
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

        <!-- Modal para agregar nuevo producto -->
        <div class="modal" id="addProductModal">
            <div class="modal-content">
                <span class="close-btn" id="closeAddProductModal">&times;</span>
                <h2>➕ Agregar Nueva Variedad de Café</h2>
                <form id="addProductForm">
                    <!-- Información de la Planta -->
                    <div class="form-section">
                        <h3>🌱 Información de la Planta</h3>
                        <div class="form-group">
                            <label for="nombre_variedad">Nombre de la Variedad:</label>
                            <input type="text" id="nombre_variedad" required>
                        </div>
                        <div class="form-group">
                            <label for="especie">Especie:</label>
                            <input type="text" id="especie" required>
                        </div>
                        <div class="form-group">
                            <label for="tamano_planta_cm">Tamaño de la Planta (cm):</label>
                            <input type="number" id="tamano_planta_cm" required>
                        </div>
                        <div class="form-group">
                            <label for="color_hoja">Color de la Hoja:</label>
                            <input type="text" id="color_hoja" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion_planta">Descripción:</label>
                            <textarea id="descripcion_planta" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="imagen_planta_url">URL de la Imagen de la Planta:</label>
                            <input type="url" id="imagen_planta_url" required>
                        </div>
                    </div>

                    <!-- Información del Grano -->
                    <div class="form-section">
                        <h3>🌾 Características del Grano</h3>
                        <div class="form-group">
                            <label for="tamano_grano_mm">Tamaño del Grano (mm):</label>
                            <input type="number" step="0.1" id="tamano_grano_mm" required>
                        </div>
                        <div class="form-group">
                            <label for="color_grano">Color del Grano:</label>
                            <input type="text" id="color_grano" required>
                        </div>
                        <div class="form-group">
                            <label for="forma_grano">Forma del Grano:</label>
                            <input type="text" id="forma_grano" required>
                        </div>
                        <div class="form-group">
                            <label for="calidad">Calidad:</label>
                            <select id="calidad" required>
                                <option value="">Selecciona la calidad</option>
                                <option value="Bueno">Bueno</option>
                                <option value="Regular">Regular</option>
                                <option value="Malo">Malo</option>
                                <option value="No Consumible">No Consumible</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="imagen_url">URL de la Imagen del Grano:</label>
                            <input type="url" id="imagen_url" required>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="form-section">
                        <h3>📊 Información Adicional</h3>
                        <div class="form-group">
                            <label for="region">Región:</label>
                            <select id="region" required>
                                <option value="">Selecciona la región</option>
                                <option value="Huila">Huila</option>
                                <option value="Narino">Narino</option>
                                <option value="Antioquia">Antioquia</option>
                                <option value="Caldas">Caldas</option>
                                <option value="Tolima">Tolima</option>
                                <option value="Cauca">Cauca</option>
                                <option value="Santander">Santander</option>
                                <option value="Valle del Cauca">Valle del Cauca</option>
                                <option value="Quindio">Quindío</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sabor">Sabor:</label>
                            <input type="text" id="sabor" placeholder="Ej: Dulce con notas achocolatadas" required>
                        </div>
                        <div class="form-group">
                            <label for="altitud_optima">Altitud Óptima (msnm):</label>
                            <input type="number" id="altitud_optima" min="500" max="2500" required>
                        </div>
                        <div class="form-group">
                            <label for="resistencia">Resistencia:</label>
                            <select id="resistencia" required>
                                <option value="">Selecciona el tipo de resistencia</option>
                                <option value="Resistente">Resistente</option>
                                <option value="Tolerante">Tolerante</option>
                                <option value="Susceptible">Susceptible</option>
                                <option value="En peligro">En peligro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="densidad_plantacion">Densidad de Plantación (plantas/ha):</label>
                            <input type="number" id="densidad_plantacion" min="1000" max="10000" value="5000" required>
                        </div>
                        <div class="form-group">
                            <label for="requerimiento_nutricion">Requerimiento de Nutrición:</label>
                            <select id="requerimiento_nutricion" required>
                                <option value="">Selecciona el requerimiento</option>
                                <option value="Alto">Alto</option>
                                <option value="Medio">Medio</option>
                                <option value="Bajo">Bajo</option>
                                <option value="Exigente">Exigente</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="desde_anhos">Tiempo de Crecimiento - Desde (años):</label>
                            <input type="number" id="desde_anhos" min="1" max="10" value="2" required>
                        </div>
                        <div class="form-group">
                            <label for="hasta_anhos">Tiempo de Crecimiento - Hasta (años):</label>
                            <input type="number" id="hasta_anhos" min="2" max="15" value="5" required>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Agregar Variedad</button>
                </form>
            </div>
        </div>

        <!-- Modal para actualizar producto -->
        <div class="modal" id="updateProductModal">
            <div class="modal-content">
                <span class="close-btn" id="closeUpdateProductModal">&times;</span>
                <h2>✏️ Actualizar Registro</h2>
                <form id="updateProductForm">
                    <div class="form-group">
                        <label for="update_table">Tabla a actualizar:</label>
                        <select id="update_table" required>
                            <option value="">Seleccione una tabla</option>
                            <option value="planta">Planta</option>
                            <option value="grano">Grano</option>
                            <option value="region">Región</option>
                            <option value="sabor">Sabor</option>
                            <option value="tiempo_crecimiento">Tiempo de Crecimiento</option>
                            <option value="datos_cafe">Datos del Café</option>
                            <option value="cafe">Café (completo)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="update_id">ID del registro:</label>
                        <input type="number" id="update_id" placeholder="Ingrese el ID" required>
                    </div>

                    <div class="form-group">
                        <label for="update_field">Campo a modificar:</label>
                        <input type="text" id="update_field" placeholder="Ej: nombre_variedad" required>
                    </div>

                    <div class="form-group">
                        <label for="update_value">Nuevo valor:</label>
                        <input type="text" id="update_value" placeholder="Nuevo valor" required>
                    </div>

                    <button type="submit" class="btn-update">✏️ Actualizar</button>
                </form>
            </div>
        </div>

        <!-- Modal para eliminar producto -->
        <div class="modal" id="deleteProductModal">
            <div class="modal-content">
                <span class="close-btn" id="closeDeleteProductModal">&times;</span>
                <h2>🗑️ Eliminar Variedad de Café</h2>
                <form id="deleteProductForm">
                    <div class="form-group">
                        <label for="delete_table">Tabla a eliminar:</label>
                        <select id="delete_table" required>
                            <option value="">Seleccione una tabla</option>
                            <option value="planta">Planta</option>
                            <option value="grano">Grano</option>
                            <option value="region">Región</option>
                            <option value="sabor">Sabor</option>
                            <option value="tiempo_crecimiento">Tiempo de Crecimiento</option>
                            <option value="datos_cafe">Datos del Café</option>
                            <option value="cafe">Café (completo)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="delete_id">ID del registro:</label>
                        <input type="number" id="delete_id" placeholder="Ingrese el ID" required>
                    </div>

                    <button type="submit" class="btn-delete">✖️ Eliminar</button>
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

    <!-- Scripts -->
    <script>
        // Inicialización del mapa
        function initializeMap() {
            try {
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
            } catch (error) {
                console.error('Error inicializando el mapa:', error);
            }
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar el mapa después de un pequeño delay
            setTimeout(initializeMap, 100);
        });

// ===========================================
// SCRIPT PARA GENERAR PDF DEL CATÁLOGO DE CAFÉ
// ===========================================

// Función para generar PDF con todas las variedades de café
function generateCoffeePDF() {
    // Mostrar mensaje de carga
    showLoadingMessage();
    
    // Obtener todas las tarjetas de café visibles
    const coffeeCards = document.querySelectorAll('.cafe-card:not([style*="display: none"])');
    
    if (coffeeCards.length === 0) {
        alert('No hay variedades de café para exportar.');
        hideLoadingMessage();
        return;
    }

    // Crear el contenido del PDF
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    
    // Configuración de colores y fuentes
    const primaryColor = [139, 69, 19]; // Café oscuro
    const secondaryColor = [210, 105, 30]; // Café claro
    const goldColor = [255, 215, 0]; // Dorado
    
    let yPosition = 20;
    const pageHeight = 297;
    const margin = 20;
    const lineHeight = 6;
    
    // Función para agregar nueva página si es necesario
    function checkNewPage(neededSpace = 50) {
        if (yPosition + neededSpace > pageHeight - margin) {
            doc.addPage();
            yPosition = margin;
            return true;
        }
        return false;
    }
    
    // PORTADA
    doc.setFillColor(primaryColor[0], primaryColor[1], primaryColor[2]);
    doc.rect(0, 0, 210, 297, 'F');
    
    // Título principal
    doc.setTextColor(255, 215, 0);
    doc.setFontSize(28);
    doc.setFont('helvetica', 'bold');
    doc.text('☕ CATÁLOGO DE CAFÉ', 105, 100, { align: 'center' });
    
    doc.setFontSize(20);
    doc.text('Variedades Colombianas', 105, 120, { align: 'center' });
    
    // Subtítulo
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(14);
    doc.setFont('helvetica', 'normal');
    doc.text('Descubre las mejores variedades de café colombiano', 105, 140, { align: 'center' });
    
    // Fecha de generación
    const currentDate = new Date().toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    doc.setFontSize(12);
    doc.text(`Generado el: ${currentDate}`, 105, 200, { align: 'center' });
    
    // Estadísticas rápidas
    doc.setTextColor(255, 215, 0);
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.text(`📊 ${coffeeCards.length} Variedades Incluidas`, 105, 230, { align: 'center' });
    
    // Nueva página para el contenido
    doc.addPage();
    yPosition = margin;
    
    // ÍNDICE
    doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
    doc.setFontSize(18);
    doc.setFont('helvetica', 'bold');
    doc.text('📋 ÍNDICE DE VARIEDADES', margin, yPosition);
    yPosition += 15;
    
    // Listar todas las variedades en el índice
    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');
    
    coffeeCards.forEach((card, index) => {
        const varietyName = card.querySelector('.variety-name')?.textContent || 
                          card.getAttribute('data-name') || 
                          `Variedad ${index + 1}`;
        
        if (checkNewPage(10)) {
            doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
            doc.setFontSize(18);
            doc.setFont('helvetica', 'bold');
            doc.text('📋 ÍNDICE DE VARIEDADES (continuación)', margin, yPosition);
            yPosition += 15;
            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
        }
        
        doc.text(`${index + 1}. ${varietyName}`, margin + 5, yPosition);
        yPosition += 8;
    });
    
    // Nueva página para las variedades
    doc.addPage();
    yPosition = margin;
    
    // CONTENIDO DE CADA VARIEDAD
    coffeeCards.forEach((card, index) => {
        checkNewPage(80);
        
        // Obtener información de la tarjeta
        const varietyName = card.querySelector('.variety-name')?.textContent || 
                          card.getAttribute('data-name') || 
                          `Variedad ${index + 1}`;
        
        const quality = card.querySelector('.quality-badge')?.textContent?.trim() || 'N/A';
        
        // Título de la variedad
        doc.setFillColor(primaryColor[0], primaryColor[1], primaryColor[2]);
        doc.rect(margin, yPosition - 5, 170, 12, 'F');
        
        doc.setTextColor(255, 215, 0);
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.text(`${index + 1}. ${varietyName}`, margin + 5, yPosition + 3);
        
        // Badge de calidad
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(10);
        doc.text(`Calidad: ${quality}`, 170, yPosition + 3, { align: 'right' });
        
        yPosition += 20;
        
        // Información del grano
        const grainInfo = extractInfoFromCard(card, '.info-section:has(.info-title:contains("Características del Grano"))');
        if (grainInfo.length > 0) {
            doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('🌾 Características del Grano', margin, yPosition);
            yPosition += 8;
            
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(0, 0, 0);
            
            grainInfo.forEach(info => {
                if (checkNewPage(15)) {
                    doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
                    doc.setFontSize(16);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`${varietyName} (continuación)`, margin, yPosition);
                    yPosition += 10;
                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'normal');
                    doc.setTextColor(0, 0, 0);
                }
                
                doc.setFont('helvetica', 'bold');
                doc.text(`• ${info.label}:`, margin + 5, yPosition);
                doc.setFont('helvetica', 'normal');
                doc.text(info.value, margin + 40, yPosition);
                yPosition += 5;
            });
            yPosition += 5;
        }
        
        // Información de la planta
        const plantInfo = extractInfoFromCard(card, '.info-section:has(.info-title:contains("Información de la Planta"))');
        if (plantInfo.length > 0) {
            checkNewPage(30);
            
            doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('🌱 Información de la Planta', margin, yPosition);
            yPosition += 8;
            
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(0, 0, 0);
            
            plantInfo.forEach(info => {
                if (checkNewPage(10)) {
                    doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
                    doc.setFontSize(16);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`${varietyName} (continuación)`, margin, yPosition);
                    yPosition += 10;
                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'normal');
                    doc.setTextColor(0, 0, 0);
                }
                
                doc.setFont('helvetica', 'bold');
                doc.text(`• ${info.label}:`, margin + 5, yPosition);
                doc.setFont('helvetica', 'normal');
                doc.text(info.value, margin + 40, yPosition);
                yPosition += 5;
            });
        }
        
        // Descripción
        const description = card.querySelector('.description p')?.textContent;
        if (description) {
            checkNewPage(20);
            
            doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.text('📝 Descripción:', margin, yPosition);
            yPosition += 6;
            
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            
            const cleanDescription = description.replace('Descripción:', '').trim();
            const lines = doc.splitTextToSize(cleanDescription, 170);
            
            lines.forEach(line => {
                if (checkNewPage(10)) {
                    doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
                    doc.setFontSize(16);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`${varietyName} (continuación)`, margin, yPosition);
                    yPosition += 10;
                    doc.setTextColor(0, 0, 0);
                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'normal');
                }
                
                doc.text(line, margin + 5, yPosition);
                yPosition += 5;
            });
        }
        
        // Datos de cultivo
        const cultivationInfo = extractInfoFromCard(card, '.info-section:has(.info-title:contains("Datos de Cultivo"))');
        if (cultivationInfo.length > 0) {
            checkNewPage(35);
            
            doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('📊 Datos de Cultivo', margin, yPosition);
            yPosition += 8;
            
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(0, 0, 0);
            
            cultivationInfo.forEach(info => {
                if (checkNewPage(10)) {
                    doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
                    doc.setFontSize(16);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`${varietyName} (continuación)`, margin, yPosition);
                    yPosition += 10;
                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'normal');
                    doc.setTextColor(0, 0, 0);
                }
                
                doc.setFont('helvetica', 'bold');
                doc.text(`• ${info.label}:`, margin + 5, yPosition);
                doc.setFont('helvetica', 'normal');
                doc.text(info.value, margin + 50, yPosition);
                yPosition += 5;
            });
        }
        
        // Separador entre variedades
        yPosition += 10;
        if (index < coffeeCards.length - 1) {
            checkNewPage(20);
            doc.setDrawColor(210, 105, 30);
            doc.setLineWidth(0.5);
            doc.line(margin, yPosition, 190, yPosition);
            yPosition += 15;
        }
    });
    
    // Página final con información adicional
    doc.addPage();
    yPosition = margin;
    
    doc.setFillColor(primaryColor[0], primaryColor[1], primaryColor[2]);
    doc.rect(0, 0, 210, 40, 'F');
    
    doc.setTextColor(255, 215, 0);
    doc.setFontSize(20);
    doc.setFont('helvetica', 'bold');
    doc.text('☕ Café de Colombia', 105, 25, { align: 'center' });
    
    doc.setTextColor(0, 0, 0);
    doc.setFontSize(12);
    doc.text('Patrimonio Cultural de la Humanidad - UNESCO 2011', 105, 60, { align: 'center' });
    
    // Información adicional
    yPosition = 80;
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text('🌍 Datos Interesantes del Café Colombiano', margin, yPosition);
    yPosition += 15;
    
    const facts = [
        '• Colombia es el tercer productor mundial de café',
        '• Cultiva principalmente café Arábica de alta calidad',
        '• 32 departamentos participan en la producción cafetera',
        '• Más de 540,000 familias dependen del café',
        '• Se producen aproximadamente 12 millones de sacos anuales',
        '• El café se cultiva entre 1,200 y 2,000 metros de altitud',
        '• Colombia tiene dos cosechas al año: principal y mitaca',
        '• El 80% de los cafetales están bajo sombra'
    ];
    
    doc.setFontSize(11);
    doc.setFont('helvetica', 'normal');
    
    facts.forEach(fact => {
        doc.text(fact, margin, yPosition);
        yPosition += 8;
    });
    
    yPosition += 20;
    doc.setFontSize(10);
    doc.setTextColor(100, 100, 100);
    doc.text(`Documento generado el ${currentDate}`, 105, yPosition, { align: 'center' });
    doc.text('Total de variedades incluidas: ' + coffeeCards.length, 105, yPosition + 10, { align: 'center' });
    
    // Guardar el PDF
    const fileName = `Catalogo_Cafe_Colombiano_${new Date().toISOString().split('T')[0]}.pdf`;
    doc.save(fileName);
    
    hideLoadingMessage();
    
    // Mostrar mensaje de éxito
    showSuccessMessage(`PDF generado exitosamente: ${fileName}`);
}

// Función auxiliar para extraer información de las tarjetas
function extractInfoFromCard(card, selector) {
    const info = [];
    
    // Buscar todas las secciones de información
    const infoSections = card.querySelectorAll('.info-section');
    
    infoSections.forEach(section => {
        const titleElement = section.querySelector('.info-title');
        if (!titleElement) return;
        
        const title = titleElement.textContent.trim();
        
        // Filtrar por el tipo de sección que queremos
        let shouldInclude = false;
        if (selector.includes('Grano') && title.includes('Grano')) shouldInclude = true;
        if (selector.includes('Planta') && title.includes('Planta')) shouldInclude = true;
        if (selector.includes('Cultivo') && title.includes('Cultivo')) shouldInclude = true;
        
        if (shouldInclude) {
            const infoItems = section.querySelectorAll('.info-item');
            infoItems.forEach(item => {
                const label = item.querySelector('.label')?.textContent?.trim() || '';
                const value = item.querySelector('.value')?.textContent?.trim() || '';
                
                if (label && value) {
                    info.push({ label: label.replace(':', ''), value });
                }
            });
        }
    });
    
    return info;
}

// Funciones de UI para mensajes
function showLoadingMessage() {
    // Crear overlay de carga
    const overlay = document.createElement('div');
    overlay.id = 'pdfLoadingOverlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        color: white;
        font-size: 18px;
        flex-direction: column;
    `;
    
    overlay.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 20px;">☕</div>
            <div>Generando PDF del catálogo...</div>
            <div style="margin-top: 10px; font-size: 14px; opacity: 0.8;">Esto puede tomar unos segundos</div>
        </div>
    `;
    
    document.body.appendChild(overlay);
}

function hideLoadingMessage() {
    const overlay = document.getElementById('pdfLoadingOverlay');
    if (overlay) {
        overlay.remove();
    }
}

function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(45deg, #4CAF50, #45a049);
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        z-index: 10001;
        font-size: 14px;
        max-width: 300px;
    `;
    
    successDiv.innerHTML = `
        <div style="display: flex; align-items: center;">
            <span style="font-size: 20px; margin-right: 10px;">✅</span>
            <div>${message}</div>
        </div>
    `;
    
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 5000);
}

// Función para agregar el botón de descarga a la página
function addDownloadButton() {
    // Buscar el contenedor donde agregar el botón
    const catalogHeader = document.querySelector('.catalog-header') || 
                         document.querySelector('.catalog-title-section');
    
    if (!catalogHeader) {
        console.error('No se encontró el contenedor para el botón de descarga');
        return;
    }
    
    // Crear el botón
    const downloadBtn = document.createElement('button');
    downloadBtn.id = 'btnDownloadPDF';
    downloadBtn.className = 'btn-download-pdf';
    downloadBtn.innerHTML = '📄 Descargar Catálogo PDF';
    
    // Estilos para el botón
    downloadBtn.style.cssText = `
        background: linear-gradient(45deg, #8B4513, #D2691E);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 1rem;
        margin: 40px 0px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        font-weight: bold;
    `;
    
    // Efectos hover
    downloadBtn.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.3)';
    });
    
    downloadBtn.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
    });
    
    // Agregar event listener
    downloadBtn.addEventListener('click', generateCoffeePDF);
    
    // Insertar el botón
    catalogHeader.appendChild(downloadBtn);
}

// Función para cargar la librería jsPDF
function loadJsPDF() {
    return new Promise((resolve, reject) => {
        if (window.jspdf) {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
        script.onload = () => {
            if (window.jspdf) {
                resolve();
            } else {
                reject(new Error('jsPDF no se cargó correctamente'));
            }
        };
        script.onerror = () => reject(new Error('Error al cargar jsPDF'));
        document.head.appendChild(script);
    });
}

// Inicialización
async function initializePDFFeature() {
    try {
        await loadJsPDF();
        addDownloadButton();
        console.log('Funcionalidad PDF inicializada correctamente');
    } catch (error) {
        console.error('Error al inicializar la funcionalidad PDF:', error);
        
        // Mostrar mensaje de error al usuario
        const errorDiv = document.createElement('div');
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f44336;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 10000;
        `;
        errorDiv.textContent = 'Error al cargar la funcionalidad de PDF';
        document.body.appendChild(errorDiv);
        
        setTimeout(() => errorDiv.remove(), 5000);
    }
}

// Auto-inicialización cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePDFFeature);
} else {
    initializePDFFeature();
}

<<<<<<< HEAD
</script>


<script src="../../frontend/js/pagina_principal.js"></script>
</body>
</html>
=======
>>>>>>> 67799e12f3dbed413da2f1a94e799c1cedf5692a
    </script>
    
    <script src="../../frontend/js/pagina_principal.js"></script>
</body>
</html>
    </script>
    
    <script src="../../frontend/js/pagina_principal.js"></script>
</body>
<<<<<<< HEAD
=======

>>>>>>> 67799e12f3dbed413da2f1a94e799c1cedf5692a
</html>