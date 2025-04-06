<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Compra de Material'; include_once './RastreadorAtividades.php';

?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() {
      <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>
      window.location.href = 'LogOut.php';
    }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);}
  }; inactivityTime();
</script>
<div class="main"><!-- Compra de material e insumos agendados por pedido de produto -->
  <div class="container-fluid"><br>
    <form method="POST" id="agendado">
      <div class="row g-3">
        <h5>Efetivação de Compra de Material</h5>
        <?php
        if(!empty($_GET['id'])){$idCompra = $_GET['id'];
          $busca = $connDB->prepare("SELECT * FROM materiais_compra WHERE ID_COMPRA = :idCompra");
          $busca->bindParam(':idCompra', $idCompra, PDO::PARAM_INT); $busca->execute(); $rowMat = $busca->fetch(PDO::FETCH_ASSOC);
          $dataAgenda = date('Y-m-d', strtotime($rowMat['DATA_PEDIDO'])); ?>
          
          <div class="col-md-7">
            <label for="descrMat" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
            <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color:yellow" type="text" class="form-control" 
                  id="descrMat" name="descrMat" value="<?php echo $rowMat['DESCRICAO'] ?>" readonly>
          </div>
          <div class="col-md-5"></div>
          <div class="col-md-2">
            <label for="dataAgenda" class="form-label" style="font-size: 10px; color:aqua">Data da Solicitação</label>
            <input style="font-weight: bold; font-size: 20px; background: rgba(0,0,0,0.3); text-align: center; color:yellow" type="text" class="form-control" 
                  id="dataAgenda" name="dataAgenda" value="<?php echo date('d/m/Y', strtotime($rowMat['DATA_PEDIDO'])) ?>" readonly>
          </div>
          <div class="col-md-2">
            <label for="dataPrazo" class="form-label" style="font-size: 10px; color:aqua">Data Prazo de Recebimento</label>
            <input style="font-weight: bold; font-size: 20px; background: rgba(0,0,0,0.3); text-align: center; color:yellow" type="text" class="form-control" 
                  id="dataPrazo" name="dataPrazo" value="<?php echo date('d/m/Y', strtotime($rowMat['DATA_PRAZO'])) ?>" readonly>
          </div>
          <div class="col-md-8"></div>
          <div class="col-md-2">
            <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade Necessária</label>
            <input style="font-weight: bold; font-size: 25px; background: rgba(0,0,0,0.3); text-align: center" type="text" class="form-control" 
                  id="qtdeLote" name="qtdeLote" value="<?php echo $rowMat['QTDE_PEDIDO']?>" autofocus required>
            <p style="font-size: 13px; color: grey">Aumente a quantidade caso seja necessário</p>
          </div>
          <div class="col-md-2">
            <label for="uniMed" class="form-label" style="font-size: 10px; color:aqua">Unidade de Medida</label>
            <input style="width: 75px; font-weight: bold; font-size: 25px; background: rgba(0,0,0,0.3); text-align: center; color:yellow" type="text" class="form-control" 
                  id="uniMed" name="uniMed" value="<?php echo $rowMat['UNIDADE'] ?>" readonly>
          </div>
          <div class="col-md-2">
            <br><br>
            <input class="btn btn-primary" type="submit" id="agendado" name="agendado" value="Confirmar e Autorizar Compra">
          </div><?php 
        } ?>
      </div>
    </form><?php $confirmaCompra = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($confirmaCompra['agendado'])){ $etapa = 1; $situacao = 'COMPRA EFETUADA, AGUARDANDO RECEBIMENTO'; $qtdeCompra = $confirmaCompra['qtdeLote'];
      $realiza = $connDB->prepare("INSERT INTO materiais_lotes (ID_COMPRA, ID_ESTOQUE, DESCRICAO, QTDE_LOTE, UNIDADE, ETAPA_PROCESS, SITUACAO, DATA_PRAZO) VALUES (:idCompra, :idEstoque, :descrMat, :qtdeLote, :uniMed, :etapa, :situacao, :dataPrazo)");
      $realiza->bindParam(':idCompra' , $_GET['id']          , PDO::PARAM_INT);
      $realiza->bindParam(':idEstoque', $rowMat['ID_ESTOQUE'], PDO::PARAM_INT);                                   
      $realiza->bindParam(':descrMat' , $rowMat['DESCRICAO'] , PDO::PARAM_STR);
      $realiza->bindParam(':qtdeLote' , $qtdeCompra          , PDO::PARAM_STR);
      $realiza->bindParam(':uniMed'   , $rowMat['UNIDADE']   , PDO::PARAM_STR);
      $realiza->bindParam(':etapa'    , $etapa               , PDO::PARAM_INT);
      $realiza->bindParam(':situacao' , $situacao            , PDO::PARAM_STR);
      $realiza->bindParam(':dataPrazo', $rowMat['DATA_PRAZO'], PDO::PARAM_STR);
      $realiza->execute();

      $dCompra = date('Y-m-d');
      $atualiza = $connDB->prepare("UPDATE materiais_compra SET ETAPA_PROCESS = :etapa, QTDE_PEDIDO = :qtdeLote, SITUACAO = :situacao, DATA_COMPRA = :dCompra WHERE ID_COMPRA = :idCompra");
      $atualiza->bindParam(':idCompra', $_GET['id'], PDO::PARAM_STR);
      $atualiza->bindParam(':etapa'   , $etapa     , PDO::PARAM_INT);
      $atualiza->bindParam(':qtdeLote', $qtdeCompra, PDO::PARAM_STR);
      $atualiza->bindParam(':situacao', $situacao  , PDO::PARAM_STR);
      $atualiza->bindParam(':dCompra' , $dCompra   , PDO::PARAM_STR);
      $atualiza->execute();

      $atualizaPedido = $connDB->prepare("UPDATE pedidos SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao WHERE NUMERO_PEDIDO = :numPedido");
      $atualizaPedido->bindParam(':numPedido', $rowMat['NUMERO_PEDIDO'], PDO::PARAM_INT);
      $atualizaPedido->bindParam(':etapa'    , $etapa                  , PDO::PARAM_INT);
      $atualizaPedido->bindParam(':situacao' , $situacao               , PDO::PARAM_STR);
      $atualizaPedido->execute();

      header('Location: ./12SetorCompras.php');
    } ?>
  </div>
</div>