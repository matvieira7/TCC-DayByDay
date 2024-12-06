<?php

session_start();

if (!isset($_SESSION['idUsuario'])) {
    header('Location: login.php');
    exit();
}

include_once './conexaocalendar.php';

$id_usuario = $_SESSION['idUsuario']; // Recupera o ID do usuário da sessão

// Atualiza a consulta para selecionar apenas eventos do usuário logado
$query_events = "SELECT id, title, color, start, end, obs
                FROM events
                WHERE id_usuario = :id_usuario";

$result_events = $conne->prepare($query_events);
$result_events->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

$result_events->execute();

$eventos = [];

// Percorrer a lista de registros retornados do banco de dados
while ($row_events = $result_events->fetch(PDO::FETCH_ASSOC)) {
    $eventos[] = [
        'id' => $row_events['id'],
        'title' => $row_events['title'],
        'color' => $row_events['color'],
        'start' => $row_events['start'],
        'end' => $row_events['end'],
        'obs' => $row_events['obs']
    ];
}

echo json_encode($eventos);
?>
