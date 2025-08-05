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

// Variables para el modal de agregar producto
let btnAddProduct, addProductModal, closeAddProductModal, addProductForm;

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
            // Intentar con el video de respaldo
            const sources = videoBackground.querySelectorAll('source');
            if (sources.length > 1) {
                videoBackground.load();
                videoBackground.play().catch(function(secondError) {
                    console.log('No se pudo reproducir el video de respaldo:', secondError);
                    // Ocultar el video si no se puede reproducir
                    videoBackground.style.display = 'none';
                });
            } else {
                videoBackground.style.display = 'none';
            }
        });
        
        // Manejar errores de carga del video
        videoBackground.addEventListener('error', function(e) {
            console.log('Error al cargar el video:', e);
            videoBackground.style.display = 'none';
        });
    }
    
    // Inicializar elementos del modal de agregar producto
    initializeAddProductElements();
    
    // Inicializar eventos
    initializeEvents();
    
    // Inicializar filtros
    initializeFilters();
    
    // Inicializar modales de actualización y eliminación
    initializeUpdateDeleteModals();
});

// Función para inicializar elementos del modal de agregar producto
function initializeAddProductElements() {
    btnAddProduct = document.getElementById('btnAddProduct');
    addProductModal = document.getElementById('addProductModal');
    closeAddProductModal = document.getElementById('closeAddProductModal');
    addProductForm = document.getElementById('addProductForm');
}

// Función para inicializar modales de actualización y eliminación
function initializeUpdateDeleteModals() {
    const updateModal = document.getElementById('updateProductModal');
    const openUpdateBtn = document.getElementById('btnOpenUpdateModal');
    const closeUpdateBtn = document.getElementById('closeUpdateProductModal');
    const updateProductForm = document.getElementById('updateProductForm');

    const deleteModal = document.getElementById('deleteProductModal');
    const openDeleteBtn = document.getElementById('btnOpenDeleteModal');
    const closeDeleteBtn = document.getElementById('closeDeleteProductModal');
    const deleteProductForm = document.getElementById('deleteProductForm');

    // Eventos para modal de actualización
    if (openUpdateBtn && updateModal) {
        openUpdateBtn.addEventListener('click', () => {
            showModal(updateModal);
        });
    }

    if (closeUpdateBtn && updateModal) {
        closeUpdateBtn.addEventListener('click', () => {
            hideModal(updateModal);
        });
    }

    if (updateProductForm) {
        updateProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleUpdateProduct();
        });
    }

    // Eventos para modal de eliminación
    if (openDeleteBtn && deleteModal) {
        openDeleteBtn.addEventListener('click', () => {
            showModal(deleteModal);
        });
    }

    if (closeDeleteBtn && deleteModal) {
        closeDeleteBtn.addEventListener('click', () => {
            hideModal(deleteModal);
        });
    }

    if (deleteProductForm) {
        deleteProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleDeleteProduct();
        });
    }
}

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

    // Eventos para el modal de agregar producto
    if (btnAddProduct) {
        btnAddProduct.addEventListener('click', function() {
            showModal(addProductModal);
        });
    }

    if (closeAddProductModal) {
        closeAddProductModal.addEventListener('click', function() {
            hideModal(addProductModal);
        });
    }

    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleAddProduct();
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

    // Los eventos de actualización y eliminación se manejan en initializeUpdateDeleteModals()

    // Eventos para elementos interactivos
    initializeInteractiveElements();
}

// Función para inicializar elementos interactivos
function initializeInteractiveElements() {
    const qualityItems = document.querySelectorAll('.quality-item');
  
    qualityItems.forEach(item => {
      item.addEventListener('click', () => {
        const region = item.dataset.region || "Región cafetera";
        const info = item.dataset.info || "Información no disponible";
  
        showNotification(`${region}: ${info}`, 'info');
      });
    });
}

