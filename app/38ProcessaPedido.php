<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Produção'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() {
      <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php';?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 600000);}
  }; inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <div class="row g-2">
      <div class="col-md-12"><br>
        <h5>Registro de Execução de Fabricação</h5><br> <h6 style="color:aqua">Informações do Pedido</h6>
      </div><?php $dadosPedido = $connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :idPed");
      $dadosPedido->bindParam(':idPed', $_SESSION['idPedido'], PDO::PARAM_INT); $dadosPedido->execute(); $rowPedido = $dadosPedido->fetch(PDO::FETCH_ASSOC); ?>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="dataPedido" name="dataPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_PEDIDO'])) ?>" readonly>
          <label for="dataPedido" style="color: aqua; font-size: 12px; background: none">Data do Pedido</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-1">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="numPedido" name="numPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowPedido['NUMERO_PEDIDO'] ?>" readonly>
          <label for="numPedido" style="color: aqua; font-size: 12px; background: none">Pedido No.</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowPedido['PRODUTO'] ?>" readonly>
          <label for="nomeProduto" style="color: aqua; font-size: 12px; background: none">Nome do Produto</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-3"></div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="qtdePedido" name="qtdePedido" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center;color: yellow" value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
          <label for="qtdePedido" style="color: aqua; font-size: 12px; background: none">Quantidade do Pedido</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="cliente" name="cliente" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowPedido['CLIENTE'] ?>" readonly>
          <label for="cliente" style="color: aqua; font-size: 12px; background: none">Cliente</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div><?php
      // algoritmo para geração de numero de lote interno
      // verifica último lote registrado
      $ultimo = $connDB->prepare("SELECT MAX(NLPSEQ) AS U_SEQ, MAX(NLPMES) AS U_MES, MAX(NLPANO) AS U_ANO FROM producao"); $ultimo->execute(); $resultado = $ultimo->fetch(PDO::FETCH_ASSOC);

      $codMes = intval(date('m')); $codAno = intval(date('y')); $codLetra = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', );
      if(!empty($resultado['U_SEQ'])){ if($resultado['U_ANO'] < $codAno){ $anoAtual = $resultado['U_ANO'] + 1; } else {$anoAtual = $resultado['U_ANO'];}   
        if($resultado['U_MES'] < $codMes){ $mesAtual = $resultado['U_MES'] + 1; $seqAtual = 1; } else {$mesAtual = $resultado['U_MES']; $seqAtual = $resultado['U_SEQ'] + 1;}   
        if($seqAtual < 10){ $seqLote = '00' . $seqAtual;} if($seqAtual >=10 && $seqAtual < 100){ $seqLote = '0' . $seqAtual;} if($seqAtual >= 100){ $seqLote = $seqAtual;}
      }
      if(empty($resultado['U_SEQ'])){ $seqLote = '001'; $seqAtual = 1; $mesAtual = intval(date('m')); $anoAtual = intval(date('y')); } $nLoteInterno = $seqLote . ' ' . $codLetra[$mesAtual] . ' ' . $anoAtual; ?>
      <div class="col-md-12"> <h6 style="color:aqua">Informações da Fabricação</h6> </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="date" class="form-control" id="dataFabri" name="dataFabri" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['dataFabri'] ?>" readonly>
          <label for="dataFabri" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="planta" name="planta" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['planta'] ?>" readonly>
          <label for="planta" style="color: aqua; font-size: 12px; background: none">Planta</label> <p style="font-size: 11px; color: grey"></p>
        </div>         
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="inicio" name="inicio" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" value="<?php echo date('H:i', strtotime($_SESSION['horaInicio'])); ?>" readonly>
          <label for="inicio" style="color: aqua; font-size: 12px; background: none">Início do Processamento</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="fim" name="fim" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" value="<?php echo date('H:i', strtotime($_SESSION['horaFinali'])); ?>" readonly>
          <label for="fim" style="color: aqua; font-size: 12px; background: none">Encerramento</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="qtdeReal" name="qtdeReal" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" value="<?php echo number_format($_SESSION['qtdeReal'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
          <label for="fim" style="color: aqua; font-size: 12px; background: none">Quantidade Produzida</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="nLotePF" name="nLotePF" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['nLoteProd'] ?>" readonly>
          <label for="nLotePF" style="color: aqua; font-size: 12px; background: none">Identificação do Lote</label> <p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <h6 style="color:aqua">Materias Utilizados</h6>
      <div class="col-md-12 overflow-auto">      
        <table class="table"> <p style="width: 100%; color: orangered; text-align: center">Ajuste a quantidade dos materiais utilizados em cada lote disponível e confirme o consumo com qualquer botão</p>
          <thead class="table-dark">
            <tr style="font-size: 11px">
              <th scope="col" style="width: 30%; color: grey;"                   >Descrição do Material</th>
              <th scope="col" style="width: 10%; text-align:center; color: grey;">Qtde. Necessária</th>
              <th scope="col" style="width: 10%; text-align:center; color: grey;">ID Interno</th>
              <th scope="col" style="width: 10%; text-align:center; color: grey;">Qtde. Disponível</th>
              <th scope="col" style="width: 15%; text-align:center; color: grey;">Qtde. Utilizada</th>
              <th scope="col" style="width: 10%; text-align:center; color: grey;">Ação</th>
            </tr>
          </thead>
          <tbody><?php $finalizado = 0;
            $query_materiais = $connDB->prepare("SELECT * FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
            $query_materiais->bindParam(':numPedido', $_SESSION['idPedido'], PDO::PARAM_INT); $query_materiais->execute(); $nTipos = $query_materiais->rowCount();
            // verifica cada item da lista de componentes do produto
            while($rowMats = $query_materiais->fetch(PDO::FETCH_ASSOC)){
              $query_lotes = $connDB->prepare("SELECT ID_ESTOQUE, DESCRICAO, ID_INTERNO, QTDE_LOTE, UNIDADE FROM materiais_lotes WHERE ID_ESTOQUE = :idEstoque");
              $query_lotes->bindParam(':idEstoque', $rowMats['ID_ESTOQUE'], PDO::PARAM_INT); $query_lotes->execute();
              while($rowLotes = $query_lotes->fetch(PDO::FETCH_ASSOC)){ ?> 
                <form method="POST">                          
                  <tr>
                    <td scope="col" style="width: 30%; color: yellow; font-size: 14px"> <?php echo $rowLotes['DESCRICAO'] ?> </td>
                    <td scope="col" style="width: 10%; color: yellow; font-size: 20px; text-align:center; font-weight: bolder"><?php echo number_format($rowMats['QTDE_RESERVA'], 0, ',', '.') . ' ' . $rowMats['UNIDADE'] ?></td>
                    <td scope="col" style="width: 10%; color: yellow; font-size: 20px; text-align:center; font-weight: bolder"><?php echo $rowLotes['ID_INTERNO'] ?></td>
                    <td scope="col" style="width: 10%; color: green; font-size: 20px; text-align:center; font-weight: bolder" ><?php echo number_format($rowLotes['QTDE_LOTE'], 0, ',', '.') . ' ' . $rowLotes['UNIDADE'] ?></td>
                    <td scope="col" style="width: 15%; color: green; font-size: 20px; text-align:center; font-weight: bolder">
                      <div class="input-group mb-3">
                        <input type="number" class="form-control" aria-label="username" aria-describedby="qtdeUsada" id="qtdeUsada" name="qtdeUsada" autofocus required>
                        <span class="input-group-text" id="qtdeUsada"><?php echo $rowLotes['UNIDADE'] ?></span>
                      </div>
                    </td>
                    <td scope="col" style="width: 10%;"><input class="btn btn-primary" style="width: 100%" type="submit" id="confirma" name="confirma" value="Confirmar"></td>
                  </tr>
                </form><?php
                $confirmaP = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($confirmaP['confirma'])){ $sobra = $rowLotes['QTDE_LOTE'] - $confirmaP['qtdeUsada']; $falta = $rowMats['QTDE_RESERVA'] - $confirmaP['qtdeUsada'];
                  if($falta == 0){$etapa = 4; $situacao = 'LOTE ESGOTADO';} if($falta >= 1){$etapa = 3; $situacao = 'MATERIAL LIBERADO PARA USO';}

                  $regProd = $connDB->prepare("INSERT INTO producao (ID_PRODUTO, NUMERO_LOTE, ID_MATERIAL, MATERIAL_COMPONENTE, QTDE_UTILIZADA, NLPSEQ, NLPMES, NLPANO, DATA_FABRI, ENCARREGADO_PRODUCAO, RESPONSAVEL)
                                                      VALUES (:idProduto, :numLote, :idMaterial, :descrMat, :qtdeUtil, :nlpSeq, :nlpMes, :nlpAno, :dataFabri, :encarregado, :responsavel)");
                  $regProd->bindParam(':idProduto'  , $_SESSION['idProd']   , PDO::PARAM_INT); $regProd->bindParam(':encarregado', $_SESSION['colaborador'], PDO::PARAM_STR); 
                  $regProd->bindParam(':descrMat'   , $rowLotes['DESCRICAO'], PDO::PARAM_STR); $regProd->bindParam(':dataFabri'  , $_SESSION['dataFabri']  , PDO::PARAM_STR);                 
                  $regProd->bindParam(':nlpSeq'     , $_SESSION['nlpSeq']   , PDO::PARAM_INT); $regProd->bindParam(':qtdeUtil'   , $confirmaP['qtdeUsada'] , PDO::PARAM_STR);
                  $regProd->bindParam(':nlpMes'     , $_SESSION['nlpMes']   , PDO::PARAM_INT); $regProd->bindParam(':idMaterial' , $rowLotes['ID_INTERNO'] , PDO::PARAM_STR);
                  $regProd->bindParam(':nlpAno'     , $_SESSION['nlpAno']   , PDO::PARAM_INT); $regProd->bindParam(':numLote'    , $_SESSION['nLoteProd']  , PDO::PARAM_STR); 
                  $regProd->bindParam(':responsavel', $_SESSION['nome_func'], PDO::PARAM_STR); $regProd->execute();
                  
                  $delReserva = $connDB->prepare("UPDATE materiais_reserva SET QTDE_RESERVA = :qtdeUsada, DISPONIBILIDADE = :etapa WHERE NUMERO_PEDIDO = :numPedido AND ID_ESTOQUE = :idEstoque");
                  $delReserva->bindParam(':etapa'    , $etapa, PDO::PARAM_INT); $delReserva->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT) ;
                  $delReserva->bindParam(':qtdeUsada', $falta, PDO::PARAM_STR); $delReserva->bindParam(':idEstoque', $rowMats['ID_ESTOQUE']     , PDO::PARAM_INT) ; $delReserva->execute();

                  $ajusteLote = $connDB->prepare("INSERT INTO materiais_ajuste (NUMERO_PEDIDO, ID_ESTOQUE, ID_INTERNO, QTDE) VALUES (:numPedido, :idEstoque, :idInterno, :qtde)");                 
                  $ajusteLote->bindParam(':idEstoque', $rowMats['ID_ESTOQUE'], PDO::PARAM_INT);$ajusteLote->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);                 
                  $ajusteLote->bindParam(':qtde'     , $confirma['qtdeUsada'], PDO::PARAM_STR);$ajusteLote->bindParam(':idInterno', $rowLotes['ID_INTERNO']    , PDO::PARAM_STR);
                  $ajusteLote->execute(); $finalizado = 1;
                }               
              }            
            } ?>
          </tbody>
        </table>
      </div>
      <div class="col-md-12"><?php if($finalizado == 1){ ?> <div class="alert alert-success" role="alert"> Materiais processados com sucesso </div><?php } ?>       </div>
      <form method="POST">
        <div class="col-md-3">
          <input class="btn btn-primary" type="submit" id="registra" name="registra" value="Registra Produção">
        </div>
      </form><?php
      $registra = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(!empty($registra['registra'])){ $etapa = 2; $situacao = 'PRODUTO FINALIZADO, AGUARDANDO ANÁLISE';
        $atualizaPedido = $connDB->prepare("UPDATE pedidos SET NUMERO_LOTE = :numLote, ETAPA_PROCESS = :etapa, SITUACAO = :situacao, DATA_FABRI = :dataFabri WHERE NUMERO_PEDIDO = :numPedido");       
        $atualizaPedido->bindParam(':etapa'    , $etapa                , PDO::PARAM_INT); $atualizaPedido->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
        $atualizaPedido->bindParam(':situacao' , $situacao             , PDO::PARAM_STR); $atualizaPedido->bindParam(':numLote'  , $_SESSION['nLoteProd']     , PDO::PARAM_STR);
        $atualizaPedido->bindParam(':dataFabri', $_SESSION['dataFabri'], PDO::PARAM_STR); $atualizaPedido->execute();
        
        $atualizaEstoque = $connDB->prepare("SELECT SUM(QTDE) AS TOTAL, ID_ESTOQUE, ID_INTERNO, QTDE FROM materiais_ajuste WHERE NUMERO_PEDIDO = :numPedido");
        $atualizaEstoque->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT); $atualizaEstoque->execute();

        while($rowStk = $atualizaEstoque->fetch(PDO::FETCH_ASSOC)){ $verificaLote = $connDB->prepare("SELECT QTDE_LOTE FROM materiais_lotes WHERE ID_INTERNO = :idInterno");
          $verificaLote->bindParam(':idInterno', $rowStk['ID_INTERNO'], PDO::PARAM_STR); $verificaLote->execute(); $rowLote = $verificaLote->fetch(PDO::FETCH_ASSOC);
          $loteUse = $rowLote['QTDE_LOTE'] - $rowStk['QTDE'];
          if($loteUse <= 0){$etapaL = 4; $situacaoL = 'LOTE ESGOTADO';}
          if($loteUse >= 1){$etapaL = 3; $situacaoL = 'MATERIAL LIBERADO PARA USO';}

          $atualizaLote = $connDB->prepare("UPDATE materiais_lotes SET QTDE_LOTE = :qtdeLote, ETAPA_PROCESS = :etapaL, SITUACAO = :situacaoL WHERE ID_INTERNO = :idInterno");          
          $atualizaLote->bindParam(':qtdeLote', $loteUse, PDO::PARAM_STR); $atualizaLote->bindParam(':idInterno', $rowStk['ID_INTERNO'], PDO::PARAM_INT);
          $atualizaLote->bindParam(':etapaL'  , $etapaL , PDO::PARAM_INT); $atualizaLote->bindParam(':situacaoL', $situacaoL           , PDO::PARAM_STR); $atualizaLote->execute();

          $verificaEstoque = $connDB->prepare("SELECT QTDE_ESTOQUE FROM materiais_estoque WHERE ID_ESTOQUE = :idEstoque");
          $verificaEstoque->bindParam(':idEstoque', $rowStk['ID_ESTOQUE'], PDO::PARAM_INT); $verificaEstoque->execute(); $rowVerify = $verificaEstoque->fetch(PDO::FETCH_ASSOC);
          $ajQtde = $rowVerify['QTDE_ESTOQUE'] - $rowStk['TOTAL'];

          $retiraFila = $connDB->prepare("DELETE FROM fila_ocupacao WHERE NUMERO_PEDIDO = :numPedido"); $retiraFila->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT); $retiraFila->execute();

          header('Location: ./03SeletorProducao.php');
        }
      } ?>
    </div><!-- fim da row g2 -->
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->