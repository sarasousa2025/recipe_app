<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$base_dados = "recipe_app";

// Criar ligação
$con = mysqli_connect($host, $usuario, $senha, $base_dados);

// Verificar ligação
if (!$con) {
    echo "Erro na ligação: " ;
} else {
    echo "Ligação à base de dados efetuada com sucesso!";
}
