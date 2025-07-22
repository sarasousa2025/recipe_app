<?php
 
// Criar uma conexão à base dados
$con = mysqli_connect('127.0.0.1', 'root', '', 'biblioteca_pessoal');
 
//verificar se a conexão foi concluida
if ($con){
    echo "Conexão com a base de dados concluída!\n";
} else {
    echo "Erro na conexão com a base de dados\n";
}
 
//menu
echo "escolha uma opção:\n";
echo "Inserir um Autor -> 0\n";
echo "Inserir um Livro -> 1\n";
 
//Receber seleção das opções do menu.
$menu = readline("");
 
 
switch ($menu) {
    case 0:
        criarAutor($con);
        break;
 
    case 1:
        criarLivro($con);
        break;
    default:
        echo "Opção inválida!";
        exit;
}
 
function criarAutor($con) {
    $nome = readline("Nome do autor: ");
    $nacionalidade = readline("Nacionalidade: ");
 
    // Criar comando SQL
    $sql = "INSERT INTO autores (nome, nacionalidade) VALUES ('$nome', '$nacionalidade')";
 
    //Executar o comando SQL
    if (mysqli_query($con, $sql)){
        echo "Autor inserido com sucesso\n";
    } else {
        echo "Erro a inserir autor\n";
    }
}
 
function criarLivro($con) {
    //Inserir atributos dos livros
    $titulo = readline("Título do livro: ");
    $ano = readline("Ano: ");
    $lido_string = readline("Já leu o livro? (s/n): ");
    if ($lido_string == "s"){
        $lido = 1;
    } else if ($lido_string == "n"){
        $lido = 0;
    } else {
        echo "Input invalido\n";
        exit;
    }
 
    //inserir os atributos dos autores
    $ids_autores_string = readline("IDs dos autores (separado por vígulas): "); // "1,2,3"
    $ids_autores = explode(",", $ids_autores_string); // "1,2,3" -> [1, 2, 3]
 
    //Comando SQL para inserir o livro
    $sql = "INSERT INTO livros (titulo, ano, lido) VALUES ('$titulo', $ano, $lido)";
 
    //Executar o comando SQL para inserir o livro
    if (mysqli_query($con, $sql)){
        echo "Livro inserido com sucesso\n";
 
        //guardar id do livro inserido
        $id_livro = mysqli_insert_id($con);
        
        //Associar os autores aos livros
        foreach ($ids_autores as $id_autor){
            if ($id_autor > 0){
                $sql_assoc = "INSERT INTO livros_autores (id_livro, id_autor) VALUES ($id_livro, $id_autor)";
                if (mysqli_query($con, $sql_assoc)){
                    echo "Livro associado ao autor com sucesso\n";
                } else {
                    echo "Erro a associar autor\n";
                }
            }
        }
 
    } else {
        echo "Erro a inserir livro\n";
    }
}
 
//fechar conexão
mysqli_close($con);

 //remover autores
function removerAutor($con) {
    mostrarAutores($con);
$id = readline("ID do Autor a remover: ");

$sql = "SELECT id FROM autores WHERE id = $id";
$verificacao = mysqli_query($con, $sql);

if(mysqli_num_rows($verificacao) == 0) {
    echo "Autor não encontrado\n";
    return;
}

//remover associacao autor aos seus livros
$sql = DELETE FROM livros_autores WHERE id_autor = $id";
mysqli_query($con, $sql);

$sql = DELETE FROM autores WHERE id = $id";
mysqli_query($con, "sql);
echo "Autor removido com sucesso\n";

}