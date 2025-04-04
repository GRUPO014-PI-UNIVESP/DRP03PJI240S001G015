<?php
// Dashboard.php
// Home do sistema

session_start(); // inicia sessão de trabalho
ob_start();      // limpa buffer de saída

//definição de hora local
date_default_timezone_set('America/Sao_Paulo');

//Chama conexão com banco de dados
include_once './ConnectDB.php';

$msg = $connDB->prepare("SELECT * FROM mensagens WHERE RECEPTOR_MSG = :receptor AND CONFIRMA = 'UNRE'");
$msg->bindParam(':receptor', $_SESSION['nome_func'], PDO::PARAM_STR); $msg->execute(); $numMsg = $msg->rowCount();

if($_SESSION['departamento'] === 'ADMINISTRATIVO' || $_SESSION['credencial'] >= 6){ //$_SESSION['ordena'] = 'NOME_FUNCIONARIO';
  $acesso1  = './00SeletorAdministrativo.php'; $acesso2  = './11SetorVendas.php'        ; $acesso3  = './12SetorCompras.php'       ; $acesso4  = './05VerificarDetalhes.php';
  $acesso5  = './06QuadroFuncionarios.php'   ; $acesso6  = './'                         ; $acesso7  = './07CadastroFuncionario.php'; $acesso8  = './08EditaRegistroFuncionario.php';
  $acesso9  = './10DeletaFunc.php'           ; $acesso10 = './11CadastroFuncionario.php'; $acesso11 = './33PedidoProduto1.php'      ; $acesso12 = './21CompraMaterial.php';
  $acesso13 = './30CadastroCliente.php'      ; $acesso14 = './31CadastroProduto.php'    ; $acesso15 = './23CadastroMaterial.php'   ; $acesso16 = './22CompraMaterial.php';
  $acesso17 = './39RelatorioVendas.php'      ; $acesso18 = './24RelatorioCompras.php'   ; $acesso19 = './25CadastroFornecedor.php' ;
}else{ $acesso1  = ''; $acesso2  = ''; $acesso3  = ''; $acesso4  = ''; $acesso5  = ''; $acesso7  = ''; $acesso8  = ''; $acesso9  = ''; $acesso10 = ''; $acesso11 = '';
       $acesso12 = ''; $acesso13 = ''; $acesso14 = ''; $acesso15 = ''; $acesso16 = ''; $acesso17 = ''; $acesso18 = ''; $acesso19 = '';}

if($_SESSION['departamento'] === 'GARANTIA DA QUALIDADE'|| $_SESSION['credencial'] >= 4){
  $acesso20 = './01SeletorGQualidade.php'; $acesso21 = './40RegistroAnalise.php'  ;  
}else{ $acesso20 = ''; $acesso21 = ''; }

if($_SESSION['departamento'] === 'LOGÍSTICA' || $_SESSION['credencial'] >= 4){ $acesso30 = './02SeletorLogistica.php';}else{ $acesso30 = ''; }

if($_SESSION['departamento'] === 'PRODUÇÃO' || $_SESSION['credencial'] >= 4){$acesso40 = './03SeletorProducao.php';}else{ $acesso40 = ''; }
if($_SESSION['credencial'] >= 2){ $acesso60 = './05MonitorLogin.php'; } else{ $acesso60 = ''; }

