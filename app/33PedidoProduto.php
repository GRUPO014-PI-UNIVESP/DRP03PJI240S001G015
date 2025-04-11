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
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div><h5>Pedido de Produto</h5></div>
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-2"> <?php 
          // verifica o identificador do último registro
          $queryLast = $connDB->prepare("SELECT MAX(NUMERO_PEDIDO) AS ULTIMO FROM pedidos");
          $queryLast->execute(); $rowID = $queryLast->fetch(PDO::FETCH_ASSOC); $novoID = $rowID['ULTIMO'] + 1;?>
          <label for="numPedido" class="form-label" style="font-size: 10px; color:aqua;">Pedido No.</label>
          <input style="font-size: 14px; text-align: center; color:yellow; background: rgba(0,0,0,0.3)" type="number" 
          class="form-control" id="numPedido" name="numPedido" value="<?php echo $novoID ?>" readonly>
        </div>
        <div class="col-md-2">
          <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
          <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="number" 
          class="form-control" id="qtdeLote" name="qtdeLote" autofocus required>
        </div>
        <div class="col-md-7">
          <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Produto</label>
          <select style="font-size: 14px; background: rgba(0,0,0,0.3)" class="form-select" id="nomeProduto" name="nomeProduto">
            <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o Produto</option><?php
              //Pesquisa de descrição do PRODUTO para seleção
              $query_produto = $connDB->prepare("SELECT DISTINCT PRODUTO FROM produtos"); $query_produto->execute();
              // inclui nome dos produtos como opções de seleção da tag <select>
              while($rowProduto = $query_produto->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)">
                  <?php echo $rowProduto['PRODUTO']; ?></option> <?php
              } ?>
          </select>
        </div>
        <div class="col-md-2">
          <input class="btn btn-primary" type="submit" id="pedido" name="pedido" value="Fazer Pedido">
        </div>
      </div><!-- Fim da div row -->
    </form><br> <?php
      $produto = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(!empty($produto['pedido']) && $produto['nomeProduto'] == 'Selecione o Produto'){?>
        <div class="alert alert-danger" role="alert">Nenhum produto foi selecionado! Reinicie o procedimento.</div>
        <div><button class="btn btn-danger" onclick="location.href='./33PedidoProduto.php'">Reiniciar</button></div><?php
      }
      if(!empty($produto['pedido']) && $produto['nomeProduto'] != 'Selecione o Produto'){
        $_SESSION['nomeProduto'] = $produto['nomeProduto'];
        $_SESSION['qtdeLote']    = $produto['qtdeLote'];
        $_SESSION['numPedido']   = $produto['numPedido']; 
        $nomeProd = $produto['nomeProduto']; 
        $qtdeLote = $produto['qtdeLote'];
        $numPedid = $produto['numPedido']; 
        $query_material = $connDB->prepare("SELECT * FROM produtos WHERE PRODUTO = :nomeProduto");
        $query_material->bindParam(':nomeProduto', $nomeProd, PDO::PARAM_STR); $query_material->execute();

        while($rowLista = $query_material->fetch(PDO::FETCH_ASSOC)){
          $_SESSION['capacidade'] = $rowLista['CAPAC_PROCESS'];          
          $descrMat = $rowLista['MATERIAL_COMPONENTE'];
          $uniMed = $rowLista['UNIDADE'];
          $qtdeMat = $qtdeLote * ($rowLista['PROPORCAO_MATERIAL'] / 100);

          $query_estoque = $connDB->prepare("SELECT ID_ESTOQUE, SUM(QTDE_ESTOQUE) AS TOTAL_ESTOQUE FROM materiais_estoque WHERE DESCRICAO = :material");
          $query_estoque->bindParam(':material', $descrMat, PDO::PARAM_STR); 
          $query_estoque->execute(); $resultEstoque = $query_estoque->fetch(PDO::FETCH_ASSOC);

          $query_reserva = $connDB->prepare("SELECT NUMERO_PEDIDO, SUM(QTDE_RESERVA) AS TOTAL_RESERVA FROM materiais_reserva WHERE ID_ESTOQUE = :idEstoque");
          $query_reserva->bindParam(':idEstoque', $resultEstoque['ID_ESTOQUE'], PDO::PARAM_INT);
          $query_reserva->execute(); $resultReserva = $query_reserva->fetch(PDO::FETCH_ASSOC);
          $qtdeDisponivel = $resultEstoque['TOTAL_ESTOQUE'] - $resultReserva['TOTAL_RESERVA'];

          if($qtdeDisponivel >= $qtdeMat){ $alerta = 'DISPONÍVEL'  ;}
          if($qtdeDisponivel < $qtdeMat) { $alerta = 'INSUFICIENTE';}
          if($alerta == 'DISPONÍVEL'){ $disp = 3;
            $veriEstoque = $connDB->prepare("SELECT ID_COMPRA FROM materiais_lotes WHERE ETAPA_PROCESS = 3 AND QTDE_LOTE > :qtdePedido ORDER BY ID_INTERNO ASC");
            $veriEstoque->bindParam(':qtdePedido', $_SESSION['qtdeLote'], PDO::PARAM_STR);
            $veriEstoque->execute(); $rowLote = $veriEstoque->fetch(PDO::FETCH_ASSOC); 

            $reserva = $connDB->prepare("INSERT INTO materiais_reserva (NUMERO_PEDIDO, ID_COMPRA, ID_ESTOQUE, DESCRICAO, QTDE_RESERVA, UNIDADE, DISPONIBILIDADE) 
                                                VALUES (:numPedido, :idCompra, :idEstoque, :descrMat, :qtdeReserva, :uniMed, :disp)");
            $reserva->bindParam(':numPedido'  , $numPedid                   , PDO::PARAM_INT);
            $reserva->bindParam(':idCompra'   , $rowLote['ID_COMPRA']       , PDO::PARAM_INT);
            $reserva->bindParam(':idEstoque'  , $resultEstoque['ID_ESTOQUE'], PDO::PARAM_STR);
            $reserva->bindParam(':descrMat'   , $descrMat                   , PDO::PARAM_STR);
            $reserva->bindParam(':qtdeReserva', $qtdeMat                    , PDO::PARAM_STR);
            $reserva->bindParam(':uniMed'     , $uniMed                     , PDO::PARAM_STR);
            $reserva->bindParam(':disp'       , $disp                       , PDO::PARAM_STR);
            $reserva->execute();
          }
          if($alerta == 'INSUFICIENTE'){ $dataPedi = date('Y-m-d'); $situacao = 'COMPRA AGENDADA'; $disp = 0;
            $compra = $connDB->prepare("INSERT INTO materiais_compra (ID_ESTOQUE, DESCRICAO, NUMERO_PEDIDO, PRODUTO, ETAPA_PROCESS, 
                                                  DATA_PEDIDO, QTDE_PEDIDO, UNIDADE, SITUACAO, CAPAC_PROCESS) 
                                               VALUES (:idEstoque, :descrMat, :numPedid, :nomeProd, :etapa,
                                                  :dataPedi, :qtdePedido, :uniMed, :situacao, :capacidade)");
            $compra->bindParam(':idEstoque' , $resultEstoque['ID_ESTOQUE'], PDO::PARAM_INT);
            $compra->bindParam(':descrMat'  , $descrMat                   , PDO::PARAM_STR);
            $compra->bindParam(':numPedid'  , $numPedid                   , PDO::PARAM_INT);
            $compra->bindParam(':nomeProd'  , $nomeProd                   , PDO::PARAM_STR);
            $compra->bindParam(':etapa'     , $disp                       , PDO::PARAM_INT);
            $compra->bindParam(':dataPedi'  , $dataPedi                   , PDO::PARAM_STR);
            $compra->bindParam(':qtdePedido', $qtdeMat                    , PDO::PARAM_STR);
            $compra->bindParam(':uniMed'    , $uniMed                     , PDO::PARAM_STR);
            $compra->bindParam(':situacao'  , $situacao                   , PDO::PARAM_STR);
            $compra->bindParam(':capacidade', $_SESSION['capacidade']     , PDO::PARAM_INT);
            $compra->execute();

            $buscaIDcompra = $connDB->prepare("SELECT ID_COMPRA FROM materiais_compra WHERE NUMERO_PEDIDO = :numPedido AND ID_ESTOQUE = :idEstoque");
            $buscaIDcompra->bindParam(':numPedido', $numPedid                   , PDO::PARAM_INT);
            $buscaIDcompra->bindParam(':idEstoque', $resultEstoque['ID_ESTOQUE'], PDO::PARAM_INT);
            $buscaIDcompra->execute(); $idCompra = $buscaIDcompra->fetch(PDO::FETCH_ASSOC);

            $reserva = $connDB->prepare("INSERT INTO materiais_reserva (NUMERO_PEDIDO, ID_COMPRA, ID_ESTOQUE, DESCRICAO, QTDE_RESERVA,
                                                  UNIDADE, DISPONIBILIDADE)
                                                VALUES (:numPedido, :idCompra, :idEstoque, :descrMat, :qtdeReserva,
                                                  :uniMed, :disp)");
            $reserva->bindParam(':numPedido'  , $numPedid                   , PDO::PARAM_INT);
            $reserva->bindParam(':idCompra'   , $idCompra['ID_COMPRA']      , PDO::PARAM_INT);
            $reserva->bindParam(':idEstoque'  , $resultEstoque['ID_ESTOQUE'], PDO::PARAM_STR);
            $reserva->bindParam(':descrMat'   , $descrMat                   , PDO::PARAM_STR);
            $reserva->bindParam(':qtdeReserva', $qtdeMat                    , PDO::PARAM_STR);
            $reserva->bindParam(':uniMed'     , $uniMed                     , PDO::PARAM_STR);
            $reserva->bindParam(':disp'       , $disp                       , PDO::PARAM_STR);
            $reserva->execute();            
          }
        }
        header('Location: ./34PedidoProduto.php');
      } ?>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->