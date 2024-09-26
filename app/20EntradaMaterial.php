<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Entrada de Material';
include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
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
    <h5>Recebimento de Material</h5><br><?php
    if(!empty($_GET['id'])){ 
      $mpEntra = $connDB->prepare("SELECT * FROM mp_estoque WHERE ID_ESTOQUE_MP = :id");
      $mpEntra->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
      $mpEntra->execute();
      $rowMP = $mpEntra->fetch(PDO::FETCH_ASSOC);

      // algoritmo para geração de numero de lote interno
      ?>
      <div class="row g-2">
        <div class="col-md-2">
          <div class="form-floating mb-3">
            <input type="dataEntrada" class="form-control" id="dataEntrada" name="dataEntrada" style="font-weight: bolder; text-align: center" value="<?php echo date('d/m/Y') ?>">
            <label for="floatingInput" style="color: aqua; font-size: 12px">Data de Recebimento</label>
          </div>
        </div>
        <div class="col-md-10">
          <div class="form-floating mb-3">
            <input type="descrMat" class="form-control" id="descrMat" name="descrMat" style="font-weight: bolder;" value="<?php echo $rowMP['DESCRICAO_MP'] ?>">
            <label for="floatingInput" style="color: aqua; font-size: 12px">Descrição do Material</label>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-3">
            <input type="qtdeLote" class="form-control" id="qtdeLote" name="qtdeLote" style="font-weight: bolder; text-align:right" value="<?php echo $rowMP['QTDE_LOTE'] . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>">
            <label for="floatingInput" style="color: aqua; font-size: 12px">Quantidade Recebida</label>
          </div>
        </div>
      </div><?php
    }?>
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->