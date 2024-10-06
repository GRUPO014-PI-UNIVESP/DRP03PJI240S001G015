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
       time = setTimeout(deslogar, 3000000);
     }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <form method="POST">
      <div class="row g-2">
        <div class="col-md-12"><br>
          <h5>Registro de Execução de Fabricação</h5><br>
          <h6 style="color:aqua">Informações do Pedido</h6>
        </div><?php
        $dadosPedido = $connDB->prepare("SELECT * FROM pf_pedido WHERE NUMERO_PEDIDO = :idPed");
        $dadosPedido->bindParam(':idPed', $_GET['id'], PDO::PARAM_INT);
        $dadosPedido->execute(); $rowPedido = $dadosPedido->fetch(PDO::FETCH_ASSOC); ?>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="dataPedido" name="dataPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_PEDIDO'])) ?>" readonly>
            <label for="dataPedido" style="color: aqua; font-size: 12px; background: none">Data do Pedido</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="numPedido" name="numPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $rowPedido['NUMERO_PEDIDO'] ?>" readonly>
            <label for="numPedido" style="color: aqua; font-size: 12px; background: none">Pedido No.</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $rowPedido['NOME_PRODUTO'] ?>" readonly>
            <label for="nomeProduto" style="color: aqua; font-size: 12px; background: none">Nome do Produto</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="qtdeLote" name="qtdeLote" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center;color: yellow" 
              value="<?php echo $rowPedido['QTDE_LOTE_PF'] . ' ' . $rowPedido['UNIDADE_MEDIDA'] ?>" readonly>
            <label for="qtdeLote" style="color: aqua; font-size: 12px; background: none">Quantidade do Pedido</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-7">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="cliente" name="cliente" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $rowPedido['CLIENTE'] ?>" readonly>
            <label for="cliente" style="color: aqua; font-size: 12px; background: none">Cliente</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div><?php
        // algoritmo para geração de numero de lote interno
        // verifica último lote registrado
        $ultimo = $connDB->prepare("SELECT MAX(N_LOTE_SEQ) AS U_SEQ, MAX(N_LOTE_MES) AS U_MES, MAX(N_LOTE_ANO) AS U_ANO FROM pf_pedido");
        $ultimo->execute(); $resultado = $ultimo->fetch(PDO::FETCH_ASSOC);

        $codMes = intval(date('m')); $codAno = intval(date('y'));
        $codLetra = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', );
        if(!empty($resultado['U_SEQ'])){
          if($resultado['U_ANO'] < $codAno){ $anoAtual = $resultado['U_ANO'] + 1; 
          } else {$anoAtual = $resultado['U_ANO'];}
    
          if($resultado['U_MES'] < $codMes){ $mesAtual = $resultado['U_MES'] + 1; $seqAtual = 1; 
          } else {$mesAtual = $resultado['U_MES']; $seqAtual = $resultado['U_SEQ'] + 1;}
    
          if($seqAtual < 10){ $seqLote = '00' . $seqAtual;}
          if($seqAtual >=10 && $seqAtual < 100){ $seqLote = '0' . $seqAtual;} 
          if($seqAtual >= 100){ $seqLote = $seqAtual;}
        }
        if(empty($resultado['U_SEQ'])){
          $seqLote = '001'; $seqAtual = 1; $mesAtual = intval(date('m')); $anoAtual = intval(date('y'));
        }
        $nLoteInterno = $seqLote . ' ' . $codLetra[$mesAtual] . ' ' . $anoAtual; ?>
        <div class="col-md-12">
          <h6 style="color:aqua">Informações da Fabricação</h6>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="date" class="form-control" id="dataFabri" name="dataFabri" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: whitesmoke" 
              value="" required autofocus>
            <label for="dataFabri" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label>
            <p style="font-size: 11px; color: grey">Inserir data</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating">
            <select class="form-select" id="planta" name="planta" aria-label="Floating label select example" style="font-weight: bolder; background: rgba(0,0,0,0.3)">
              <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione</option>
              <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)">ALFA 1.0</option>
              <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)">BETA 2.0</option>
              <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)">OMEGA 4.0</option>
            </select><label style="color: aqua; font-size: 12px; background: none" for="planta">Planta</label>
            <p style="font-size: 11px; color: grey">Selecione a planta</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="inicio" name="inicio" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="<?php echo date('H:i') ?>" required>
            <label for="inicio" style="color: aqua; font-size: 12px; background: none">Início do Processamento</label>
            <p style="font-size: 11px; color: grey">Inserir hora</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="fim" name="fim" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="<?php echo date('H:i') ?>" required>
            <label for="fim" style="color: aqua; font-size: 12px; background: none">Encerramento</label>
            <p style="font-size: 11px; color: grey">Inserir hora</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="number" class="form-control" id="qtdeProd" name="qtdeProd" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="" required>
            <label for="fim" style="color: aqua; font-size: 12px; background: none">Quantidade Produzida</label>
            <p style="font-size: 11px; color: grey">Inserir a quantidade gerada</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="nLotePF" name="nLotePF" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color:whitesmoke" 
              value="<?php echo $nLoteInterno ?>" readonly>
            <label for="nLotePF" style="color: aqua; font-size: 12px; background: none">Identificação do Lote</label>
            <p style="font-size: 11px; color: grey">Gerado automaticamente</p>
          </div>
        </div>
        <div class="col-md-4"></div>
        <h6 atyle="color: aqua">Materiais Utilizados</h6> <?php
          $listaMat = $connDB->prepare("SELECT * FROM pf_tabela WHERE NOME_PRODUTO = :nomeProduto");
          $listaMat->bindParam(':nomeProduto', $rowPedido['NOME_PRODUTO'], PDO::PARAM_STR);
          $listaMat->execute(); $nComponentes = $listaMat->rowCount(); $j = 1;
          while($rowMat = $listaMat->fetch(PDO::FETCH_ASSOC)){
            $loteMat = $connDB->prepare("SELECT * FROM mp_estoque WHERE DESCRICAO_MP = :material");
            $loteMat->bindParam(':material', $rowMat['DESCRICAO_MP'], PDO::PARAM_STR);
            $loteMat->execute(); $nLoteMat = $loteMat->rowCount(); $qtdeMat = $rowPedido['QTDE_LOTE_PF'] * ($rowMat['PROPORCAO_MATERIAL'] / 100); ?>
            <div class="col-md-4" style="border-style: ridge; border-color: grey; border-radius: 6px; padding: 5px">
              <h6 style="color: yellow; font-size: 12px"><?php echo $rowMat['DESCRICAO_MP'] . ' [ Qtde Necessária: ' . $qtdeMat . ' ' . $rowMat['UNIDADE_MEDIDA'] . ' ]' ?></h6> <?php $i = 1; 
              while($rowLote = $loteMat->fetch(PDO::FETCH_ASSOC)){ 
                $campo[$j][$i] = 'campo' . $j . $i; $idLote[$j][$i] = $rowLote['NUMERO_LOTE_INTERNO']; ?>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="<?php echo $campo[$j][$i] ?>"><?php echo $rowLote['NUMERO_LOTE_INTERNO'] ?></span>
                  <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"
                          id="<?php echo $campo[$j][$i] ?>" name="<?php echo $campo[$j][$i] ?>" style="background: rgba(0,0,0,0.3); text-align:center" required>
                  <span class="input-group-text" id="<?php echo $campo[$j][$i] ?>"><?php echo $rowLote['UNIDADE_MEDIDA'] ?></span>
                </div> <?php echo $campo[$j][$i]; $i = $i + 1;
              } ?>
            </div><?php $j = $j + 1;
          } ?>
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
    </form> <?php
    $confirma = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($confirma['confirma'])){
      $dataFabri = date('Y-m-d', strtotime($confirma['dataFabri']));
      $situacao = 'FABRICAÇÃO FINALIZADA';
      
      // retira pedido da fila de ocupação da planta de fabricação
      $tiraFila = $connDB->prepare("DELETE FROM fila_ocupacao WHERE PEDIDO_NUM = :numPedido");
      $tiraFila->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
      $tiraFila->execute();

      // registra processamento do pedido
      $registra = $connDB->prepare("INSERT INTO pf_finalizado (NUMERO_PEDIDO, DATA_FABRICACAO, NOME_PRODUTO, QTDE_PRODUZIDA, PLANTA, HORA_INICIO, HORA_FIM, NUMERO_LOTE_PF, SITUACAO_QUALI)
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
    } ?>
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->