// Función para inicializar filtros
function initializeFilters() {
    // Elementos de filtros
    const searchInput = document.getElementById('searchInput');
    const filterCalidad = document.getElementById('filterCalidad');
    const filterRegion = document.getElementById('filterRegion');
    const filterColorGrano = document.getElementById('filterColorGrano');
    const filterTamanoGrano = document.getElementById('filterTamanoGrano');
    const filterResistencia = document.getElementById('filterResistencia');
    const filterAltitud = document.getElementById('filterAltitud');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const resultsCount = document.getElementById('resultsCount');
    
    // Aplicar filtros y búsqueda
    function applyFilters() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const calidadFilter = filterCalidad ? filterCalidad.value.toLowerCase() : '';
        const regionFilter = filterRegion ? filterRegion.value.toLowerCase() : '';
        const colorGranoFilter = filterColorGrano ? filterColorGrano.value.toLowerCase() : '';
        const tamanoGranoFilter = filterTamanoGrano ? filterTamanoGrano.value.toLowerCase() : '';
        const resistenciaFilter = filterResistencia ? filterResistencia.value.toLowerCase() : '';
        const altitudFilter = filterAltitud ? filterAltitud.value.toLowerCase() : '';
        
        const cards = document.querySelectorAll('.cafe-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            let shouldShow = true;
            
            // Obtener datos de la tarjeta
            const cardText = card.textContent.toLowerCase();
            const varietyName = card.dataset.name?.toLowerCase() || '';
            
            // Filtro de búsqueda por texto
            if (searchTerm && !varietyName.includes(searchTerm) && !cardText.includes(searchTerm)) {
                shouldShow = false;
            }
            
            // Filtro por calidad
            if (calidadFilter && shouldShow) {
                const calidadElement = card.querySelector('.quality-badge');
                const calidad = calidadElement ? calidadElement.textContent.toLowerCase().trim() : '';
                if (!calidad.includes(calidadFilter)) {
                    shouldShow = false;
                }
            }
            
            // Filtro por región
            if (regionFilter && shouldShow) {
                const regionText = extractInfoFromCard(card, 'región');
                if (!regionText.toLowerCase().includes(regionFilter)) {
                    shouldShow = false;
                }
            }
            
            // Filtro por color del grano
            if (colorGranoFilter && shouldShow) {
                const colorText = extractInfoFromCard(card, 'color');
                if (!colorText.toLowerCase().includes(colorGranoFilter)) {
                    shouldShow = false;
                }
            }
            
            // Filtro por tamaño del grano
            if (tamanoGranoFilter && shouldShow) {
                const tamanoText = extractInfoFromCard(card, 'tamaño');
                const tamanoValue = parseFloat(tamanoText);
                
                if (!isNaN(tamanoValue)) {
                    switch(tamanoGranoFilter) {
                        case 'pequeño':
                            if (tamanoValue >= 5) shouldShow = false;
                            break;
                        case 'mediano':
                            if (tamanoValue < 5 || tamanoValue > 7) shouldShow = false;
                            break;
                        case 'grande':
                            if (tamanoValue <= 7) shouldShow = false;
                            break;
                    }
                } else {
                    shouldShow = false;
                }
            }
            
            // Filtro por resistencia
            if (resistenciaFilter && shouldShow) {
                const resistenciaText = extractInfoFromCard(card, 'resistencia');
                if (!resistenciaText.toLowerCase().includes(resistenciaFilter)) {
                    shouldShow = false;
                }
            }
            
            // Filtro por altitud
            if (altitudFilter && shouldShow) {
                const altitudText = extractInfoFromCard(card, 'altitud óptima');
                const altitudValue = parseFloat(altitudText);
                
                if (!isNaN(altitudValue)) {
                    switch(altitudFilter) {
                        case 'baja':
                            if (altitudValue >= 1200) shouldShow = false;
                            break;
                        case 'media':
                            if (altitudValue < 1200 || altitudValue > 1600) shouldShow = false;
                            break;
                        case 'alta':
                            if (altitudValue <= 1600) shouldShow = false;
                            break;
                    }
                } else {
                    shouldShow = false;
                }
            }
            
            // Mostrar u ocultar tarjeta con animación
            if (shouldShow) {
                card.style.display = 'block';
                card.style.animation = 'fadeInScale 0.3s ease';
                visibleCount++;
            } else {
                card.style.animation = 'fadeOutScale 0.3s ease';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
        
        // Actualizar contador de resultados
        updateResultsCount(visibleCount, cards.length);
    }
    
    // Función para extraer información específica de una tarjeta
    function extractInfoFromCard(card, labelText) {
        const labels = card.querySelectorAll('.label');
        let result = '';
        
        labels.forEach(label => {
            if (label.textContent.toLowerCase().includes(labelText.toLowerCase())) {
                const valueElement = label.parentElement.querySelector('.value');
                if (valueElement) {
                    result = valueElement.textContent.trim();
                }
            }
        });
        
        return result;
    }
    
    // Actualizar contador de resultados
    function updateResultsCount(visible, total) {
        if (!resultsCount) return;
        
        if (visible === total) {
            resultsCount.textContent = `Mostrando todas las variedades (${total})`;
            resultsCount.style.color = '#FFD700';
        } else if (visible === 0) {
            resultsCount.textContent = 'No se encontraron variedades con estos criterios';
            resultsCount.style.color = '#FF6B6B';
        } else {
            resultsCount.textContent = `Mostrando ${visible} de ${total} variedades`;
            resultsCount.style.color = '#90EE90';
        }
    }
    
    // Limpiar todos los filtros
    function clearAllFilters() {
        if (searchInput) searchInput.value = '';
        if (filterCalidad) filterCalidad.value = '';
        if (filterRegion) filterRegion.value = '';
        if (filterColorGrano) filterColorGrano.value = '';
        if (filterTamanoGrano) filterTamanoGrano.value = '';
        if (filterResistencia) filterResistencia.value = '';
        if (filterAltitud) filterAltitud.value = '';
        
        // Mostrar todas las tarjetas
        const cards = document.querySelectorAll('.cafe-card');
        cards.forEach(card => {
            card.style.display = 'block';
            card.style.animation = 'fadeInScale 0.3s ease';
        });
        
        updateResultsCount(cards.length, cards.length);
        
        // Notificación de limpieza
        showNotification('Filtros limpiados - Mostrando todas las variedades', 'info');
    }
    
    // Event listeners para todos los filtros
    if (searchInput) {
        searchInput.addEventListener('input', debounce(applyFilters, 300));
    }
    
    if (filterCalidad) {
        filterCalidad.addEventListener('change', applyFilters);
    }
    
    if (filterRegion) {
        filterRegion.addEventListener('change', applyFilters);
    }
    
    if (filterColorGrano) {
        filterColorGrano.addEventListener('change', applyFilters);
    }
    
    if (filterTamanoGrano) {
        filterTamanoGrano.addEventListener('change', applyFilters);
    }
    
    if (filterResistencia) {
        filterResistencia.addEventListener('change', applyFilters);
    }
    
    if (filterAltitud) {
        filterAltitud.addEventListener('change', applyFilters);
    }
    
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearAllFilters);
    }
    
    // Inicializar contador
    const initialCards = document.querySelectorAll('.cafe-card');
    updateResultsCount(initialCards.length, initialCards.length);
    
    // Autocompletar dinámico para la búsqueda
    setupAutocomplete();
}

