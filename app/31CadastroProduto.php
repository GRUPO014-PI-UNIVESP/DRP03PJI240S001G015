<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Cadastro de Produto';
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
       time = setTimeout(deslogar, 300000);
     }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br> 
    <h5>Cadastro de Novo Produto</h5>         
    <form method="POST" action="#" id="cadastroProduto">
      <div class="row g-1">
        <div class="col-md-5">
          <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia do Produto</label>
          <input style="font-size: 12px; text-transform: uppercase; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="nomeProduto" name="nomeProduto" autofocus required>
        </div>
        <div class="col-md-5">
          <label for="descrProduto" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
          <input style="font-size: 12px; text-transform: uppercase; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="descrProduto" name="descrProduto" required>
        </div>
        <div class="col-md-2">
          <label for="capacidade" class="form-label" style="font-size: 10px; color:aqua">Capacidade Produtiva</label>
          <div class="input-group mb-2">
            <input type="number" class="form-control" id="capacidade" name="capacidade" style="font-size: 13px; background: rgba(0,0,0,0.3)" onchange="this.form.submit()" required>
              <span class="input-group-text" style="font-size: 13px">Kg/Hora</span>
          </div>
        </div>
      </div>
    </form><?php
    $verifica = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($verifica['descrProduto'])){
      $fantasia = strtoupper($verifica['nomeProduto']); $descrProduto = strtoupper($verifica['descrProduto']);
      $_SESSION['nomeProduto']  = strtoupper($verifica['nomeProduto']);
      $_SESSION['descrProduto'] = strtoupper($verifica['descrProduto']);
      $_SESSION['capacidade']   = $verifica['capacidade']; $_SESSION['ciclo'] = 1;
      $buscaRegistro = $connDB->prepare("SELECT DISTINCT NOME_PRODUTO FROM pf_tabela WHERE NOME_PRODUTO = :fantasia LIMIT 1");
      $buscaRegistro->bindParam(':fantasia', $fantasia, PDO::PARAM_STR);
      $buscaRegistro->execute(); $resultado = $buscaRegistro->fetch(PDO::FETCH_ASSOC); $contReg = $buscaRegistro->rowCount();
      if($contReg != 0){ ?><br>
        <div class="alert alert-danger" role="alert">
          O Nome Fantasia já consta no banco de dados, verifique!
          <button class="btn btn-warning" onclick="location.href='./31CadastroProduto.php';">Reiniciar Cadastro</button>
        </div> <?php 
      } header('Location: ./32CadastroProduto.php');
    } ?>
  </div>
</div>