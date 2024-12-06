<?php
$mensagem = ''; // Variável para armazenar mensagens

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $host = '127.0.0.1:3306';
    $dbname = 'u116672606_tcc';
    $user = 'u116672606_daybyday';
    $password = 'D@ybyday02';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verifica o token
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE reset_token = :token AND reset_token_expires > NOW()");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();

        if (!$user) {
            $mensagem = '<div class="alert alert-danger">Token inválido ou expirado!</div>';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha'])) {
            $nova_senha = $_POST['senha'];

            if (strlen($nova_senha) < 6) {
                $mensagem = '<div class="alert alert-warning">A senha deve ter pelo menos 6 caracteres.</div>';
            } else {
                $senha_criptografada = password_hash($nova_senha, PASSWORD_BCRYPT);

                $stmt_update = $pdo->prepare("UPDATE usuarios SET senha = :senha, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
                $stmt_update->execute(['senha' => $senha_criptografada, 'id' => $user['id']]);

                $mensagem = '<div class="alert alert-success">Sua senha foi atualizada com sucesso!</div>';
            }
        }
    } catch (PDOException $e) {
        $mensagem = '<div class="alert alert-danger">Erro ao conectar ao banco de dados: ' . $e->getMessage() . '</div>';
    }
} else {
    $mensagem = '<div class="alert alert-danger">Token não fornecido!</div>';
}
?>