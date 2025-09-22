<?php
session_start();
include('conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $cpf = str_replace('-', '', str_replace('.', '', $_POST['cpf']));
    $data_nascimento = $_POST['data-nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sql = "UPDATE usuarios SET nome='$nome', sobrenome='$sobrenome', telefone='$telefone', cpf='$cpf', email='$email', data_nascimento='$data_nascimento' WHERE id='$_SESSION[id]'";
    if (!mysqli_query($conexao, $sql)) {
        header('Location: ../menu/configuracoes.php');
        exit();
        echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
    }
    $_SESSION['nome'] = $nome;
    $_SESSION['sobrenome'] = $sobrenome;
    $_SESSION['cpf'] = $cpf;
    $_SESSION['data_nascimento'] = $data_nascimento;
    $_SESSION['email'] = $email;
    $_SESSION['telefone'] = $telefone;

    $_SESSION['msg_alert'] = ['success', 'Alterações feitas com sucesso!'];
    header('Location: ../menu/configuracoes.php');
    exit();
} else {
    header('Location: ../index.php');
    exit();
}

mysqli_close($conexao);
