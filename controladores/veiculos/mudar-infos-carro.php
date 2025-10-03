<?php
session_start();
include('../conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_veiculo = $_SESSION['id_veiculo_edit'];

    $estado_local = $_POST['estado_local'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $versao = $_POST['versao'] ?? '';
    $carroceria = $_POST['carroceria'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $quilometragem = preg_replace('/\D/', '', $_SESSION['quilometragem']) ?? '';
    $ano_fabricacao = $_POST['ano_fabricacao'] ?? '';
    $ano_modelo = $_POST['ano_modelo'] ?? '';
    $propulsao = $_POST['propulsao'] ?? '';
    $combustivel = $_POST['combustivel'] ?? '';
    $blindagem = $_POST['blindagem'] ?? '';
    $portas_qtd = $_POST['portas_qtd'] ?? '';
    $assentos_qtd = $_POST['assentos_qtd'] ?? '';
    $placa = $_POST['placa'] ?? '';
    $cor = $_POST['cor'] ?? '';
    $proprietario = $_POST['proprietario'] ?? '';
    $revisao = $_POST['revisao'] ?? '';
    $vistoria = $_POST['vistoria'] ?? '';
    $sinistro = $_POST['sinistro'] ?? '';
    $ipva = $_POST['ipva'] ?? '';
    $licenciamento = $_POST['licenciamento'] ?? '';
    $conservacao = $_POST['conservacao'] ?? '';
    $uso = $_POST['uso'] ?? '';
    $aceita_troca = $_POST['troca'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $garantia = $_POST['garantia'] ?? '';

    $sql = "UPDATE anuncios_carros SET 
        estado_local='$estado_local', cidade='$cidade', marca='$marca', modelo='$modelo', versao='$versao', carroceria='$carroceria', preco='$preco', quilometragem='$quilometragem', 
        ano_fabricacao='$ano_fabricacao', ano_modelo='$ano_modelo', propulsao='$propulsao', combustivel='$combustivel', blindagem='$blindagem', portas_qtd='$portas_qtd', assentos_qtd='$assentos_qtd', 
        placa='$placa', cor='$cor', quant_proprietario='$proprietario', revisao='$revisao', vistoria='$vistoria', sinistro='$sinistro', ipva='$ipva', licenciamento='$licenciamento', 
        estado_conservacao='$conservacao', uso_anterior='$uso', aceita_troca='$aceita_troca', email='$email', telefone='$telefone', garantia='$garantia' 
        WHERE id='$id_veiculo'";

    if (!mysqli_query($conexao, $sql)) {
        header('Location: ../menu/configuracoes.php');
        exit();
        echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
    }

    $_SESSION['msg_alert'] = ['success', 'Alterações feitas com sucesso!'];
    header('Location: ../../menu/editar-anuncio.php?id='.$id_veiculo.'&teste='.$assentos_qtd);
    exit();
} else {
    header('Location: ../../index.php');
    exit();
}

mysqli_close($conexao);
