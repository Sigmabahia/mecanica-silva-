<?php

include("conexao.php");

if(isset($_POST['cadastrar'])){

    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $senha = password_hash(
        $_POST['senha'],
        PASSWORD_DEFAULT
    );

    mysqli_query(
        $conn,
        "INSERT INTO usuarios(nome,email,senha)
        VALUES('$nome','$email','$senha')"
    );

    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Cadastro</title>
<link rel="stylesheet" href="style_v3.css">
</head>

<body>

<section class="categoria">
    <h2>Cadastro</h2>

    <form method="POST">

    <input type="text"
    name="nome"
    placeholder="Nome"
    required>

    <br><br>

    <input type="email"
    name="email"
    placeholder="Email"
    required>

    <br><br>

    <input type="password"
    name="senha"
    placeholder="Senha"
    required>

    <br><br>

    <button
    name="cadastrar">
    Cadastrar
    </button>

    </form>

    </section>

</body>
</html>