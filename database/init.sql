CREATE TABLE IF NOT EXISTS lines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) ,
    sobrenome VARCHAR(200) ,
    email VARCHAR(100) ,
    endereco VARCHAR(200) ,
    telefone VARCHAR(11) ,
    whatsapp VARCHAR(13) ,
    cidade VARCHAR(50) ,
    cep CHAR(9) ,
    data_nascimento DATE ,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
