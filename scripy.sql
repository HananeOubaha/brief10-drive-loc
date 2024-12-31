CREATE DATABASE location_vehicule;
USE location_vehicule;

CREATE TABLE roles (
    id_role INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(200) NOT NULL
);
CREATE TABLE clients (
    id_client INT AUTO_INCREMENT PRIMARY KEY,  
    nom VARCHAR(100) NOT NULL,          
    prenom VARCHAR(100) NOT NULL,      
    adresse TEXT,                      
    numtel VARCHAR(15) NOT NULL,        
    email VARCHAR(255) NOT NULL UNIQUE, 
    mdp VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    FOREIGN KEY (id_role) REFERENCES roles (id_role)          
);

CREATE TABLE categories(
    id_category INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(25)
);

CREATE TABLE vehicules (
    id_vehicule INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(200) NOT NULL,
    modele VARCHAR(200) NOT NULL,
    annee INT NOT NULL,
    prixparjour DECIMAL(10, 2) NOT NULL,
    disponible BOOLEAN NOT NULL,
    img VARCHAR(255) NOT NULL , 
    id_category INT NOT NULL,
    FOREIGN KEY (id_category) REFERENCES categories(id_category)
);

CREATE TABLE Avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    id_vehicule INT NOT NULL,
    contenu TEXT NOT NULL,
    note INT,
    date DATE NOT NULL,
    FOREIGN KEY (id_client) REFERENCES clients(id_client),
    FOREIGN KEY (id_vehicule) REFERENCES vehicules(id_vehicule)
);

CREATE TABLE reservations (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,  
    id_vehicule INT NOT NULL,                    
    id_client INT NOT NULL,                    
    datedebut DATE NOT NULL,            
    datefin DATE NOT NULL,   
    lieuPriseCharge VARCHAR(255) NOT NULL, 
    statut ENUM('Confirmee', 'En attente', 'Annulee') NOT NULL DEFAULT 'En attente',           
    prixtotal DECIMAL(10, 2) NOT NULL,  
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (id_vehicule) REFERENCES vehicules(id_vehicule) ON DELETE CASCADE,  
    FOREIGN KEY (id_client) REFERENCES clients(id_client) ON DELETE CASCADE     
);