<?php

function conectar() {
    $host = '127.0.0.1:3306';
    $dbname = 'u116672606_tcc';
    $user = 'u116672606_daybyday';
    $password = 'D@ybyday02';

    $conexao = new mysqli($host, $user, $password, $dbname);

    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    return $conexao;
}

$conn = conectar();

?>
