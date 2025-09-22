<?php
session_start();
include('conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['senha'])) {
        $id = $_SESSION['id'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM usuarios WHERE id = '$id'";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            $linha = mysqli_fetch_array($resultado);
            if ($linha['senha'] == $senha) {
                $sql = "DELETE FROM usuarios WHERE id = '$id'";
                $resultado = mysqli_query($conexao, $sql);
                $_SESSION['msg_alert'] = ['success', 'Conta deletada com sucesso!'];
                header('Location: logout.php');
            } else {
                $_SESSION['msg_alert'] = ['danger', 'Senha incorreta!'];
                header('Location: ../menu/configuracoes.php');
                exit();
            }
            exit();
        } else {
            $_SESSION['msg_alert'] = ['danger', 'Usuário não encontrado!'];
            header('Location: ../menu/configuracoes.php');
            exit();
        }
    }
} else {
    $_SESSION['msg_alert'] = ['danger', 'Usuário não encontrado!'];
    header('Location: logout.php');
    exit();
}

mysqli_close($conexao);
?>