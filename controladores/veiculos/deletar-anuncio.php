<?php
session_start();
include('../conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];

    $sql = "SELECT * FROM anuncios_carros WHERE id = '$id'";
    $resultado = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $linha = mysqli_fetch_array($resultado);
        $sql = "DELETE FROM anuncios_carros WHERE id = '$id'";
        $resultado = mysqli_query($conexao, $sql);
        $_SESSION['msg_alert'] = ['success', 'Anúncio deletado com sucesso!'];
        header('Location: ../../menu/anuncios.php');
        exit();
    } else {
        $_SESSION['msg_alert'] = ['danger', 'Anúncio não encontrado!'];
        header('Location: ../../menu/anuncios.php');
        exit();
    }
} else {
    $_SESSION['msg_alert'] = ['danger', 'Usuário não encontrado!'];
    header('Location: ../../menu/anuncios.php');
    exit();
}

mysqli_close($conexao);
