<?php
include('conexao_bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_veiculo'])) {
    $id_veiculo = (int)$_POST['id_veiculo'];
    
    // Update clicks count
    $sql = "UPDATE anuncios_carros SET clicks = clicks + 1 WHERE id = $id_veiculo";
    $resultado = mysqli_query($conexao, $sql);
    
    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conexao)]);
    }
    
    mysqli_close($conexao);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
