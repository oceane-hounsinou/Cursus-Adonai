-- Création de la base de données
CREATE DATABASE IF NOT EXISTS ism_adonai;

-- Utilisation de la base
USE ism_adonai;


-- =====================================================
-- TABLE : utilisateurs
-- Contient les comptes étudiants et administrateurs
-- =====================================================

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    matricule VARCHAR(50) UNIQUE NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('etudiant','admin') NOT NULL DEFAULT 'etudiant',
    photo VARCHAR(255) DEFAULT 'default.png'
);


-- =====================================================
-- TABLE : types_demandes
-- Liste des demandes disponibles
-- =====================================================

CREATE TABLE types_demandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);


-- =====================================================
-- TABLE : demandes
-- Demandes effectuées par les étudiants
-- =====================================================

CREATE TABLE demandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    utilisateur_id INT NOT NULL,
    type_demande_id INT NOT NULL,

    objet VARCHAR(255) NOT NULL,
    description TEXT,

    statut ENUM(
        'En attente',
        'En cours',
        'Validée',
        'Rejetée'
    ) DEFAULT 'En attente',

    commentaire_admin TEXT,

    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_demande_utilisateur
    FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_demande_type
    FOREIGN KEY (type_demande_id)
    REFERENCES types_demandes(id)
    ON DELETE CASCADE
);


-- =====================================================
-- TABLE : documents
-- Documents générés ou déposés par l'administration
-- =====================================================

CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,

    demande_id INT NOT NULL,

    nom_document VARCHAR(255) NOT NULL,
    fichier VARCHAR(255) NOT NULL,

    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_document_demande
    FOREIGN KEY (demande_id)
    REFERENCES demandes(id)
    ON DELETE CASCADE
);


-- =====================================================
-- TABLE : notifications
-- Messages envoyés aux utilisateurs
-- =====================================================

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,

    utilisateur_id INT NOT NULL,

    titre VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,

    statut ENUM(
        'Non lu',
        'Lu'
    ) DEFAULT 'Non lu',

    date_notification TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notification_utilisateur
    FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE
);


-- =====================================================
-- TABLE : historiques
-- Historique des actions réalisées
-- =====================================================

CREATE TABLE historiques (
    id INT AUTO_INCREMENT PRIMARY KEY,

    utilisateur_id INT NOT NULL,

    action TEXT NOT NULL,

    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_historique_utilisateur
    FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE
);


-- =====================================================
-- INSERTION DES TYPES DE DEMANDES PAR DÉFAUT
-- =====================================================

INSERT INTO types_demandes(libelle, description) VALUES
('Attestation de scolarité',
 'Document attestant qu’un étudiant est régulièrement inscrit.'),

('Relevé de notes',
 'Document contenant les résultats académiques de l’étudiant.'),

('Certificat de réussite',
 'Document confirmant la réussite de l’étudiant.'),

('Demande de stage',
 'Demande relative aux stages académiques ou professionnels.'),

('Correction d’informations personnelles',
 'Demande de modification des informations administratives.');
