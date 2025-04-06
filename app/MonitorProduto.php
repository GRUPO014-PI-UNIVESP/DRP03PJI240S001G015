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
    <table class="table table-dark">
      <thead style="font-size: 12px">
        <tr>
          <th scope="col" style="width: 10%"><?php echo 'Data do Pedido' . '<br>' . 'Pedido No.'; ?></th>
          <th scope="col" style="width: 30%"><?php echo 'Produto' . '<br>' . 'Cliente'; ?></th>
          <th scope="col" style="width: 10%"><?php echo 'Quantidade' . '<br>' . 'Data de Entrega'; ?></th>
          <th scope="col" style="width: 50%; text-align: center">Progresso</th>
        </tr>
      </thead>
      <?php $query_pedido = $connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 8 ORDER BY DATA_PEDIDO ASC"); $query_pedido->execute(); ?>
      <tbody style="height: 75%; font-size: 11px;"><?php 
        while($rowPedido = $query_pedido->fetch(PDO::FETCH_ASSOC)){
          $clear = 'font-size:12px; text-align:center; color:black; background-color:limegreen;';
          $exec  = 'font-size:12px; text-align:center; color:whitesmoke; background-color:dodgerblue;';
          $wait  = 'font-size:12px; text-align:center; color:whitesmoke; background-color:lightslategrey;';
          if($rowPedido['ETAPA_PROCESS'] >= 0){$a = $wait ; $b = $wait ; $c = $wait ; $d = $wait ; $e = $wait ; $f = $wait ; }
          if($rowPedido['ETAPA_PROCESS'] >= 1){$a = $exec ; $b = $wait ; $c = $wait ; $d = $wait ; $e = $wait ; $f = $wait ; }
          if($rowPedido['ETAPA_PROCESS'] >= 2){$a = $clear; $b = $exec ; $c = $wait ; $d = $wait ; $e = $wait ; $f = $wait ; }
          if($rowPedido['ETAPA_PROCESS'] >= 3){$a = $clear; $b = $clear; $c = $exec ; $d = $wait ; $e = $wait ; $f = $wait ; }
          if($rowPedido['ETAPA_PROCESS'] >= 4){$a = $clear; $b = $clear; $c = $clear; $d = $exec ; $e = $wait ; $f = $wait ; }
          if($rowPedido['ETAPA_PROCESS'] >= 5){$a = $clear; $b = $clear; $c = $clear; $d = $clear; $e = $exec ; $f = $wait ; }
          if($rowPedido['ETAPA_PROCESS'] >= 6){$a = $clear; $b = $clear; $c = $clear; $d = $clear; $e = $clear; $f = $exec ; }
          if($rowPedido['ETAPA_PROCESS'] >= 7){$a = $clear; $b = $clear; $c = $clear; $d = $clear; $e = $clear; $f = $clear; } ?>
          <tr>
            <td scope="col" style="width: 10%; text-align:right;"><?php echo $rowPedido['DATA_PEDIDO'] . '<br>' . $rowPedido['NUMERO_PEDIDO'] ; ?></td>
            <td scope="col" style="width: 30%;                  "><?php echo $rowPedido['PRODUTO']     . '<br>' . $rowPedido['CLIENTE']; ?></td>
            <td scope="col" style="width: 10%; text-align:right;"><?php echo $rowPedido['QTDE_PEDIDO'] . ' '    . $rowPedido['UNIDADE'] . '<br>' . $rowPedido['DATA_ENTREGA']; ?></td>
            <td scope="col" style="width: 50%;">
              <div class="row g-2">
                <div class="col md-2" style="<?php echo $a ?>">Compra</div>
                <div class="col md-2" style="<?php echo $b ?>">Recebida</div>
                <div class="col md-2" style="<?php echo $c ?>">Análise</div>
                <div class="col md-2" style="<?php echo $d ?>">Fabricação</div>
                <div class="col md-2" style="<?php echo $e ?>">Análise</div>
                <div class="col md-2" style="<?php echo $f ?>">Entrega</div>
              </div>
              <div class="row g-2">
                <div class="col md-6" style="font-size: 11px; color:aqua;">Matéria Prima</div>
                <div class="col md-6" style="font-size: 11px; color:aqua;">Produto Final</div>
              </div>
            </td>
          </tr><?php
        } ?>                    
      </tbody>
    </table>
  </div>
</div>