function limitador($texto, $limite, $quebra = true){ $tamanho = strlen($texto);
  if($tamanho <= $limite){ $novo_texto = $texto; }else{
    if($quebra == true){ $novo_texto = trim(substr($texto, 0, $limite))."..."; }else{
      $ultimo_espaco = strrpos(substr($texto, 0, $limite), " ");
      $novo_texto = trim(substr($texto, 0, $ultimo_espaco))."...";}} return $novo_texto;}
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="dark">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
<style>
/* Configuração da barra laterial superior */
.sidebarTop {position: absolute;height: 30%;width: 200px;position: fixed;z-index: 1;top: 0;left: 0;background-color: rgba(8, 165, 142, 0.573);overflow-x: hidden;padding-top: 10px;color: whitesmoke;}
/* Configuração da barra laterial inferior */
.sidebarBottom {position: absolute;height: 60%;width: 200px;position: fixed;z-index: 1;top: 30%;left: 0;background-color: rgba(8, 165, 142, 0.573);overflow-x: hidden;padding-top: 10px;font-size: 11px;color: whitesmoke;}
/* Configuração da barra laterial superior */
.sidebarFoot {position: absolute;height: 10%;width: 200px;position: fixed;z-index: 1;top: 90%;left: 0;background-color: rgba(8, 165, 142, 0.573);overflow-x: hidden;padding-top: 10px;color: whitesmoke;}
/* Estilo da barra lateral */
.sidebarTop a {padding: 6px 8px 6px 16px;text-decoration: none;font-size: 13px;color: whitesmoke;display: block;}
.sidebarBottom a {padding: 6px 8px 6px 16px;text-decoration: none;font-size: 13px;color: whitesmoke;display: block;}
/* Animação do mouse sobre itens da barra lateral superior */
.sidebarTop a:hover {color:aqua;}
/* Animação do mouse sobre itens da barra lateral inferior */
.sidebarBottom a:hover {color:aqua;}
/* Estilo do bloco principal */
.main {margin-left: 200px;padding: 0px 10px;}
/* Add media queries for small screens (when the height of the screen is less than 450px, add a smaller padding and font-size) */
@media screen and (max-height: 450px) {.sidebarTop {padding-top: 15px;}.sidebarTop a {font-size: 18px;}}
.tab0{width: 99%;height: 10px;padding: 3px;margin-left: 5px;}
.tab1{width: 99%;height: 30px;padding: 3px;margin-left: 5px;}
</style>
<script>
  //script Java Script para criar mascaras de inserção de dados formatados como RG, CPF, Telefone, CEP
  function criaMascara(mascaraInput) {
    const maximoInput = document.getElementById(`${mascaraInput}Input`).maxLength;
    let valorInput    = document.getElementById(`${mascaraInput}Input`).value;
    let valorSemPonto = document.getElementById(`${mascaraInput}Input`).value.replace(/([^0-9])+/g, "");
    const mascaras    = {
                        CPF:     valorInput.replace(/[^\d]/g, "").replace(/(\d{3})(\d{3})(\d{3})(\d{2})/       , "$1.$2.$3-$4"),
                        CNPJ:    valorInput.replace(/[^\d]/g, "").replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5"),
                        IE:      valorInput.replace(/[^\d]/g, "").replace(/(\d{3})(\d{3})(\d{3})(\d{3})/       , "$1.$2.$3.$4"),
                        Celular: valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{5})(\d{4})/             , "($1) $2-$3"),
                        Fixo:    valorInput.replace(/[^\d]/g, "").replace(/^(\d{2})(\d{4})(\d{4})/             , "($1) $2-$3"),
                        CEP:     valorInput.replace(/[^\d]/g, "").replace(/(\d{5})(\d{3})/                     , "$1-$2"),
                        RG:      valorInput.replace(/[^\d]/g, "").replace(/(\d{2})(\d{3})(\d{3})(\d{1})/       , "$1.$2.$3-$4"),
                        };
    valorInput.length === maximoInput ? document.getElementById(`${mascaraInput}Input`).value = mascaras[mascaraInput]
    : document.getElementById(`${mascaraInput}Input`).value = valorSemPonto;
    };
    function limpaTela() {$('#buscaProduto div').empty()}
    const input = document.getElementById("campo");
    //para valores numéricos
    input.addEventListener("keypress", somenteNumeros);
    function somenteNumeros(e) {
      var charCode = (e.which) ? e.which : e.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
        e.preventDefault();
    }

    // para valores monetários
    input.addEventListener("keypress", formatarMoeda); 
    function formatarMilhar(e) {
      var v = e.target.value.replace(/\D/g,"");
      v = (v/100).toFixed(2) + "";
      v = v.replace(".", ",");
      v = v.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
      v = v.replace(/(\d)(\d{3}),/g, "$1.$2,");
      e.target.value = v;
    }
</script>
<body>
  <!-- Barra lateral Superior-->
  <div class="sidebarTop">
      <a href="./MapaGeral.php">
      <!-- ícone do mapa do sistema  -->
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.5.5 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103M10 1.91l-4-.8v12.98l4 .8zm1 12.98 4-.8V1.11l-4 .8zm-6-.8V1.11l-4 .8v12.98z"/>
      </svg>  Mapa Geral do Sistema</a>

    <a href="./LogOut.php">
      <!-- ícone da saída do sistema  -->
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
      </svg>  Sair do Sistema</a>
  </div><!-- fim da DIV side top-->

  <!-- Barra lateral Inferior-->
  <div class="sidebarBottom">
    <p style="text-align: center; font-size: 15px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif; color:aqua">Informações do Usuário</p>
    <p style="padding: 3px; color:aqua" class="text-break">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill-check" viewBox="0 0 16 16">
        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
        <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
      </svg> <?php echo '  '.$_SESSION['nome_func']  ?>
    </p>
    <p style="padding: 3px; color:aqua">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
        <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
        <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
      </svg> <?php 
        $dLog = strtotime($_SESSION['dataLog']);
        echo '  '.date('d/m/Y', $dLog);  ?>
    </p>
    <p style="padding: 3px; color:aqua">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
      </svg> <?php 
        $hLog = strtotime($_SESSION['horaLog']);
        echo '  '.date('H:i:s', $hLog);  ?>
    </p>
      <a href="./14AlterarSenhaAcesso.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
          <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5"/>
          <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
        </svg> Alterar Senha</a>
      <a href="./09EditaDadosPessoais.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
          <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
        </svg> Atualizar Dados Pessoais</a>
      <a href="./15Mensagens.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
          <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
        </svg> Mensagens</a>
    <P style="padding: 5px; color: greenyellow; float: right"><?php echo ' Você tem ' .$numMsg. ' mensagens não lidas' ?></P><br><br><br>
    <div class="container text-center">
      <div class="row">
        <div class="col" style="margin-left: 0%">
          <a class="btn btn" href="./RelatorioFinal.pdf" target="_blank" role="button" style="border-style: none">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16">
              <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
            </svg>
            <p>Documentação do Sistema</p>
          </a>    
        </div>
      </div>
    </div> 
  </div>

  <!-- Rodapé com créditos-->
  <div class="sidebarFoot">
    <p class="text-break" style="font-size: 11px; color:#DEB887; text-align: center;">Developed by DRP03PJI310S002G013 2025</p>
  </div>
    
  <!-- Área Principal-->
  <div class="main">

  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
</body>
</html>
