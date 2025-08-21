<?php
$servername = "localhost";
$database = "fahren";
$username = "root";
$password = "";

$conexao = mysqli_connect($servername, $username, $password, $database);

if (!$conexao) {
die("Falha na Conexão: " . mysqli_connect_error());
}

mysqli_select_db($conexao, $database);
?>