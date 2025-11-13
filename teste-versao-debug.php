<?php
include('controladores/conexao_bd.php');

// Simular a URL recebida
$versao_url = isset($_GET['versao_test']) ? $_GET['versao_test'] : '2.0 16V TURBO FLEX SPORT GP AUTOMÁTICO';
$versao_esc = mysqli_real_escape_string($conexao, $versao_url);

echo "<h2>Debug: Versão do Carro</h2>";
echo "<p><strong>Versão da URL (recebida):</strong> '$versao_url'</p>";
echo "<p><strong>Versão escaped:</strong> '$versao_esc'</p>";

// Query com TRIM (como está agora em compras.php)
$sql_trim = "SELECT carros.id, carros.versao, carros.modelo, marcas.nome
             FROM anuncios_carros carros
             INNER JOIN marcas ON carros.marca = marcas.id
             WHERE carros.ativo = 'A' 
             AND marcas.nome = 'BMW'
             AND carros.modelo LIKE '%320i%'
             AND TRIM(carros.versao) = TRIM('$versao_esc')
             LIMIT 5";

echo "<h3>Com TRIM (versão procurada): '$versao_url'</h3>";
echo "<p><strong>SQL:</strong> <pre>$sql_trim</pre></p>";
$res_trim = mysqli_query($conexao, $sql_trim);
if ($res_trim) {
    $count = mysqli_num_rows($res_trim);
    echo "<p style='color: " . ($count > 0 ? 'green' : 'red') . ";'><strong>Encontrados: $count carros</strong></p>";
    while ($row = mysqli_fetch_assoc($res_trim)) {
        echo "<p>ID: " . $row['id'] . " | Versão: '" . $row['versao'] . "' | Modelo: " . $row['modelo'] . "</p>";
    }
} else {
    echo "<p style='color:red;'>Erro SQL: " . mysqli_error($conexao) . "</p>";
}

// Verificar todas as versões do BMW 320i
echo "<h3>Todas as versões do BMW 320i:</h3>";
$sql_all = "SELECT DISTINCT carros.id, carros.versao, carros.condicao, carros.ativo
            FROM anuncios_carros carros
            INNER JOIN marcas ON carros.marca = marcas.id
            WHERE marcas.nome = 'BMW'
            AND carros.modelo LIKE '%320i%'
            ORDER BY carros.versao";
$res_all = mysqli_query($conexao, $sql_all);
if ($res_all) {
    while ($row = mysqli_fetch_assoc($res_all)) {
        echo "<p>ID: " . $row['id'] . " | Versão: '" . $row['versao'] . "' | Condição: " . $row['condicao'] . " | Ativo: " . $row['ativo'] . "</p>";
    }
}

// Testar a query completa como seria em compras.php
echo "<h3>Query completa como seria em compras.php:</h3>";
$id = null;
$whereParts = [];
$whereParts[] = "ativo = 'A'";

// Simular os filtros
$whereParts[] = "(marcas.nome = 'BMW' OR marcas.value = 'BMW')";
$whereParts[] = "carros.modelo LIKE '%320i%'";

// Codicao (usado,seminovo,novo)
$codes = ['U', 'S', 'N'];
$codes_esc = array_map(function($c) use ($conexao){ return mysqli_real_escape_string($conexao, $c); }, $codes);
$whereParts[] = "carros.condicao IN ('" . implode("','", $codes_esc) . "')";

// Versão
$whereParts[] = "TRIM(carros.versao) = TRIM('$versao_esc')";

$where_sql = ' WHERE ' . implode(' AND ', $whereParts);
$sql_full = "SELECT carros.*, marcas.nome AS marca_nome, IF(favoritos.id IS NULL, 0, 1) AS favoritado
FROM anuncios_carros carros
INNER JOIN marcas ON carros.marca = marcas.id
LEFT JOIN favoritos ON favoritos.anuncio_id = carros.id AND favoritos.usuario_id = '$id'
$where_sql
LIMIT 36";

echo "<p><strong>SQL Completa:</strong> <pre>" . htmlspecialchars($sql_full) . "</pre></p>";
$res_full = mysqli_query($conexao, $sql_full);
if ($res_full) {
    $count = mysqli_num_rows($res_full);
    echo "<p style='color: " . ($count > 0 ? 'green' : 'red') . ";'><strong>Encontrados: $count carros</strong></p>";
    while ($row = mysqli_fetch_assoc($res_full)) {
        echo "<p>ID: " . $row['id'] . " | Modelo: " . $row['modelo'] . " | Versão: '" . $row['versao'] . "'</p>";
    }
} else {
    echo "<p style='color:red;'>Erro SQL: " . mysqli_error($conexao) . "</p>";
}

mysqli_close($conexao);
?>
