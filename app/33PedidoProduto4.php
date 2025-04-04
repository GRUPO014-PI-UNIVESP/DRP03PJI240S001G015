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
<style> 
.tabela1{ width: 300px ; height: 300px; overflow-y: scroll;}
.tabela2{ width: 980px; height: 300px; overflow-y: scroll;}
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div><h5>Pedido de Produto - Agendamento de Compra</h5></div>   
    <div class="row g-5">
      <div class="col-md-1">
        <label for="numPedido" style="font-size: 10px; color:aqua;">Pedido No.</label>
        <p style="color:yellow; font-size: 13px; text-align: center; border-bottom: 2px solid whitesmoke"><?php echo $_SESSION['numPedido'] ?></p>
      </div>
      <div class="col-md-7">
        <label for="nomeProduto" style="font-size: 10px; color:aqua">Produto</label>
        <p style="color:yellow; font-size: 13px; border-bottom: 2px solid whitesmoke"><?php echo $_SESSION['nomeProduto'] ?></p>    
      </div>
      <div class="col-md-2">
        <label for="qtdeLote" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
        <p style="color:yellow; font-size: 16px; border-bottom: 2px solid whitesmoke; text-align: center;"><?php echo number_format($_SESSION['qtdeLote'], 0, ',', '.') . ' ' . $_SESSION['unidade'] ?></p> 
      </div>
      <div class="col-md-1"></div>
    </div><!-- Fim da div row -->
    <br>
    <div class="row g-0">
      <div class="col-md-10"><!-- Construção da tabela dos materiais ingredientes e disponibilidades -->
        <p style="color:aqua">Materiais Ingredientes</p>
        <div class="tabela2">
          <table class="table table-dark">
            <thead style="font-size: 12px">
              <tr>
                <th scope="col" style="width: 30%;">Ingrediente/Proporção</th>
                <th scope="col" style="width: 10%; text-align: right">Qtde Exigida</th>
                <th scope="col" style="width: 10%; text-align: right">Qtde Disponível</th>
                <th scope="col" style="width: 10%; text-align: center">Condição</th>
                <th scope="col" style="width: 10%; text-align: center">Compra Mínima</th>
              </tr>
            </thead>
            <tbody style="height: 25%;">
              <?php
                $query_matDisponivel = $connDB->prepare('SELECT * FROM produtos WHERE PRODUTO = :nomeProd');
                $query_matDisponivel->bindParam(':nomeProd', $_SESSION['nomeProduto'], PDO::PARAM_STR);
                $query_matDisponivel->execute();
                while($rowMat = $query_matDisponivel->fetch(PDO::FETCH_ASSOC)){                 
                  $query_matLista = $connDB->prepare('SELECT * FROM materiais_estoque WHERE DESCRICAO = :nomeMat');
                  $query_matLista->bindParam(':nomeMat', $rowMat['MATERIAL_COMPONENTE'], PDO::PARAM_STR);
                  $query_matLista->execute();
                  $proporcao = $_SESSION['qtdeLote'] * ($rowMat['PROPORCAO_MATERIAL'] / 100); $i = 1;
                  while($dataMat = $query_matLista->fetch(PDO::FETCH_ASSOC)){ ?>
                    <tr>
                      <td scope="col" style="width: 30%; font-size: 11px;"> <?php echo $dataMat['DESCRICAO'] . ' [ ' . $rowMat['PROPORCAO_MATERIAL'] . '% ]' ?> </td>
                      <th scope="col" style="width: 10%; text-align: right; font-size: 13px;"> <?php echo number_format($proporcao, 0, ',', '.') . ' ' . $rowMat['UNIDADE'] ?> </th>        
                      <th scope="col" style="width: 10%; text-align: right; font-size: 13px;"> <?php echo number_format($dataMat['QTDE_ESTOQUE'], 0, ',', '.') . ' ' . $dataMat['UNIDADE'] ?> </th>        
                      <?php
                        $condicao = $dataMat['QTDE_ESTOQUE'] - $proporcao;
                        if($condicao > 0){
                          $barra = 'alert alert-success'; $alerta = 'DISPONÍVEL';
                        }
                        if($condicao < 0){
                          $barra = 'alert alert-danger'; $alerta = 'INSUFICIENTE';
                        } ?>
                        <td scope="col" style="width: 10%; text-align: center; font-size: 13px"><div class="<?php echo $barra ?>" role="alert"><?php echo $alerta ?></div></td>
                      <th scope="col" style="width: 10%; text-align: right; font-size: 13px;"> <?php $compra = ($proporcao + ($proporcao * 0.1)) - $dataMat['QTDE_ESTOQUE'];
                        echo number_format($compra, 0, ',', '.') . ' ' . $dataMat['UNIDADE'] ?> </th>        
                    </tr><?php
                  }
                } 
              ?> 
            </tbody>               
          </table>
        </div>
        <form action="" method="POST">
          <d class="row g-0">
            <div class="col-md-1">
              <label for="dataPedido" class="form-label" style="font-size: 10px; color:aqua;">Data do Pedido</label>
              <input style="font-size: 14px; text-align: center; color:yellow; background: rgba(0,0,0,0.3); width:85px" type="text" class="form-control" id="dataPedido" name="dataPedido"
                value="<?php echo date('d/m/y') ?>" readonly>
            </div>
            <div class="col-md-1">
              <label for="horaPedido" class="form-label" style="font-size: 10px; color:aqua;">Hora</label>
              <input style="font-size: 14px; text-align: center; color:yellow; background: rgba(0,0,0,0.3); width:70px" type="text" class="form-control" id="horaPedido" name="horaPedido"
                value="<?php echo date('H:i') ?>" readonly>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">
              <label for="dataEstimada" class="form-label" style="font-size: 10px; color:aqua;">Disponível em:</label>
              <input style="font-size: 14px; text-align: center; color:whitesmoke; background: rgba(0,0,0,0.3); width:150px" type="date" class="form-control" id="dataEstimada" name="dataEstimada"
                value="" required autofocus>
                <p style="font-size: 10px; color: grey">Selecione uma data estimada de entrega do material</p>
            </div>
            <div class="col-md-4"><br>
              <input type="submit" id="submit" name="submit" value="Confirmar" class="btn btn-primary" style="width:250px ;float:inline-end">
            </div>
            <div class="col-md-12">
              <p style="float: inline-end;">Confirme o agendamento de compra dos ingredientes insuficientes e siga para o próximo passo</p>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-12"><br>
        <?php
          $confirmaAgenda = filter_input_array(INPUT_POST, FILTER_DEFAULT);         
          if(!empty($confirmaAgenda)){
            $_SESSION['dataPedido']   = date('Y-m-d', strtotime($confirmaAgenda['dataPedido']));
            $_SESSION['horaPedido']   = $confirmaAgenda['horaPedido'];
            $_SESSION['dataEstimada'] = $confirmaAgenda['dataEstimada'];
            header("Location: ./33PedidoProduto5.php"); 
          }
        ?>
      </div>
    </div>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->