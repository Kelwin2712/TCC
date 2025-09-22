<?php
session_start();
include('conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
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
        header('Location: ../index.php');
      } else {
        $_SESSION['msg_alert'] = ['danger', 'Senha incorreta!'];
        header('Location: ../sign-in.php');
        exit();
      }
      exit();
    } else {
      $_SESSION['msg_alert'] = ['danger', 'Email não encontrado!'];
      header('Location: ../sign-in.php');
      exit();
    }
  }
} else {
  header('Location: ../index.php');
  exit();
}

mysqli_close($conexao);
