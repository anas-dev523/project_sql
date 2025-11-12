
// Database connection
$servername = "localhost";
$username   = "root";       // modify if needed
$password   = "";           // modify if needed
$dbname     = "delivecrous_db"; // name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die(" Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Array of SQL statements
$sqls = [

/* 1) Étudiant */
"CREATE TABLE IF NOT EXISTS etudiant (
  num_etudiant INT PRIMARY KEY AUTO_INCREMENT,
  nom        VARCHAR(100)   NOT NULL,
  prenom     VARCHAR(100)   NOT NULL,
  email      VARCHAR(190)   NOT NULL UNIQUE,
  tel        VARCHAR(20),
  adresse    VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

/* 2) Livreur */
"CREATE TABLE IF NOT EXISTS livreur (
  ID_Livreur INT PRIMARY KEY AUTO_INCREMENT,
  Nom        VARCHAR(100) NOT NULL,
  Ville      VARCHAR(100),
  Prix       DECIMAL(10,2) DEFAULT 0.00,
  tel        VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

/* 3) Crous */
"CREATE TABLE IF NOT EXISTS crous (
  CROUS_ID INT PRIMARY KEY AUTO_INCREMENT,
  Lieu     VARCHAR(150),
  Nom      VARCHAR(150) NOT NULL,
  tel      VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

/* 4) Campagne de marketing */
"CREATE TABLE IF NOT EXISTS campagne_de_marketing (
  CAMPAGNE_DE_MARKETING_ID INT PRIMARY KEY AUTO_INCREMENT,
  nom        VARCHAR(150) NOT NULL,
  email      VARCHAR(190),
  date_debut DATE,
  tel        VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

/* 5) Repas (FK -> Crous) */
"CREATE TABLE IF NOT EXISTS repas (
  Repas_ID    INT PRIMARY KEY AUTO_INCREMENT,
  nom         VARCHAR(150) NOT NULL,
  prix        DECIMAL(10,2) NOT NULL,
  Ingredients TEXT,
  Note        DECIMAL(3,1),
  categorie   VARCHAR(100),
  description TEXT,
  CROUS_ID    INT,
  CONSTRAINT fk_repas_crous
    FOREIGN KEY (CROUS_ID) REFERENCES crous(CROUS_ID)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

/* 6) Panier (FK -> Étudiant, Livreur) */
"CREATE TABLE IF NOT EXISTS panier (
  Panier_ID   INT PRIMARY KEY AUTO_INCREMENT,
  date_crea   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Status      VARCHAR(50)   NOT NULL DEFAULT 'CREATED',
  Meth_paiement VARCHAR(50),
  Montant     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  num_etu     INT           NOT NULL,
  id_livreur  INT,
  CONSTRAINT fk_panier_etudiant
    FOREIGN KEY (num_etu)   REFERENCES etudiant(num_etudiant)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_panier_livreur
    FOREIGN KEY (id_livreur) REFERENCES livreur(ID_Livreur)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

/* 7) Ajout_repas (relation Panier <-> Repas) */
"CREATE TABLE IF NOT EXISTS ajout_repas (
  Panier_ID  INT NOT NULL,
  Repas_ID   INT NOT NULL,
  Quantite   INT NOT NULL DEFAULT 1,
  Date       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Panier_ID, Repas_ID),
  CONSTRAINT fk_ajoutrepas_panier
    FOREIGN KEY (Panier_ID) REFERENCES panier(Panier_ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_ajoutrepas_repas
    FOREIGN KEY (Repas_ID)  REFERENCES repas(Repas_ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

/* 8) Promotion (relation Repas <-> Campagne) */
"CREATE TABLE IF NOT EXISTS promotion (
  REPAS_ID                   INT NOT NULL,
  CAMPAGNE_DE_MARKETING_ID   INT NOT NULL,
  PRIMARY KEY (REPAS_ID, CAMPAGNE_DE_MARKETING_ID),
  CONSTRAINT fk_promo_repas
    FOREIGN KEY (REPAS_ID) REFERENCES repas(Repas_ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_promo_campagne
    FOREIGN KEY (CAMPAGNE_DE_MARKETING_ID) REFERENCES campagne_de_marketing(CAMPAGNE_DE_MARKETING_ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];


