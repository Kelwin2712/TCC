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
        $_SESSION['email'] = $linha['email'];

        $_SESSION['msg_alert'] = ['success', 'Login realizado com sucesso!'];
        header('Location: ../index.php');
      } else {
        $_SESSION['msg_alert'] = ['danger', 'Senha incorreta!'];
        header('Location: ../sign-in.php');
        exit();
      }
      exit();
    } else {
      $_SESSION['msg_alert'] = ['danger', 'Email incorreto!'];
      header('Location: ../sign-in.php');
      exit();
    }
  } else {
    $_SESSION['senha'] = $_POST['senha'];
    $data_criacao_conta = date('Y-m-d H:i:s');

    $nome = $_SESSION['nome'];
    $email = $_SESSION['email'];
    $senha = $_SESSION['senha'];
    $sql = "INSERT INTO usuarios(nome, senha, email, data_criacao_conta) VALUES ('$nome', '$senha', '$email', '$data_criacao_conta')";
    if (!mysqli_query($conexao, $sql)) {
      echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
    }
  }
} else {
  header('Location: ../index.php');
  exit();
}

mysqli_close($conexao);
