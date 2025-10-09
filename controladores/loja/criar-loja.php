<?php
session_start();
include('../conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $_SESSION['msg_alert'] = ['success', 'Cadastro feito com sucesso!'];

  $nome = $_POST['nome'];
  $razao_social = $_POST['razao_social'];
  $cnpj = $_POST['cnpj'];
  $inscricao_estadual = $_POST['inscricao_estadual'];
  $endereco = $_POST['endereco'];
  $numero = $_POST['numero'];
  $cep = $_POST['cep'];
  $bairro = $_POST['bairro'];
  $cidade = $_POST['cidade'];
  $estado = $_POST['estado'];
  $telefone_fixo = $_POST['telefone_fixo'];
  $whatsapp = $_POST['whatsapp'];
  $email_corporativo = $_POST['email_corporativo'];
  $site = $_POST['site'];
  $instagram = $_POST['instagram'];
  $facebook = $_POST['facebook'];
  $logo = isset($_FILES['logo']['name']) ? $_FILES['logo']['name'] : '';
  $capa = isset($_FILES['capa']['name']) ? $_FILES['capa']['name'] : '';
  $descricao_loja = $_POST['descricao_loja'];
  $hora_abre = $_POST['hora_abre'];
  $hora_fecha = $_POST['hora_fecha'];
  $dias_funcionamento = isset($_POST['dias_funcionamento']) ? implode(',', $_POST['dias_funcionamento']) : '';

  $sql = "INSERT INTO lojas (
    nome, razao_social, cnpj, inscricao_estadual, endereco, numero, cep, bairro, cidade, estado,
    telefone_fixo, whatsapp, email_corporativo, site, instagram, facebook, logo, capa, descricao_loja,
    hora_abre, hora_fecha, dias_funcionamento
  ) VALUES (
    '$nome', '$razao_social', '$cnpj', '$inscricao_estadual', '$endereco', '$numero', '$cep', '$bairro', '$cidade', '$estado',
    '$telefone_fixo', '$whatsapp', '$email_corporativo', '$site', '$instagram', '$facebook', '$logo', '$capa', '$descricao_loja',
    '$hora_abre', '$hora_fecha', '$dias_funcionamento'
  )";
  if (!mysqli_query($conexao, $sql)) {
    header('Location: ../sign-up-senha.php');
    exit();
    echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
  }
  header('Location: ../../index.php');
  exit();
} else {
  header('Location: ../../index.php');
  exit();
}

mysqli_close($conexao);
?>