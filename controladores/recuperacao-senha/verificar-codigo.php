<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se o email está na sessão
    if (!isset($_SESSION['recuperacao_email'])) {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'titulo' => 'Erro',
            'mensagem' => 'Sessão expirada. Tente novamente.'
        ];
        header('Location: ../../sign-in-esq-senha.php');
        exit();
    }

    // Montar o código inserido
    $codigo_inserido = $_POST['num1'] . $_POST['num2'] . $_POST['num3'] . $_POST['num4'];
    $codigo_correto = $_SESSION['recuperacao_codigo'];

    if ($codigo_inserido === $codigo_correto) {
        // Código correto, redirecionar para página de nova senha
        header('Location: ../../sign-in-nova-senha.php');
        exit();
    } else {
        // Código incorreto
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'titulo' => 'Código Inválido',
            'mensagem' => 'O código inserido está incorreto. Tente novamente.'
        ];
        header('Location: ../../sign-in-code-senha.php');
        exit();
    }
}
?>
