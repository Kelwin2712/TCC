    <?php
    session_start();
    include('../conexao_bd.php');
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $de = $_POST['de'];
        $para = $_POST['para'];
        $anuncio = $_POST['anuncio'];
        $texto = $_POST['texto'];


        $sql = "SELECT id_vendedor FROM anuncios_carros WHERE id = '$anuncio'";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            $linha = mysqli_fetch_assoc($resultado);
            $vendedor_id = $linha['id_vendedor'];

            if ($de == $vendedor_id) {
                $comprador_id = $para;
            } else {
                $comprador_id = $de;
            }

            $sql = "INSERT INTO conversas (comprador_id, vendedor_id, anuncio_id, ultima_mensagem) VALUES ('$comprador_id', '$vendedor_id', '$anuncio', '$texto') ON DUPLICATE KEY UPDATE ultima_mensagem = VALUES(ultima_mensagem),data_ultima_mensagem = VALUES(data_ultima_mensagem)";
            $resultado = mysqli_query($conexao, $sql);

            $sql = "INSERT INTO mensagens_chat(de_usuario, para_usuario, anuncio, texto) VALUES ('$de', '$para', '$anuncio', '$texto')";
            if (!mysqli_query($conexao, $sql)) {
                echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
            } else {
                echo true;
            }
        }
    }

    mysqli_close($conexao);
    ?>