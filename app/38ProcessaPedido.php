<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Produção'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php';?> window.location.href = 'LogOut.php';}
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 6000000);}
  }; inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <div class="row g-2">
      <div class="col-md-12"><br>
        <h5>Registro de Execução de Fabricação</h5><br> <h6 style="color:aqua">Informações do Pedido</h6>
      </div><?php 
      $dadosPedido = $connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :idPed");
      $dadosPedido->bindParam(':idPed', $_SESSION['idPedido'], PDO::PARAM_INT); 
      $dadosPedido->execute(); $rowPedido = $dadosPedido->fetch(PDO::FETCH_ASSOC); ?>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="dataPedido" name="dataPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_PEDIDO'])) ?>" readonly>
          <label for="dataPedido" style="color: aqua; font-size: 12px; background: none">Data do Pedido</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-1">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="numPedido" name="numPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowPedido['NUMERO_PEDIDO'] ?>" readonly>
          <label for="numPedido" style="color: aqua; font-size: 12px; background: none">Pedido No.</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowPedido['PRODUTO'] ?>" readonly>
          <label for="nomeProduto" style="color: aqua; font-size: 12px; background: none">Nome do Produto</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-3"></div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="qtdePedido" name="qtdePedido" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center;color: yellow" value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
          <label for="qtdePedido" style="color: aqua; font-size: 12px; background: none">Quantidade do Pedido</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-7">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="cliente" name="cliente" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowPedido['CLIENTE'] ?>" readonly>
          <label for="cliente" style="color: aqua; font-size: 12px; background: none">Cliente</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-12"><h6 style="color:aqua">Informações da Fabricação</h6></div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="date" class="form-control" id="dataFabri" name="dataFabri" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['dataFabri'] ?>" readonly>
          <label for="dataFabri" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="planta" name="planta" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['planta'] ?>" readonly>
          <label for="planta" style="color: aqua; font-size: 12px; background: none">Planta</label><p style="font-size: 11px; color: grey"></p>
        </div>         
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="inicio" name="inicio" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" value="<?php echo date('H:i', strtotime($_SESSION['horaInicio'])); ?>" readonly>
          <label for="inicio" style="color: aqua; font-size: 12px; background: none">Início do Processamento</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="fim" name="fim" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" value="<?php echo date('H:i', strtotime($_SESSION['horaFinali'])); ?>" readonly>
          <label for="fim" style="color: aqua; font-size: 12px; background: none">Encerramento</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="qtdeReal" name="qtdeReal" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center; color: yellow" value="<?php echo number_format($_SESSION['qtdeReal'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
          <label for="fim" style="color: aqua; font-size: 12px; background: none">Quantidade Produzida</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-floating mb-2">
          <input type="text" class="form-control" id="nLotePF" name="nLotePF" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['nLoteProd'] ?>" readonly>
          <label for="nLotePF" style="color: aqua; font-size: 12px; background: none">Identificação do Lote</label><p style="font-size: 11px; color: grey"></p>
        </div>
      </div>
      <h6 style="color:aqua">Materias Utilizados</h6>
      <div class="col-md-12 overflow-auto">      
        <table class="table">
          <thead class="table-dark">
            <tr style="font-size: 11px">
              <th scope="col" style="width:30%; text-align:left  ; color:grey;">Descrição do Material</th>
              <th scope="col" style="width:10%; text-align:center; color:grey;">Qtde. Necessária</th>
              <th scope="col" style="width:10%; text-align:center; color:grey;">ID Interno</th>
              <th scope="col" style="width:10%; text-align:center; color:grey;">Qtde. Disponível</th>
              <th scope="col" style="width:15%; text-align:center; color:grey;">Qtde. Utilizada</th>
              <th scope="col" style="width:10%; text-align:center; color:grey;">Ação</th>
            </tr>
          </thead>
          <tbody><?php
            $query_reaj = $connDB->prepare("SELECT * FROM materiais_reajuste WHERE NUMERO_PEDIDO = :numPedido ORDER BY ID_ESTOQUE ASC");
            $query_reaj->bindParam(':numPedido', $_SESSION['idPedido'], PDO::PARAM_INT);
            while($rowReaj = $query_reaj->fetch(PDO::FETCH_ASSOC)){ ?>
              <tr>
                <td scope="col" style="width:30%; color:yellow; font-size:14px; text-align:left  ; font-weight:none  "><?php echo $rowReaj['DESCRICAO'] ?></td>
                <td scope="col" style="width:10%; color:yellow; font-size:20px; text-align:center; font-weight:bolder"><?php echo $rowReaj['QTDE_RESERVA'] ?></td>
                <td scope="col" style="width:10%; color:yellow; font-size:20px; text-align:center; font-weight:bolder"><?php echo $rowReaj['ID_INTERNO'] ?></td>
                <td scope="col" style="width:10%; color:green ; font-size:20px; text-align:center; font-weight:bolder"><?php echo $rowReaj['QTDE_DISP'] ?></td>
                <td scope="col" style="width:15%; color:green ; font-size:20px; text-align:center; font-weight:bolder"><?php echo $rowReaj['QTDE_USADA'] ?></td>
              </tr><?php
            }
            $query_res = $connDB->prepare("SELECT * FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido AND QTDE_RESERVA > 1 ORDER BY ID_ESTOQUE ASC LIMIT 1");
            $query_res->bindParam(':numPedido', $_SESSION['idPedido'], PDO::PARAM_INT); $query_res->execute();
            while($rowRes = $query_res->fetch(PDO::FETCH_ASSOC)){
              $query_lote = $connDB->prepare("SELECT * FROM materiais_lotes WHERE DESCRICAO = :descrMat AND ETAPA_PROCESS = 3 AND QTDE_LOTE > 1 ORDER BY ID_INTERNO ASC LIMIT 1");
              $query_lote->bindParam(':descrMat', $rowRes['DESCRICAO'], pdo::PARAM_STR); $query_lote->execute(); $rowLote = $query_lote->fetch(PDO::FETCH_ASSOC);
              ?>
              <tr>
                <td scope="col" style="width:30%; color:yellow; font-size:14px; text-align:left  ; font-weight:none  "><?php echo $rowRes['DESCRICAO'] ?></td>
                <td scope="col" style="width:10%; color:yellow; font-size:18px; text-align:center; font-weight:bolder"><?php echo number_format($rowRes['QTDE_RESERVA'], 1, ',', '.') . ' ' . $rowRes['UNIDADE'] ?></td>
                <td scope="col" style="width:10%; color:yellow; font-size:18px; text-align:center; font-weight:bolder"><?php echo $rowLote['ID_INTERNO'] ?></td>
                <td scope="col" style="width:10%; color:green ; font-size:18px; text-align:center; font-weight:bolder"><?php echo number_format($rowLote['QTDE_LOTE'], 1, ',', '.') . ' ' . $rowLote['UNIDADE'] ?></td>
                <form method="POST">
                  <td>
                    <div class="input-group mb-3">
                      <input type="number" class="form-control" aria-label="username" aria-describedby="qtdeUsada" id="qtdeUsada" name="qtdeUsada" autofocus required>
                      <span class="input-group-text" id="qtdeUsada"><?php echo $rowLote['UNIDADE'] ?></span>
                    </div>
                  </td>
                  <td scope="col" style="width: 10%;"><input class="btn btn-primary" style="width: 100%" type="submit" id="confirma" name="confirma" value="Confirmar"></td>
                </form><?php
                $regLote = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($regLote['confirma']) && $regLote['qtdeUsada'] > 0){
                  $sobra = $rowRes['QTDE_RESERVA'] - $regLote['qtdeUsada'];
                  if($sobra == 0){
                    $disp = 4; $situacao = 'LOTE ESGOTADO';
                  } else { $disp = 3; $situacao = 'MATERIAL LIBERADO PARA USO'; }
                  $reajustaReserva = $connDB->prepare("UPDATE materiais_reserva SET QTDE_RESERVA = :qtdeReajuste, DISPONIBILIDADE = :disp WHERE NUMERO_PEDIDO = :numPedido AND ID_ESTOQUE = :idEstoque");
                  $reajustaReserva->bindParam(':qtdeReajuste', $sobra, PDO::PARAM_STR);
                  $reajustaReserva->bindParam(':disp'        , $disp , PDO::PARAM_INT);
                  $reajustaReserva->bindParam(':numPedido'   , $_SESSION['idPedido'], PDO::PARAM_INT);
                  $reajustaReserva->bindParam(':idEstoque'   , $rowRes['ID_ESTOQUE'], PDO::PARAM_INT);
                  $reajustaReserva->execute();

                  $preencheReajuste = $connDB->prepare("INSERT INTO materiais_reajuste (NUMERO_PEDIDO, ID_ESTOQUE, DESCRICAO, QTDE_RESERVA, ID_INTERNO, QTDE_DISP, QTDE_USADA)
                                                               VALUES (:numPedido, :idEstoque, :descrMat, :qtdeSobra, :idInterno, :qtdeLote, :qtdeUsada)");
                  $preencheReajuste->bindParam(':numPedido', $_SESSION['idPedido'] , PDO::PARAM_INT);
                  $preencheReajuste->bindParam(':idEstoque', $rowRes['ID_ESTOQUE'] , PDO::PARAM_INT);
                  $preencheReajuste->bindParam(':descrMat' , $rowRes['DESCRICAO']  , PDO::PARAM_STR);
                  $preencheReajuste->bindParam(':qtdeSobra', $sobra                , PDO::PARAM_STR);
                  $preencheReajuste->bindParam(':idInterno', $rowLote['ID_INTERNO'], PDO::PARAM_STR);
                  $preencheReajuste->bindParam(':qtdeLote' , $rowLote['QTDE_LOTE'] , PDO::PARAM_STR);
                  $preencheReajuste->bindParam(':qtdeUsada', $regLote['qtdeUsada'] , PDO::PARAM_STR);
                  $preencheReajuste->execute();

                  $ajustaLote = $connDB->prepare("UPDATE materiais_lotes SET QTDE_LOTE = :qtdeSobra, ETAPA_PROCESS = :etapa, SITUACAO = :situacao WHERE ID_INTERNO = :idInterno");
                  $ajustaLote->bindParam(':qtdeLote' , $sobra                , PDO::PARAM_STR);
                  $ajustaLote->bindParam(':etapa'    , $disp                 , PDO::PARAM_INT);
                  $ajustaLote->bindParam(':situacao' , $situacao             , PDO::PARAM_STR);
                  $ajustaLote->bindParam(':idInterno', $rowLote['ID_INTERNO'], PDO::PARAM_STR);
                  $ajustaLote->execute();

                } ?>
              </tr><?php
            } ?>        
          </tbody>
        </table>
      </div>
      <div class="col-md-12"></div>
      <form method="POST">
        <div class="col-md-3">
          <input class="btn btn-primary" type="submit" id="registra" name="registra" value="Registra Produção">
        </div>
      </form>
    </div><!-- fim da row g2 -->
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->
<!--
<tr>
<td scope="col" style="width:30%; color:yellow; font-size:14px; text-align:left  ; font-weight:none  "></td>
<td scope="col" style="width:10%; color:yellow; font-size:20px; text-align:center; font-weight:bolder"></td>
<td scope="col" style="width:10%; color:yellow; font-size:20px; text-align:center; font-weight:bolder"></td>
<td scope="col" style="width:10%; color:green ; font-size:20px; text-align:center; font-weight:bolder"></td>
<td scope="col" style="width:15%; color:green ; font-size:20px; text-align:center; font-weight:bolder"></td>
</tr>

<form method="POST">
  <div class="input-group mb-3">
    <input type="number" class="form-control" aria-label="username" aria-describedby="qtdeUsada" id="qtdeUsada" name="qtdeUsada" autofocus required>
    <span class="input-group-text" id="qtdeUsada"></span>
  </div>
  <td scope="col" style="width: 10%;"><input class="btn btn-primary" style="width: 100%" type="submit" id="confirma" name="confirma" value="Confirmar"></td>
</form>
              </tr>
-->