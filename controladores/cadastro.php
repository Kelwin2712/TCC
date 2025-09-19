<?php
session_start();
include('conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $_SESSION['senha'] = $_POST['senha'];
  date_default_timezone_set('America/Sao_Paulo');
  $data_criacao_conta = date('Y-m-d H:i:s');
  $_SESSION['msg_alert'] = ['success', 'Cadastro feito com sucesso!'];
  $nome = $_SESSION['nome'];
  $sobrenome = $_SESSION['sobrenome'];
  $email = $_SESSION['email'];
  $senha = $_SESSION['senha'];
  $sql = "INSERT INTO usuarios(nome, sobrenome, senha, email, data_criacao_conta) VALUES ('$nome', '$sobrenome', '$senha', '$email', '$data_criacao_conta')";
  if (!mysqli_query($conexao, $sql)) {
    header('Location: ../sign-up-senha.php');
    exit();
    echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
  }
  unset($_SESSION['nome']);
  header('Location: ../sign-in.php');
  exit();
} else {
  header('Location: ../index.php');
  exit();
}

mysqli_close($conexao);
