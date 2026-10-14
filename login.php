<?php

session_start();
include("conexao.php");

if(isset($_POST['entrar'])){

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = mysqli_query(
$conn,
"SELECT * FROM usuarios
WHERE email='$email'"
);

$user = mysqli_fetch_assoc($sql);

if(
$user &&
password_verify(
$senha,
$user['senha']
)
){

$_SESSION['id'] = $user['id'];
$_SESSION['nome'] = $user['nome'];

header("Location: index.php");
exit();

}

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style_v3.css">
</head>

<body>

<section class="categoria">

<h2>Login</h2>

<form method="POST">

<input
type="email"
name="email"
placeholder="Email"
required>

<br><br>

<input
type="password"
name="senha"
placeholder="Senha"
required>

<br><br>

<button
name="entrar">
Entrar
</button>

</form>

<br>

<a href="cadastro.php">
Criar Conta
</a>

</section>

</body>
</html>