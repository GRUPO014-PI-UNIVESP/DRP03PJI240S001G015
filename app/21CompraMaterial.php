<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Compra de Material';
  include_once './RastreadorAtividades.php';

  
  if(empty($_GET['id'])){
    $material = '';
    $uniMed   = '';
    $totalCompra = 0;
    $dataAgenda = '';
    $dataPrazo  = '';
  }

  if(!empty($_GET['id'])){
    $material = $_GET['id'];
    $busca = $connDB->prepare("SELECT * FROM agenda_compra WHERE DESCRICAO_MP = :descrMat");
    $busca->bindParam(':descrMat', $material, PDO::PARAM_STR);
    $busca->execute();
    $rowMat = $busca->fetch(PDO::FETCH_ASSOC);
    $uniMed = $rowMat['UNIDADE_MEDIDA'];
    $dataPrazo = $rowMat['DATA_PRAZO'];
    
    $busca2 = $connDB->prepare("SELECT SUM(QTDE_PEDIDO) AS TOTAL FROM agenda_compra WHERE DESCRICAO_MP = :descrMat");
    $busca2->bindParam(':descrMat', $material, PDO::PARAM_STR);
    $busca2->execute();
    $rowQtde = $busca2->fetch(PDO::FETCH_ASSOC);
    $totalCompra = $rowQtde['TOTAL'];
  }

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
        time = setTimeout(deslogar, 3000000);
    }
  };
  inactivityTime();
</script>
<div class="main">
  <div class="container-fluid"><br>
    <div class="row g-2">
      <h5>Efetivação de Compra de Material</h5>
      <div class="col-md-6">
        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
        <input style="font-size: 14px; background: rgba(0,0,0,0.3)" type="ext" class="form-control" 
               id="qtdeLote" name="qtdeLote" value="<?php echo $material ?>" autofocus required>
      </div>
      <div class="input-group mb-3">
        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade Necessária</label>
        <input type="number" class="form-control" id="xtend" name="xtend" 
                style="font-size: 13px; text-align: center; background: rgba(0,0,0,0.3)" value="<?php echo $totalCompra ?>">
          <span class="input-group-text" style="font-size: 13px"><?php echo $uniMed ?></span>
      </div>
    </div>
  </div>
</div>