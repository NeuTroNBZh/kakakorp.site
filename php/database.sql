-- Création de la base de données
CREATE DATABASE IF NOT EXISTS kakakorp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE kakakorp;

-- Table des contacts
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_creation DATETIME NOT NULL,
    lu BOOLEAN DEFAULT FALSE,
    INDEX idx_email (email),
    INDEX idx_date_creation (date_creation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des membres de l'équipe
CREATE TABLE IF NOT EXISTS membres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL,
    nom_complet VARCHAR(100),
    role VARCHAR(50),
    age INT,
    nationalite VARCHAR(50),
    description TEXT,
    image_url VARCHAR(255),
    date_ajout DATETIME NOT NULL,
    actif BOOLEAN DEFAULT TRUE,
    UNIQUE KEY uk_pseudo (pseudo),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des jeux
CREATE TABLE IF NOT EXISTS jeux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    actif BOOLEAN DEFAULT TRUE,
    UNIQUE KEY uk_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table de liaison membres-jeux
CREATE TABLE IF NOT EXISTS membres_jeux (
    membre_id INT NOT NULL,
    jeu_id INT NOT NULL,
    PRIMARY KEY (membre_id, jeu_id),
    FOREIGN KEY (membre_id) REFERENCES membres(id) ON DELETE CASCADE,
    FOREIGN KEY (jeu_id) REFERENCES jeux(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des actualités
CREATE TABLE IF NOT EXISTS actualites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    image_url VARCHAR(255),
    date_publication DATETIME NOT NULL,
    auteur_id INT,
    FOREIGN KEY (auteur_id) REFERENCES membres(id),
    INDEX idx_date_publication (date_publication)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des membres fondateurs
INSERT INTO membres (pseudo, nom_complet, role, age, nationalite, description, date_ajout) VALUES
('NeuTroNBZh', NULL, 'Sniper', 20, 'Française', 'Expert en précision et stratégie', NOW()),
('Fulzyk', NULL, 'Défenseur / Soutien', 20, 'Française', 'Spécialiste de la défense et du support', NOW()),
('Nab3tse', NULL, 'Choker', 21, 'Française', 'Maître des situations critiques', NOW()),
('ThePhilosoft', NULL, 'In-Game-Leader', 20, 'Française', 'Leader stratégique de l\'équipe', NOW()),
('Pouille561', NULL, 'Dueliste', 20, 'Française', 'Expert en combat rapproché', NOW()),
('Néxo', NULL, 'Smoker', 18, 'Française', 'Spécialiste des écrans de fumée', NOW());

-- Insertion des jeux
INSERT INTO jeux (nom, description) VALUES
('Valorant', 'Jeu de tir tactique en équipe'),
('Counter Strike', 'Jeu de tir tactique classique'),
('Rocket League', 'Football avec des voitures'),
('Brawlstars', 'Jeu de combat mobile'),
('Overwatch', 'Jeu de tir en équipe'),
('Fortnite', 'Battle Royale populaire');

-- Association des membres avec leurs jeux
INSERT INTO membres_jeux (membre_id, jeu_id) VALUES
(1, 1), (1, 2), -- NeuTroNBZh
(2, 1), (2, 2), (2, 4), -- Fulzyk
(3, 1), (3, 2), (3, 3), -- Nab3tse
(4, 1), (4, 2), -- ThePhilosoft
(5, 1), (5, 2), (5, 3), (5, 6), -- Pouille561
(6, 1), (6, 5), (6, 3), (6, 2); -- Néxo 