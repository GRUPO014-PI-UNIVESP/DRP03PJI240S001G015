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
        </div><?php
        $dadosPedido = $connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :idPed");
        $dadosPedido->bindParam(':idPed', $_SESSION['idPedido'], PDO::PARAM_INT);
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
              value="<?php echo $rowPedido['PRODUTO'] ?>" readonly>
            <label for="nomeProduto" style="color: aqua; font-size: 12px; background: none">Nome do Produto</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="qtdePedido" name="qtdePedido" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center;color: yellow" 
              value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
            <label for="qtdePedido" style="color: aqua; font-size: 12px; background: none">Quantidade do Pedido</label>
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
        $ultimo = $connDB->prepare("SELECT MAX(NLPSEQ) AS U_SEQ, MAX(NLPMES) AS U_MES, MAX(NLPANO) AS U_ANO FROM producao");
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
            <input type="date" class="form-control" id="dataFabri" name="dataFabri" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $_SESSION['dataFabri'] ?>" readonly>
            <label for="dataFabri" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="planta" name="planta" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $_SESSION['planta'] ?>" readonly>
            <label for="planta" style="color: aqua; font-size: 12px; background: none">Planta</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>         
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="inicio" name="inicio" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" 
              value="<?php echo date('H:i', strtotime($_SESSION['horaInicio'])); ?>" readonly>
            <label for="inicio" style="color: aqua; font-size: 12px; background: none">Início do Processamento</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="fim" name="fim" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" 
              value="<?php echo date('H:i', strtotime($_SESSION['horaFinali'])); ?>" readonly>
            <label for="fim" style="color: aqua; font-size: 12px; background: none">Encerramento</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="qtdeReal" name="qtdeReal" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" 
              value="<?php echo number_format($_SESSION['qtdeReal'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
            <label for="fim" style="color: aqua; font-size: 12px; background: none">Quantidade Produzida</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="nLotePF" name="nLotePF" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $_SESSION['nLoteProd'] ?>" readonly>
            <label for="nLotePF" style="color: aqua; font-size: 12px; background: none">Identificação do Lote</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <h6 style="color:aqua">Materias Utilizados</h6>
        <div class="col-md-12 overflow-auto">
          <table class="table">
            <thead class="table-dark">
              <tr style="color: grey; font-size: 14px">
                <th scope="col" style="width: 30%"><?php echo 'Descrição do Material' . '<br>' . 'ID Interno' ?></th>
                <th scope="col" style="width: 10%; text-align:center">Qtde. Necessária</th>
                <th scope="col" style="width: 10%; text-align:center">Qtde. Disponível</th>
                <th scope="col" style="width: 10%; text-align:center">Qtde. Utilizada</th>
                <th scope="col" style="width: 10%; text-align:center">Ação</th>
                <th scope="col" style="width: 20%; text-align:center">Situação</th>
              </tr>
            </thead><?php
            $query_material = $connDB->prepare("SELECT ID_ESTOQUE, QTDE_RESERVA, UNIDADE FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
            $query_material->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
            $query_material->execute(); $nTipos = $query_material->rowCount();
            ?>
            <tbody>
              <tr style="color: yellow; font-size: 15px"><?php

                while($rowMat = $query_material->fetch(PDO::FETCH_ASSOC)){
                  $query_lote = $connDB->prepare("SELECT DESCRICAO, ID_INTERNO, QTDE_LOTE, UNIDADE
                                                         FROM materiais_lotes WHERE ID_ESTOQUE = :idEstoque AND QTDE_LOTE > 1 LIMIT 3");
                  $query_lote->bindParam(':idEstoque', $rowMat['ID_ESTOQUE'], PDO::PARAM_INT);
                  $query_lote->execute(); $nLotes = $query_lote->rowCount(); $qtdeNecessaria = $rowMat['QTDE_RESERVA'];

                  
                  while($rowLote = $query_lote->fetch(PDO::FETCH_ASSOC)){
                    if($qtdeNecessaria > 0){ ?>
                      <td scope="col" style="width: 10%; color: yellow; font-weight: bolder">
                        <?php echo $rowLote['DESCRICAO'] . '<br>' . $rowLote['ID_INTERNO'] ?>
                      </td>

                      <td scope="col" style="width: 10%; text-align:center; color: yellow; font-weight: bolder; font-size: 18px">
                        <?php echo number_format($rowMat['QTDE_RESERVA'], 0, ',', '.') . ' ' . $rowMat['UNIDADE'] ?>
                      </td>

                      <td scope="col" style="width: 10%; text-align:center; color: yellow; font-weight: bolder; font-size: 18px">
                        <?php echo number_format($rowLote['QTDE_LOTE'], 0, ',', '.') . ' ' . $rowLote['UNIDADE'] ?>
                      </td>

                      <form method="POST">
                        <td scope="col" style="width: 10%; text-align:center; color: whitesmoke"><?php
                          if($qtdeNecessaria > $rowLote['QTDE_LOTE']){ $qtdeUso = $rowLote['QTDE_LOTE']; $etapa = 4;}
                          if($qtdeNecessaria < $rowLote['QTDE_LOTE']){ $qtdeUso = $qtdeNecessaria; $etapa = 3;} ?>
                          <input style="width: 120px; height: 36px; font-size:18px; text-align: center; font-weight: bolder; background: rgba(0,0,0,0.3)" type="text" id="uso" name="uso" 
                                 value="<?php echo number_format($qtdeUso, 0, ',', '.') ?>" required autofocus>
                        </td>
                        <td>
                          <input class="btn btn-outline-primary" type="submit" id="usa" name="usa" value="Confirma">
                        </td>
                      </form><?php
                      $atualiza = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                      if(!empty($atualiza['usa'])){
                        $qtdeNecessaria = $qtdeNecessaria - $qtdeUso;
                        // registra dados da fabricação do produto com lote do material usado n vezes necessário
                        $dataVali = date('Y-m-d', strtotime($_SESSION['dataFabri']."+ 180 days" ));
                        $regProd = $connDB->prepare("INSERT INTO producao (ID_PRODUTO, NUMERO_LOTE, ID_MATERIAL, MATERIAL_COMPONENTE, QTDE_UTILIZADA, NLPSEQ, NLPMES, NLPANO, DATA_FABRI, DATA_VALI, ENCARREGADO_PRODUCAO, RESPONSAVEL) 
                                                            VALUES (:idProd, :numLote, :idMat, :matComp, :qtdeUtil, :nlpSeq, :nlpMes, :nlpAno, :dataFabri, :dataVali, :colaborador, :responsavel)");
                        $regProd->bindParam(':idProd'     , $_SESSION['idProd']     , PDO::PARAM_INT);
                        $regProd->bindParam(':numLote'    , $_SESSION['nLoteProd']  , PDO::PARAM_STR);
                        $regProd->bindParam(':idMat'      , $rowLote['ID_INTERNO']  , PDO::PARAM_STR);
                        $regProd->bindParam(':matComp'    , $rowLote['DESCRICAO']   , PDO::PARAM_STR);
                        $regProd->bindParam(':qtdeUtil'   , $qtdeUso                , PDO::PARAM_STR);
                        $regProd->bindParam(':nlpSeq'     , $seqAtual               , PDO::PARAM_INT);
                        $regProd->bindParam(':nlpMes'     , $mesAtual               , PDO::PARAM_INT);
                        $regProd->bindParam(':nlpAno'     , $anoAtual               , PDO::PARAM_INT);
                        $regProd->bindParam(':dataFabri'  , $_SESSION['dataFabri']  , PDO::PARAM_STR);
                        $regProd->bindParam(':dataVali'   , $dataVali               , PDO::PARAM_STR);
                        $regProd->bindParam(':colaborador', $_SESSION['colaborador'], PDO::PARAM_STR);
                        $regProd->bindParam(':responsavel', $_SESSION['nome_func']  , PDO::PARAM_STR);
                        $regProd->execute(); ?>                       
                        <td>
                          <div class="alert alert-success" role="alert">
                            Executado!
                          </div>  
                        </td> <?php
                      }
                    }
                  }
                  //retira a reserva de material necessario
                  $retiraReserva = $connDB->prepare("DELETE FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
                  $retiraReserva->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
                  $retiraReserva->execute();
                } ?>                 
              </tr>
            </tbody>           
          </table>
        </div>
        <div class="col-md-12">
          <div class="alert alert-success" role="alert">
            Materiais processados com sucesso!
          </div>
        </div>
        <form method="POST">
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
        </form><?php
        $confirma = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(!empty($confirma['confirma'])){ $etapaPedido = 2; $situacaoPedido = 'PROCESSAMENTO CONCLUÍDO, AGUARDANDO ANÁLISE.';
          //deleta pedido da fila de ocupação da planta
          /*
          $retiraFila = $connDB->prepare("DELETE FROM fila_ocupacao WHERE NUMERO_PEDIDO = :numPedido");
          $retiraFila->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
          $retiraFila->execute();
          //atualiza situação do pedido
          $pedidoAtual = $connDB->prepare("UPDATE pedidos SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao WHERE NUMERO_PEDIDO = :numPedido");
          $pedidoAtual->bindParam(':etapa'    , $etapaPedido               , PDO::PARAM_INT);
          $pedidoAtual->bindParam(':situacao' , $situacaoPedido            , PDO::PARAM_STR);
          $pedidoAtual->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
          $pedidoAtual->execute();
          */
          header('Location: ./03SeletorProducao.php');

        } ?>
      </div><!-- fim da row g2 -->
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->