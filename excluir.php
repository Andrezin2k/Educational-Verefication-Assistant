<?php
header('Content-Type: application/json');

if (isset($_POST['nome_salvo'])) {
    $arquivoJson = 'arquivos.json';
    $dados = [];

    if (file_exists($arquivoJson)) {
        $dados = json_decode(file_get_contents($arquivoJson), true);
    }

    $novoDados = [];
    foreach ($dados as $item) {
        if ($item['nome_salvo'] === $_POST['nome_salvo']) {
            if (file_exists($item['caminho'])) {
                unlink($item['caminho']);
            }
        } else {
            $novoDados[] = $item;
        }
    }

    file_put_contents($arquivoJson, json_encode($novoDados, JSON_PRETTY_PRINT));

    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Arquivo excluído com sucesso.']);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Parâmetro nome_salvo ausente.']);
}
?>
