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

// ============================
// CRIAR NOVA CATEGORIA
// ============================

echo "\n============================\n";
echo "CRIAR NOVA CATEGORIA\n";
echo "============================\n";

$sql_nova_categoria = "INSERT INTO categoria (nome) VALUES ('Sobremesa')";
if (mysqli_query($con, $sql_nova_categoria)) {
    echo "Categoria 'Sobremesa' criada com sucesso!\n\n";
} else {
    echo "Erro ao criar categoria: " . mysqli_error($con) . "\n\n";
}


// ============================
// LISTAR CATEGORIAS EXISTENTES
// ============================

echo "\n============================\n";
echo "LISTAR CATEGORIAS EXISTENTES\n";
echo "============================\n";

$sql_categorias = "SELECT * FROM categoria";
$resultado_cat = mysqli_query($con, $sql_categorias);

if (mysqli_num_rows($resultado_cat) > 0) {
    while ($cat = mysqli_fetch_assoc($resultado_cat)) {
        echo "ID: {$cat['id_categoria']} - Nome: {$cat['nome']}\n";
    }
    echo "\n";
} else {
    echo "Nenhuma categoria encontrada.\n\n";
}


// ============================
// ASSOCIAR RECEITA A CATEGORIA
// ============================

echo "\n============================\n";
echo "ASSOCIAR RECEITA ID 1 À CATEGORIA ID 2\n";
echo "============================\n";

$sql_associar = "INSERT IGNORE INTO receita_categoria (id_receita, id_categoria) VALUES (1, 2)";
if (mysqli_query($con, $sql_associar)) {
    echo "Associação realizada com sucesso!\n\n";
} else {
    echo "Erro ao associar: " . mysqli_error($con) . "\n\n";
}


// ============================
// DESASSOCIAR RECEITA DE CATEGORIA
// ============================

echo "\n============================\n";
echo "DESASSOCIAR RECEITA ID 1 DA CATEGORIA ID 2\n";
echo "============================\n";

$sql_desassociar = "DELETE FROM receita_categoria WHERE id_receita = 1 AND id_categoria = 2";
if (mysqli_query($con, $sql_desassociar)) {
    echo "Desassociação realizada com sucesso!\n\n";
} else {
    echo "Erro ao desassociar: " . mysqli_error($con) . "\n\n";
}


// ============================
// LISTAR RECEITAS POR CATEGORIA
// ============================

echo "\n============================\n";
echo "RECEITAS DA CATEGORIA 'Pequeno Almoço'\n";
echo "============================\n";

$sql_receitas_por_cat = "
    SELECT r.id_receita, r.nome, r.modo_preparacao, r.tempo_preparacao, r.numero_doses
    FROM receita r
    JOIN receita_categoria rc ON r.id_receita = rc.id_receita
    JOIN categoria c ON c.id_categoria = rc.id_categoria
    WHERE c.nome = 'Pequeno Almoço'
";

$resultado_receitas = mysqli_query($con, $sql_receitas_por_cat);

if (mysqli_num_rows($resultado_receitas) > 0) {
    while ($r = mysqli_fetch_assoc($resultado_receitas)) {
        echo "ID: {$r['id_receita']} - Nome: {$r['nome']} - Tempo: {$r['tempo_preparacao']}min - Doses: {$r['numero_doses']}\n";
    }
    echo "\n";
} else {
    echo "Nenhuma receita encontrada para a categoria.\n\n";
}

