<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Saida de Produto Final'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 600000);}
  }; inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br> <?php
    if(!empty($_GET['id'])){ $entrega = $connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :idPed");
      $entrega->bindParam(':idPed', $_GET['id'], PDO::PARAM_INT);
      $entrega->execute();
      $rowEntrega = $entrega->fetch(PDO::FETCH_ASSOC);
      if(!empty($rowEntrega['CLIENTE'] == 'INTERNO - ESTOQUE')){ ?>
        <form method="POST">
          <div class="row g-2"><h6>Transbordo para Armazém de Estoque</h6>
            <div class="col-md-7">
              <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Nome do Produto</label>
              <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color: yellow" type="text" class="form-control" id="nomeProduto" name="nomeProduto" value="<?php echo $rowEntrega['PRODUTO'] ?>" readonly>
            </div>
            <div class="col-md-2">
              <label for="idLote" class="form-label" style="font-size: 10px; color:aqua">Identificação do Lote</label>
              <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color: yellow; text-align: center" type="text" class="form-control" id="idLote" name="idLote" value="<?php echo $rowEntrega['NUMERO_LOTE'] ?>" readonly>
            </div><div class="col-md-3"></div>
            <div class="col-md-7">
              <label for="cliente" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
              <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color: yellow" type="text" class="form-control" id="cliente" name="cliente" value="<?php echo $rowEntrega['CLIENTE'] ?>" readonly>
            </div>
            <div class="col-md-2">
              <label for="qLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade</label>
              <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color: yellow; text-align: center" type="text" class="form-control" id="qLote" name="qlote" 
                value="<?php echo number_format($rowEntrega['QTDE_PEDIDO'],0,',','.') . ' ' . $rowEntrega['UNIDADE'] ?>" readonly>
            </div>
            <div class="col-md-2">
              <label for="dataS" class="form-label" style="font-size: 10px; color:aqua">Data de Saída</label>
              <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: center" type="date" class="form-control" id="dataS" name="dataS" required>
            </div>
            <div class="col-md-9"><br>
              <div class="form-floating"><?php $depto = 'LOGÍSTICA';
                $query_analista = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = :depto");
                $query_analista->bindParam(':depto', $depto, PDO::PARAM_STR); $query_analista->execute(); ?>
                <select class="form-select" id="colaborador" name="colaborador" aria-label="Floating label select example" style="background: rgba(0,0,0,0.3);">
                  <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o nome do encarregado</option><?php
                  while($rowAna = $query_analista->fetch(PDO::FETCH_ASSOC)){ ?>
                    <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $rowAna['NOME_FUNCIONARIO']; ?></option><?php 
                  } ?>
                </select>
                <label for="colaborador" style="font-size: 12px; color:aqua">Encarregado pelo Transbordo</label>
              </div>
            </div>
            <div class="col-md-2"><br>
              <input class="btn btn-primary" type="submit" id="armazem" name="armazem" value="Confirmar" style="width: 180px">
            </div>
            <div class="col-md-2"><br>
              <input class="btn btn-danger" type="reset" id="descartar" name="descartar" value="Descartar" style="width: 180px" onclick="location.href='./02SeletorLogistica.php'">
            </div>            
          </div>
        </form><?php $regEntrega = filter_input_array(INPUT_POST, FILTER_DEFAULT);          
        if(!empty($regEntrega['armazem'])){ $etapa = 6;
          $situacao = 'PRODUTO ARMAZENADO NO ESTOQUE COM SUCESSO.';
          $saida = date('Y-m-d', strtotime($regEntrega['dataS']));

          $deliveryP = $connDB->prepare("UPDATE pedidos SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao, DATA_ENTREGA = :dataS, TRANSPORTADORA = :transp, ENCARREGADO_ENTREGA = :responsavel 
                                                WHERE NUMERO_PEDIDO = :idPed");
          $deliveryP->bindParam(':etapa'      , $etapa                    , PDO::PARAM_INT);
          $deliveryP->bindParam(':responsavel', $regEntrega['colaborador'], PDO::PARAM_STR);
          $deliveryP->bindParam(':idPed'      , $_GET['id']               , PDO::PARAM_INT);
          $deliveryP->bindParam(':transp'     , $regEntrega['transport']  , PDO::PARAM_STR);
          $deliveryP->bindParam(':dataS'      , $saida                    , PDO::PARAM_STR);
          $deliveryP->bindParam(':situacao'   , $situacao                 , PDO::PARAM_STR);
          $deliveryP->execute();

          $estoque = $connDB->prepare("INSERT INTO produto_estoque (NOME_PRODUTO, NUMERO_LOTE, QTDE_ESTOQUE, UNIDADE_MEDIDA) 
                                              VALUES (:nomeProduto, :numLote, :qtdeLote, :uniMed)") ;
          $estoque->bindParam(':nomeProduto', $rowEntrega['PRODUTO']    , PDO::PARAM_STR);
          $estoque->bindParam(':numLote'    , $rowEntrega['NUMERO_LOTE'], PDO::PARAM_STR); 
          $estoque->bindParam(':qtdeLote'   , $rowEntrega['QTDE_PEDIDO'], PDO::PARAM_INT); 
          $estoque->bindParam(':uniMed'     , $rowEntrega['UNIDADE']    , PDO::PARAM_STR);
          $estoque->execute();

          //definição de hora local
          date_default_timezone_set('America/Sao_Paulo');
          $dataEntrega = date('Y-m-d H:i');

          $buscaTanaPro = $connDB->prepare("SELECT T_ANAPRO FROM historico_tempo WHERE NUMERO_PEDIDO = :numPedido");
          $buscaTanaPro->bindParam(':numPedido', $_GET['id'], PDO::PARAM_INT);
          $buscaTanaPro->execute(); $rowLinha = $buscaTanaPro->fetch(PDO::FETCH_ASSOC);

          $dataC   = new datetime($dataEntrega); 
          $dataI   = new datetime($rowLinha['T_ANAPRO']);
          $entrega = ($dataC->getTimestamp() - $dataI->getTimestamp()) / 60;

          $marcaData = $connDB->prepare("UPDATE historico_tempo SET T_ENTREGA = :entrega, ETAPA_PROCESS = :etapa, ENTREGA = :tentrega WHERE NUMERO_PEDIDO = :numPedido");
          $marcaData->bindParam(':numPedido', $_GET['id'] , PDO::PARAM_INT);
          $marcaData->bindParam(':entrega'  , $dataEntrega, PDO::PARAM_STR);
          $marcaData->bindParam(':etapa'    , $etapa      , PDO::PARAM_INT);
          $marcaData->bindParam(':tentrega' , $entrega    , PDO::PARAM_INT);
          $marcaData->execute();
  
          header('Location: ./02SeletorLogistica.php');
        }
      } 
    }
    if(!empty($rowEntrega['CLIENTE'] != 'INTERNO - ESTOQUE')){ ?>
      <form method="POST">
        <div class="row g-2"><h6>Saída do Produto para Entrega</h6>
          <div class="col-md-7">
            <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Nome do Produto</label>
            <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color: yellow" type="text" class="form-control" id="nomeProduto" name="nomeProduto" value="<?php echo $rowEntrega['PRODUTO'] ?>" readonly>
          </div>
          <div class="col-md-2">
            <label for="idLote" class="form-label" style="font-size: 10px; color:aqua">Identificação do Lote</label>
            <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color: yellow; text-align: center" type="text" class="form-control" id="idLote" name="idLote" value="<?php echo $rowEntrega['NUMERO_LOTE'] ?>" readonly>
          </div><div class="col-md-3"></div>
          <div class="col-md-7">
            <label for="cliente" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
            <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color: yellow" type="text" class="form-control" id="cliente" name="cliente" value="<?php echo $rowEntrega['CLIENTE'] ?>" readonly>
          </div>
          <div class="col-md-2">
            <label for="qLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade</label>
            <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); color: yellow; text-align: center" type="text" class="form-control" id="qLote" name="qlote" value="<?php echo $rowEntrega['QTDE_PEDIDO'] . ' ' . $rowEntrega['UNIDADE'] ?>" readonly>
          </div>
          <div class="col-md-7">
            <label for="transport" class="form-label" style="font-size: 10px; color:aqua">Transportadora Contratada</label>
            <select style="font-size: 18px;" class="form-select" id="transport" name="transport" style="background: rgba(0,0,0,0.3);">
              <option style="font-size: 14px; background: rgba(0,0,0,0.3)" selected>Selecione</option>
              <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="Formula 1 Transportes">Direcionamento para Armazém de Estoque</option>
              <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="Formula 1 Transportes">Formula 1 Transport Service</option>
              <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="Carga Pesada Caminhões para Aluguel">Carga Pesada Caminhões de Aluguel</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="dataS" class="form-label" style="font-size: 10px; color:aqua">Data de Saída</label>
            <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: center" type="date" class="form-control" id="dataS" name="dataS" required>
          </div>
          <div class="col-md-9"><br>
            <div class="form-floating"><?php $depto = 'LOGÍSTICA';
              $query_analista = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = :depto");
              $query_analista->bindParam(':depto', $depto, PDO::PARAM_STR); $query_analista->execute(); ?>
              <select class="form-select" id="colaborador" name="colaborador" aria-label="Floating label select example" style="background: rgba(0,0,0,0.3);">
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o nome do encarregado</option><?php
                  while($rowAna = $query_analista->fetch(PDO::FETCH_ASSOC)){ ?>
                    <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $rowAna['NOME_FUNCIONARIO']; ?></option><?php 
                  } ?>
              </select><label for="colaborador" style="font-size: 12px; color:aqua">Encarregado pelo Despacho</label>
            </div>
          </div><div class="col-md-3"></div>
          <div class="col-md-2"><br>
            <input class="btn btn-primary" type="submit" id="entrega" name="entrega" value="Despachar Produto" style="width: 180px">
          </div>
          <div class="col-md-2"><br>
            <input class="btn btn-danger" type="reset" id="descartar" name="descartar" value="Descartar" style="width: 180px" onclick="location.href='./02SeletorLogistica.php'">
          </div>
        </div>
      </form><?php $regEntrega = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(!empty($regEntrega['entrega'])){ $etapa = 6; $situacao = 'PRODUTO DESPACHADO COM SUCESSO.'; $saida = date('Y-m-d', strtotime($regEntrega['dataS']));

        $deliveryP = $connDB->prepare("UPDATE pedidos SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao, DATA_ENTREGA = :dataS, TRANSPORTADORA = :transp, ENCARREGADO_ENTREGA = :responsavel WHERE ID_PEDIDO = :idPed");
        $deliveryP->bindParam(':etapa', $etapa     , PDO::PARAM_INT); $deliveryP->bindParam(':responsavel', $regEntrega['colaborador'], PDO::PARAM_STR);
        $deliveryP->bindParam(':idPed', $_GET['id'], PDO::PARAM_INT); $deliveryP->bindParam(':transp'     , $regEntrega['transport']  , PDO::PARAM_STR);
        $deliveryP->bindParam(':dataS', $saida     , PDO::PARAM_STR); $deliveryP->bindParam(':situacao'   , $situacao                 , PDO::PARAM_STR);
        $deliveryP->execute();

        //definição de hora local
        date_default_timezone_set('America/Sao_Paulo');
        $dataEntrega = date('Y-m-d H:i');
        $marcaData = $connDB->prepare("UPDATE historico_tempo SET T_ENTREGA = :entrega WHERE NUMERO_PEDIDO = :numPedido");
        $marcaData->bindParam(':numPedido', $_GET['id'] , PDO::PARAM_INT);
        $marcaData->bindParam(':entrega'   , $dataEntrega, PDO::PARAM_STR);
        $marcaData->execute();
        
        header('Location: ./02SeletorLogistica.php');
      } 
    }?>
  </div>
</div>