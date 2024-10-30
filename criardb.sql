CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(80) NOT NULL,
    data_nascimento DATE NOT NULL,
    sexo CHAR(1) NOT NULL,
    nome_materno VARCHAR(80) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    telefone_celular VARCHAR(15) NOT NULL,
    telefone_fixo VARCHAR(15),
    endereco_completo TEXT NOT NULL,
    login VARCHAR(6) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL
);
