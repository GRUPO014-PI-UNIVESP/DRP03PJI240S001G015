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
    <div class="row g-2">
      <div class="col-md-9">
        <label for="" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
        <input style="font-size: 14px; color:yellow;" type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['nomeProduto'] ?>" readonly>
      </div>
      <div class="col-md-3">
        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
        <input style="font-size: 14px; color:yellow; text-align:right" type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['qtdeLote'] . ' Kg' ?>" readonly>
      </div>
    </div><!-- Fim da div row --><br>

    <div class="row">
      <h6>Lista dos Compostos do Produto</h6>
    </div>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->