<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/../conexao_bd.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'message'=>'Método inválido']); exit; }
$loja_id = isset($_POST['loja_id']) ? (int)$_POST['loja_id'] : 0;
$usuario_id = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
if ($loja_id <= 0 || $usuario_id <= 0) { echo json_encode(['success'=>false,'message'=>'Dados inválidos']); exit; }
// ensure table exists
mysqli_query($conexao, "CREATE TABLE IF NOT EXISTS equipe (
	id INT AUTO_INCREMENT PRIMARY KEY,
	loja_id INT NOT NULL,
	usuario_id INT NOT NULL,
	convidado_por INT DEFAULT NULL,
	status CHAR(1) DEFAULT 'P',
	pode_editar_anuncio TINYINT(1) DEFAULT 0,
	pode_responder_mensagem TINYINT(1) DEFAULT 0,
	pode_editar_loja TINYINT(1) DEFAULT 0,
	pode_adicionar_membros TINYINT(1) DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	UNIQUE KEY(loja_id, usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
);

// delete row entirely
$sql = "DELETE FROM equipe WHERE loja_id = " . (int)$loja_id . " AND usuario_id = " . (int)$usuario_id . " LIMIT 1";
if (!mysqli_query($conexao, $sql)) { echo json_encode(['success'=>false,'message'=>'Erro ao remover membro: '.mysqli_error($conexao)]); exit; }
$affected = mysqli_affected_rows($conexao);
if ($affected <= 0) { echo json_encode(['success'=>false,'message'=>'Membro não encontrado.']); exit; }
echo json_encode(['success'=>true,'message'=>'Membro removido.']);
?>