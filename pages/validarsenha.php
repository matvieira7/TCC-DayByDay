<?php
session_start();

$msg = ''; // Inicializa a mensagem

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['idUsuario'];
    $senha = $_POST['password'];

    $conn = mysqli_connect('127.0.0.1', 'u116672606_daybyday', 'D@ybyday02', 'u116672606_tcc');
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Erro de conexão: " . $conn->connect_error]));
}

    $sql = "SELECT senha FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['senha'];

        // Adicione estas linhas para depuração
        error_log("Senha fornecida: $senha");
        error_log("Senha hashada do banco de dados: $hashed_password");

        if (password_verify($senha, $hashed_password)) {
            $_SESSION['authenticated'] = true;
            header('Location: index.php?p=perfil');
            exit();
        } else {
            $msg = 'Senha incorreta. Tente novamente.';
        }
    } else {
        $msg = 'Erro ao verificar a senha. Tente novamente.';
    }

    $stmt->close();
    $conn->close();

    // Armazena a mensagem na sessão e redireciona
    $_SESSION['msg'] = $msg;
    header('Location: index.php'); // Redireciona para a página onde o modal está
    exit();
}
?>