<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Pedido de Produto';
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
        time = setTimeout(deslogar, 300000);
    }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div>
      <h5>Pedido de Produto</h5>
    </div>
    <form action="#" method="POST">
      <div class="row g-3">
        <div class="col-md-3">
          <label for="padrao" class="form-label" style="font-size: 10px; color:aqua">Prazo para Entrega</label>
          <div class="input-group mb-2">
            <input type="number" class="form-control" id="padrao" name="padrao" style="font-size: 13px; text-align: center; background: rgba(0,0,0,0.3)" value="7">
              <span class="input-group-text" style="font-size: 13px">dias em média</span>
          </div>
          <p style="font-size:11px; color:grey">Tempo para fabricação e análises de qualidade</p>
        </div>
        <div class="col-md-3">
          <label for="xtend" class="form-label" style="font-size: 10px; color:aqua">Prazo para Entrega Extendida</label>
          <div class="input-group mb-2">
            <input type="number" class="form-control" id="xtend" name="xtend" 
                   style="font-size: 13px; text-align: center; background: rgba(0,0,0,0.3)" value="14">
              <span class="input-group-text" style="font-size: 13px">dias em média</span>
          </div>
          <p style="font-size:11px; color:grey">Em casos de insuficiência de materiais e insumos</p>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-2"> <?php 
          // verifica o identificador do último registro
          $queryLast = $connDB->prepare("SELECT MAX(NUMERO_PEDIDO) AS ULTIMO FROM pf_pedido");
          $queryLast->execute();
          $rowID = $queryLast->fetch(PDO::FETCH_ASSOC); $novoID = $rowID['ULTIMO'] + 1;?>
          <label for="numPedido" class="form-label" style="font-size: 10px; color:aqua;">Pedido No.</label>
          <input style="font-size: 14px; text-align: center; color:yellow; background: rgba(0,0,0,0.3)" type="number" class="form-control" id="numPedido" name="numPedido" value="<?php echo $novoID ?>" readonly>
        </div>
        <div class="col-md-2">
          <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
          <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="number" class="form-control" id="qtdeLote" name="qtdeLote" autofocus required>
        </div>
        <div class="col-md-8">
          <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
          <select style="font-size: 14px; background: rgba(0,0,0,0.3)" class="form-select" id="nomeProduto" name="nomeProduto" onchange="this.form.submit()">
            <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o Produto</option><?php

              //Pesquisa de descrição do PRODUTO para seleção
              $query_produto = $connDB->prepare("SELECT DISTINCT NOME_PRODUTO FROM pf_tabela");
              $query_produto->execute();

              // inclui nome dos produtos como opções de seleção da tag <select>
              while($produto = $query_produto->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $produto['NOME_PRODUTO']; ?></option> <?php
              } ?>
          </select>
        </div>
      </div><!-- Fim da div row -->
    </form> <?php
      $produto = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      if(!empty($produto['nomeProduto']) && !empty($produto['qtdeLote'])){
        $_SESSION['nomeProduto'] = $produto['nomeProduto']; $nomeProduto = $produto['nomeProduto'];
        $_SESSION['qtdeLote']    = $produto['qtdeLote']   ; $qtdeLote    = $produto['qtdeLote'];
        $_SESSION['numPedido']   = $produto['numPedido']  ; $numPedido   = $produto['numPedido'];
        $_SESSION['padrao']      = $produto['padrao']     ; $_SESSION['xtend'] = $produto['xtend'];

        $query_material = $connDB->prepare("SELECT * FROM pf_tabela WHERE NOME_PRODUTO = :nomeProduto");
        $query_material->bindParam(':nomeProduto', $nomeProduto, PDO::PARAM_STR);
        $query_material->execute();

        while($rowLista = $query_material->fetch(PDO::FETCH_ASSOC)){ 
          $_SESSION['capacidade'] = $rowLista['CAPACIDADE_PROCESS'];
          $descrMaterial          = $rowLista['DESCRICAO_MP'];
          $uniMed                 = $rowLista['UNIDADE_MEDIDA'];
          $qtdeMaterial           = $qtdeLote * ($rowLista['PROPORCAO_MATERIAL'] / 100);

          $query_disponivel = $connDB->prepare("SELECT SUM(QTDE_ESTOQUE) AS estoque, SUM(QTDE_RESERVADA) AS reservado FROM mp_estoque WHERE DESCRICAO_MP = :material");
          $query_disponivel->bindParam(':material', $descrMaterial, PDO::PARAM_STR);
          $query_disponivel->execute();
          $resultado = $query_disponivel->fetch(PDO::FETCH_ASSOC);

          $qtdeDisponivel = $resultado['estoque'] - $resultado['reservado'];

          if($qtdeDisponivel >= $qtdeMaterial){ $alerta = 'DISPONÍVEL';} else 
          if($qtdeDisponivel < $qtdeMaterial) { $alerta = 'INSUFICIENTE';}

          if($alerta == 'INSUFICIENTE'){
            $dataAgenda = date('Y-m-d');
            $dataPrazo  = date('Y-m-d', strtotime("+ 1 week"));
            $situacao   = 'COMPRA AGENDADA';
            $compra = $connDB->prepare("INSERT INTO agenda_compra (DESCRICAO_MP, PEDIDO_NUM, NOME_PRODUTO, DATA_AGENDA, DATA_PRAZO, QTDE_PEDIDO, UNIDADE_MEDIDA, SITUACAO_QUALI, CAPACIDADE_PROCESS) 
                                               VALUES (:descrMaterial, :numPedido, :nomeProduto, :dataAgenda, :dataPrazo, :qtdePedido, :uniMed, :situacao, :capacidade)");
            $compra->bindParam(':descrMaterial', $descrMaterial         , PDO::PARAM_STR);
            $compra->bindParam(':numPedido'    , $numPedido             , PDO::PARAM_STR);
            $compra->bindParam(':nomeProduto'  , $nomeProduto           , PDO::PARAM_STR);
            $compra->bindParam(':dataAgenda'   , $dataAgenda            , PDO::PARAM_STR);
            $compra->bindParam(':dataPrazo'    , $dataPrazo             , PDO::PARAM_STR);
            $compra->bindParam(':qtdePedido'   , $qtdeMaterial          , PDO::PARAM_STR);
            $compra->bindParam(':uniMed'       , $uniMed                , PDO::PARAM_STR);
            $compra->bindParam(':situacao'     , $situacao              , PDO::PARAM_STR);
            $compra->bindParam(':capacidade'   , $_SESSION['capacidade'], PDO::PARAM_INT);
            $compra->execute();
          }
        }
        header('Location: ./34PedidoProduto.php');
      } ?>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->