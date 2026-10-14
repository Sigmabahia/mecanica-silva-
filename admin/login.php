<?php
/* ============================================================
 * MECÂNICA SILVA — Painel Administrativo
 * Tela de login do administrador
 * ============================================================ */

require_once __DIR__ . '/../api/helpers.php';

iniciar_sessao();

if (admin_logado()) {
    header('Location: index.php');
    exit;
}

$erro = '';

if (metodo() === 'POST') {
    $usuario = limpar($_POST['usuario'] ?? '');
    $senha   = (string) ($_POST['senha'] ?? '');

    if ($usuario === '' || $senha === '') {
        $erro = 'Preencha usuário e senha.';
    } else {
        $pdo  = db();
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE usuario = :u');
        $stmt->execute([':u' => $usuario]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($senha, $admin['senha'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = (int) $admin['id'];
            $_SESSION['admin_nome'] = $admin['usuario'];
            header('Location: index.php');
            exit;
        }

        $erro = 'Usuário ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Painel Mecânica Silva</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="login-page">

    <form class="login-card" method="POST">
        <div class="logo">Mecânica <span>Silva</span></div>
        <div class="sub">Painel Administrativo</div>

        <?php if ($erro): ?>
            <div class="login-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <label>Usuário</label>
        <input type="text" name="usuario" placeholder="Digite o usuário" autofocus>

        <label>Senha</label>
        <input type="password" name="senha" placeholder="Digite a senha">

        <button type="submit">ENTRAR</button>

        <div class="login-dica">
            Acesso padrão: <strong>admin</strong> / <strong>admin123</strong>
        </div>
    </form>

</body>
</html>
