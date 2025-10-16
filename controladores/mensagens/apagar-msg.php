    <?php
    session_start();
    include('../conexao_bd.php');
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $msg_id = $_POST['msg_id'];
        $tipo = $_POST['tipo'];
        $quem = false;
        $real = false;

        $sql = "SELECT * FROM mensagens_chat WHERE id = '$msg_id'";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            $linha = mysqli_fetch_assoc($resultado);
            if ($linha['apagada_de'] == 1 || $linha['apagada_para'] == 1) {
                $real = true;
            }
            if ($linha['de_usuario'] == $_SESSION['id']) {
                $quem = true;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Mensagem não encontrada.']);
            exit;
        }


        if ($tipo == 1) {
            $sql = "UPDATE mensagens_chat SET apagada_de = 1, apagada_para = 1 WHERE id = '$msg_id'";
            $resultado = mysqli_query($conexao, $sql);
        } else {
            if ($real) {
                if ($quem) {
                    $sql = "UPDATE mensagens_chat SET double_apagada_de = 1 WHERE id = '$msg_id'";
                } else {
                    $sql = "UPDATE mensagens_chat SET double_apagada_para = 1 WHERE id = '$msg_id'";
                }
                $resultado = mysqli_query($conexao, $sql);
            } else {
                if ($quem) {
                    $sql = "UPDATE mensagens_chat SET apagada_de = 1 WHERE id = '$msg_id'";
                } else {
                    $sql = "UPDATE mensagens_chat SET apagada_para = 1 WHERE id = '$msg_id'";
                }
                $resultado = mysqli_query($conexao, $sql);
            }
        }
    }

    mysqli_close($conexao);
    ?>