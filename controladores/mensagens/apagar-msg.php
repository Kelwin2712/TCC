    <?php
    session_start();
    include('../conexao_bd.php');
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $msg_ids = isset($_POST['msg_ids']) ? json_decode($_POST['msg_ids'], true) : [];
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 0;
        
        if (empty($msg_ids)) {
            echo json_encode(['success' => false, 'message' => 'Nenhuma mensagem para apagar.']);
            exit;
        }

        foreach ($msg_ids as $msg_id) {
            $msg_id = intval($msg_id);
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
                continue;
            }

            if ($tipo == 1) {
                // Apagar para todos - só permitido se a mensagem é do usuário
                if ($quem) {
                    $sql = "UPDATE mensagens_chat SET apagada_de = 1, apagada_para = 1 WHERE id = '$msg_id'";
                    $resultado = mysqli_query($conexao, $sql);
                }
            } else {
                // Apagar para si mesmo
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

        echo json_encode(['success' => true, 'message' => 'Mensagens apagadas.']);
    }

    mysqli_close($conexao);
    ?>