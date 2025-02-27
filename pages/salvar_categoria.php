<?php
session_start();

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && isset( $_SESSION[ 'idUsuario' ] ) ) {
    $id_usuario = $_SESSION[ 'idUsuario' ];
    $nome_categoria = $_POST[ 'nome_categoria' ];
    $cor_escolhida = $_POST[ 'cor_escolhida' ];

    $conn = mysqli_connect('127.0.0.1', 'u116672606_daybyday', 'D@ybyday02', 'u116672606_tcc');
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Erro de conexão: " . $conn->connect_error]));
}
    $stmt = $conn->prepare( 'INSERT INTO categoria (nome, id_usuario, cor) VALUES (?, ?, ?)' );
    $stmt->bind_param( 'sis', $nome_categoria, $id_usuario, $cor_escolhida );

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Categoria criada com sucesso.";
        header('Location: index.php?p=notas');
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao criar categoria: " . $stmt->error;
        header('Location: index.php?p=criar_categoria');
        exit();
    }

    $mensagem = isset( $_SESSION[ 'mensagem' ] ) ? $_SESSION[ 'mensagem' ] : '';
    unset( $_SESSION[ 'mensagem' ] );

    echo $mensagem;

    $stmt->close();
    $conn->close();
} else {
    header( 'Location: login.php' );
    exit();
}

?>