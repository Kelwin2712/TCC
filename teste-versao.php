<?php
include('controladores/conexao_bd.php');

// Test the query with the exact versao from URL
$versao_test = "3.0 24V H6 GASOLINA CARRERA S PDK";
$versao_esc = mysqli_real_escape_string($conexao, $versao_test);

echo "<h2>Debug: Procurando versão</h2>";
echo "<p><strong>Versão procurada:</strong> '$versao_test'</p>";

// Query 1: Check what versions exist for Porsche 911
$sql1 = "SELECT DISTINCT carros.versao, carros.id, carros.marca, carros.modelo, marcas.nome 
         FROM anuncios_carros carros
         INNER JOIN marcas ON carros.marca = marcas.id
         WHERE carros.modelo LIKE '%911%' AND marcas.nome = 'Porsche'
         LIMIT 10";

echo "<h3>Todas as versões do Porsche 911:</h3>";
$res1 = mysqli_query($conexao, $sql1);
if ($res1) {
    while ($row = mysqli_fetch_assoc($res1)) {
        echo "<p>ID: " . $row['id'] . " | Versão: '" . $row['versao'] . "' | Modelo: " . $row['modelo'] . "</p>";
    }
}

// Query 2: Test with TRIM
$sql2 = "SELECT carros.id, carros.versao, marcas.nome, carros.modelo
         FROM anuncios_carros carros
         INNER JOIN marcas ON carros.marca = marcas.id
         WHERE carros.ativo = 'A' 
         AND marcas.nome = 'Porsche'
         AND carros.modelo LIKE '%911%'
         AND TRIM(carros.versao) = TRIM('$versao_esc')
         LIMIT 5";

echo "<h3>Com TRIM (versão procurada): '$versao_test'</h3>";
$res2 = mysqli_query($conexao, $sql2);
if ($res2) {
    $count = mysqli_num_rows($res2);
    echo "<p>Encontrados: $count carros</p>";
    while ($row = mysqli_fetch_assoc($res2)) {
        echo "<p>ID: " . $row['id'] . " | Versão: '" . $row['versao'] . "'</p>";
    }
} else {
    echo "<p style='color:red;'>Erro: " . mysqli_error($conexao) . "</p>";
}

// Query 3: Check hex values to see if there are special chars
$sql3 = "SELECT carros.id, carros.versao, HEX(carros.versao) as hex_versao
         FROM anuncios_carros carros
         INNER JOIN marcas ON carros.marca = marcas.id
         WHERE carros.modelo LIKE '%911%' AND marcas.nome = 'Porsche'
         LIMIT 3";

echo "<h3>Valores HEX (para verificar caracteres especiais):</h3>";
$res3 = mysqli_query($conexao, $sql3);
if ($res3) {
    while ($row = mysqli_fetch_assoc($res3)) {
        echo "<p>Versão: '" . $row['versao'] . "' | HEX: " . $row['hex_versao'] . "</p>";
    }
}

mysqli_close($conexao);
?>
