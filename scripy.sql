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
-- CREATE TABLE clients (
--     id INT AUTO_INCREMENT PRIMARY KEY,  -- Identifiant unique pour chaque client
--     nom VARCHAR(100) NOT NULL,          -- Nom du client
--     prenom VARCHAR(100) NOT NULL,       -- Prénom du client
--     adresse TEXT,                       -- Adresse du client (facultatif)
--     numtel VARCHAR(15) NOT NULL,        -- Numéro de téléphone
--     email VARCHAR(255) NOT NULL UNIQUE, -- Adresse email, unique pour éviter les doublons
--     mdp VARCHAR(255) NOT NULL           -- Mot de passe, stocké de manière sécurisée (hashé)
-- );
INSERT INTO clients (nom, prenom, adresse, numtel, email, mdp, id_role) VALUES
('Smith', 'John', '123 Elm Street, Springfield', '1234567890', 'john.smith@example.com', 'hashed_password1', 2),
('Doe', 'Jane', '456 Maple Avenue, Springfield', '0987654321', 'jane.doe@example.com', 'hashed_password2', 2),
('Brown', 'Charlie', '789 Oak Lane, Springfield', '1112223333', 'charlie.brown@example.com', 'hashed_password3', 2),
('Taylor', 'Emma', '321 Pine Street, Springfield', '4445556666', 'emma.taylor@example.com', 'hashed_password4', 2),
('Johnson', 'Liam', '654 Birch Boulevard, Springfield', '7778889999', 'liam.johnson@example.com', 'hashed_password5', 2),
('Williams', 'Olivia', '987 Cedar Court, Springfield', '1122334455', 'olivia.williams@example.com', 'hashed_password6', 2),
('Miller', 'Sophia', '123 Aspen Drive, Springfield', '6677889900', 'sophia.miller@example.com', 'hashed_password7', 2),
('Davis', 'Mason', '456 Fir Street, Springfield', '1233211234', 'mason.davis@example.com', 'hashed_password8', 2),
('Wilson', 'Ava', '789 Redwood Way, Springfield', '9876543210', 'ava.wilson@example.com', 'hashed_password9', 2),
('Moore', 'Ethan', '321 Willow Terrace, Springfield', '5556667777', 'ethan.moore@example.com', 'hashed_password10', 2);
INSERT INTO vehicules (marque, modele, annee, prixparjour, disponible, img, id_category) VALUES
('Toyota', 'Corolla', 2022, 50.00, TRUE, 'images/toyota_corolla.jpg', 1),
('Honda', 'Civic', 2023, 60.00, TRUE, 'images/honda_civic.jpg', 2),
('Ford', 'Mustang', 2021, 100.00, FALSE, 'images/ford_mustang.jpg', 3),
('Tesla', 'Model 3', 2023, 120.00, TRUE, 'images/tesla_model3.jpg', 4),
('BMW', 'X5', 2022, 150.00, TRUE, 'images/bmw_x5.jpg', 5),
('Audi', 'A4', 2022, 80.00, FALSE, 'images/audi_a4.jpg', 2),
('Chevrolet', 'Camaro', 2021, 90.00, TRUE, 'images/chevrolet_camaro.jpg', 3),
('Mercedes', 'C-Class', 2023, 110.00, TRUE, 'images/mercedes_cclass.jpg', 5),
('Nissan', 'Altima', 2022, 70.00, TRUE, 'images/nissan_altima.jpg', 1),
('Volkswagen', 'Golf', 2021, 55.00, TRUE, 'images/vw_golf.jpg', 2);
INSERT INTO Avis (id_client, id_vehicule, contenu, note, date) VALUES
(16, 1, 'Great car! Smooth ride and excellent fuel efficiency.', 5, '2024-01-01'),
(17, 2, 'The car is okay, but the interior could be improved.', 3, '2024-01-03'),
(18, 3, 'I had a bad experience with this car. It broke down twice.', 1, '2024-01-05'),
(19, 4, 'Amazing vehicle! The design is sleek and it drives like a dream.', 5, '2024-01-07'),
(20, 5, 'Not worth the price. The performance is underwhelming.', 2, '2024-01-09'),
(21, 6, 'A decent car for its price range. Good value for money.', 4, '2024-01-11'),
(22, 7, 'Fantastic for long road trips. Very comfortable seats.', 5, '2024-01-13'),
(23, 8, 'The engine noise is too loud, but overall it performs well.', 3, '2024-01-15'),
(24, 9, 'A reliable car with great features. Highly recommend.', 4, '2024-01-17'),
(25, 10, 'The suspension system could be better, but it handles well.', 3, '2024-01-19');
