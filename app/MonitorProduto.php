<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Monitor de Produtos';
include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time;
    window.onload        = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress  = resetTimer;
    function deslogar() {
      <?php
        $_SESSION['posicao'] = 'Encerrado por inatividade';
        include_once './RastreadorAtividades.php';
      ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() {
      clearTimeout(time);
       time = setTimeout(deslogar, 69900000);
     }
  };
  inactivityTime();
</script>
<style>
  .tabela{ width: 100%; height: 500px; overflow-y: scroll;}
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <p style="font-size: 25px;">Monitoramento da Execução dos Pedidos</p>
    <br><br>
    <div class="row g-0">
        <div class="col md-5">
          <p style="text-align: center;">Nome do Produto</p>
        </div>
        <div class="col md-4">
          <p style="text-align: center;">Quantidade</p>
        </div>
        <div class="col md-2">
          <p style="text-align: center;">Data de Entrega</p>
        </div>
      </div>
    <div class="tabela">
      <div class="row g-0">
        <div class="col md-5">
          <p>Produto A</p>
        </div>
        <div class="col md-4">
          <p style="text-align: center;">3.000 Kg</p>
        </div>
        <div class="col md-2">
          <p style="text-align: center;">29/03/2025</p>
        </div>
      </div>
      <div class="row g-0">
        <div class="col md-2">
          <p style="font-size: 12px; background-color: green; color:black; text-align: center;">Compra</p>
        </div>
        <div class="col md-2">
          <p style="font-size: 12px; background-color: green; color:black; text-align: center;">Recebimento</p>
        </div>
        <div class="col md-2">
          <p style="font-size: 12px; background-color: green; color:black; text-align: center;">Análise dos Materiais</p>
        </div>
        <div class="col md-2">
          <p style="font-size: 12px; background-color: aquamarine; color:black; text-align: center;">Processamento</p>
        </div>
        <div class="col md-2">
          <p style="font-size: 12px; background-color: deepskyblue; color:black; text-align: center;">Análise do Produto</p>
        </div>
        <div class="col md-2">
          <p style="font-size: 12px; background-color: gray; color:black; text-align: center;">Entrega</p>
        </div>
        <div class="col md-2">
          <p style="font-size: 12px; background-color: gray; color:black; text-align: center;">Concluído</p>
        </div>
      </div>
    </div>
  </div>
</div>