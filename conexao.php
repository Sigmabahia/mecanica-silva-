<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "autopecas";

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);

if(!$conn){
    die("Erro ao conectar ao banco.");
}
?>