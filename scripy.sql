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
INSERT INTO categories (id_category, category_name) VALUES
(1, 'Sedan'),
(2, 'SUV'),
(3, 'Truck'),
(4, 'vonvertible'),
(5, 'Coupe'),
(6, 'Hatchback'),
(7, 'Minivan'),
(8, 'Wagon');
ALTER TABLE Avis ADD COLUMN is_deleted TINYINT(1) DEFAULT 0;

-- Table des articles
CREATE TABLE articles (
    id_article INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    -- date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('En attente', 'Approuvé', 'Rejeté') NOT NULL DEFAULT 'En attente',
    id_client INT NOT NULL,
    id_theme INT,
    FOREIGN KEY (id_theme) REFERENCES themes (id_theme) ON DELETE SET NULL,
    FOREIGN KEY (id_client) REFERENCES clients (id_client) ON DELETE CASCADE
);
-- Table des tags
CREATE TABLE tags (
    id_tag INT AUTO_INCREMENT PRIMARY KEY,
    nom_tag VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE article_tags (
    id_article INT NOT NULL,
    id_tag INT NOT NULL,
    PRIMARY KEY (id_article, id_tag),
    FOREIGN KEY (id_article) REFERENCES articles (id_article) ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tags (id_tag) ON DELETE CASCADE
);
-- Table des commentaires
CREATE TABLE commentaires (
    id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    id_article INT NOT NULL,
    id_client INT NOT NULL,
    FOREIGN KEY (id_article) REFERENCES articles (id_article) ON DELETE CASCADE,
    FOREIGN KEY (id_client) REFERENCES clients (id_client) ON DELETE CASCADE
); 

CREATE TABLE favoris (
    id_favori INT PRIMARY KEY AUTO_INCREMENT,
    id_client INT NOT NULL,
    id_article INT NOT NULL,
    FOREIGN KEY (id_client) REFERENCES clients (id_client) ON DELETE CASCADE,
    FOREIGN KEY (id_article) REFERENCES articles (id_article) ON DELETE CASCADE
);
CREATE TABLE themes (
    id_theme INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);
INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Amélioration du site web de Drive & Loc', 
'Nous sommes ravis d\'annoncer que Drive & Loc travaille actuellement sur une refonte de son site web pour offrir une expérience utilisateur améliorée. 
Nous avons pris en compte les commentaires de nos utilisateurs et nous nous efforçons de rendre la navigation plus intuitive. 
Attendez-vous à une interface plus moderne, des temps de chargement plus rapides, et de nouvelles fonctionnalités excitantes comme la comparaison de véhicules et des recommandations personnalisées. 
Restez à l\'écoute pour plus de mises à jour!', 
'Approuvé', 
23, 
1);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Lancement du blog interactif Drive & Loc', 
'Drive & Loc est fier de présenter son nouveau blog interactif! Cet espace dédié permettra à nos clients et passionnés d\'automobiles de partager leurs expériences, poser des questions et discuter des dernières tendances. 
Nous publierons régulièrement des articles sur les nouveautés du secteur automobile, des conseils pour la location de véhicules, et des témoignages de nos clients. 
Participez aux discussions et partagez vos histoires avec notre communauté!', 
'Approuvé', 
24, 
1);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Partagez vos expériences de location de véhicules', 
'Nous invitons tous nos clients à partager leurs expériences de location de véhicules sur notre nouveau blog. 
Que vous ayez loué une voiture pour un voyage d\'affaires, des vacances en famille, ou un événement spécial, vos avis sont précieux et aideront d\'autres clients à faire le bon choix. 
N\'hésitez pas à parler de la qualité du service, de l\'état du véhicule, et de tout autre aspect de votre expérience avec Drive & Loc. 
Votre feedback est essentiel pour nous permettre d\'améliorer nos services!', 
'Approuvé', 
25, 
2);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Les dernières tendances automobiles', 
'Découvrez les dernières tendances et innovations dans le monde de l\'automobile sur notre blog. 
Nous couvrons tout, des nouveaux modèles de véhicules aux technologies émergentes comme les voitures électriques et autonomes. 
Apprenez-en plus sur les avancées en matière de sécurité, d\'efficacité énergétique, et de confort. 
Nous vous tiendrons informés des salons de l\'automobile, des lancements de nouveaux modèles, et des développements les plus passionnants du secteur.', 
'Approuvé', 
26, 
3);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Conseils pour une location de véhicule réussie', 
'Sur notre blog, nous partageons des conseils et astuces pour vous aider à tirer le meilleur parti de votre expérience de location de véhicule. 
Découvrez comment choisir le bon véhicule en fonction de vos besoins, comment économiser de l\'argent sur la location, et quelles sont les meilleures pratiques pour une conduite en toute sécurité. 
Nous vous donnons également des conseils sur l\'entretien du véhicule pendant la période de location et comment gérer les situations d\'urgence.', 
'Approuvé', 
27, 
4);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Événements et rencontres passionnés d\'automobiles', 
'Rejoignez notre communauté de passionnés d\'automobiles et participez à nos événements et rencontres. 
Nous organisons régulièrement des rencontres pour discuter des dernières nouveautés, échanger des conseils, et partager notre passion commune pour les voitures. 
Participez à nos rallyes, concours de photos, et autres événements spéciaux. 
Restez connecté avec d\'autres amateurs et partagez votre passion sur notre blog!', 
'Approuvé', 
28, 
5);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Les avantages de la location de véhicules électriques', 
'La location de véhicules électriques devient de plus en plus populaire. 
Découvrez les avantages écologiques et économiques de conduire un véhicule électrique avec Drive & Loc. 
Les véhicules électriques offrent une conduite silencieuse, des coûts de carburant réduits, et une empreinte carbone plus faible. 
Nous vous expliquons tout ce que vous devez savoir sur la recharge, l\'autonomie, et les incitations gouvernementales pour les véhicules électriques.', 
'Approuvé', 
29, 
6);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Comment choisir le bon véhicule pour vos besoins', 
'Choisir le bon véhicule de location peut être difficile. 
Lisez nos conseils pour sélectionner le véhicule qui répondra le mieux à vos besoins et à votre budget. 
Que vous ayez besoin d\'une petite voiture pour un déplacement urbain, d\'un SUV pour une sortie en famille, ou d\'une voiture de luxe pour une occasion spéciale, nous vous aidons à faire le bon choix. 
Nous vous donnons également des conseils sur les options supplémentaires comme les sièges enfants, le GPS, et les assurances.', 
'Approuvé', 
30, 
4);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Nos services de location à long terme', 
'Savez-vous que Drive & Loc propose des services de location à long terme? 
Découvrez les avantages et les conditions de nos offres de location à long terme. 
La location à long terme peut être une solution flexible et économique pour les entreprises, les expatriés, et les particuliers ayant des besoins de mobilité prolongés. 
Nous vous expliquons les modalités, les tarifs, et les services inclus dans nos offres de location à long terme.', 
'Approuvé', 
31, 
3);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Témoignages de nos clients satisfaits', 
'Lisez les témoignages de nos clients satisfaits qui ont utilisé les services de Drive & Loc. 
Découvrez leurs expériences, les véhicules qu\'ils ont loués, et leurs avis sur la qualité du service. 
Partagez également votre propre expérience sur notre blog et aidez d\'autres clients à faire le bon choix. 
Nous sommes fiers de la satisfaction de nos clients et nous nous efforçons de maintenir un service de haute qualité.', 
'Approuvé', 
32, 
2);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Les meilleurs itinéraires pour un road trip', 
'Planifiez votre prochain road trip avec Drive & Loc. 
Découvrez les meilleurs itinéraires, attractions et conseils pour une aventure inoubliable. 
Que vous soyez à la recherche de paysages pittoresques, de monuments historiques, ou de plages ensoleillées, nous vous proposons des itinéraires adaptés à tous les goûts. 
Nous vous donnons également des conseils pratiques pour préparer votre voyage et profiter pleinement de votre road trip.', 
'Approuvé', 
33, 
1);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('L\'importance de l\'entretien régulier des véhicules', 
'Un entretien régulier est essentiel pour garantir la sécurité et la performance des véhicules. 
Apprenez-en davantage sur les pratiques d\'entretien recommandées pour nos véhicules de location. 
Nous vous expliquons les vérifications à effectuer avant de prendre la route, les signes d\'alerte à surveiller, et comment prolonger la durée de vie du véhicule. 
Un bon entretien permet également de réduire les risques de panne et d\'accidents, assurant ainsi une conduite en toute tranquillité.', 
'Approuvé', 
34, 
5);

INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES
('Les meilleures applications pour les conducteurs', 
'Découvrez les meilleures applications mobiles pour les conducteurs, y compris les applications de navigation, de musique, et de gestion des dépenses de carburant. 
Nous vous présentons des applications pratiques pour planifier vos trajets, trouver les stations-service les plus proches, et éviter les embouteillages. 
Que vous soyez un conducteur occasionnel ou un voyageur fréquent, ces applications vous aideront à rendre vos trajets plus agréables et efficaces.', 
'Approuvé', 
35, 
6);

-- Table des favoris
-- CREATE TABLE favoris (
--     id_client INT NOT NULL,
--     id_article INT NOT NULL,
--     PRIMARY KEY (id_client, id_article),
--     FOREIGN KEY (id_client) REFERENCES clients (id_client) ON DELETE CASCADE,
--     FOREIGN KEY (id_article) REFERENCES articles (id_article) ON DELETE CASCADE
-- );
