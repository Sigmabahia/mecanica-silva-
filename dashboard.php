<?php

session_start();

if(!isset($_SESSION['id'])){
    header("Location: login.html");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Painel</title>
</head>
<body>

<h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>

<p>Login realizado com sucesso.</p>

<a href="logout.php">Sair</a>

</body>
</html>