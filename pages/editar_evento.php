<?php

include_once './conexaocalendar.php';

// Filtra os dados recebidos via POST
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Verifica se todos os campos obrigatórios estão presentes
if (!isset($dados['edit_id'], $dados['edit_title'], $dados['edit_color'], $dados['edit_start'], $dados['edit_end'], $dados['edit_obs'])) {
    $retorna = ['status' => false, 'msg' => 'Erro: Dados incompletos para a edição!'];
    echo json_encode($retorna);
    exit;
}

try {
    // Converte as datas para o formato aceito pelo banco de dados (Y-m-d H:i:s)
    $startDateTime = (new DateTime($dados['edit_start']))->format('Y-m-d H:i:s');
    $endDateTime = (new DateTime($dados['edit_end']))->format('Y-m-d H:i:s');
} catch (Exception $e) {
    $retorna = ['status' => false, 'msg' => 'Erro: Formato de data inválido!'];
    echo json_encode($retorna);
    exit;
}

// Prepara a query para atualizar o evento no banco de dados
$query_edit_event = "UPDATE events SET title=:title, color=:color, start=:start, end=:end, obs=:obs WHERE id=:id";

$edit_event = $conne->prepare($query_edit_event);

// Faz o bind dos parâmetros
$edit_event->bindParam(':title', $dados['edit_title']);
$edit_event->bindParam(':color', $dados['edit_color']);
$edit_event->bindParam(':start', $startDateTime);
$edit_event->bindParam(':end', $endDateTime);
$edit_event->bindParam(':obs', $dados['edit_obs']);
$edit_event->bindParam(':id', $dados['edit_id']);

try {
    // Executa a query e verifica se ocorreu sucesso
    if ($edit_event->execute()) {
        // Retorna sucesso com os dados atualizados
        $retorna = [
            'status' => true,
            'msg' => 'Evento editado com sucesso!',
            'id' => $dados['edit_id'],
            'title' => $dados['edit_title'],
            'color' => $dados['edit_color'],
            'start' => $startDateTime,
            'end' => $endDateTime,
            'obs' => $dados['edit_obs']
        ];
    } else {
        $retorna = ['status' => false, 'msg' => 'Erro: Não foi possível editar o evento no banco de dados.'];
    }
} catch (PDOException $e) {
    // Captura exceções do PDO e retorna erro com detalhes
    $retorna = ['status' => false, 'msg' => 'Erro ao editar evento: ' . $e->getMessage()];
}

// Retorna a resposta em JSON
echo json_encode($retorna);

?>
