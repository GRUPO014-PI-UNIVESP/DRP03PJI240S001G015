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
.tabela2{ width: 800px; height: 300px; overflow-y: scroll;}
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div><h5>Pedido de Produto - Situação de Estoque e Materiais Ingredientes</h5></div>   
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
      <div class="col-md-4"><!-- Construção da tabela do estoque de produtos e disponibilidades -->
        <p style="color:aqua">Estoque Disponível</p>
        <div class="tabela1">
          <table class="table table-dark">
            <thead style="font-size: 12px">
              <tr>
                <th scope="col" style="width: 50%; text-align: center">Lote</th>
                <th scope="col" style="width: 50%; text-align: center">Quantidade</th>
              </tr>
            </thead>
            <tbody style="height: 25%; font-size: 13px;">
              <?php
                $query_prodDisponivel = $connDB->prepare('SELECT * FROM produto_estoque WHERE NOME_PRODUTO = :nomeProd AND QTDE_ESTOQUE >= 1 ORDER BY QTDE_ESTOQUE ASC');
                $query_prodDisponivel->bindParam(':nomeProd', $_SESSION['nomeProduto'], PDO::PARAM_STR);
                $query_prodDisponivel->execute(); 
              while($rowEstoque = $query_prodDisponivel->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                  <th style="width: 10%; text-align: center"> <?php echo $rowEstoque['NUMERO_LOTE'] ?> </th>
                  <td style="width: 20%; text-align: center"> <?php echo number_format($rowEstoque['QTDE_ESTOQUE'], 0, ',', '.') . ' ' . $rowEstoque['UNIDADE_MEDIDA'] ?> </td>        
                </tr><?php 
              } ?> 
            </tbody>               
          </table>
        </div><?php 
          $totaliza = $connDB->prepare("SELECT SUM(QTDE_ESTOQUE) AS TOTAL FROM produto_estoque WHERE NOME_PRODUTO = :nomeProduto AND QTDE_ESTOQUE >=1");
          $totaliza->bindParam(':nomeProduto', $_SESSION['nomeProduto'], PDO::PARAM_STR);
          $totaliza->execute(); $totalLotes = $totaliza->fetch(PDO::FETCH_ASSOC);         
          if(!empty($totalLotes['TOTAL']) && $totalLotes['TOTAL'] >= 1){ ?><button class="btn btn-outline-primary" onclick="location.href='./33PedidoProduto3.php'">Utilizar Estoque</button><?php }
          if(empty($totalLotes['TOTAL']) && $totalLotes['TOTAL'] < 1){ ?><button class="btn btn-outline-secondary" disabled onclick="">Utilizar Estoque</button><?php }?>       
      </div>
      <div class="col-md-8"><!-- Construção da tabela dos materiais ingredientes e disponibilidades -->
        <p style="color:aqua">Materiais Ingredientes</p>
        <div class="tabela2">
          <table class="table table-dark">
            <thead style="font-size: 12px">
              <tr>
                <th scope="col" style="width: 30%;">Ingrediente/Proporção</th>
                <th scope="col" style="width: 20%; text-align: right">Qtde Exigida</th>
                <th scope="col" style="width: 20%; text-align: right">Qtde Disponível</th>
                <th scope="col" style="width: 20%; text-align: center">Condição</th>
              </tr>
            </thead>
            <tbody style="height: 25%;">
              <?php
                $query_matDisponivel = $connDB->prepare('SELECT * FROM produtos WHERE PRODUTO = :nomeProd');
                $query_matDisponivel->bindParam(':nomeProd', $_SESSION['nomeProduto'], PDO::PARAM_STR);
                $query_matDisponivel->execute(); 
                while($rowMat = $query_matDisponivel->fetch(PDO::FETCH_ASSOC)){                 
                  $query_matLista = $connDB->prepare('SELECT * FROM materiais_estoque WHERE DESCRICAO = :nomeMat');
                  $query_matLista->bindParam(':nomeMat', $rowMat['MATERIAL_COMPONENTE'], PDO::PARAM_STR);
                  $query_matLista->execute();
                  $proporcao = $_SESSION['qtdeLote'] * ($rowMat['PROPORCAO_MATERIAL'] / 100);
                  while($dataMat = $query_matLista->fetch(PDO::FETCH_ASSOC)){ ?>
                    <tr>
                      <th scope="col" style="width: 30%; font-size: 11px;"> <?php echo $dataMat['DESCRICAO'] . ' [ ' . $rowMat['PROPORCAO_MATERIAL'] . '% ]' ?> </th>
                      <td scope="col" style="width: 20%; text-align: right; font-size: 13px;"> <?php echo number_format($proporcao, 0, ',', '.') . ' ' . $rowMat['UNIDADE'] ?> </td>        
                      <td scope="col" style="width: 20%; text-align: right; font-size: 13px;"> <?php echo number_format($dataMat['QTDE_ESTOQUE'], 0, ',', '.') . ' ' . $dataMat['UNIDADE'] ?> </td>        
                      <?php
                        $condicao = $dataMat['QTDE_ESTOQUE'] - $proporcao;
                        if($condicao > 0){
                          $barra = 'alert alert-success'; $alerta = 'DISPONÍVEL';
                        }
                        if($condicao < 0){
                          $barra = 'alert alert-danger'; $alerta = 'INSUFICIENTE';
                        } ?>
                        <td scope="col" style="width: 10%; text-align: center; font-size: 13px"><div class="<?php echo $barra ?>" role="alert"><?php echo $alerta ?></div></td>
                    </tr><?php
                  }
                } 
              ?> 
            </tbody>               
          </table>
        </div>
        <button class="btn btn-outline-success" onclick="location.href='./33PedidoProduto4.php'">Pedido de Novo Lote</button>
      </div>
    </div>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->