// Función debounce para mejorar performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Autocompletar dinámico para la búsqueda
function setupAutocomplete() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    const varieties = [];
    const cards = document.querySelectorAll('.cafe-card');
    
    cards.forEach(card => {
        const name = card.dataset.name;
        if (name && !varieties.includes(name)) {
            varieties.push(name);
        }
    });
    
    searchInput.addEventListener('focus', () => {
        if (varieties.length > 0) {
            searchInput.setAttribute('placeholder', 
                `Buscar entre ${varieties.length} variedades (ej. ${varieties.slice(0, 2).join(', ')}) 🔍`
            );
        }
    });
    
    searchInput.addEventListener('blur', () => {
        searchInput.setAttribute('placeholder', 
            'Buscar por nombre de variedad (ej. Castillo, Caturra) 🔍'
        );
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

// Funciones de modales mejoradas
function showModal(modal) {
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Agregar clase para animación
        modal.classList.add('modal-show');
        
        // Focus en el primer input
        const firstInput = modal.querySelector('input[type="text"], input[type="email"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

function hideModal(modal) {
    if (modal) {
        modal.classList.remove('modal-show');
        
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Limpiar formularios
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
            }
        }, 300);
    }
}

function hideAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        hideModal(modal);
    });
}

// Función mejorada para manejar el envío del formulario de agregar producto
function handleAddProduct() {
    try {
        // Validar campos requeridos
        const requiredFields = [
            'nombre_variedad', 'especie', 'tamano_planta_cm', 'color_hoja', 
            'descripcion_planta', 'tamano_grano_mm', 'color_grano', 
            'forma_grano', 'calidad', 'imagen_url', 'region', 'sabor', 
            'altitud_optima', 'resistencia', 'densidad_plantacion',
            'requerimiento_nutricion', 'desde_anhos', 'hasta_anhos',
            'imagen_planta_url'
        ];
        
        const formData = {};
        for (const fieldId of requiredFields) {
            const element = document.getElementById(fieldId);
            if (!element) {
                showNotification(`Campo ${fieldId} no encontrado`, 'error');
                return;
            }
            formData[fieldId] = element.value.trim();
            if (!formData[fieldId]) {
                showNotification(`El campo ${fieldId.replace('_', ' ')} es requerido`, 'error');
                element.focus();
                return;
            }
        }

        // Crear objeto con estructura exacta que espera la API del backend
        const apiData = {
            plant: {
                nombre_variedad: formData.nombre_variedad,
                especie: formData.especie,
                nombre_comun: formData.nombre_variedad, // Campo requerido por la BD
                tamano_planta_cm: parseInt(formData.tamano_planta_cm),
                color_hoja: formData.color_hoja,
                descripcion: formData.descripcion_planta,
                imagen_url: formData.imagen_planta_url
            },
            grain: {
                tamano_grano_mm: parseFloat(formData.tamano_grano_mm),
                color_grano: formData.color_grano,
                forma_grano: formData.forma_grano,
                calidad: formData.calidad,
                imagen_url: formData.imagen_url
            },
            time_growth: {
                Desde_anhos: parseInt(formData.desde_anhos),
                Hasta_anhos: parseInt(formData.hasta_anhos)
            },
            flavor: formData.sabor,
            region: {
                nombre: formData.region,
                clima: 'Templado humedo', // Valor por defecto requerido
                suelo: 'Franco arcilloso', // Valor por defecto requerido
                pais_id: 1 // Colombia (ID 1 según los datos de ejemplo)
            },
            coffee_data: {
                resistencia: formData.resistencia,
                densidad_plantacion: parseInt(formData.densidad_plantacion),
                requerimiento_nutricion: formData.requerimiento_nutricion,
                primera_siembra: new Date().toISOString().split('T')[0]
            },
            altitud_optima: parseFloat(formData.altitud_optima) // Debe ser número, no string
        };

        console.log('Datos a enviar:', JSON.stringify(apiData, null, 2));
        console.log('URL de la API:', 'http://localhost:8081/caracteristicas_cafe/post');
        console.log('Headers:', {
            'Content-Type': 'application/json',
            'Authorization': 'Basic ' + btoa('Adrian@gmail.com:soylacontra')
        });

        // Configurar botón de enviar
        const submitBtn = document.querySelector('#addProductForm .submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Enviando... <span class="spinner"></span>';

        // Enviar a la API
        fetch('http://localhost:8081/caracteristicas_cafe/post', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Basic ' + btoa('Adrian@gmail.com:soylacontra')
            },
            body: JSON.stringify(apiData)
        })
        .then(async response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            const data = await response.json();
            console.log('Response data:', data);
            if (!response.ok) {
                throw new Error(data.message || data.error || `Error ${response.status}`);
            }
            return data;
        })
        .then(data => {
            showNotification('✅ Producto agregado exitosamente', 'success');
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error completo:', error);
            showNotification(`Error: ${error.message}`, 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Agregar Variedad';
        });

    } catch (error) {
        console.error('Error inesperado:', error);
        showNotification('Error inesperado al procesar el formulario', 'error');
    }
}

