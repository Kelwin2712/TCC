<?php
session_start();
include('../conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $ativo = $_POST['ativo'] == true ? 'A' : 'D';
    $anuncio = $_POST['anuncio'];

    $sql = "UPDATE anuncios_carros SET 
        ativo='$ativo'
        WHERE id='$anuncio'";

    if (!mysqli_query($conexao, $sql)) {
        echo $ativo;
    }
}

mysqli_close($conexao);
?>