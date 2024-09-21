<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Administrativo';
  include_once './RastreadorAtividades.php';
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
        time = setTimeout(deslogar, 3000000);
    }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div>
      <h5>Pedido de Produto</h>
    </div>
    <form action="#" method="POST">
      <div class="row g-2">
        <div class="col-md-8">
          <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
          <select style="font-size: 14px;" class="form-select" id="nomeProduto" name="nomeProduto" autofocus>
            <option style="font-size: 14px" selected>Selecione o Produto</option><?php
              //Pesquisa de descrição do PRODUTO para seleção
              $query_produto = $connDB->prepare("SELECT DISTINCT NOME_FANTASIA FROM pf_tabela");
              $query_produto->execute();
              // inclui nome dos produtos como opções de seleção da tag <select>
              while($produto = $query_produto->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 14px"><?php echo $produto['NOME_FANTASIA']; ?></option> <?php
              } ?>
          </select>
        </div>
        <div class="col-md-4">
          <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
          <input style="font-size: 14px; text-align:right" type="number" class="form-control" id="qtdeLote" name="qtdeLote" onchange="this.form.submit()" required>
        </div>
      </div><!-- Fim da div row -->
    </form> <?php
      $produto = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(!empty($produto['nomeProduto']) && !empty($produto['qtdeLote'])){
        $_SESSION['nomeProduto'] = $produto['nomeProduto']; $_SESSION['qtdeLote'] = $produto['qtdeLote'];
        header('Location: ./34PedidoProduto.php');
      } ?>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->