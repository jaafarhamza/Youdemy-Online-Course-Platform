SHOW DATABASES;

CREATE DATABASE Youdemy

use Youdemy

SHOW TABLES;


CREATE TABLE visiteur (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    bio TEXT,
    profile_picture_url VARCHAR(255),
    role ENUM('visiteur', 'enseignant', 'admin') NOT NULL DEFAULT 'visiteur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE categories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE cours (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    video_url VARCHAR(255),
    description VARCHAR(160),
    category_id BIGINT NOT NULL,
    featured_image VARCHAR(255),
    status ENUM('draft', 'published', 'scheduled') NOT NULL DEFAULT 'draft',
    enseignant_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cours_category FOREIGN KEY (category_id) 
        REFERENCES categories (id),
    CONSTRAINT fk_cours_enseignant FOREIGN KEY (enseignant_id)
        REFERENCES visiteur (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE cours DROP FOREIGN KEY fk_cours_enseignant;

ALTER TABLE cours ADD CONSTRAINT fk_cours_enseignant FOREIGN KEY (enseignant_id)
    REFERENCES visiteur (id) ON DELETE CASCADE;

CREATE TABLE tags (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE cours_tags (
    cours_id BIGINT UNSIGNED,
    tag_id BIGINT,
    PRIMARY KEY (cours_id, tag_id),
    CONSTRAINT fk_cours_tags_cours FOREIGN KEY (cours_id) 
        REFERENCES cours (id) ON DELETE CASCADE,
    CONSTRAINT fk_cours_tags_tag FOREIGN KEY (tag_id) 
        REFERENCES tags (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cours_etudiants (
    cours_id BIGINT UNSIGNED,
    etudiant_id BIGINT,
    PRIMARY KEY (cours_id, etudiant_id),
    CONSTRAINT fk_cours_etudiants_cours FOREIGN KEY (cours_id) 
        REFERENCES cours (id) ON DELETE CASCADE,
    CONSTRAINT fk_cours_etudiants_etudiant FOREIGN KEY (etudiant_id) 
        REFERENCES visiteur (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE cours
DROP FOREIGN KEY fk_cours_category;

ALTER TABLE cours
ADD CONSTRAINT fk_cours_category
FOREIGN KEY (category_id) REFERENCES categories(id)
ON DELETE CASCADE;


ALTER TABLE visiteur
MODIFY COLUMN role ENUM('visiteur', 'enseignant', 'admin', 'etudiant') NOT NULL DEFAULT 'visiteur';

ALTER TABLE visiteur
ADD COLUMN status ENUM('non_validated', 'validated', 'rejected') NOT NULL DEFAULT 'non_validated';

ALTER TABLE visiteur
MODIFY COLUMN status ENUM('non_validated', 'validated', 'rejected', 'banned', 'active') NOT NULL DEFAULT 'active';

ALTER TABLE visiteur
MODIFY COLUMN status ENUM('banned', 'active') NOT NULL DEFAULT 'active',
ADD COLUMN enseignant_status ENUM('non_validated', 'validated', 'rejected') NOT NULL DEFAULT 'non_validated';

ALTER TABLE cours 
MODIFY COLUMN content TEXT ;

ALTER TABLE cours_tags ADD CONSTRAINT unique_cours_tag UNIQUE (cours_id, tag_id);

SELECT * FROM categories;

SELECT 
    c.id AS course_id,
    c.title,
    c.description,
    c.content,
    c.video_url,
    c.featured_image,
    c.created_at,
    v.username AS enseignant_name,  -- Adjusted column name
    GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
FROM 
    cours c
JOIN 
    visiteur v ON c.enseignant_id = v.id
LEFT JOIN 
    cours_tags ct ON c.id = ct.cours_id
LEFT JOIN 
    tags t ON ct.tag_id = t.id
LEFT JOIN 
    cours_etudiants ce ON c.id = ce.cours_id
GROUP BY 
    c.id;


