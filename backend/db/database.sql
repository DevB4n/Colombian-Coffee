-- Active: 1754306268955@@127.0.0.1@3306@coffee

-- Datase connection

SHOW DATABASES;

CREATE DATABASE IF NOT EXISTS coffee;

USE coffee

SHOW TABLES;

-- Drop tables

DROP TABLE IF EXISTS caracteristicas_cafe;

DROP TABLE IF EXISTS datos_cafe;

DROP TABLE IF EXISTS sabor;

DROP TABLE IF EXISTS tiempo_crecimiento;

DROP TABLE IF EXISTS granos_cafe;

DROP TABLE IF EXISTS plantas_cafe;

DROP TABLE IF EXISTS region;

DROP TABLE IF EXISTS pais;

DROP TABLE IF EXISTS usuarios;


-- Tables creation

CREATE TABLE pais (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);


CREATE TABLE region (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    clima VARCHAR(50) NOT NULL,
    suelo VARCHAR(50) NOT NULL,
    pais_id INT NOT NULL
);

CREATE TABLE plantas_cafe (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_variedad VARCHAR(100) NOT NULL,      -- Ej: Castillo, Bourbon, Caturra
    especie VARCHAR(50) NOT NULL ,               -- Ej: Coffea arabica
    nombre_comun VARCHAR(50) NOT NULL,          -- Ej: Cafe arabica
    color_hoja VARCHAR(50) NOT NULL,            -- Ej: Verde brillante
    tamano_planta_cm DECIMAL(5,2) NOT NULL CHECK(tamano_planta_cm > 0),       -- Tamano planta
    descripcion TEXT NOT NULL,
    imagen_url TEXT NOT NULL
);

CREATE TABLE granos_cafe (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    planta_id INT NOT NULL,
    tamano_grano_mm DECIMAL(4,2) NOT NULL CHECK(tamano_grano_mm > 0),          -- Ej: 6.5 mm
    color_grano VARCHAR(50) NOT NULL,               -- Ej: Verde, amarillo, rojo o marron
    forma_grano VARCHAR(50) NOT NULL,               -- Ej: Ovalado, redondo, alargado
    calidad ENUM('Bueno', 'Malo', 'Regular', 'No Consumible') NOT NULL,
    imagen_url TEXT NOT NULL
);

CREATE TABLE tiempo_crecimiento (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Desde_anhos DECIMAL(10,2) NOT NULL,
    Hasta_anhos DECIMAL(10,2) NOT NULL 
);

CREATE TABLE sabor (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    caracteristica VARCHAR(50) NOT NULL
);

CREATE TABLE caracteristicas_cafe (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    granos_cafe_id INT NOT NULL,
    tiempo_crecimiento_id INT NOT NULL,
    region_id INT NOT NULL,
    sabor_id INT NOT NULL,
    altitud_optima DECIMAL(10,2) NOT NULL,
    datos_cafe_id INT NOT NULL
);

CREATE TABLE datos_cafe (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    requerimiento_nutricion ENUM('Bajo', 'Medio', 'Alto', 'Exigente') NOT NULL,
    densidad_plantacion DECIMAL(10,2) NOT NULL,
    resistencia ENUM('En peligro','Susceptible', 'Tolerante', 'Resistente') NOT NULL,
    primera_siembra DATE NOT NULL
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);


/*
-- Altura planta --
Alto -> >4.5
Medio Largo 3.5 a 4.5
Medio Corto 2 a 3.5
Medio 1 a 2
Bajo >0 a 1
*/

/*
-- tamano grano --
Pequeno -> menos de 6.0 mm
Mediano -> entre 6.0 mm y 6.75 mm
Grande -> 6.75 mm o más
Promedio -> 6.375 mm
*/

-- Foreign keys

