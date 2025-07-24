<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$base_dados = "recipe_app";

$con = mysqli_connect($host, $usuario, $senha, $base_dados);

if (!$con) {
    echo "Erro na ligação: " . mysqli_connect_error() . "\n";
} else {
    echo "Ligação à base de dados efetuada com sucesso!\n\n";

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
?>
