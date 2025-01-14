SHOW DATABASES;

CREATE DATABASE Youdemy

use Youdemy


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

ALTER TABLE cours
DROP FOREIGN KEY fk_cours_category;

ALTER TABLE cours
ADD CONSTRAINT fk_cours_category
FOREIGN KEY (category_id) REFERENCES categories(id)
ON DELETE CASCADE;

