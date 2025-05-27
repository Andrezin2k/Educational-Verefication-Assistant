<?php
header('Content-Type: application/json');

$diretorio = 'uploads/';
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0777, true);
}

$permitidos = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg'];

if (isset($_FILES['arquivo'])) {
    $extensao = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));

    if (in_array($extensao, $permitidos)) {
        $novoNome = uniqid() . '.' . $extensao;
        $caminho = $diretorio . $novoNome;

        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho)) {
            $arquivoJson = 'arquivos.json';
            $dados = [];

            if (file_exists($arquivoJson)) {
                $dados = json_decode(file_get_contents($arquivoJson), true);
            }

            $dados[] = [
                'nome_original' => $_FILES['arquivo']['name'],
                'nome_salvo' => $novoNome,
                'extensao' => $extensao,
                'caminho' => $caminho,
                'data' => date('Y-m-d H:i:s')
            ];

            file_put_contents($arquivoJson, json_encode($dados, JSON_PRETTY_PRINT));

            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Arquivo enviado com sucesso!']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao mover o arquivo.']);
        }
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Tipo de arquivo não permitido.']);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Nenhum arquivo enviado.']);
}
?>
