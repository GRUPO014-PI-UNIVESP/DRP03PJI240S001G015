<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Setor de Compras'; include_once './RastreadorAtividades.php';
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
  }; inactivityTime();
</script>
<!-- Área Principal -->
  <div class="main">
    <div class="container-fluid">
      <br>
      <p style="font-size: 20px; color: whitesmoke">Departamento Administrativo - Setor de Compras</p>    
      <!-- Menu do Setor de Compras -->
      <div class="row g-3">
        <div class="col-md-3"><br><br>
          <button type="button" class="btn btn-outline-warning" style="width:250px" onclick="location.href='<?php echo $acesso16 ?>'">Compra de Material</button><br><br>
          <button type="button" class="btn btn-outline-warning" style="width:250px" onclick="location.href='<?php echo $acesso15 ?>'">Cadastro de Novo Material</button><br><br>
          <button type="button" class="btn btn-outline-warning" style="width:250px" onclick="location.href='<?php echo $acesso18 ?>'">Relatório de Compras</button><br><br>
        </div>

        <div class="col-md-9">
          <h5 style="text-align: center; color: aqua;">Lista de Compras Agendadas</h5><?php
          $query_compra = $connDB->prepare("SELECT * FROM materiais_compra WHERE ETAPA_PROCESS = 0 ORDER BY DATA_PRAZO ASC"); $query_compra->execute();
          while($rowCompra = $query_compra->fetch(PDO::FETCH_ASSOC)){ $idCompra = $rowCompra['ID_COMPRA']; ?>
            <div class="card text-bg-success mb-3" style="width: 50rem;">
              <div class="card-body">
                <div class="row g-2">
                  <div class="col-md-3">
                    <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Pedido No.</span>
                      <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                            value="<?php echo $rowCompra['NUMERO_PEDIDO'] ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Material</span>
                      <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                            value="<?php echo $rowCompra['DESCRICAO'] ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Qtde.Mínima</span>
                      <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                            value="<?php echo number_format($rowCompra['QTDE_PEDIDO'], 1, ',', '.') . ' ' . $rowCompra['UNIDADE'] ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Data do Pedido</span>
                      <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                            value="<?php echo date('d/m/Y', strtotime($rowCompra['DATA_PEDIDO'])) ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Prazo de Recebimento</span>
                      <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                            value="<?php echo date('d/m/Y', strtotime($rowCompra['DATA_PRAZO'])) ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                      <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none; color: orange" value="<?php echo $rowCompra['SITUACAO'] ?>" readonly>
                    </div>
                  </div>
                  <?php
                  // verifica se compra já foi efetuada para desativar botão de compra
                  $sitCompra = 'COMPRA AGENDADA';
                  if($rowCompra['SITUACAO'] == $sitCompra){ ?>
                    <div class="col-md-3">
                      <button class="btn btn-primary" style="font-size: 14px; float: right" onclick="location.href='./21CompraMaterial.php?id=<?php echo $idCompra ?>'">Autorizar Compra</button>
                    </div> <?php                        
                  } else { ?>
                    <div class="col-md-3">
                      <button class="btn btn-secondary" style="font-size: 14px; float: right">Autorizar Compra</button>
                    </div> <?php
                  } ?>
                </div><!-- fim da DIV row do cartão -->
              </div><!-- fim da DIV do corpo do cartão -->
            </div><!-- fim da DIV do cartão --><?php
          } ?>
        </div><!-- fim da div col md 9 -->
      </div><!-- fim da div row g-3 -->
    </div><!-- fim da div container --> 
  </div><!-- fim da div main -->
