<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$base_dados = "recipe_app";

$con = mysqli_connect($host, $usuario, $senha, $base_dados);

if (!$con) {

    echo "Erro na ligação: " . mysqli_connect_error() . "\n";

} else {

    echo "Ligação à base de dados efetuada com sucesso!\n\n"; }

    // ===============================
    // CRIAR NOVA RECEITA
    // ===============================
    echo "Criar nova receita\n";

    $sql_insert = "INSERT INTO receita (nome, modo_preparacao, tempo_preparacao, numero_doses)
                   VALUES ('Omelete simples', 'Bater os ovos e fritar', 5, 1)";

    if (mysqli_query($con, $sql_insert)) {
        echo "Receita criada com sucesso!\n\n";
    } else {
        echo "Erro ao criar receita: " . mysqli_error($con) . "\n\n";
    }

    // ===============================
    // LISTAR TODAS AS RECEITAS
    // ===============================
    echo "Lista de receitas\n\n";

    $sql_select = "SELECT * FROM receita";
    $resultado = mysqli_query($con, $sql_select);

    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "ID: {$linha['id_receita']}\n";
            echo "Nome: {$linha['nome']}\n";
            echo "Modo: {$linha['modo_preparacao']}\n";
            echo "Tempo: {$linha['tempo_preparacao']} min\n";
            echo "Doses: {$linha['numero_doses']}\n\n";
        }
    } else {
        echo "Nenhuma receita encontrada\n\n";
    }

    // ===============================
    // ATUALIZAR RECEITA EXISTENTE
    // ===============================
    echo "Atualizar receita com ID 1\n";

    $sql_update = "UPDATE receita SET nome = 'Omelete com queijo', tempo_preparacao = 7 WHERE id_receita = 1";

    if (mysqli_query($con, $sql_update)) {
        echo "Receita atualizada com sucesso!\n\n";
    } else {
        echo "Erro ao atualizar receita: " . mysqli_error($con) . "\n\n";
    }

    // ===============================
    // APAGAR RECEITA
    // ===============================
    echo "Apagar receita com ID 2\n";

    $sql_delete = "DELETE FROM receita WHERE id_receita = 2";

    if (mysqli_query($con, $sql_delete)) {
        echo "Receita apagada com sucesso!\n\n";
    } else {
        echo "Erro ao apagar receita: " . mysqli_error($con) . "\n\n";
    }

    mysqli_close($con);
}

if (!$con) {
    echo "Erro na ligação: " . mysqli_connect_error() . "\n";
    exit;
}
echo "Ligação estabelecida com sucesso!\n\n";

// =========================================
// LISTAR TODOS OS INGREDIENTES
// =========================================
echo "Ingredientes cadastrados:\n";

$sql = "SELECT * FROM ingrediente";
$resultado = mysqli_query($con, $sql);

while ($linha = mysqli_fetch_assoc($resultado)) {
    echo "ID: {$linha['id_ingrediente']} - Nome: {$linha['nome_ingrediente']}\n";
}
echo "\n";

// =========================================
// ASSOCIAR INGREDIENTE A UMA RECEITA
// =========================================
echo "Associar ingrediente 'Sal' à receita 1\n";

$sql = "INSERT INTO receita_ingrediente (id_receita, id_ingrediente, quantidade, unidade_medida)
        VALUES (1, 3, 1, 'pitada')";

if (mysqli_query($con, $sql)) {
    echo "Ingrediente associado com sucesso.\n\n";
} else {
    echo "Erro: " . mysqli_error($con) . "\n\n";
}

// =========================================
// ATUALIZAR QUANTIDADE/UNIDADE DE INGREDIENTE
// =========================================
echo "Atualizar quantidade do ingrediente 2 da receita 1\n";

$sql = "UPDATE receita_ingrediente
        SET quantidade = 2, unidade_medida = 'colheres'
        WHERE id_receita = 1 AND id_ingrediente = 2";

if (mysqli_query($con, $sql)) {
    echo "Quantidade atualizada com sucesso.\n\n";
} else {
    echo "Erro ao atualizar: " . mysqli_error($con) . "\n\n";
}

// =========================================
// REMOVER INGREDIENTE DE UMA RECEITA
// =========================================
echo "Remover ingrediente 3 (Sal) da receita 1\n";

$sql = "DELETE FROM receita_ingrediente
        WHERE id_receita = 1 AND id_ingrediente = 3";

if (mysqli_query($con, $sql)) {
    echo "Ingrediente removido da receita com sucesso.\n\n";
} else {
    echo "Erro ao remover ingrediente: " . mysqli_error($con) . "\n\n";
}

// =========================================
// MOSTRAR DETALHES COMPLETOS DA RECEITA
// =========================================
echo "Detalhes da receita 1:\n";

$sql = "SELECT receita.nome AS receita, receita.modo_preparacao, receita.tempo_preparacao, receita.numero_doses,
               ingrediente.nome_ingrediente, receita_ingrediente.quantidade, receita_ingrediente.unidade_medida
        FROM receita
        INNER JOIN receita_ingrediente ON receita.id_receita = receita_ingrediente.id_receita
        INNER JOIN ingrediente ON receita_ingrediente.id_ingrediente = ingrediente.id_ingrediente
        WHERE receita.id_receita = 1";

$resultado = mysqli_query($con, $sql);

$primeira_linha = true;
while ($linha = mysqli_fetch_assoc($resultado)) {
    if ($primeira_linha) {
        echo "Nome: {$linha['receita']}\n";
        echo "Modo de preparo: {$linha['modo_preparacao']}\n";
        echo "Tempo: {$linha['tempo_preparacao']} min\n";
        echo "Doses: {$linha['numero_doses']}\n";
        echo "Ingredientes:\n";
        $primeira_linha = false;
    }
    echo "- {$linha['quantidade']} {$linha['unidade_medida']} de {$linha['nome_ingrediente']}\n";
}

mysqli_close($con);

?>

