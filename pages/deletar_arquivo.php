<?php
include_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os valores enviados pela requisição AJAX
    $nota_id = $_POST['nota_id'];
    $user_id = $_POST['user_id'];
    $file_name = $_POST['file_name'];

    // Caminho do diretório de uploads
    $file_path = __DIR__ . '/uploads/' . $file_name;

    // Verifica se o arquivo existe e exclui
    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            // Atualiza o registro da nota no banco para remover o nome do arquivo
            $sql = "UPDATE nota SET arquivo = NULL WHERE id = ? AND id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $nota_id, $user_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Arquivo excluído com sucesso.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o registro no banco de dados.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir o arquivo.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Arquivo não encontrado.']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
