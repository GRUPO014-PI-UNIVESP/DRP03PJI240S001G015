<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<script>
const input = document.getElementById("valorNumerico");

//para valores numéricos
input.addEventListener("keypress", somenteNumeros);

function somenteNumeros(e) {

var charCode = (e.which) ? e.which : e.keyCode

if (charCode > 31 && (charCode < 48 || charCode > 57))

e.preventDefault();

 }
// para valores monetários
 input.addEventListener("keyup", formatarMoeda); 

function formatarMoeda(e) {

var v = e.target.value.replace(/\D/g,"");

v = (v/100).toFixed(2) + "";

v = v.replace(".", ",");

v = v.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");

v = v.replace(/(\d)(\d{3}),/g, "$1.$2,");

e.target.value = v;

}
// para formatar cpf
input.addEventListener("keyup", formatarCPF);

function formatarCPF(e){

var v=e.target.value.replace(/\D/g,"");

v=v.replace(/(\d{3})(\d)/,"$1.$2");

v=v.replace(/(\d{3})(\d)/,"$1.$2");

v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2");

e.target.value = v;
}

input.addEventListener("keyup", formatarCNPJ);
function formatarCNPJ(e){

var v= e.target.value.replace(/\D/g,"");

v=v.replace(/^(\d{2})(\d)/,"$1.$2");

v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3");

v=v.replace(/\.(\d{3})(\d)/,".$1/$2");

v=v.replace(/(\d{4})(\d)/,"$1-$2");  

e.target.value = v;
}
// verificar dados digitados ex:telefone
input.addEventListener("keyup", formatarTelefone);
input.addEventListener("blur", validarTelefone); 
function formatarTelefone(e){

var v=e.target.value.replace(/\D/g,"");

v=v.replace(/^(\d\d)(\d)/g,"($1)$2"); 

v=v.replace(/(\d{5})(\d)/,"$1-$2");    

e.target.value = v;

}

function validarTelefone(e){

var texto = e.target.value;

var RegExp = /^\(\d{2}\)\d{5}-\d{4}/;

if (texto.match(RegExp) != null) {

alert("telefone válido");

} else {

alert("telefone inválido");

e.target.value = "";

}

}
</script>
<body>


</body>
</html>