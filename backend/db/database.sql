-- Active: 1753786602013@@127.0.0.1@3306@coffee

-- Datase connection

SHOW DATABASES;

CREATE DATABASE IF NOT EXISTS coffee_kawaii;

USE coffee_kawaii

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
    tamano_hoja_cm DECIMAL(5,2) NOT NULL CHECK(tamano_hoja_cm > 0),       -- Tamano promedio de la hoja (largo)
    descripcion TEXT NOT NULL
);

CREATE TABLE granos_cafe (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    planta_id INT NOT NULL,
    tamano_grano_mm DECIMAL(4,2) NOT NULL CHECK(tamano_grano_mm > 0),          -- Ej: 6.5 mm
    color_grano VARCHAR(50) NOT NULL,               -- Ej: Verde, amarillo, rojo o marron
    forma_grano VARCHAR(50) NOT NULL,               -- Ej: Ovalado, redondo, alargado
    calidad ENUM('Bueno', 'Malo', 'Regular', 'No Consumible') NOT NULL
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
    resistencia VARCHAR(100) NOT NULL,
    primera_siembra DATE NOT NULL
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
('Caldas', 'Templado humedo', 'Franco arenoso', 1);


INSERT INTO plantas_cafe (nombre_variedad, especie, nombre_comun, color_hoja, tamano_hoja_cm, descripcion)
VALUES 
('Castillo', 'Coffea arabica', 'Cafe arabica', 'Verde brillante', 15.50, 'Variedad resistente a enfermedades, de alta productividad.'),
('Caturra', 'Coffea arabica', 'Cafe arabica', 'Verde oscuro', 13.75, 'Variedad de porte bajo, ideal para alta densidad de siembra.'),
('Bourbon', 'Coffea arabica', 'Cafe arabica', 'Verde claro', 14.20, 'Variedad tradicional con buen perfil de sabor.');

INSERT INTO granos_cafe (planta_id, tamano_grano_mm, color_grano, forma_grano, calidad)
VALUES 
(1, 6.50, 'Rojo', 'Ovalado', 'Bueno'),
(2, 6.10, 'Amarillo', 'Redondo', 'Regular'),
(3, 6.80, 'Marron', 'Alargado', 'Bueno');

INSERT INTO tiempo_crecimiento (Desde_anhos, Hasta_anhos)
VALUES 
(2.00, 3.50),
(1.50, 3.00),
(2.00, 4.00);

INSERT INTO sabor (caracteristica)
VALUES 
('Dulce'),
('Citrico'),
('Chocolate'),
('Frutal');

INSERT INTO datos_cafe (requerimiento_nutricion, densidad_plantacion, resistencia, primera_siembra)
VALUES 
('Alto', 5000.00, 'Resistente a roya', '2015-04-12'),
('Medio', 4000.00, 'Moderada resistencia a plagas', '2016-06-18'),
('Exigente', 3000.00, 'Alta sensibilidad al clima', '2014-02-20');

INSERT INTO caracteristicas_cafe (granos_cafe_id, tiempo_crecimiento_id, region_id, sabor_id, altitud_optima, datos_cafe_id)
VALUES 
(1, 1, 1, 1, 1600.00, 1),
(2, 2, 2, 2, 1800.00, 2),
(3, 3, 3, 3, 1700.00, 3);
