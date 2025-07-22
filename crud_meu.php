<?php

// CRUD = Create Read Update Delete

// Criar uma conexão à base dados
$con = mysqli_connect('127.0.0.1', 'root', '', 'biblioteca_pessoal');

// Verificar se a conexão foi concluída
if ($con) {
    echo "Conexão com a base de dados concluída!\n";
} else {
    echo "Erro na conexão com a base de dados\n";
    exit;
}

$fim = false;

while (!$fim) {
    // Menu
    echo "Escolha uma opção:\n";
    echo "1 -> Inserir um Autor\n";
    echo "2 -> Inserir um Livro\n";
    echo "3 -> Listar Autores\n";
    echo "4 -> Listar Livros\n";
    echo "5 -> Remover Livro\n";
    echo "6 -> Remover Autor\n";
    echo "7 -> Inserir Conhecido\n";
    echo "8 -> Inserir Empréstimo\n";
    echo "0 -> Sair do programa\n";

    $menu = readline("");

    switch ($menu) {
        case 0:
            echo "Adeus!\n";
            $fim = true;
            break;
        case 1:
            criarAutor($con);
            break;
        case 2:
            criarLivro($con);
            break;
        case 3:
            mostrarAutores($con, true);
            break;
        case 4:
            mostrarLivros($con, true);
            break;
        case 5:
            removerLivro($con);
            break;
        case 6:
            removerAutor($con);
            break;
        case 7:
            inserirConhecidos($con);
            break;
        case 8:
            inserirEmprestimos($con);
            break;
        default:
            echo "Opção inválida!\n";
            break;
    }
}

function criarAutor($con) {
    $nome = readline("Nome do autor: ");
    $nacionalidade = readline("Nacionalidade: ");
    $nome = limparInput($nome);
    $nacionalidade = limparInput($nacionalidade);

    $sql = "INSERT INTO autores (nome, nacionalidade) VALUES ('$nome', '$nacionalidade')";

    if (mysqli_query($con, $sql)) {
        echo "Autor inserido com sucesso\n";
    } else {
        echo "Erro ao inserir autor\n";
    }
}

function criarLivro($con) {
    $titulo = readline("Título do livro: ");
    $ano = readline("Ano: ");
    $lido_string = readline("Já leu o livro? (s/n): ");
    $titulo = limparInput($titulo);
    $ano = (int)$ano;

    $lido = ($lido_string === 's') ? 1 : 0;

    mostrarAutores($con, false);

    $ids_autores_string = readline("IDs dos autores (separado por vírgulas): ");
    $ids_autores = explode(",", $ids_autores_string);

    $sql = "INSERT INTO livros (titulo, ano, lido) VALUES ('$titulo', $ano, $lido)";

    if (mysqli_query($con, $sql)) {
        echo "Livro inserido com sucesso\n";
        $id_livro = mysqli_insert_id($con);

        foreach ($ids_autores as $id_autor) {
            $id_autor = (int)$id_autor;
            if ($id_autor > 0) {
                $sql_assoc = "INSERT INTO livros_autores (id_livro, id_autor) VALUES ($id_livro, $id_autor)";
                if (mysqli_query($con, $sql_assoc)) {
                    echo "Livro associado ao autor com sucesso\n";
                } else {
                    echo "Erro ao associar autor\n";
                }
            }
        }
    } else {
        echo "Erro ao inserir livro\n";
    }
}

function mostrarAutores($con, $voltarMenu) {
    $sql = "SELECT id, nome, nacionalidade FROM autores";
    $resultado = mysqli_query($con, $sql);

    while ($linha = mysqli_fetch_assoc($resultado)) {
        echo "ID: {$linha['id']} | Nome: {$linha['nome']} | Nacionalidade: {$linha['nacionalidade']}\n";
    }

    if ($voltarMenu) {
        voltarMenu();
    }
}

function mostrarLivros($con, $voltarMenu) {
    $sql = <<<SQL
SELECT livros.id, livros.titulo, livros.ano, livros.lido, autores.nome AS autor_nome
FROM livros
INNER JOIN livros_autores ON livros.id = livros_autores.id_livro
INNER JOIN autores ON livros_autores.id_autor = autores.id
ORDER BY livros.id
SQL;

    $resultado = mysqli_query($con, $sql);
    $livro_atual = null;
    $autores = [];

    while ($linha = mysqli_fetch_assoc($resultado)) {
        $id = $linha["id"];
        $lido_string = $linha["lido"] ? "Sim" : "Não";

        if ($livro_atual != $id) {
            if ($livro_atual != null) {
                echo " | Autores: " . implode(", ", $autores) . "\n";
            }

            echo "ID: {$linha['id']} | Título: {$linha['titulo']} | Ano: {$linha['ano']} | Lido: $lido_string";
            $livro_atual = $id;
            $autores = [];
        }

        $autores[] = $linha["autor_nome"];
    }

    if ($livro_atual != null) {
        echo " | Autores: " . implode(", ", $autores) . "\n";
    }

    if ($voltarMenu) {
        voltarMenu();
    }
}

function voltarMenu() {
    do {
        $input = readline("Selecione 0 para voltar: ");
    } while ($input != "0");
}

function removerLivro($con) {
    mostrarLivros($con, false);
    $id = (int)readline("ID do Livro a remover: ");

    $sql = "SELECT id FROM livros WHERE id = $id";
    $verificacao = mysqli_query($con, $sql);
    if (mysqli_num_rows($verificacao) == 0) {
        echo "Livro não encontrado\n";
        return;
    }

    mysqli_query($con, "DELETE FROM livros_autores WHERE id_livro = $id");
    mysqli_query($con, "DELETE FROM livros WHERE id = $id");

    echo "Livro removido\n";
}

function removerAutor($con) {
    mostrarAutores($con, false);
    $id = (int)readline("ID do autor a remover: ");

    $sql = "SELECT id FROM autores WHERE id = $id";
    $verificacao = mysqli_query($con, $sql);
    if (mysqli_num_rows($verificacao) == 0) {
        echo "Erro: Autor não encontrado\n";
        return;
    }

    $sql = <<<SQL
SELECT id_livro, id_autor 
FROM livros_autores
GROUP BY id_livro
HAVING COUNT(*) = 1
SQL;

    $resultado_livros = mysqli_query($con, $sql);
    $livros_a_apagar = [];

    while ($linha = mysqli_fetch_assoc($resultado_livros)) {
        if ($id == $linha["id_autor"]) {
            $livros_a_apagar[] = $linha["id_livro"];
        }
    }

    mysqli_query($con, "DELETE FROM livros_autores WHERE id_autor = $id");

    foreach ($livros_a_apagar as $id_livro) {
        mysqli_query($con, "DELETE FROM livros WHERE id = $id_livro");
    }

    mysqli_query($con, "DELETE FROM autores WHERE id = $id");
    echo "Sucesso: Autor removido\n";
}

function inserirConhecidos($con) {
    $nome = limparInput(readline("Nome do Conhecido: "));
    $telemovel = limparInput(readline("Número de telemóvel: "));

    $sql = "INSERT INTO conhecido (nome, telemovel) VALUES ('$nome', '$telemovel')";
    if (mysqli_query($con, $sql)) {
        echo "Conhecido inserido com sucesso\n";
    } else {
        echo "Erro ao inserir conhecido\n";
    }
}

function inserirEmprestimos($con) {
    echo "Função de empréstimos ainda não implementada\n";
}

// Função simples para limpar inputs (evita injeções básicas)
function limparInput($input) {
    return str_replace(["'", '"', ";", "--"], "", $input);
}