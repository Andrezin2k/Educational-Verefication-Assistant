<?php
header('Content-Type: application/json');

$arquivo = 'arquivos.json';

if (file_exists($arquivo)) {
    $dados = file_get_contents($arquivo);
    echo $dados;
} else {
    echo json_encode([]);
}
?>
