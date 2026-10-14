// ======================
// CARRINHO
// ======================

let carrinho =
JSON.parse(
localStorage.getItem("carrinho")
) || [];

const contador =
document.getElementById(
"contador-carrinho"
);

atualizarContador();


// ======================
// ADICIONAR PRODUTOS
// ======================

const botoes =
document.querySelectorAll(
".card button"
);

botoes.forEach(botao=>{

botao.addEventListener("click",()=>{

const card =
botao.parentElement;

const nome =
card.querySelector("h3")
.innerText;

const precoTexto =
card.querySelector("span")
.innerText;

const preco =
Number(
precoTexto
.replace("R$","")
.replace(",",".")
);

const imagem =
card.querySelector("img")
.src;

carrinho.push({
nome,
preco,
imagem
});

salvarCarrinho();

atualizarContador();

alert(
nome +
" adicionado ao carrinho!"
);

});

});


// ======================
// SALVAR
// ======================

function salvarCarrinho(){

localStorage.setItem(
"carrinho",
JSON.stringify(carrinho)
);

}


// ======================
// CONTADOR
// ======================

function atualizarContador(){

if(contador){

contador.innerText =
carrinho.length;

}

}


// ======================
// BUSCA
// ======================

const busca =
document.getElementById(
"busca"
);

if(busca){

busca.addEventListener(
"keyup",
()=>{

const texto =
busca.value.toLowerCase();

const cards =
document.querySelectorAll(
".card"
);

cards.forEach(card=>{

const nome =
card.querySelector("h3")
.innerText
.toLowerCase();

if(
nome.includes(texto)
){

card.style.display =
"block";

}else{

card.style.display =
"none";

}

});

});

}