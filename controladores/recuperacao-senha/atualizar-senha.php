<?php
session_start();
include_once '../conexao_bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se o email está na sessão
    if (!isset($_SESSION['recuperacao_email'])) {
        $_SESSION['msg_alert'] = ['danger', 'Sessão expirada. Tente novamente.'];
        header('Location: ../../sign-in-esq-senha.php');
        exit();
    }

    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // Validar senhas
    if (strlen($senha) < 8) {
        $_SESSION['msg_alert'] = ['danger', 'A senha deve ter pelo menos 8 caracteres.'];
        header('Location: ../../sign-in-nova-senha.php');
        exit();
    }

    if (!preg_match('/\d/', $senha)) {
        $_SESSION['msg_alert'] = ['danger', 'A senha deve conter pelo menos um número.'];
        header('Location: ../../sign-in-nova-senha.php');
        exit();
    }

    if (!preg_match('/[A-Z]/', $senha)) {
        $_SESSION['msg_alert'] = ['danger', 'A senha deve conter pelo menos uma letra maiúscula.'];
        header('Location: ../../sign-in-nova-senha.php');
        exit();
    }

    if ($senha !== $confirma_senha) {
        $_SESSION['msg_alert'] = ['danger', 'As senhas não conferem.'];
        header('Location: ../../sign-in-nova-senha.php');
        exit();
    }

    $email = $_SESSION['recuperacao_email'];

    // Atualizar senha no banco
    $sql = "UPDATE usuarios SET senha = ? WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $senha, $email);

    if ($stmt->execute()) {
        // Limpar sessão de recuperação
        unset($_SESSION['recuperacao_email']);
        unset($_SESSION['recuperacao_codigo']);

        $_SESSION['msg_alert'] = ['success', 'Sua senha foi alterada com sucesso. Faça login com sua nova senha.'];
        header('Location: ../../sign-in.php');
        exit();
    } else {
        $_SESSION['msg_alert'] = ['danger', 'Erro ao atualizar a senha. Tente novamente.'];
        header('Location: ../../sign-in-nova-senha.php');
        exit();
    }
}
?>
