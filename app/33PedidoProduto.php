<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Pedido de Produto'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function(){
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar(){ <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer(){ clearTimeout(time); time = setTimeout(deslogar, 69900000); }
  }; inactivityTime();
</script>
<style> 
.tabela1{ width: 300px; height: 300px; overflow-y: scroll;}
.tabela2{ width: 850px; height: 300px; overflow-y: scroll;}
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div><h5>Pedido de Produto</h5></div>
    <form method="POST">
      <div class="row g-2">
        <div class="col-md-1"> <?php 
          // verifica o identificador do último registro
          $queryLast = $connDB->prepare("SELECT MAX(NUMERO_PEDIDO) AS ULTIMO FROM pedidos"); $queryLast->execute(); $rowID = $queryLast->fetch(PDO::FETCH_ASSOC); $novoID = $rowID['ULTIMO'] + 1;?>
          <label for="numPedido" class="form-label" style="font-size: 10px; color:aqua;">Pedido No.</label>
          <input style="font-size: 14px; text-align: center; color:yellow; background: rgba(0,0,0,0.3)" type="number" class="form-control" id="numPedido" name="numPedido" value="<?php echo $novoID ?>" readonly>
        </div>
        <div class="col-md-5">
          <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Produto</label>
          <select style="font-size: 14px; background: rgba(0,0,0,0.3)" class="form-select" id="nomeProduto" name="nomeProduto">
            <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o Produto</option><?php
              //Pesquisa de descrição do PRODUTO para seleção
              $query_produto = $connDB->prepare("SELECT DISTINCT PRODUTO FROM produtos"); $query_produto->execute();
              // inclui nome dos produtos como opções de seleção da tag <select>
              while($rowProduto = $query_produto->fetch(PDO::FETCH_ASSOC)){ ?>
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $rowProduto['PRODUTO']; ?></option> <?php
              } ?>
          </select>
        </div>
        <div class="col-md-2">
          <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
          <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="number" class="form-control" id="qtdeLote" name="qtdeLote" autofocus required>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-8">
          <input class="btn btn-outline-info" type="submit" id="pedido" name="pedido" value="Verificar Estoque" style="font-size: 13px; float:inline-end">
        </div>
      </div><!-- Fim da div row -->
    </form><br> <?php
      $produto = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      //Verifica se campo PRODUTO foi selecionado
      if(!empty($produto['pedido']) && $produto['nomeProduto'] == 'Selecione o Produto'){?>
        <div class="alert alert-danger" role="alert">Nenhum produto foi selecionado! Reinicie o procedimento.</div>
        <div><button class="btn btn-danger" onclick="location.href='./33PedidoProduto.php'">Reiniciar</button></div><?php
      }
      if(!empty($produto['pedido']) && $produto['nomeProduto'] != 'Selecione o Produto'){
        $verificaUnidade = $connDB->prepare("SELECT UNIDADE FROM produtos WHERE PRODUTO = :nomeProduto");
        $verificaUnidade->bindParam(':nomeProduto', $produto['nomeProduto'], PDO::PARAM_STR);
        $verificaUnidade->execute(); $unidade = $verificaUnidade->fetch(PDO::FETCH_ASSOC);
        $_SESSION['nomeProduto'] = $produto['nomeProduto'];
        $_SESSION['qtdeLote']    = $produto['qtdeLote']   ;
        $_SESSION['numPedido']   = $produto['numPedido']  ;
        $_SESSION['unidade']     = $unidade['UNIDADE']    ;
        header("Location: ./33PedidoProduto2.php");
      } ?>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->