<?php
session_start();
include_once '../conexao_bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Verificar se o email existe
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email encontrado, armazenar na sessão
        $_SESSION['recuperacao_email'] = $email;
        $_SESSION['recuperacao_codigo'] = '1234'; // Código padrão
        
        header('Location: ../../sign-in-code-senha.php');
        exit();
    } else {
        // Email não encontrado
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'titulo' => 'Erro',
            'mensagem' => 'Email não encontrado no sistema.'
        ];
        header('Location: ../../sign-in-esq-senha.php');
        exit();
    }
}
?>
