<?php
session_start();
include('../conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $anuncio = $_POST['anuncio'];

    $sql = "SELECT * FROM favoritos WHERE usuario_id = '$usuario' AND anuncio_id = '$anuncio'";
    $resultado = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $favorito = mysqli_fetch_array($resultado);
        $fav_id = $favorito['id'];
        $sql = "DELETE FROM favoritos WHERE id = '$fav_id'";
        $resultado = mysqli_query($conexao, $sql);
        echo 'desfav';
    } else {
        $linha = mysqli_fetch_array($resultado);
        $sql = "INSERT INTO favoritos (usuario_id, anuncio_id) VALUES ('$usuario', '$anuncio')";
        if (!mysqli_query($conexao, $sql)) {
            header('Location: ../sign-up-senha.php');
            exit();
            echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
        }
        echo 'fav';
    }
}


mysqli_close($conexao);
