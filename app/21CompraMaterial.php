<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Compra de Material';
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
        time = setTimeout(deslogar, 600000);
    }
  };
  inactivityTime();
</script>
<div class="main">
  <div class="container-fluid"><br>
  <form method="POST" id="agendado">
    <div class="row g-3">
      <h5>Efetivação de Compra de Material</h5>
      <?php
      if(!empty($_GET['id'])){
        $material = $_GET['id'];
        $busca = $connDB->prepare("SELECT * FROM materiais_compra WHERE DESCRICAO = :descrMat");
        $busca->bindParam(':descrMat', $material, PDO::PARAM_STR);
        $busca->execute(); $rowMat = $busca->fetch(PDO::FETCH_ASSOC);
        $uniMed     = $rowMat['UNIDADE'];
        $dataAgenda = $rowMat['DATA_AGENDA'];
        $dataPrazo  = $rowMat['DATA_PRAZO'];
        
        $busca2 = $connDB->prepare("SELECT SUM(QTDE_PEDIDO) AS TOTAL FROM materiais_compra WHERE DESCRICAO = :descrMat");
        $busca2->bindParam(':descrMat', $material, PDO::PARAM_STR);
        $busca2->execute(); $rowQtde = $busca2->fetch(PDO::FETCH_ASSOC);
        $totalCompra = $rowQtde['TOTAL']; ?>
        <div class="col-md-7">
          <label for="descrMat" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
          <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3)" type="text" class="form-control" 
                 id="descrMat" name="descrMat" value="<?php echo $material ?>" readonly>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
          <label for="dataAgenda" class="form-label" style="font-size: 10px; color:aqua">Data da Solicitação</label>
          <input style="font-weight: bold; font-size: 20px; background: rgba(0,0,0,0.3); text-align: center" type="text" class="form-control" 
                 id="dataAgenda" name="dataAgenda" value="<?php echo date('d/m/Y', strtotime($dataAgenda)) ?>" readonly>
        </div>
        <div class="col-md-2">
          <label for="dataPrazo" class="form-label" style="font-size: 10px; color:aqua">Data Prazo de Recebimento</label>
          <input style="font-weight: bold; font-size: 20px; background: rgba(0,0,0,0.3); text-align: center" type="text" class="form-control" 
                 id="dataPrazo" name="dataPrazo" value="<?php echo date('d/m/Y', strtotime($dataPrazo."- 2 days")) ?>" readonly>
        </div>
        <div class="col-md-8"></div>
        <div class="col-md-2">
          <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade Necessária</label>
          <input style="font-weight: bold; font-size: 25px; background: rgba(0,0,0,0.3); text-align: center" type="number" class="form-control" 
                 id="qtdeLote" name="qtdeLote" value="<?php echo $totalCompra ?>" autofocus>
          <p style="font-size: 13px; color: grey">Aumente a quantidade caso seja necessário</p>
        </div>
        <div class="col-md-2">
          <label for="uniMed" class="form-label" style="font-size: 10px; color:aqua">Unidade de Medida</label>
          <input style="width: 75px; font-weight: bold; font-size: 25px; background: rgba(0,0,0,0.3); text-align: center" type="text" class="form-control" 
                 id="uniMed" name="uniMed" value="<?php echo $uniMed ?>" readonly>
        </div>
        <div class="col-md-2">
          <br><br>
          <input class="btn btn-primary" type="submit" id="agendado" name="agendado" value="Confirmar e Autorizar Compra">
        </div><?php 
      } 
      if(empty($_GET['id'])){ ?>
        <div class="col-md-7">
          <label for="descrMat" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
          <select style="font-size: 20px; background: rgba(0,0,0,0.3)" class="form-select" id="descrMat" name="descrMat" autofocus required>
            <option style="font-size: 20px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o Produto</option><?php

              //Pesquisa de descrição do PRODUTO para seleção
              $busca = $connDB->prepare("SELECT DISTINCT DESCRICAO FROM materiais_estoque");
              $busca->execute();

              // inclui nome dos produtos como opções de seleção da tag <select>
              while($rowMat = $busca->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $rowMat['DESCRICAO']; ?></option> <?php
              } ?>
          </select>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-2">
          <label for="dataAgenda" class="form-label" style="font-size: 10px; color:aqua">Data da Solicitação</label>
          <input style="font-weight: bold; font-size: 20px; background: rgba(0,0,0,0.3); text-align: center" type="text" class="form-control" 
                 id="dataAgenda" name="dataAgenda" value="<?php echo date('d/m/Y') ?>" readonly>
        </div>
        <div class="col-md-2">
          <label for="dataPrazo" class="form-label" style="font-size: 10px; color:aqua">Data Prazo de Recebimento</label>
          <input style="font-weight: bold; font-size: 20px; background: rgba(0,0,0,0.3); text-align: center" type="text" class="form-control" 
                 id="dataPrazo" name="dataPrazo" value="<?php echo date('d/m/Y', strtotime("+ 1 week")) ?>" readonly>
        </div>
        <div class="col-md-2">
          <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade Necessária</label>
          <input style="font-weight: bold; font-size: 25px; background: rgba(0,0,0,0.3); text-align: center" type="number" class="form-control" 
                 id="qtdeLote" name="qtdeLote" required>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-2">
          <br><br>
          <input class="btn btn-primary" type="submit" id="reposicao" name="reposicao" value="Confirmar e Autorizar Compra">
        </div><?php 
      } ?>
    </div>
  </form><?php
    $confirmaCompra = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if(!empty($confirmaCompra['agendado'])){

      $descrMat    = $confirmaCompra['descrMat'];
      $dataEntrega = date('Y-m-d', strtotime("+ 5 days"));
      $qtdeCompra  = $confirmaCompra['qtdeLote'];
      $situacao    = 'COMPRA EFETUADA, AGUARDANDO RECEBIMENTO';
      $etapa       = 1;

      $query_material = $connDB->prepare("SELECT ID_ESTOQUE, UNIDADE FROM materiais_estoque WHERE DESCRICAO = :descrMat");
      $query_material->bindParam(':descrMat', $descrMat, PDO::PARAM_STR);
      $query_material->execute(); $rowMaterial = $query_material->fetch(PDO::FETCH_ASSOC);

      $realiza = $connDB->prepare("INSERT INTO materiais_lotes (ID_ESTOQUE, DESCRICAO, QTDE_LOTE, UNIDADE, ETAPA_PROCESS, SITUACAO)
                                          VALUES (:idEstoque, :descrMat, :qtdeLote, :uniMed, :etapa, :situacao)");
      $realiza->bindParam(':idEstoque', $rowMaterial['ID_ESTOQUE'], PDO::PARAM_STR);                                    
      $realiza->bindParam(':descrMat' , $descrMat                 , PDO::PARAM_STR);
      $realiza->bindParam(':qtdeLote' , $qtdeCompra               , PDO::PARAM_STR);
      $realiza->bindParam(':uniMed'   , $rowMaterial['UNIDADE']   , PDO::PARAM_STR);
      $realiza->bindParam(':etapa'    , $etapa                    , PDO::PARAM_STR);
      $realiza->bindParam(':situacao' , $situacao                 , PDO::PARAM_STR);
      $realiza->execute();
      
      if(!empty($_GET['id'])){

        $idCompra = $connDB->prepare("SELECT MAX(ID_ESTOQUE) AS ultimo FROM materiais_estoque WHERE DESCRICAO = :descrMat");
        $idCompra->bindParam(':descrMat', $descrMat, PDO::PARAM_STR);
        $idCompra->execute();
        $rowID = $idCompra->fetch(PDO::FETCH_ASSOC);

        $atualiza = $connDB->prepare("UPDATE materiais_compra SET SITUACAO = :situacao WHERE DESCRICAO = :descrMat");
        $atualiza->bindParam(':descrMat', $descrMat, PDO::PARAM_STR);
        $atualiza->bindParam(':situacao', $situacao, PDO::PARAM_STR);
        $atualiza->execute();
      }
      header('Location: ./00SeletorAdministrativo.php');
    }
    if(!empty($confirmaCompra['reposicao'])){

      $descrMat    = $confirmaCompra['descrMat'];
      $dataEntrega = date('Y-m-d', strtotime($confirmaCompra['dataPrazo']));
      $qtdeCompra  = $confirmaCompra['qtdeLote'];
      $situacao    = 'COMPRA EFETUADA, AGUARDANDO RECEBIMENTO';

      $query_material = $connDB->prepare("SELECT ID_ESTOQUE, UNIDADE FROM materiais_estoque WHERE DESCRICAO = :descrMat");
      $query_material->bindParam(':descrMat', $descrMat, PDO::PARAM_STR);
      $query_material->execute(); $rowMaterial = $query_material->fetch(PDO::FETCH_ASSOC);     

      $realiza = $connDB->prepare("INSERT INTO materiais_lotes (ID_ESTOQUE, DESCRICAO, QTDE_LOTE, UNIDADE, ETAPA_PROCESS, SITUACAO)
                                          VALUES (:idEstoque, :descrMat, :qtdeLote, :uniMed, :etapa, :situacao)");
      $realiza->bindParam(':idEstoque', $rowMaterial['ID_ESTOQUE'], PDO::PARAM_STR);                                    
      $realiza->bindParam(':descrMat' , $descrMat                 , PDO::PARAM_STR);
      $realiza->bindParam(':qtdeLote' , $qtdeCompra               , PDO::PARAM_STR);
      $realiza->bindParam(':uniMed'   , $rowMaterial['UNIDADE']   , PDO::PARAM_STR);
      $realiza->bindParam(':etapa'    , $etapa                    , PDO::PARAM_STR);
      $realiza->bindParam(':situacao' , $situacao                 , PDO::PARAM_STR);
      $realiza->execute();
      header('Location: ./00SeletorAdministrativo.php');
    }?>
  </div>
</div>