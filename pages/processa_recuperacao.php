<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

session_start(); // Iniciar sessão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $conn = mysqli_connect('127.0.0.1', 'u116672606_daybyday', 'D@ybyday02', 'u116672606_tcc');

    if ($conn->connect_error) {
        $_SESSION['mensagem'] = '<div class="alert alert-danger">Erro ao conectar ao banco de dados.</div>';
        header('Location: recuperar_senha.php');
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        $reset_token = bin2hex(random_bytes(16));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt_update = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_token_expires = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $reset_token, $expires_at, $user_id);
        $stmt_update->execute();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = 'smtp.gmass.co';
            $mail->Username = 'lojadosprimos2711@gmail.com';
            $mail->Password = 'c02bc05f-1c26-4825-b723-fe04d962e33d';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('lojadosprimos2711@gmail.com', 'DayByDay');
            $mail->addAddress($email);

            $reset_link = "https://etecdaybyday.tech/pages/resetar_senha.php?token=" . $reset_token;
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha';
            $mail->Body    = 'Clique no link abaixo para redefinir sua senha:<br><br>' . 
                             '<a href="' . $reset_link . '">' . $reset_link . '</a>';
            $mail->AltBody = 'Clique no link abaixo para redefinir sua senha: ' . $reset_link;

            $mail->send();
            $_SESSION['mensagem'] = '<div class="alert alert-success">A mensagem foi enviada para o seu e-mail!</div>';
        } catch (Exception $e) {
            $_SESSION['mensagem'] = '<div class="alert alert-danger">Erro ao enviar o e-mail: ' . $mail->ErrorInfo . '</div>';
        }
    } else {
        $_SESSION['mensagem'] = '<div class="alert alert-warning">Este e-mail não está cadastrado em nossa base de dados.</div>';
    }

    $stmt->close();
    $conn->close();

    header('Location: esqueceu_senha.php');
    exit();
}
?>
