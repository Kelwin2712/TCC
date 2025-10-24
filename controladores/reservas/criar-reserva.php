<?php
session_start();
include('../conexao_bd.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Mensagem padrão (alterada conforme resultado)
    $_SESSION['msg_alert'] = ['success', 'Reserva criada com sucesso!'];

    // Aceita diferentes nomes de campo para o id do veículo (compatibilidade)
    $id_veiculo = isset($_POST['id_veiculo']) ? $_POST['id_veiculo'] : (isset($_POST['anuncio']) ? $_POST['anuncio'] : null);

    // Campos do visitante
    $nome = isset($_POST['nome']) ? mysqli_real_escape_string($conexao, trim($_POST['nome'])) : '';
    
    $telefone_input = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $telefone_clean = preg_replace('/\D+/', '', $telefone_input);
    $telefone = $telefone_clean !== '' ? mysqli_real_escape_string($conexao, trim($telefone_clean)) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conexao, trim($_POST['email'])) : '';
    $preferencia_contato = isset($_POST['preferencia_contato']) ? mysqli_real_escape_string($conexao, trim($_POST['preferencia_contato'])) : 'telefone';

    // Data/hora
    $data = isset($_POST['data']) ? mysqli_real_escape_string($conexao, trim($_POST['data'])) : '';
    $hora = isset($_POST['hora']) ? mysqli_real_escape_string($conexao, trim($_POST['hora'])) : '';
    $acompanhantes_qtd = isset($_POST['acompanhantes_qtd']) ? (int) $_POST['acompanhantes_qtd'] : 0;

    // Endereço do visitante
    $estado = isset($_POST['estado']) ? mysqli_real_escape_string($conexao, trim($_POST['estado'])) : '';
    $cidade = isset($_POST['cidade']) ? mysqli_real_escape_string($conexao, trim($_POST['cidade'])) : '';
    $bairro = isset($_POST['bairro']) ? mysqli_real_escape_string($conexao, trim($_POST['bairro'])) : '';
    $rua = isset($_POST['rua']) ? mysqli_real_escape_string($conexao, trim($_POST['rua'])) : '';
    $numero = isset($_POST['numero']) ? mysqli_real_escape_string($conexao, trim($_POST['numero'])) : '';
    $complemento = isset($_POST['complemento']) ? mysqli_real_escape_string($conexao, trim($_POST['complemento'])) : '';
    $cep = isset($_POST['cep']) ? mysqli_real_escape_string($conexao, trim($_POST['cep'])) : '';

    // Observações
    $observacoes = isset($_POST['observacoes']) ? mysqli_real_escape_string($conexao, trim($_POST['observacoes'])) : '';

    // Validação mínima
    $redirect_base = '../../menu/editar-anuncio.php';
    if ($id_veiculo === null || $id_veiculo === '') {
        $_SESSION['msg_alert'] = ['danger', 'ID do veículo não informado.'];
        header('Location: ' . $redirect_base . (isset($_POST['anuncio']) ? '?id=' . urlencode($_POST['anuncio']) : ''));
        exit();
    }

    if ($nome === '' || $telefone === '' || $data === '' || $hora === '') {
        $_SESSION['msg_alert'] = ['danger', 'Preencha os campos obrigatórios: nome, telefone, data e hora.'];
        header('Location: ' . $redirect_base . '?id=' . urlencode($id_veiculo));
        exit();
    }

    // Garantir tipos/formatos simples (opcional)
    $id_veiculo_int = (int) $id_veiculo;
    $acompanhantes_qtd = (int) $acompanhantes_qtd;

    // Monta SQL
    $sql = "INSERT INTO reservas (
        id_veiculo, nome, telefone, email, preferencia_contato,
        data, hora, acompanhantes_qtd,
        estado, cidade, bairro, rua, numero, complemento, cep,
        observacoes
    ) VALUES (
        '$id_veiculo_int', '$nome', '$telefone', '$email', '$preferencia_contato',
        '$data', '$hora', '$acompanhantes_qtd',
        '$estado', '$cidade', '$bairro', '$rua', '$numero', '$complemento', '$cep',
        '$observacoes'
    )";

    if (!mysqli_query($conexao, $sql)) {
        // Falha
        $_SESSION['msg_alert'] = ['danger', 'Erro ao criar reserva: ' . mysqli_error($conexao)];
        header('Location: ' . $redirect_base . '?id=' . urlencode($id_veiculo_int));
        exit();
    }

    // Sucesso: redirecionar de volta para a página do anúncio
    $_SESSION['msg_alert'] = ['success', 'Reserva criada com sucesso!'];
    header('Location: ' . $redirect_base . '?id=' . urlencode($id_veiculo_int));
    exit();
} else {
    // Acesso direto por GET -> redireciona para home
    header('Location: ../../index.php');
    exit();
}

mysqli_close($conexao);
?>