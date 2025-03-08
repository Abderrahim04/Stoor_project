-- Create database
CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Create villes table
CREATE TABLE IF NOT EXISTS villes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(100) NOT NULL
);

-- Create pays table
CREATE TABLE IF NOT EXISTS pays (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(100) NOT NULL
);

-- Create utilisateurs table
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY,
    salaire INT NOT NULL,
    FOREIGN KEY (id) REFERENCES utilisateurs(id)
);

-- Create clients table
CREATE TABLE IF NOT EXISTS clients (
    id INT PRIMARY KEY,
    teleohon INT,
    adress VARCHAR(255),
    FOREIGN KEY (id) REFERENCES utilisateurs(id)
);

-- Create produits table
CREATE TABLE IF NOT EXISTS produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

-- Create commandes table
CREATE TABLE IF NOT EXISTS commandes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date DATETIME NOT NULL,
    quantite INT NOT NULL,
    state VARCHAR(50) NOT NULL,
    client_id INT,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Create commande_produit table (for many-to-many relationship)
CREATE TABLE IF NOT EXISTS commande_produit (
    commande_id INT,
    produit_id INT,
    quantite INT NOT NULL,
    PRIMARY KEY (commande_id, produit_id),
    FOREIGN KEY (commande_id) REFERENCES commandes(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
); 