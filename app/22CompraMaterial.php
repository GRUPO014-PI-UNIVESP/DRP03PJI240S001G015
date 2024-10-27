<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Compra de Material'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php';}
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 6000000);}
  }; inactivityTime();
</script>
<div class="main">
  <div class="container-fluid"><br>
    <form method="POST">
      <div class="row g-3"><h5>Compra Avulsa de Material</h5>
        <div class="col-md-8">
          <label for="descrMat" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
          <select style="font-size: 16px;" class="form-select" id="descrMat" name="descrMat">
            <option style="font-size: 16px" selected>Selecione o Material</option><?php
              $query_material = $connDB->prepare("SELECT * FROM materiais_estoque"); $query_material->execute();
              while($rowMat = $query_material->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 16px"><?php echo $rowMat['DESCRICAO']; ?></option> <?php
              }?>
          </select>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-2">
          <label for="dataPedido" class="form-label" style="font-size: 10px; color:aqua">Data da Solicitação</label>
          <input style="font-weight: bold; font-size: 20px; background: rgba(0,0,0,0.3); text-align: center; color:yellow" type="date" class="form-control" id="dataPedido" name="dataPedido">
        </div>
        <div class="col-md-2">
          <label for="dataPrazo" class="form-label" style="font-size: 10px; color:aqua">Data Prazo de Recebimento</label>
          <input style="font-weight: bold; font-size: 20px; background: rgba(0,0,0,0.3); text-align: center; color:yellow" type="date" class="form-control" id="dataPrazo" name="dataPrazo">
        </div>
        <div class="col-md-8"></div>
        <div class="col-md-2">
          <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade Necessária</label>
          <input style="font-weight: bold; font-size: 25px; background: rgba(0,0,0,0.3); text-align: center" type="number" class="form-control" id="qtdeLote" name="qtdeLote" >
        </div>
        <div class="col-md-2"><br><br>
            <input class="btn btn-primary" type="submit" id="agendado" name="agendado" value="Confirmar e Autorizar Compra">
        </div>
      </div>
    </form><br><?php $confirmaCompra = filter_input_array(INPUT_POST, FILTER_DEFAULT);     
    if(!empty($confirmaCompra['agendado']) && $confirmaCompra['descrMat'] == 'Selecione o Material'){ ?>
      <div class="alert alert-danger" role="alert">Nenhum material foi selecionado! Reinicie o procedimento.</div>
      <div><button class="btn btn-danger" onclick="location.href='./22CompraMaterial.php'">Reiniciar</button></div><?php
    }
    if(!empty($confirmaCompra['agendado']) && $confirmaCompra['descrMat'] != 'Selecione o Material'){ 
      $qtdeCompra = $confirmaCompra['qtdeLote']; $numPedido = 0; $etapa = 1; $situacao = 'COMPRA EFETUADA, AGUARDANDO RECEBIMENTO'; $produto = 'REPOSIÇÃO DE ESTOQUE';
      $dataPedido = date('Y-m-d', strtotime($confirmaCompra['dataPedido'])); $dataPrazo = date('Y-m-d', strtotime($confirmaCompra['dataPrazo']));

      $buscaMaterial = $connDB->prepare("SELECT * FROM materiais_estoque WHERE DESCRICAO = :descrMat");
      $buscaMaterial->bindParam(':descrMat', $confirmaCompra['descrMat'], PDO::PARAM_STR);
      $buscaMaterial->execute(); $rowBusca = $buscaMaterial->fetch(PDO::FETCH_ASSOC);

      $compra = $connDB->prepare("INSERT INTO materiais_compra (ID_ESTOQUE, DESCRICAO, NUMERO_PEDIDO, PRODUTO, ETAPA_PROCESS, DATA_PEDIDO, DATA_PRAZO, QTDE_PEDIDO, UNIDADE, SITUACAO) VALUES (:idEstoque, :descrMat, :numPedido, :produto, :etapa, :dataPedido, :dataPrazo, :qtdePedido, :uniMed, :situacao)");
      $compra->bindParam(':numPedido' , $numPedido , PDO::PARAM_INT); $compra->bindParam(':idEstoque', $rowBusca['ID_ESTOQUE'], PDO::PARAM_INT);
      $compra->bindParam(':etapa'     , $etapa     , PDO::PARAM_INT); $compra->bindParam(':descrMat' , $rowBusca['DESCRICAO'] , PDO::PARAM_STR);
      $compra->bindParam(':dataPedido', $dataPedido, PDO::PARAM_STR); $compra->bindParam(':uniMed'   , $rowBusca['UNIDADE']   , PDO::PARAM_STR);
      $compra->bindParam(':qtdePedido', $qtdeCompra, PDO::PARAM_STR); $compra->bindParam(':situacao' , $situacao              , PDO::PARAM_STR);
      $compra->bindParam(':dataPrazo' , $dataPrazo , PDO::PARAM_STR); $compra->bindParam(':produto'  , $produto               , PDO::PARAM_STR); $compra->execute();

      $buscaCompra = $connDB->prepare("SELECT ID_COMPRA FROM materiais_compra WHERE ID_ESTOQUE =:idEstoque AND NUMERO_PEDIDO = :numPedido AND ETAPA_PROCESS = :etapa AND DATA_PRAZO = :dataPrazo LIMIT 1");
      $buscaCompra->bindParam(':idEstoque', $rowBusca['ID_ESTOQUE'], PDO::PARAM_INT);
      $buscaCompra->bindParam(':numPedido', $numPedido             , PDO::PARAM_INT);
      $buscaCompra->bindParam(':etapa'    , $etapa                 , PDO::PARAM_INT);
      $buscaCompra->bindParam(':dataPrazo', $dataPrazo             , PDO::PARAM_STR);
      $buscaCompra->execute(); $idCompra = $buscaCompra->fetch(PDO::FETCH_ASSOC); $lastID = $idCompra['ID_COMPRA'];

      $realiza = $connDB->prepare("INSERT INTO materiais_lotes (ID_COMPRA, ID_ESTOQUE, DESCRICAO, QTDE_LOTE, UNIDADE, ETAPA_PROCESS, SITUACAO) VALUES (:idCompra, :idEstoque, :descrMat, :qtdeLote, :uniMed, :etapa, :situacao)");                                               
      $realiza->bindParam(':qtdeLote', $qtdeCompra, PDO::PARAM_STR); $realiza->bindParam(':idEstoque', $rowBusca['ID_ESTOQUE'], PDO::PARAM_INT);       
      $realiza->bindParam(':etapa'   , $etapa     , PDO::PARAM_INT); $realiza->bindParam(':descrMat' , $rowBusca['DESCRICAO'] , PDO::PARAM_STR);
      $realiza->bindParam(':situacao', $situacao  , PDO::PARAM_STR); $realiza->bindParam(':uniMed'   , $rowBusca['UNIDADE']   , PDO::PARAM_STR);
      $realiza->bindParam(':idCompra', $lastID    , PDO::PARAM_INT); $realiza->execute();     

      header('Location: ./00SeletorAdministrativo.php');
    } ?>
  </div>
</div>
