# ☕ COLOMBIAN-COFFEE

Proyecto web desarrollado en PHP usando el microframework **Slim**. Esta aplicación muestra un catálogo de variedades de café colombiano, permite autenticación de usuarios y consumo de una API REST para gestionar datos de café.

---

## 🚀 Características principales

- ✅ Catálogo de cafés consultado desde una API externa.
- 🔐 Sistema de login seguro con contraseña encriptada (bcrypt).
- 🧱 Arquitectura limpia: separación en controllers, domain, infrastructure, routes y middleware.
- 🌐 Frontend con HTML, CSS y JavaScript.
- 🧪 Middleware global para verificar sesiones activas.
- 🧰 Consumo de la API mediante cURL.

---

## 📁 Estructura del proyecto

```
Colombian-Coffee/
│
├── backend/
│   ├── controllers/
│   ├── domain/
│   ├── infrastructure/
│   ├── middleware/
│   ├── routes/
│   ├── public/           ← Punto de entrada (index.php)
│   ├── vendor/           ← Dependencias de Composer
│   └── composer.json
│
├── frontend/
│   ├── index.html
│   ├── js/
│   ├── css/
│   └── img/
│
└── README.md
```

---

## ⚙️ Requisitos

- PHP 8.1 o superior
- Composer
- Extensión `curl` habilitada

---

## 🧪 Instalación

1. **Clona el repositorio:**

2. **Instala las dependencias con Composer:**
   ```bash
   composer install
   ```

3. **Asegúrate de tener el archivo `.htaccess`** para habilitar URL amigables (si usas Apache).

4. **Inicia el servidor** (opcional para pruebas locales):
   ```bash
   php -S localhost:8081 en backend
   php -S localhost:8082/backend/routes/index.php
   ```

5. **Abre el frontend** desde tu navegador o desde un servidor local.

---

## 🔐 Autenticación

El sistema de login está enrutado mediante `routes/auth.php` y validado en `middleware/AuthMiddleware.php`. 

---

## 🧪 Consumo de API

El archivo `coneccion.php` usa cURL para consultar la API de variedades de café y mostrar los datos en el frontend.

---

## 📦 Dependencias clave

- Slim Framework
- Composer
- vlucas/phpdotenv (opcional para manejar variables de entorno)

---

## 🧠 Futuras mejoras

- Registro de nuevos usuarios
- Panel de administración para cafés
- Carrito de compras (simulado)
- Internacionalización (i18n)

---

## 🧑‍💻 Autor

**Desarrollado por Esteban Chacón, Juan David y Yarith Meliza**  
🔗 Proyecto académico - Campuslands  
📅 Año: 2025

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT.