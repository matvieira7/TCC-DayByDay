<?php
session_start();

$conn = mysqli_connect('127.0.0.1:3306', 'u116672606_daybyday', 'D@ybyday02', 'u116672606_tcc');
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Erro de conexão: " . $conn->connect_error]));
}

$nome = $_POST['txtnome'];
$email = $_POST['txtemail'];
$senha = password_hash($_POST['txtsenha'], PASSWORD_DEFAULT);
$profilePicUrl = "../img/profile/profile_1.png";

// Verifica se o email já está cadastrado
$sql_check_email = "SELECT * FROM usuarios WHERE email='$email'";
$result_check_email = $conn->query($sql_check_email);

if ($result_check_email->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "O E-mail Fornecido já está Cadastrado. Por favor, Escolha outro E-mail."]);
} else {
    // Insere o novo usuário com os valores padrão para nota_tuto e calendar_tuto
    $sql_insert_usuario = "INSERT INTO usuarios (nome, email, senha, profile_pic_url, nota_tuto, calendar_tuto) 
                           VALUES ('$nome', '$email', '$senha', '$profilePicUrl', 'y', 'y')";

    if ($conn->query($sql_insert_usuario) === TRUE) {
        // Login automático após o cadastro
        $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($_POST['txtsenha'], $senha)) {
                $_SESSION['idUsuario'] = $row['id'];
                $_SESSION['nomeUsuario'] = $row['nome'];
                $_SESSION['emailUsuario'] = $row['email'];

                echo json_encode(["status" => "success", "message" => "Cadastro e login bem-sucedidos!"]);
                exit();
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao cadastrar o usuário: " . $conn->error]);
    }
}

$conn->close();
