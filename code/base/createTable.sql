USE `dbrakhedair`;
DROP TABLE IF EXISTS `TACHE`;
CREATE TABLE IF NOT EXISTS `TACHE`(
    `id` int(6) PRIMARY KEY AUTO_INCREMENT,
    `nom` VARCHAR(30) NOT NULL,
    `priorite` NUMERIC,
    `dateCreation` DATE NOT NULL,
    `dateFin` DATE NOT NULL CHECK (dateCreation<dateFin),
    `repete` BOOL NOT NULL,
    `checked` BOOL NOT NULL,
    `userid` VARCHAR(30) REFERENCES UTILISATEUR,
    `liste` numeric(6) REFERENCES LISTE
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `LISTE`;
CREATE TABLE IF NOT EXISTS `LISTE` (
    `id` int(6) PRIMARY KEY AUTO_INCREMENT,
    `nom` VARCHAR(30) NOT NULL,
    `visibilite` BOOL NOT NULL,
    `description` VARCHAR(150),
    `userid` VARCHAR(30)  REFERENCES UTILISATEUR
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `UTILISATEUR`;
CREATE TABLE IF NOT EXISTS `UTILISATEUR` (
    `id` VARCHAR(30)  PRIMARY KEY,
    `mail` VARCHAR(100) NOT NULL,
    `nom` VARCHAR(30) NOT NULL,
    `roleU` VARCHAR(30) NOT NULL,
    `mdp` VARCHAR(100) NOT NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

COMMIT;