ALTER TABLE region 
ADD CONSTRAINT fk_pais
FOREIGN KEY (pais_id) 
REFERENCES pais(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE granos_cafe 
ADD CONSTRAINT fk_planta_cafe
FOREIGN KEY (planta_id) 
REFERENCES plantas_cafe(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- 'caracteristicas_cafe'

-- FK para granos_cafe_id (referencia a la tabla 'granos_cafe')
ALTER TABLE caracteristicas_cafe
ADD CONSTRAINT fk_granos_cafe
FOREIGN KEY (granos_cafe_id)
REFERENCES granos_cafe(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- FK para tiempo_crecimiento_id (referencia a la tabla 'tiempo_crecimiento')
ALTER TABLE caracteristicas_cafe
ADD CONSTRAINT fk_tiempo_crecimiento
FOREIGN KEY (tiempo_crecimiento_id)
REFERENCES tiempo_crecimiento(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- FK para region_id (referencia a la tabla 'region')
ALTER TABLE caracteristicas_cafe
ADD CONSTRAINT fk_region
FOREIGN KEY (region_id)
REFERENCES region(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- FK para sabor_id (referencia a la tabla 'sabor')
ALTER TABLE caracteristicas_cafe
ADD CONSTRAINT fk_sabor
FOREIGN KEY (sabor_id)
REFERENCES sabor(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- FK para datos_cafe_id (referencia a la tabla 'datos_cafe')
ALTER TABLE caracteristicas_cafe
ADD CONSTRAINT fk_datos_cafe
FOREIGN KEY (datos_cafe_id)
REFERENCES datos_cafe(id)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Inserts

INSERT INTO pais (nombre) VALUES ('Colombia');

INSERT INTO region (nombre, clima, suelo, pais_id)
VALUES
('Antioquia', 'Templado humedo', 'Franco arcilloso', 1),
('Huila', 'Templado seco', 'Volcanico', 1),
('Caldas', 'Templado humedo', 'Franco arenoso', 1),
('Tolima', 'Templado humedo', 'Arcilloso', 1),
('Narino', 'Templado frio', 'Volcanico', 1),
('Cauca', 'Templado humedo', 'Franco arcilloso', 1),
('Santander', 'Templado seco', 'Franco arenoso', 1),
('Valle del Cauca', 'Templado humedo', 'Volcanico', 1),
('Quindio', 'Templado humedo', 'Franco arcilloso', 1);

INSERT INTO plantas_cafe (nombre_variedad, especie, nombre_comun, color_hoja, tamano_planta_cm, descripcion, imagen_url)
VALUES 
('Castillo', 'Coffea arabica', 'Cafe arabica', 'Verde brillante', 230.00, 'Variedad resistente a enfermedades, de alta productividad.', 'https://www.nurserywarehouse.com.au/cdn/shop/files/CoffeaArabica_ArabianCoffee_2.jpg?v=1732070133&width=1100'),
('Caturra', 'Coffea arabica', 'Cafe arabica', 'Verde oscuro', 175.00, 'Variedad de porte bajo, ideal para alta densidad de siembra.', 'https://upload.wikimedia.org/wikipedia/commons/2/24/Detail_of_coffee_plant_showing_beans_and_leaves.jpg'),
('Bourbon', 'Coffea arabica', 'Cafe arabica', 'Verde claro', 400.00, 'Variedad tradicional con buen perfil de sabor.', 'https://optimise2.assets-servd.host/worldcoffee-research/production/images/Arabica/Bourbon-2.jpg?w=1920&q=82&auto=format&fit=min&crop=focalpoint&fp-x=0.5&fp-y=0.5&dm=1684915604&s=a8c2ae68b694ece7f64a8d0079a8a4e6'),
('Robusta comun', 'Coffea canephora', 'Cafe robusta', 'Verde oscuro', 800.00, 'Variedad clasica Robusta, ya evaluada en Colombia para uso industrial y mezclas.', 'https://worldoffloweringplants.com/wp-content/uploads/2018/10/Coffea-canephora-Robusta-Coffee1.jpg'),
('Robusta congoles', 'Coffea canephora', 'Robusta congolea', 'Verde oscuro', 800.00, 'Variedad congolena introducida en pruebas de adaptacion en zonas bajas colombianas.', 'https://cdn.shopify.com/s/files/1/0111/2352/files/BDQ_Robusta_Coffee_Tree_01.jpg?v=1745603027'),
('Clon 500', 'Coffea canephora', 'Clon robusta mejorado', 'Verde oscuro', 800.00, 'Clon mejorado en evaluacion por Agrosavia; mayor rendimiento y posible calidad.', 'https://www.researchgate.net/publication/366021381/figure/fig3/AS%3A11431281104868285%401670286375337/Vertical-clonal-garden-system-of-robusta-coffee-Coffea-canephora-plants-at-18-months.png');

INSERT INTO granos_cafe (planta_id, tamano_grano_mm, color_grano, forma_grano, calidad, imagen_url)
VALUES 
(1, 6.50, 'Rojo', 'Ovalado', 'Bueno', 'https://farallonesdelcitara.bioexploradores.com/wp-content/uploads/2022/10/IMG_3619-2-768x403.jpg'),
(2, 6.10, 'Amarillo', 'Redondo', 'Regular', 'https://ocultoco.com/wp-content/uploads/2024/02/Caturra-Amarillo-Fruto-OCulto.png'),
(3, 6.80, 'Marron', 'Alargado', 'Bueno', 'https://www.ecured.cu/images/8/8a/Rama_borbon.jpg'),
(4, 7.00, 'Verdes', 'Ovalado', 'Bueno', 'https://coffeefactz.com/wp-content/uploads/2024/05/coffea-canephora-robusta-coffee-scaled.jpg'),
(5, 6.80, 'Rojo', 'Redondo', 'Regular', 'https://upload.wikimedia.org/wikipedia/commons/d/da/Ripe_Seeds_Coffee_Robusta_Coorg_Karnataka_India_Feb24_D72_25688.jpg'),
(6, 7.10, 'Marron', 'Alargado', 'Bueno', 'https://fstapdltb.filerobot.com/prismic/global/BLOG/coffea-canephora.png?vh=0baf92');

INSERT INTO tiempo_crecimiento (Desde_anhos, Hasta_anhos)
VALUES 
(2.00, 3.50),  -- Castillo
(1.50, 3.00),  -- Caturra
(2.00, 4.00),  -- Bourbon
(2.50, 4.00),  -- Robusta comun
(2.50, 4.50),  -- Robusta congoles
(3.00, 5.00);  -- Clon 500

INSERT INTO sabor (caracteristica)
VALUES 
('Dulce'),
('Citrico'),
('Chocolate'),
('Frutal'),
('Terroso'),
('Amargo');

INSERT INTO datos_cafe (requerimiento_nutricion, densidad_plantacion, resistencia, primera_siembra)
VALUES 
('Alto', 5000.00, 'Resistente', '2015-04-12'),   -- Castillo
('Medio', 4000.00, 'Tolerante', '2016-06-18'),  -- Caturra
('Exigente', 3000.00, 'Susceptible', '2014-02-20'), -- Bourbon
('Medio', 2500.00, 'Resistente', '2013-05-10'),  -- Robusta comun
('Medio', 2200.00, 'Tolerante', '2018-08-15'),   -- Robusta congoles
('Medio', 2700.00, 'Resistente', '2019-01-25');  -- Clon 500

INSERT INTO caracteristicas_cafe (granos_cafe_id, tiempo_crecimiento_id, region_id, sabor_id, altitud_optima, datos_cafe_id)
VALUES
(1, 1, 1, 1, 1600.00, 1),  -- Castillo en Antioquia, sabor Dulce
(2, 2, 2, 2, 1800.00, 2),  -- Caturra en Huila, sabor Cítrico
(3, 3, 3, 3, 1700.00, 3),  -- Bourbon en Caldas, sabor Chocolate
(4, 4, 7, 5, 700.00, 4),   -- Robusta comun en Santander, sabor Terroso
(5, 5, 8, 6, 750.00, 5),   -- Robusta congoles en Valle del Cauca, sabor Amargo
(6, 6, 9, 5, 1700.00, 6);  -- Clon 500 en Quindío, sabor Terroso

