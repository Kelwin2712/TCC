<?php
session_start();
include('conexao_bd.php');
$sql = "SELECT * FROM carros";
$resultado = mysqli_query($conexao, $sql);

if (mysqli_num_rows($resultado) > 0) {
    $linha = mysqli_fetch_array($resultado);
    if ($linha['senha'] == $senha) {
        $_SESSION['id'] = $linha['id'];
        $_SESSION['nome'] = $linha['nome'];
        $_SESSION['sobrenome'] = $linha['sobrenome'];
        $_SESSION['email'] = $linha['email'];
        $_SESSION['data_nascimento'] = $linha['data_nascimento'];

        $_SESSION['msg_alert'] = ['success', 'Login realizado com sucesso!'];
        header('Location: ../../compras.php');
        exit();
    }
} else {
    $_SESSION['msg_alert'] = ['danger', 'Não foi possível carregar os veículos!'];
    header('Location: ../../index.php');
    exit();
}

mysqli_close($conexao);
