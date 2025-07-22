
-- Tabela de receitas
CREATE TABLE receita (
    id_receita INT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    modo_preparacao TEXT,
    tempo_preparacao INT,
    numero_doses INT
);

-- Tabela de categorias
CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de ingredientes
CREATE TABLE ingrediente (
    id_ingrediente INT AUTO_INCREMENT PRIMARY KEY,
    nome_ingrediente VARCHAR(100) NOT NULL
);

-- Tabela de ligação receita_categoria
CREATE TABLE receita_categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_receita INT,
    id_categoria INT,
    FOREIGN KEY (id_receita) REFERENCES receita(id_receita),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);

-- Tabela de ligação receita_ingrediente
CREATE TABLE receita_ingrediente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_receita INT,
    id_ingrediente INT,
    quantidade INT,
    unidade_medida VARCHAR(50),
    FOREIGN KEY (id_receita) REFERENCES receita(id_receita),
    FOREIGN KEY (id_ingrediente) REFERENCES ingrediente(id_ingrediente)
);

-- Inserir receitas
INSERT INTO receita (id_receita, nome, modo_preparacao, tempo_preparacao, numero_doses) VALUES
(1, 'Ovo Mexido', 'Fritar com manteiga', 10, 1),
(2, 'Ovo Cozido', 'Cozer em água fervente', 10, 1);

-- Inserir categorias
INSERT INTO categoria (id_categoria, nome) VALUES
(1, 'Lanche'),
(2, 'Pequeno Almoço');

-- Inserir ingredientes
INSERT INTO ingrediente (id_ingrediente, nome_ingrediente) VALUES
(1, 'Ovo'),
(2, 'Manteiga'),
(3, 'Sal'),
(4, 'Água fervida');

-- Associar receitas a categorias
INSERT INTO receita_categoria (id_receita, id_categoria) VALUES
(1, 1),
(2, 2);

-- Associar receitas a ingredientes
INSERT INTO receita_ingrediente (id_receita, id_ingrediente, quantidade, unidade_medida) VALUES
(1, 1, 2, 'unidades'),
(1, 2, 1, 'unidade');


