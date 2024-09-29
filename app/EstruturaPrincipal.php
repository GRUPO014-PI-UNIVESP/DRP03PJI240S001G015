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
  $msg->bindParam(':receptor', $_SESSION['nome_func'], PDO::PARAM_STR);
  $msg->execute();
  $numMsg = $msg->rowCount();

  if($_SESSION['departamento'] === 'ADMINISTRATIVO' || $_SESSION['credencial'] >= 4){
     $acesso1  = './00SeletorAdministrativo.php'   ; //$_SESSION['ordena'] = 'NOME_FUNCIONARIO';
     $acesso5  = './06QuadroFuncionarios.php'      ; $acesso7  = './07CadastroFuncionario.php' ;
     $acesso8  = './08EditaRegistroFuncionario.php'; $acesso9  = './10DeletaFunc.php'          ;
     $acesso10 = './11CadastroFuncionario.php'     ; $acesso11 = './33PedidoProduto.php'       ;
     $acesso12 = './21CompraMaterial.php'          ; $acesso13 = './30CadastroCliente.php'     ;
     $acesso14 = './31CadastroProduto.php'         ; $acesso15 = './22CadastroMaterial.php'    ;

  }else{ $acesso1 = ''; $acesso5 = ''; $acesso7 = ''; $acesso8 = ''; $acesso9 = ''; $acesso10 = ''; $acesso11 = '';}

  if($_SESSION['departamento'] === 'GARANTIA DA QUALIDADE'|| $_SESSION['credencial'] >= 4){
    $acesso20 = './01SeletorGQualidade.php';
    $acesso21 = './40RegistroAnalise.php'  ;  
  }else{ $acesso20 = ''; $acesso21 = ''; }

  if($_SESSION['departamento'] === 'LOGÍSTICA'      || $_SESSION['credencial'] >= 4){
    $acesso30 = './02SeletorLogistica.php';     
  }else{ $acesso30 = ''; }

  if($_SESSION['departamento'] === 'PRODUÇÃO'       || $_SESSION['credencial'] >= 4){
    $acesso40 = './03SeletorProducao.php';      
  }else{ $acesso40 = ''; }
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
    <p style="text-align: center; font-size: 15px">Departamentos</p>
    <a href="<?php echo $acesso1 ?>">
      <!-- ícone do Administrativo  -->
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
        <path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z"/>
      </svg>  Administrativo</a>

    <a href="<?php echo $acesso20 ?>">
      <!-- ícone do GQ  -->
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clipboard2-check-fill" viewBox="0 0 16 16">
        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5"/>
        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5m6.769 6.854-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
      </svg> Garantia da Qualidade</a>

    <a href="<?php echo $acesso30 ?>">
      <!-- ícone da Logística  -->
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
      </svg> Logística</a>

    <a href="<?php echo $acesso40 ?>">
      <!-- ícone da Produção  -->
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-building-gear" viewBox="0 0 16 16">
        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z"/>
        <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.386 1.46c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
      </svg> Produção</a>

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
          <a class="btn btn" href="./Cenário do Projeto.pdf" target="_blank" role="button" style="border-style: none">
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
    <p class="text-break" style="font-size: 11px; color:#DEB887; text-align: center;">Developed by DRP03PJI240S001G015 2024</p>
  </div>
    
  <!-- Área Principal-->
  <div class="main">

  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
</body>
</html>
