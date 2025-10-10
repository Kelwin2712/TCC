<?php
session_start();
include('../conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $_SESSION['msg_alert'] = ['success', 'Cadastro feito com sucesso!'];
  $preco_post = isset($_POST['preco']) ? $_POST['preco'] : null;
  $preco = null;

  if ($preco_post) {
    $preco_limpo = str_replace(['R$', '.', ' ', ','], ['', '', '', '.'], $preco_post);
    $preco = number_format((float)$preco_limpo, 2, '.', '');
  }
  
  $troca = isset($_POST['troca']) ? $_POST['troca'] : null;
  $email = isset($_POST['email']) ? $_POST['email'] : null;
  $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;
  $quilometragem = preg_replace('/\D/', '', $_SESSION['quilometragem']);
  $proprietario = $_SESSION['proprietario'];
  $condicao = $_SESSION['condicao'];
  $revisao = $_SESSION['revisao'];
  $vistoria = $_SESSION['vistoria'];
  $sinistro = $_SESSION['sinistro'];
  $ipva = $_SESSION['ipva'];
  $licenciamento = $_SESSION['licenciamento'];
  $consevacao = $_SESSION['consevacao'];
  $uso_anterior = $_SESSION['uso_anterior'];
  $placa = empty($_SESSION['placa']) ? null : $_SESSION['placa'];
  $cor = $_SESSION['cor'];
  $fabr = $_SESSION['fabr'];
  $ano = $_SESSION['ano'];
  $marca = $_SESSION['marca'];
  $modelo = $_SESSION['modelo'];
  $versao = $_SESSION['versao'];

  $sql = "INSERT INTO anuncios_carros(modelo, marca, versao, ano_fabricacao, ano_modelo, placa, cor, id_vendedor, preco, condicao, quilometragem, quant_proprietario, revisao, vistoria, sinistro, ipva, licenciamento, estado_conservacao, uso_anterior, aceita_troca, email, telefone) VALUES ('$modelo', '$marca', '$versao', '$fabr', '$ano', '$placa', '$cor', '" . $_SESSION['id'] . "', '$preco', '$condicao', '$quilometragem', '$proprietario', '$revisao', '$vistoria', '$sinistro', '$ipva', '$licenciamento', '$consevacao', '$uso_anterior', '$troca', '$email', '$telefone')";
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