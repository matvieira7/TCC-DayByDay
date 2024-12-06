<?php

$host = "127.0.0.1";
$user = "u116672606_daybyday";
$pass = "D@ybyday02";
$dbname = "u116672606_tcc";
$port = 3307;

try {

    $conne = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    //echo "Conexão com banco de dados realizado com sucesso.";
 } catch (PDOException $err) {
    die("Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado " . $err->getMessage());
}