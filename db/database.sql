-- Active: 1753736497888@@127.0.0.1@3306@coffee_kawaii

-- Datase connection

SHOW DATABASES;

CREATE DATABASE IF NOT EXISTS coffee_kawaii;

USE coffee_kawaii

SHOW TABLES;

-- Drop tables

DROP TABLE IF EXISTS pais;

DROP TABLE IF EXISTS region;

DROP TABLE IF EXISTS plantas_cafe;

DROP TABLE IF EXISTS granos_cafe;

DROP TABLE IF EXISTS tiempo_crecimiento;

DROP TABLE IF EXISTS sabor;

DROP TABLE IF EXISTS caracteristicas_cafe;

DROP TABLE IF EXISTS datos_cafe;

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
    especie VARCHAR(50) NOT NULL UNIQUE,               -- Ej: Coffea arabica
    nombre_comun VARCHAR(50) NOT NULL UNIQUE,          -- Ej: Cafe arabica
    color_hoja VARCHAR(50) NOT NULL,            -- Ej: Verde brillante
    tamano_hoja_cm DECIMAL(5,2) NOT NULL,       -- Tamano promedio de la hoja (largo)
    descripcion TEXT NOT NULL
);

CREATE TABLE granos_cafe (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    planta_id INT NOT NULL,
    tamano_grano_mm DECIMAL(4,2) NOT NULL,          -- Ej: 6.5 mm
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