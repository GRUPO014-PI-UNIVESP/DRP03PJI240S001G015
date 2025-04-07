<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Logística'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() {
      <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 600000); }
  };  inactivityTime();
</script>
<style>
  .tabela{ height: 450px; overflow-y: scroll; }
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <p style="font-size: 20px; color: whitesmoke">Departamento de Logística e Armazenamento</p>
    <h5 style="color: aqua">Estoque de Materiais e Insumos</h5><br>
    <div class="row g-0">
      <div class="col-md-4"><p style="font-size:12px; color: grey;">Descrição do Material</p></div>
      <div class="col-md-8">
        <div class="row g-0">
          <div class="col-md-3"><p style="font-size:12px; color: grey; text-align:center">ID Interno/No.Lote</p></div>
          <div class="col-md-3"><p style="font-size:12px; color: grey; text-align:center">Qtde Disponível   </p></div>
          <div class="col-md-6"><p style="font-size:12px; color: grey"                   >Situação do Lote  </p></div>
        </div>
      </div>
    </div>
    <div class="tabela"><?php 
      $query_material = $connDB->prepare("SELECT * FROM materiais_estoque");$query_material->execute();
      while($rowMat = $query_material->fetch(PDO::FETCH_ASSOC)){ ?>
        <div class="row g-0" style="border-bottom: 1px solid grey">
          <div class="col-md-4"> <p style="padding-left: 5px; font-size: 16px;"   ><?php echo $rowMat['DESCRICAO'] ?></p> </div>
          <div class="col-md-8">
            <div class="row g-1"><?php
              $query_lotes = $connDB->prepare("SELECT *FROM materiais_lotes WHERE ID_ESTOQUE = :idEstoque AND QTDE_LOTE >= 1 AND ETAPA_PROCESS = 3 ORDER BY QTDE_LOTE ASC");
              $query_lotes->bindParam(':idEstoque', $rowMat['ID_ESTOQUE'], PDO::PARAM_INT); $query_lotes->execute(); $nLotes = $query_lotes->rowCount();
              while($rowLotes = $query_lotes->fetch(PDO::FETCH_ASSOC)){ ?>
                <div class="col-md-3"><p style="font-size: 16px; text-align: center"><?php echo $rowLotes['ID_INTERNO']; ?></p></div>
                <div class="col-md-2"><p style="font-size: 16px; text-align: right" ><?php echo number_format($rowLotes['QTDE_LOTE'], 1, ',', '.') . ' ' . $rowLotes['UNIDADE']; ?></p></div>
                <div class="col-md-6"><p style="font-size: 16px; text-align: center"><?php echo $rowLotes['SITUACAO']; ?></p></div><?php
               } ?>
            </div>
          </div>
        </div><?php
      } ?>
    </div><!-- fim da classe tabela -->
    <p style="color:grey">A tabela mostra somente os materiais aprovados pelo laboratório</p>
    <div class="tab-pane fade" id="transporte-tab-pane" role="tabpanel" aria-labelledby="transporte-tab" tabindex="0"><br><br>
      <button type="button" class="btn btn-outline-info" style="width:400px" onclick="location.href='./60RastreamentoEntrega.php'">Rastreamento de Entrega</button><br><br>
    </div>
    <div class="tab-pane fade" id="other-tab-pane" role="tabpanel" aria-labelledby="other-tab" tabindex="0" style="color: whitesmoke"><br><br>
      <a href="" class="font-family: aria-current=">
    </div>
  </div><!-- fim da DIV container-fluid -->
</div><!-- fim da DIV main -->