const updateModal = document.getElementById('updateProductModal');
const openUpdateBtn = document.getElementById('btnOpenUpdateModal');
const closeUpdateBtn = document.getElementById('closeUpdateProductModal');

if (openUpdateBtn && updateModal && closeUpdateBtn) {
    openUpdateBtn.addEventListener('click', () => {
        updateModal.style.display = 'block';
    });

    closeUpdateBtn.addEventListener('click', () => {
        updateModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === updateModal) {
            updateModal.style.display = 'none';
        }
    });
}

// Función para actualizar registro
function handleUpdateProduct() {
    const table = document.getElementById('update_table').value.trim();
    const id = document.getElementById('update_id').value.trim();
    const field = document.getElementById('update_field').value.trim();
    const value = document.getElementById('update_value').value.trim();

    if (!table || !id || !field || !value) {
        showNotification('Todos los campos son obligatorios.', 'error');
        return;
    }

    const data = {};
    data[field] = value;

    const submitBtn = document.querySelector('#updateProductForm .btn-update');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Actualizando... <span class="spinner"></span>';

    // Construir la URL correcta para la actualización
    let updateUrl = `http://localhost:8081/caracteristicas_cafe/${table}/${id}`;
    
    // Si es una tabla anidada, ajustar la URL
    if (table === 'grano' || table === 'planta' || table === 'datos_cafe' || table === 'tiempo_crecimiento') {
        updateUrl = `http://localhost:8081/caracteristicas_cafe/${table}/${id}`;
    }

    fetch(updateUrl, {
        method: 'PATCH',
        headers: {
            'Authorization': 'Basic ' + btoa('Adrian@gmail.com:soylacontra'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(async res => {
        const json = await res.json();
        if (!res.ok) {
            console.error('Error response:', json);
            throw new Error(json.error || json.message || `Error ${res.status}: ${res.statusText}`);
        }
        return json;
    })
    .then(data => {
        showNotification('✅ Registro actualizado correctamente', 'success');
        setTimeout(() => window.location.reload(), 1500);
    })
    .catch(err => {
        console.error('Error completo al actualizar:', err);
        showNotification(`❌ Error al actualizar: ${err.message}`, 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '✏️ Actualizar';
    });
}

function handleDeleteProduct() {
    const table = document.getElementById('delete_table').value.trim();
    const id = document.getElementById('delete_id').value.trim();

    if (!table || !id) {
        showNotification('Por favor completa todos los campos', 'error');
        return;
    }

    const confirmed = confirm(`¿Deseas eliminar el registro con ID ${id} de la tabla ${table}?`);
    if (!confirmed) return;

    const submitBtn = document.querySelector('#deleteProductForm .btn-delete');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Eliminando... <span class="spinner"></span>';

    fetch(`http://localhost:8081/caracteristicas_cafe/delete?table=${table}&id=${id}`, {
        method: 'DELETE',
        headers: {
            'Authorization': 'Basic ' + btoa('Adrian@gmail.com:soylacontra')
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || `Error ${response.status}`);
        }
        return data;
    })
    .then(data => {
        showNotification('✅ Registro eliminado exitosamente', 'success');
        setTimeout(() => window.location.reload(), 1500);
    })
    .catch(error => {
        console.error('Error completo:', error);
        showNotification(`Error al eliminar: ${error.message}`, 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Eliminar';
    });
}


function handleUserRegistration() {
    const newUsername = document.getElementById('newUsername').value;
    const email = document.getElementById('email').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (validateRegistrationForm(newUsername, email, newPassword, confirmPassword)) {
        console.log('Intento de registro:', { newUsername, email, newPassword });
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

// Sistema de notificaciones mejorado
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

// Funciones utilitarias para las imágenes
function handleImageError(img) {
    img.onerror = null;
    // Usar una imagen local o un data URI en lugar de via.placeholder.com
    img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiBubyBkaXNwb25pYmxlPC90ZXh0Pjwvc3ZnPg==';
    img.alt = 'Imagen no disponible';
}

// Lazy loading para las imágenes
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

// Función para filtrar variedades
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

// Función para buscar en el catálogo
function searchCatalog(searchTerm) {
    const cards = document.querySelectorAll('.cafe-card');
    const term = searchTerm.toLowerCase();
    
    cards.forEach(card => {
        const cardText = card.textContent.toLowerCase();
        const shouldShow = cardText.includes(term);
        card.style.display = shouldShow ? 'block' : 'none';
    });
}

// Agregar estilos de animación
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
    
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes fadeOutScale {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.9);
        }
    }
    
    .modal-show {
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .cafe-card {
        transition: all 0.3s ease;
    }
    
    .cafe-card:hover {
        transform: translateY(-5px) scale(1.02);
    }
    
    /* Estilos para mejorar el scroll en modales */
    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }
    
    /* Scroll suave para el modal de agregar producto */
    #addProductModal .modal-content {
        max-height: 85vh;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #8B4513 #f1f1f1;
    }
    
    #addProductModal .modal-content::-webkit-scrollbar {
        width: 8px;
    }
    
    #addProductModal .modal-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    #addProductModal .modal-content::-webkit-scrollbar-thumb {
        background: #8B4513;
        border-radius: 4px;
    }
    
    #addProductModal .modal-content::-webkit-scrollbar-thumb:hover {
        background: #D2691E;
    }
`;
document.head.appendChild(styleSheet);