<?php
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'conexao.php';

// Obtém os dados do formulário
$id_usuario = $_SESSION['idUsuario'];
$titulo = $_POST['txttitulo'];
$subtitulo = $_POST['txtsubtitulo'];
$conteudo = $_POST['txtconteudo'];
$cor = $_POST['cor_selecionada'];
$id_categoria = $_POST['id_categoria']; // Adiciona a categoria

// Variável para armazenar o nome do arquivo (caso um arquivo seja enviado)
$arquivo = null; 

// Verifica se um arquivo foi enviado e se não há erros
if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    // Caminho do diretório de uploads
    $diretorioDestino = __DIR__ . '/uploads/'; 
    
    // Nome do arquivo enviado
    $arquivoNome = basename($_FILES['arquivo']['name']);
    
    // Caminho completo para salvar o arquivo
    $caminhoArquivo = $diretorioDestino . $arquivoNome;

    // Cria o diretório se ele não existir
    if (!is_dir($diretorioDestino)) {
        mkdir($diretorioDestino, 0755, true);
    }

    // Move o arquivo para o diretório
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminhoArquivo)) {
        $arquivo = $arquivoNome; // Salva o nome do arquivo para o banco de dados
    } else {
        $_SESSION['mensagem'] = "Erro ao enviar o arquivo.";
        header('Location: index.php?p=notas');
        exit();
    }
}

// Insere ou atualiza a nota no banco de dados
if (isset($_POST['nota_id'])) {
    // Atualiza a nota (caso a nota_id exista)
    $nota_id = $_POST['nota_id'];

    // Se o arquivo foi enviado, incluímos ele na atualização
    if ($arquivo) {
        $sql = "UPDATE nota SET titulo=?, subtitulo=?, conteudo=?, cor=?, arquivo=?, id_categoria=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $titulo, $subtitulo, $conteudo, $cor, $arquivo, $id_categoria, $nota_id);
    } else {
        // Caso o arquivo não tenha sido enviado, fazemos a atualização sem ele
        $sql = "UPDATE nota SET titulo=?, subtitulo=?, conteudo=?, cor=?, id_categoria=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $titulo, $subtitulo, $conteudo, $cor, $id_categoria, $nota_id);
    }
} else {
    // Insere uma nova nota (caso não haja nota_id)
    $sql = "INSERT INTO nota (id_usuario, titulo, subtitulo, conteudo, cor, arquivo, id_categoria) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($arquivo) {
        $stmt->bind_param('isssssi', $id_usuario, $titulo, $subtitulo, $conteudo, $cor, $arquivo, $id_categoria);
    } else {
        $stmt->bind_param('isssssi', $id_usuario, $titulo, $subtitulo, $conteudo, $cor, $arquivo, $id_categoria);
    }
}

// Executa a consulta e verifica se deu certo
if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Nota salva com sucesso!";
    header('Location: index.php?p=notas');
    exit();
} else {
    $_SESSION['mensagem'] = "Erro ao salvar a nota: " . $stmt->error;
    header('Location: index.php?p=notas');
    exit();
}

// Fechar conexão
$stmt->close();
$conn->close();
?>
