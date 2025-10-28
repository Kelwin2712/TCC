<?php
session_start();
include('../conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_veiculo = $_SESSION['id_veiculo_edit'];

    $estado_local = $_POST['estado_local'] ?? 'SP';
    $cidade = $_POST['cidade'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $versao = $_POST['versao'] ?? '';
    $carroceria = $_POST['carroceria'] ?? '';
    $preco_raw = $_POST['preco'] ?? '';
    // Normalize price sent in BR format (e.g. "1.234,56" or "1234,56") to SQL decimal format ("1234.56").
    $preco = $preco_raw;
    if ($preco !== '') {
        // Remove spaces
        $preco = str_replace(' ', '', $preco);
        // If contains comma, assume BR format: remove dot thousands separators and convert comma to dot
        if (strpos($preco, ',') !== false) {
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
        } else {
            // No comma: remove any non-digit or dot, keep dot as decimal separator
            $preco = preg_replace('/[^0-9.]/', '', $preco);
        }
        // Ensure decimal part with two decimals
        if (strpos($preco, '.') === false) {
            $preco = $preco . '.00';
        } else {
            $preco = number_format((float)$preco, 2, '.', '');
        }
    } else {
        $preco = '0.00';
    }

    // Read quilometragem from POST (submitted by the edit form). The form may contain formatted text like "0 km" or "12.345 km".
    $quilometragem_raw = $_POST['quilometragem'] ?? ($_SESSION['quilometragem'] ?? '');
    // Keep only digits (store as integer kilometers). If empty, default to 0.
    $quilometragem = preg_replace('/\D/', '', $quilometragem_raw);
    if ($quilometragem === null || $quilometragem === '') {
        $quilometragem = '0';
    }
    $ano_fabricacao = $_POST['ano_fabricacao'] ?? '';
    $condicao = $_POST['condicao'] ?? '';
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
    condicao='$condicao', estado_conservacao='$conservacao', uso_anterior='$uso', aceita_troca='$aceita_troca', email='$email', telefone='$telefone', garantia='$garantia' 
        WHERE id='$id_veiculo'";

    if (!mysqli_query($conexao, $sql)) {
        header('Location: ../menu/configuracoes.php');
        exit();
        echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
    }

    $_SESSION['msg_alert'] = ['success', 'Alterações feitas com sucesso!'];
    header('Location: ../../menu/anuncios.php');
    exit();
} else {
    header('Location: ../../index.php');
    exit();
}

mysqli_close($conexao);
