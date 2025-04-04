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
.tabela1{ width: 600px; height: 300px; overflow-y: scroll;}
.tabela2{ width: 800px; height: 300px; overflow-y: scroll;}
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div><h5>Pedido de Produto - Selecionar Lotes do Estoque</h5></div>   
    <div class="row g-5">
      <div class="col-md-1">
        <label for="numPedido" style="font-size: 10px; color:aqua;">Pedido No.</label>
        <p style="color:yellow; font-size: 13px; text-align: center; border-bottom: 2px solid whitesmoke"><?php echo $_SESSION['numPedido'] ?></p>
      </div>
      <div class="col-md-7">
        <label for="nomeProduto" style="font-size: 10px; color:aqua">Produto</label>
        <p style="color:yellow; font-size: 13px; border-bottom: 2px solid whitesmoke"><?php echo $_SESSION['nomeProduto'] ?></p>    
      </div>
      <div class="col-md-2">
        <label for="qtdeLote" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
        <p style="color:yellow; font-size: 16px; border-bottom: 2px solid whitesmoke; text-align: center;"><?php echo number_format($_SESSION['qtdeLote'], 0, ',', '.') . ' ' . $_SESSION['unidade'] ?></p> 
      </div>
      <div class="col-md-1"></div>
    </div><!-- Fim da div row -->
    <br>
    <div class="row g-0">
      <div class="col-md-7"><!-- Construção da tabela do estoque de produtos e disponibilidades -->
        <p style="color:aqua">Estoque Disponível</p>
        <?php
          $query_prodDisponivel = $connDB->prepare('SELECT * FROM produto_estoque WHERE NOME_PRODUTO = :nomeProd AND QTDE_ESTOQUE >= 1 ORDER BY QTDE_ESTOQUE ASC');
          $query_prodDisponivel->bindParam(':nomeProd', $_SESSION['nomeProduto'], PDO::PARAM_STR);
          $query_prodDisponivel->execute(); 
        ?>
        <div class="tabela1">
          <form action="" method="POST">
            <table class="table table-dark table-hover">
              <thead style="font-size: 12px">
                <tr>
                  <th scope="col" style="width: 10%; ">No.Lote</th>
                  <th scope="col" style="width: 10%; ">Qtde.Disponível</th>
                  <th scope="col" style="width: 10%; ">Qtde.Desejada</th>
                </tr>
              </thead>
              <tbody style="height: 25%; font-size: 13px;">
                <?php 
                while($rowEstoque = $query_prodDisponivel->fetch(PDO::FETCH_ASSOC)){ ?>
                  <tr>
                    <th style="width: 10%; "> <?php echo $rowEstoque['NUMERO_LOTE'] ?> </th>
                    <td style="width: 10%; "> <?php echo number_format($rowEstoque['QTDE_ESTOQUE'], 0, ',', '.') . ' ' . $rowEstoque['UNIDADE_MEDIDA'] ?> </td>
                    <td style="width: 10%; ">
                      <div>
                        <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3); width: 100px;" type="text" class="form-control" id="qtdeReq" name="qtdeReq" 
                          value="<?php echo number_format($rowEstoque['QTDE_ESTOQUE'], 0, ',', '.') ?>" autofocus required>
                      </div>
                    </td>       
                  </tr><?php 
                } ?> 
              </tbody>               
            </table>
          </form>
        </div>
      </div>
    </div>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->