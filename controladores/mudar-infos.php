<?php
session_start();
include('conexao_bd.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $cpf = str_replace('-', '', str_replace('.', '', $_POST['cpf']));
    $data_nascimento = $_POST['data-nascimento'];
    $email = $_POST['email'];
    $telefone = str_replace(' ', '', str_replace(')', '', str_replace('(', '', str_replace('-', '', $_POST['telefone']))));

    // handle avatar upload if present
    $avatar_path = null;
    if (isset($_FILES['avatar']) && isset($_FILES['avatar']['tmp_name']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['avatar']['tmp_name'];
        $size = $_FILES['avatar']['size'];
        // basic mime check
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        // limit file size to 2 MB
        if (in_array($mime, $allowed) && $size <= 2 * 1024 * 1024) {
            // target directory: img/usuarios/avatares
            $target_dir = __DIR__ . '/../img/usuarios/avatares/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $uid = (int) $_SESSION['id'];
            // include timestamp in filename to avoid browser caching issues
            $timestamp = time();
            $filename = 'usuario_' . $uid . '_' . $timestamp . '.png';
            $dest = $target_dir . $filename;

            // remove any previous avatar files for this user (usuario_{id}_*.png)
            $existing = glob($target_dir . 'usuario_' . $uid . '_*.png');
            if ($existing && is_array($existing)) {
                foreach ($existing as $ex) {
                    if (is_file($ex)) {
                        @unlink($ex);
                    }
                }
            }

            // try to create image resource and convert to PNG (keeps transparency where possible)
            if (function_exists('imagecreatefromstring')) {
                $data = @file_get_contents($tmp);
                if ($data !== false) {
                    $im = @imagecreatefromstring($data);
                    if ($im !== false) {
                        // if there's an existing file, remove it
                        if (file_exists($dest)) {
                            @unlink($dest);
                        }
                        // save as PNG (quality 6)
                        if (imagepng($im, $dest, 6)) {
                            imagedestroy($im);
                            $avatar_path = 'img/usuarios/avatares/' . $filename;
                        } else {
                            imagedestroy($im);
                        }
                    }
                }
            } else {
                // GD not available on this PHP build. As a fallback accept uploaded PNG files only.
                if ($mime === 'image/png') {
                    // move uploaded PNG directly to destination
                    if (move_uploaded_file($tmp, $dest)) {
                        $avatar_path = 'img/usuarios/avatares/' . $filename;
                    }
                } else {
                    // set an alert so user knows why conversion failed
                    $_SESSION['msg_alert'] = ['danger', 'Servidor sem suporte à biblioteca GD; envie um arquivo PNG ou habilite a extensão GD no php.ini e reinicie o servidor.'];
                }
            }
        }
    }

    $sql = "UPDATE usuarios SET nome='$nome', sobrenome='$sobrenome', telefone='$telefone', cpf='$cpf', email='$email', data_nascimento='$data_nascimento'";
    if ($avatar_path) {
        $sql .= ", avatar='$avatar_path'";
    }
    $sql .= " WHERE id='$_SESSION[id]'";
    if (!mysqli_query($conexao, $sql)) {
        header('Location: ../menu/configuracoes.php');
        exit();
        echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
    }
    // update session avatar if changed
    if (!empty($avatar_path)) {
        $_SESSION['avatar'] = $avatar_path;
    }
    $_SESSION['nome'] = $nome;
    $_SESSION['sobrenome'] = $sobrenome;
    $_SESSION['cpf'] = $cpf;
    $_SESSION['data_nascimento'] = $data_nascimento;
    $_SESSION['email'] = $email;
    $_SESSION['telefone'] = $telefone;

    $_SESSION['msg_alert'] = ['success', 'Alterações feitas com sucesso!'];
    header('Location: ../menu/configuracoes.php');
    exit();
} else {
    header('Location: ../');
    exit();
}

mysqli_close($conexao);
