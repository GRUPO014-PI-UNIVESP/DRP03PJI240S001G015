<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Produção';
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
       time = setTimeout(deslogar, 6000000);
     }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <div class="row g-2">
      <div class="col-md-12"><br>
        <h5>Registro de Execução de Fabricação</h5><br>
        <h6 style="color:aqua">Informações do Pedido</h6>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="dataPedido" name="dataPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
                 value="<?php echo date('d/m/Y', strtotime($_SESSION['dataPedido'])) ?>" readonly>
          <label for="dataPedido" style="color: aqua; font-size: 12px; background: none">Data do Pedido</label>
        </div>
      </div>
      <div class="col-md-1">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="numPedido" name="numPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
                 value="<?php echo $_SESSION['numPedido'] ?>" readonly>
          <label for="numPedido" style="color: aqua; font-size: 12px; background: none">Pedido No.</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" 
                 value="<?php echo $_SESSION['nomeProduto'] ?>" readonly>
          <label for="nomeProduto" style="color: aqua; font-size: 12px; background: none">Nome do Produto</label>
        </div>
      </div>
      <div class="col-md-3"></div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="qtdeLote" name="qtdeLote" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center;color: yellow" 
              value="<?php echo number_format($_SESSION['qtdePedido'] , 0, ',', '.') . ' ' . $_SESSION['unidade'] ?>" readonly>
            <label for="qtdeLote" style="color: aqua; font-size: 12px; background: none">Quantidade do Pedido</label>
          </div>
        </div>
        <div class="col-md-7">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="cliente" name="cliente" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $_SESSION['cliente']  ?>" readonly>
            <label for="cliente" style="color: aqua; font-size: 12px; background: none">Cliente</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-12">
          <h6 style="color:aqua">Informações da Fabricação</h6>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="date" class="form-control" id="dataFabri" name="dataFabri" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: whitesmoke" 
              value="<?php echo date('d/m/Y', strtotime($_SESSION['dataFabri'])) ?>" readonly>
            <label for="dataFabri" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label>
          </div>
        </div>
        <div class="col-md-2">
        <div class="form-floating mb-2">
            <input type="date" class="form-control" id="planta" name="planta" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: whitesmoke" 
              value="<?php echo $_SESSION['planta']  ?>" readonly>
            <label for="planta" style="color: aqua; font-size: 12px; background: none">Planta</label>
          </div>         
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="inicio" name="inicio" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="<?php echo date('H:i', strtotime($_SESSION['inicio'])) ?>">
            <label for="inicio" style="color: aqua; font-size: 12px; background: none">Início do Processamento</label>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="fim" name="fim" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="<?php echo date('H:i', strtotime($_SESSION['fim'])) ?>">
            <label for="fim" style="color: aqua; font-size: 12px; background: none">Encerramento</label>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="number" class="form-control" id="qtdeProd" name="qtdeProd" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="<?php echo number_format($_SESSION['qtdeProd'] , 0, ',', '.') . ' ' . $_SESSION['unidade'] ?>">
            <label for="fim" style="color: aqua; font-size: 12px; background: none">Quantidade Produzida</label>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="nLotePF" name="nLotePF" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color:whitesmoke" 
              value="<?php echo $_SESSION['planta']  ?>" readonly>
            <label for="nLotePF" style="color: aqua; font-size: 12px; background: none">Identificação do Lote</label>
          </div>
        </div>
        <div class="col-md-4"></div>
        <h6 atyle="color: aqua">Materiais Utilizados</h6>
        <div class="overflow-auto">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col" style="font-size: 13px; width: 30%; color: gray">Descrição do Material</th>
                <th scope="col" style="font-size: 13px; width: 10%; color: gray; text-align: center;">Qtde Necessária</th>
                <th scope="col" style="font-size: 13px; width: 10%; color: gray; text-align: center;">Qtde Disponível</th>
                <th scope="col" style="font-size: 13px; width: 10%; color: gray; text-align: center;">Qtde Utilizada</th>
              </tr>
            </thead>
            <tbody><?php
              $query_material = $connDB->prepare("SELECT * FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
              $query_material->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
              $query_material->execute(); $nTipos = $query_material->rowCount(); $i = 0; 
              while($rowMat = $query_material->fetch(PDO::FETCH_ASSOC)){
                $qtdeNecessaria = $rowMat['QTDE_RESERVA'];
                $query_estoque = $connDB->prepare("SELECT DESCRICAO, ID_INTERNO, QTDE_LOTE, UNIDADE FROM materiais_lotes WHERE ID_ESTOQUE = :idEstoque AND QTDE_LOTE > 1 LIMIT 3 ORDER BY ID_INTERNO, QTDE_LOTE ASC");
                $query_estoque->bindParam(':idEstoque', $rowMat['ID_ESTOQUE'], PDO::PARAM_INT);
                $query_estoque->execute(); $j = 0;
                while($rowStk = $query_estoque->fetch(PDO::FETCH_ASSOC)){ $inputName[$i][$j] = 'id' . $i . $j ;  ?>
                  <tr>
                    <td scope="col" style="width: 30%; font-size: 13px; color: yellow;">
                      <?php echo $rowStk['DESCRICAO']; ?>
                    </td>
                    <td scope="col" style="width: 10%; font-size: 16px; color: yellow; text-align: center;">
                      <?php echo number_format($rowMat['QTDE_RESERVA'], 0, ',', '.') . ' ' . $rowMat['UNIDADE']; ?>
                    </td>
                    <td scope="col" style="width: 10%; font-size: 16px; color: yellow; text-align: center;">
                      <?php echo number_format($rowStk['QTDE_LOTE'], 0, ',', '.') . ' ' . $rowStk['UNIDADE']; ?>
                    </td>
                    <td scope="col" style="width: 10%; font-size: 16px; text-align: center;">
                      <input style="width: 120px; background: rgba(0,0,0,0.3);  text-align: center;" type="text" id="<?php echo $inputName[$i][$j] ?>" name="<?php echo $inputName[$i][$j] ?>" 
                             value="<?php echo number_format($rowStk['QTDE_LOTE'], 0, ',', '.') ?>">
                    </td>
                  </tr> <?php $j = $j++ ;
                } $i = $i++ ;
              } ?>
            </tbody>           
          </table>
        </div>
        <div class="col-md-12"></div>
        <div class="col-md-2">
          <div class="form-floating mb-3"><br>
            <input class="btn btn-primary" type="submit" id="confirma" name="confirma" value="Confirmar" style="font-size:18px; width: 160px">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-3"><br>
            <input class="btn btn-danger" type="reset" id="descarta" name="descarta" value="Descartar" style="font-size:18px; width: 160px" onclick="location.href='./03SeletorProducao.php'">
          </div>
        </div>  
      </div><!-- fim da row g2 -->
    <?php


    /*
    if(!empty($confirma['confirma'])){
      $dataFabri = date('Y-m-d', strtotime($confirma['dataFabri']));
      $situacao = 'FABRICAÇÃO FINALIZADA';
      
      // retira pedido da fila de ocupação da planta de fabricação
      $tiraFila = $connDB->prepare("DELETE FROM fila_ocupacao WHERE NUMERO_PEDIDO = :numPedido");
      $tiraFila->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
      $tiraFila->execute();

      // registra processamento do pedido
      $registra = $connDB->prepare("INSERT INTO producao (NUMERO_PEDIDO, DATA_FABRICACAO, NOME_PRODUTO, QTDE_PRODUZIDA, PLANTA, HORA_INICIO, HORA_FIM, NUMERO_LOTE_PF, SITUACAO_QUALI)
                                           VALUES (:numPedido, :dataFabri, :nomeProduto, :qtdeProd, :planta, :horaIni, :horaFim, :nLotePF, :situacao)");
      $registra->bindParam(':numPedido'  , $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
      $registra->bindParam(':dataFabri'  , $dataFabri                 , PDO::PARAM_STR);
      $registra->bindParam(':nomeProduto', $rowPedido['NOME_PRODUTO'] , PDO::PARAM_STR);
      $registra->bindParam(':qtdeProd'   , $confirma['qtdeProd']      , PDO::PARAM_INT);
      $registra->bindParam(':planta'     , $confirma['planta']        , PDO::PARAM_STR);
      $registra->bindParam(':horaIni'    , $confirma['inicio']        , PDO::PARAM_STR);
      $registra->bindParam(':horaFim'    , $confirma['fim']           , PDO::PARAM_STR);
      $registra->bindParam(':nLotePF'    , $confirma['nLotePF']       , PDO::PARAM_STR);
      $registra->bindParam(':situacao'   , $situacao                  , PDO::PARAM_STR);
      $registra->execute();

      // atualiza dados do pedido
      $etapa = 2;
      $atualizaPedido = $connDB->prepare("UPDATE pf_pedido SET ETAPA_PROD = :etapa, DATA_FABRI = :dataFabri, SITUACAO_QUALI = :situacao, NUMERO_LOTE_PF = :nLotePF,
                                                                      N_LOTE_SEQ = :nLoteSeq, N_LOTE_MES = :nLoteMes, N_LOTE_ANO = :nLoteAno, REGISTRO_PRODUCAO = :responsavel
                                                                  WHERE NUMERO_PEDIDO = :numPedido ");
      $atualizaPedido->bindParam(':etapa'      , $etapa                     , PDO::PARAM_INT);
      $atualizaPedido->bindParam(':dataFabri'  , $dataFabri                 , PDO::PARAM_STR);
      $atualizaPedido->bindParam(':situacao'   , $situacao                  , PDO::PARAM_STR);
      $atualizaPedido->bindParam(':nLotePF'    , $confirma['nLotePF']       , PDO::PARAM_STR);
      $atualizaPedido->bindParam(':nLoteSeq'   , $seqAtual                  , PDO::PARAM_STR);
      $atualizaPedido->bindParam(':nLoteMes'   , $mesAtual                  , PDO::PARAM_STR);
      $atualizaPedido->bindParam(':nLoteAno'   , $anoAtual                  , PDO::PARAM_STR);
      $atualizaPedido->bindParam(':responsavel', $_SESSION['nome_func']     , PDO::PARAM_STR);
      $atualizaPedido->bindParam(':numPedido'  , $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_STR);
      $atualizaPedido->execute();
      
      // atualiza estoque de materiais
      $j = 1;
      $listaMat = $connDB->prepare("SELECT * FROM pf_tabela WHERE NOME_PRODUTO = :nomeProduto");
      $listaMat->bindParam(':nomeProduto', $rowPedido['NOME_PRODUTO'], PDO::PARAM_STR);
      $listaMat->execute(); $nComponentes = $listaMat->rowCount();
      while($rowMat = $listaMat->fetch(PDO::FETCH_ASSOC)){
        $i = 1;
        $loteMat = $connDB->prepare("SELECT * FROM mp_estoque WHERE DESCRICAO_MP = :descrMat");
        $loteMat->bindParam(':descrMat', $rowMat['DESCRICAO_MP'], PDO::PARAM_STR);
        $loteMat->execute(); $nLoteMat = $loteMat->rowCount(); $qtdeMat = $rowPedido['QTDE_LOTE_PF'] * ($rowMat['PROPORCAO_MATERIAL'] / 100);
        while($rowLote = $loteMat->fetch(PDO::FETCH_ASSOC)){ 
          $idComp = $idLote[$j][$i];
          $qUse   = $campo[$j][$i];
          if($idComp == $rowLote['NUMERO_LOTE_INTERNO']){
            $atual  = $rowLote['QTDE_ESTOQUE'] - $confirma[$qUse];
            $retira = $rowLote['QTDE_RESERVADA'] - $confirma[$qUse];
            if($retira < 0){
              $retira = 0;
            }
            $atualizaEstoque = $connDB->prepare("UPDATE mp_estoque SET QTDE_ESTOQUE = :estoque, QTDE_RESERVADA = :reserva WHERE NUMERO_LOTE_INTERNO = :nLoteI ");
            $atualizaEstoque->bindParam(':estoque', $atual , PDO::PARAM_STR);
            $atualizaEstoque->bindParam(':reserva', $retira, PDO::PARAM_STR);
            $atualizaEstoque->bindParam(':nLoteI' , $idComp, PDO::PARAM_STR);
            $atualizaEstoque->execute();
          }
          $i = $i +1;
        }
        $j = $j + 1;
      }
      header('Location: ./03SeletorProducao.php');         
    } */ ?>
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->