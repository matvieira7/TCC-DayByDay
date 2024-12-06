<?php
session_start();

// Conexão com o banco de dados

$conn = mysqli_connect('127.0.0.1', 'u116672606_daybyday', 'D@ybyday02', 'u116672606_tcc');
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Erro de conexão: " . $conn->connect_error]));
}


// Recebe os dados do formulário
$email = $_POST['txtemail'];
$senha = $_POST['txtsenha'];

// Consulta SQL para obter os dados do usuário, incluindo a URL da foto de perfil
$sql = "SELECT id, nome, email, senha, profile_pic_url FROM usuarios WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($senha, $row['senha'])) {
        // Armazena os dados do usuário na sessão
        $_SESSION['idUsuario'] = $row['id'];
        $_SESSION['nomeUsuario'] = $row['nome'];
        $_SESSION['emailUsuario'] = $row['email'];
        $_SESSION['profilePicUrl'] = !empty($row['profile_pic_url']) ? $row['profile_pic_url'] : '../img/profile/profile_1.png';

        // Responde com sucesso
        echo json_encode(["status" => "success", "redirect" => "index.php?p=notas"]);
    } else {
        // Senha incorreta
        echo json_encode(["status" => "error", "message" => "Senha incorreta!"]);
    }
} else {
    // Usuário não encontrado
    echo json_encode(["status" => "error", "message" => "Usuário não encontrado!"]);
}

$conn->close();
?>