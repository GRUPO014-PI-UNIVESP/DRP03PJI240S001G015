<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Pedido de Produto';
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
    <div class="row g-2" id="entradaProduto">
      <div class="col-md-9">
        <label for="" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
        <input style="font-size: 14px; color:yellow;" type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['nomeProduto'] ?>" readonly>
      </div>
      <div class="col-md-3">
        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
        <input style="font-size: 14px; color:yellow; text-align:right" type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['qtdeLote'] . ' Kg' ?>" readonly>
      </div>
    </div><!-- Fim da div row --><br>

    <div class="row g-2" id="tabelaMateriais">
      <h6>Lista dos Compostos do Produto e Disponibilidade</h6>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 30%; color: gray">Descrição do Material</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Necessária</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Disponível</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Condição do Estoque</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Ação</th>
            </tr>
          </thead>
          <?php
            $nomeProduto = $_SESSION['nomeProduto'];
            $componente = $connDB->prepare("SELECT * FROM pf_tabela WHERE NOME_PRODUTO = :nomeProduto LIMIT 1");
            $componente->bindParam(':nomeProduto', $nomeProduto, PDO::PARAM_STR);
            $componente->execute();
            $result = $componente->fetch(PDO::FETCH_ASSOC);
          ?>

        </table>
      </div><!-- Fim da div overflow -->
    </div><!-- Fim da div row -->
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->