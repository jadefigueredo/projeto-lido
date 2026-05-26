CREATE DATABASE lido CHARACTER SET utf8mb4;

USE lido;

CREATE TABLE usuarios (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE livros (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo     VARCHAR(200) NOT NULL,
    autor      VARCHAR(150),
    nota       TINYINT CHECK (nota BETWEEN 1 AND 5),
    opiniao    TEXT,
    lido_em    DATE,
    criado_em  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- vinculadas a um livro
CREATE TABLE citacoes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    livro_id   INT NOT NULL,
    usuario_id INT NOT NULL,
    texto      TEXT NOT NULL,
    favorita   BOOLEAN DEFAULT FALSE,
    criado_em  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (livro_id)   REFERENCES livros(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);