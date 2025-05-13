<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor de Produtos'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
$nPedido = '';  
 ?>
<!-- Área Principal -->
<div class="main">
  <br><p style="font-size: 20px; color: whitesmoke">Dados Complementares de Produção</p><br>
  <form id="addProd" method="POST">
    <div class="row g-1">
      <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Número do Pedido</label>
        <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: center; width: 160px;" 
               type="number" class="form-control" id="nPedido" name="nPedido" value="" onclick="submeterFormulario()" required autofocus>
      </div>
    </div>   
  </form><?php
  if(isset($_POST['nPedido'])){
    $_SESSION['nPedido'] = $_POST['nPedido'];
    header('Location: ./AdicionaisProducao2.php');
  } ?>
</div>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
  }; inactivityTime();
  // atribui função de enviar formulário
  function submeterFormulario() { document.getElementById("enviar").submit(); }
</script>
<style>  .tabela{ width: 100%; height: 480px; overflow-y: scroll;} </style>
  