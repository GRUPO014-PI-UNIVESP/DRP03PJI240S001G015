<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor de Produtos'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
  }; inactivityTime();
</script>
<style> .tabela{ width: 100%; height: 680px; overflow-y: scroll;} </style>
<!-- Área Principal -->
<div class="main">
  <div class="row g-1">
    <div class="col md-6">
      <br>
      <p style="font-size: 25px; color:cyan">Monitor da Execução dos Pedidos</p>
    </div>
    <div class="col md-6" style="text-align:center;">
      <br>
      <img src="./legenda de cores.jpg" />
    </div>
  </div>
  <div class="tabela">
    <table class="table table-dark table-hover">
      <thead style="font-size: 10px">
        <tr>
          <th scope="col" style="width: 5%; text-align:right"><?php echo 'Data' . '<br>' . 'Pedido No.'; ?></th>
          <th scope="col" style="width: 20%"><?php echo 'Produto' . '<br>' . 'Cliente'; ?></th>
          <th scope="col" style="width: 5%; text-align:right"><?php echo 'Quantidade' . '<br>' . 'Entrega'; ?></th>
          <th scope="col" style="width: 60%; text-align: center">Progresso</th>
          <th scope="col" style="width: 10%; text-align: center">Detalhes</th>
        </tr>
      </thead>
      <?php $query_pedido = $connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 8 ORDER BY DATA_PEDIDO ASC"); $query_pedido->execute(); ?>
      <tbody style="height: 75%; font-size: 11px;"><?php 
        while($rowPedido = $query_pedido->fetch(PDO::FETCH_ASSOC)){
          //busca registro do pedido na tabela de histórico de tempos da atividade
          $buscaHistorico = $connDB->prepare("SELECT * FROM historico_tempo WHERE NUMERO_PEDIDO = :numPedido");
          $buscaHistorico->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
          $buscaHistorico->execute(); $rowHistorico = $buscaHistorico->fetch(PDO::FETCH_ASSOC);

          //busca registro do tempo de referência da atividade do produto
          $buscaReferencia = $connDB->prepare("SELECT * FROM historico_tempo WHERE ID_PRODUTO = :idProd AND NUMERO_PEDIDO = 0");
          $buscaReferencia->bindParam('idProd', $rowPedido['N_PRODUTO'], PDO::PARAM_INT);
          $buscaReferencia->execute(); $rowReferencia = $buscaReferencia->fetch(PDO::FETCH_ASSOC);

          if($rowHistorico['COMPRA']           <= $rowReferencia['COMPRA'])          { $clear1 = 'font-size:12px; text-align:center; color:black; background-color:limegreen;';}
          if($rowHistorico['COMPRA']            > $rowReferencia['COMPRA'])          { $clear1 = 'font-size:12px; text-align:center; color:black; background-color:orange;'   ;}
          if($rowHistorico['RECEBIMENTO']      <= $rowReferencia['RECEBIMENTO'])     { $clear2 = 'font-size:12px; text-align:center; color:black; background-color:limegreen;';}
          if($rowHistorico['RECEBIMENTO']       > $rowReferencia['RECEBIMENTO'])     { $clear2 = 'font-size:12px; text-align:center; color:black; background-color:orange;'   ;}
          if($rowHistorico['ANALISE_MATERIAL'] <= $rowReferencia['ANALISE_MATERIAL']){ $clear3 = 'font-size:12px; text-align:center; color:black; background-color:limegreen;';}
          if($rowHistorico['ANALISE_MATERIAL']  > $rowReferencia['ANALISE_MATERIAL']){ $clear3 = 'font-size:12px; text-align:center; color:black; background-color:orange;'   ;}
          if($rowHistorico['FABRICACAO']       <= $rowReferencia['FABRICACAO'])      { $clear4 = 'font-size:12px; text-align:center; color:black; background-color:limegreen;';}
          if($rowHistorico['FABRICACAO']        > $rowReferencia['FABRICACAO'])      { $clear4 = 'font-size:12px; text-align:center; color:black; background-color:orange;'   ;}
          if($rowHistorico['ANALISE_PRODUTO']  <= $rowReferencia['ANALISE_PRODUTO']) { $clear5 = 'font-size:12px; text-align:center; color:black; background-color:limegreen;';}
          if($rowHistorico['ANALISE_PRODUTO']   > $rowReferencia['ANALISE_PRODUTO']) { $clear5 = 'font-size:12px; text-align:center; color:black; background-color:orange;'   ;}
          if($rowHistorico['ENTREGA']          <= $rowReferencia['ENTREGA'])         { $clear6 = 'font-size:12px; text-align:center; color:black; background-color:limegreen;';}
          if($rowHistorico['ENTREGA']           > $rowReferencia['ENTREGA'])         { $clear6 = 'font-size:12px; text-align:center; color:black; background-color:orange;'   ;}
          $exec  = 'font-size:12px; text-align:center; color:black     ; background-color:dodgerblue    ;';
          $wait  = 'font-size:12px; text-align:center; color:whitesmoke; background-color:lightslategrey;';

          if($rowPedido['ETAPA_PROCESS'] == 0){ $a = $exec  ; $b = $wait  ; $c = $wait  ; $d = $wait  ; $e = $wait  ; $f = $wait  ; }
          if($rowPedido['ETAPA_PROCESS'] == 1){ $a = $clear1; $b = $exec  ; $c = $wait  ; $d = $wait  ; $e = $wait  ; $f = $wait  ; } 
          if($rowPedido['ETAPA_PROCESS'] == 2){ $a = $clear1; $b = $clear2; $c = $exec  ; $d = $wait  ; $e = $wait  ; $f = $wait  ; } 
          if($rowPedido['ETAPA_PROCESS'] == 3){ $a = $clear1; $b = $clear2; $c = $clear3; $d = $exec  ; $e = $wait  ; $f = $wait  ; }
          if($rowPedido['ETAPA_PROCESS'] == 4){ $a = $clear1; $b = $clear2; $c = $clear3; $d = $clear4; $e = $exec  ; $f = $wait  ; }
          if($rowPedido['ETAPA_PROCESS'] == 5){ $a = $clear1; $b = $clear2; $c = $clear3; $d = $clear4; $e = $clear5; $f = $wait  ; }
          if($rowPedido['ETAPA_PROCESS'] == 6){ $a = $clear1; $b = $clear2; $c = $clear3; $d = $clear4; $e = $clear5; $f = $clear6; }?>
          <tr>
            <td scope="col" style="width: 5%; text-align:right;"><?php 
              echo '<br>' . date('d/m/Y', strtotime($rowPedido['DATA_PEDIDO'])) . 
              '<br>' . $rowPedido['NUMERO_PEDIDO'] ; ?></td>
            <td scope="col" style="width: 20%;                  "><?php echo '<br>' . $rowPedido['PRODUTO']     . '<br>' . $rowPedido['CLIENTE']; ?></td>
            <td scope="col" style="width: 5%; text-align:right;"><?php 
              echo '<br>' . number_format($rowPedido['QTDE_PEDIDO'],0,',','.') . ' '    . $rowPedido['UNIDADE'] . 
              '<br>' . date('d/m/Y',strtotime($rowPedido['DATA_ENTREGA'])); ?></td>
            <td scope="col" style="width: 60%;">
              <div class="row g-2">
                <div class="col md-6" style="font-size: 11px; color:aqua;">Matéria Prima</div>
                <div class="col md-6" style="font-size: 11px; color:aqua;">Produto Final</div>
              </div>
              <div class="row g-0">
                <div class="col md-2" style="<?php echo $a ?>;border: 1px solid white;"><?php echo 'COMPRA'     . '<br>' . 'Administrativo' ?></div>
                <div class="col md-2" style="<?php echo $b ?>;border: 1px solid white;"><?php echo 'RECEBIDA'   . '<br>' . 'Logística' ?></div>
                <div class="col md-2" style="<?php echo $c ?>;border: 1px solid white;"><?php echo 'ANÁLISE'    . '<br>' . 'Ctl.Quali' ?></div>
                <div class="col md-2" style="<?php echo $d ?>;border: 1px solid white;"><?php echo 'FABRICAÇÃO' . '<br>' . 'Produção' ?></div>
                <div class="col md-2" style="<?php echo $e ?>;border: 1px solid white;"><?php echo 'ANÁLISE'    . '<br>' . 'Ctl.Quali' ?></div>
                <div class="col md-2" style="<?php echo $f ?>;border: 1px solid white;"><?php echo 'ENTREGA'    . '<br>' . 'Logística' ?></div>
              </div>
            </td>
            <td scope="col" style="width: 10%;">
              <br>
              <input type="text" class="btn btn-outline-primary" style="width:90px;"
                value="Verificar">
            </td>
          </tr><?php
        } ?>                    
      </tbody>
    </table>
  </div>
</div>