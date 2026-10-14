<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Mecânica Silva</title>

<link rel="stylesheet" href="style_v3.css">

</head>

<body>

<header class="top-bar">

    <div class="logo">
        Mecânica <span>Silva</span>
    </div>

    <input
    type="text"
    id="busca"
    placeholder="Buscar peças, óleos, filtros...">

    <div class="acoes-topo">

        <a href="carrinho.php" class="cart-btn">
            🛒
            <span id="contador-carrinho">0</span>
        </a>

        <?php if(isset($_SESSION['nome'])){ ?>

        <div style="color:white">

        Olá,
        <?= $_SESSION['nome']; ?>

        <a
        href="logout.php"
        style="color:#ff6900">
        Sair
        </a>

        </div>

         <?php } else { ?>

        <div class="usuario-logado">

            <span>
                Olá, <?= $_SESSION['nome']; ?>
            </span>

            <a href="logout.php" class="btn-sair">
                Sair
            </a>

        </div>

        <a href="login.php" class="user"></a>

        <?php } ?>

    </div>

</header>

<nav class="menu">

    <a href="../mecanica.html">HOME</a>
    <a href="#oleos">ÓLEOS</a>
    <a href="#filtros">FILTROS</a>
    <a href="#freios">FREIOS</a>
    <a href="#estetica">ESTÉTICA</a>
    <a href="#performance">PERFORMANCE</a>

</nav>

<section class="banner">

    <div class="texto-banner">

        <h1>PROMOÇÕES DA SEMANA</h1>

        <p>
            Os melhores produtos para seu veículo
        </p>

        <button>
            Ver Ofertas
        </button>

    </div>

</section>

<!--Oleo-->

<section class="categoria" id="oleos">

    <h2>Óleos de Motor</h2>

    <div class="grid">

    <div class="card">
     <img src="https://www.liquimoly.com.br/cdn/shop/products/9047_1596620471368_ctx-1.png?v=1720918458&width=1517">
     <h3>Liqui Moly 5W-30</h3>
     <span>R$ 89,90</span>
     <button>Comprar</button>
    </div>

    <div class="card">
      <img src="https://www.motul.com/_next/image?url=https%3A%2F%2Fazupim01.motul.com%2Fmedia%2FmotulData%2FIM%2Fbigweb%2Fmotul_109775_4L_8100_X-CESS_GEN2_5W40_png.png&w=1080&q=75">
      <h3>Motul 8100 5W-40</h3>
      <span>R$ 115,90</span>
      <button>Comprar</button>
    </div>

    <div class="card">
      <img src="https://www.texaco.com.br/wp-content/uploads/2017/09/07784201_Hav-ProDS-FS-5W20_1L_2022_F-dr06-frasco-platina_preto_512x1024.png">
      <h3>Havoline 5W-20</h3>
      <span>R$ 74,90</span>
      <button>Comprar</button>
    </div>

    </div>

</section>

<!--Filtro-->

<section class="categoria" id="filtros">

    <h2>Filtros</h2>

    <div class="grid">

    <div class="card">
      <img src="https://stgecomm.blob.core.windows.net/imagesprod2/0355958_filtro-de-ar-audi-a3-2014-2016-tecfil-arl6077.jpeg">
      <h3>Filtro de Ar Tecfil ARL6077</h3>
      <span>R$ 119,99</span>
      <button>Comprar</button>
    </div>

    <div class="card">
    <img src="https://res.cloudinary.com/knfilters-com/image/upload/c_lpad,dpr_2.0,f_auto,h_540,q_auto,w_540/v1/media/catalog/product/3/3/33-3005_2.jpg?_i=AB">
    <h3>Filtros K&N</h3>
    <span>R$ 881,99</span>
    <button>Comprar</button>
    </div>

    </div>

</section>

<!--Freio-->

<section class="categoria" id="freios">

<h2>Sistema de Freios</h2>

<div class="grid">

<div class="card">
<img src="https://acdn-us.mitiendanube.com/stores/001/414/802/products/1000497350-8d8b412e05c0bf902317679001373034-1024-1024.webp">
<h3>Pastilha Brembo</h3>
<span>R$ 899,99</span>
<button>Comprar</button>
</div>

<div class="card">
<img src="https://acdn-us.mitiendanube.com/stores/001/414/802/products/xxxxxx11-f8cae5b2f1b3b25e6916661295911664-1024-1024.webp">
<h3>Disco Brembo</h3>
<span>R$ 2.879,00</span>
<button>Comprar</button>
</div>

</div>

</section>

<!--Estética-->

<section class="categoria" id="estetica">

<h2>Estética Automotiva</h2>

<div class="grid">

<div class="card">
<img src="https://http2.mlstatic.com/D_NQ_NP_2X_696010-MLA99351155090_112025-F.webp">
<h3>Cera Carnaúba Soft99</h3>
<span>R$ 35,26</span>
<button>Comprar</button>
</div>

<div class="card">
<img src="https://http2.mlstatic.com/D_NQ_NP_2X_814363-MLA99339908636_112025-F.webp">
<h3>Power Wash</h3>
<span>R$ 50,90</span>
<button>Comprar</button>
</div>

<div class="card">
<img src="https://http2.mlstatic.com/D_NQ_NP_2X_625466-MLA107049122276_022026-F.webp">
<h3>Power Tire</h3>
<span>R$ 59,71</span>
<button>Comprar</button>
</div>

</div>

</section>

<!--Performance-->

<section class="categoria" id="performance">

<h2>Performance</h2>

<div class="grid">

<div class="card">
<img src="https://http2.mlstatic.com/D_NQ_NP_2X_734643-MLB91524614195_092025-F.webp">
<h3>Velas NGK Iridium Jetta </h3>
<span>R$ 335,00</span>
<button>Comprar</button>
</div>

<div class="card">
<img src="https://acdn-us.mitiendanube.com/stores/001/414/802/products/rg-1001wt_1-jpg-77fba010b5cd93d9ab17373692758092-1024-1024.webp">
<h3>Filtro Esportivo</h3>
<span>R$ 398,99</span>
<button>Comprar</button>
</div>

<div class="card">
<img src="https://http2.mlstatic.com/D_NQ_NP_2X_927964-MLB106281796083_012026-F.webp">
<h3>Kit Intake</h3>
<span>R$ 1.567,08</span>
<button>Comprar</button>
</div>

</div>

</section>

<footer>
    <h3>MECÂNICA SILVA</h3><br>

    <p><i>Seu carro em boas mãos.</i></p><br>

    <p><strong><i>Telefone:</strong> (15) 99999-9999</p></i>
     <p><strong><i>Instagram:</strong>@mecanicasilva.ofc</i></p><br>

    <p>Rua Exemplo, 123 – Votorantim/SP</p>
    <p><strong>CEP:</strong>18100-000</p><br>

    <p>© 2026 Todos os direitos reservados.</p><br>
</footer>
<script src="script.js"></script>
</body>
</html>
