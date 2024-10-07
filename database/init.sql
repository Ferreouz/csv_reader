CREATE DATABASE IF NOT EXISTS db_campaigns;
use db_campaigns;

CREATE TABLE IF NOT EXISTS campaigns (
    nome VARCHAR(200) PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS campaign_lines (
    id INT AUTO_INCREMENT,
    nome VARCHAR(200),
    sobrenome VARCHAR(200),
    email VARCHAR(100),
    endereco VARCHAR(200),
    telefone VARCHAR(11),
    cidade VARCHAR(50),
    cep VARCHAR(9),
    data_nascimento DATE,
    campaign VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_Campaign FOREIGN KEY (campaign) REFERENCES campaigns (nome) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id)
);