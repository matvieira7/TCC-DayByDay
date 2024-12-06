<?php
// Verifica se a sessão já está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão apenas se não estiver ativa
}

// Verifica se o usuário está logado
if (!isset($_SESSION['idUsuario'])) {
    echo "<p>Você precisa estar logado para ver seus eventos.</p>";
    exit();
}

// Conexão com o banco de dados usando mysqli
$servername = "127.0.0.1:3306";
$username = "u116672606_daybyday";
$password = "D@ybyday02"; // Insira sua senha
$dbname = "u116672606_tcc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Pega o ID do usuário logado
$idUsuario = $_SESSION['idUsuario'];

// Query para buscar os eventos do usuário logado
$query = "SELECT * FROM events WHERE id_usuario = ? AND end >= NOW() ORDER BY end ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idUsuario); // 'i' para inteiro
$stmt->execute();
$result = $stmt->get_result();

// HTML para o conteúdo do modal
echo "<h2 style='font-size: 18px; margin-bottom: 15px;'>Eventos Próximos de Expirar:</h2>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $titulo = $row['title']; // Campo 'title' do banco de dados
        $data = date('d/m/Y', strtotime($row['end'])); // Campo 'end' do banco de dados
        $color = $row['color']; // Campo 'color' do banco de dados

        echo "<div class='mb-3' style='border-left: 5px solid $color; padding: 10px; margin-bottom: 10px;'>
                <strong>$titulo</strong> - $data
              </div>";
    }
} else {
    echo "<p>Nenhum evento próximo de expirar encontrado.</p>";
}

// Fecha a conexão
$stmt->close(); // Fecha a declaração preparada
$conn->close(); // Fecha a conexão
?>
