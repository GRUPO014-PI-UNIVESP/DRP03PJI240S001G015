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
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 60000000); }
  };  inactivityTime();
</script>
<style>
  .tabela{ height: 480px; overflow-y: scroll; border: solid 1px darkslategray; }
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="controle-tab" data-bs-toggle="tab" data-bs-target="#controle-tab-pane" type="button" role="tab" aria-controls="controle-tab-pane" aria-selected="true">Controle</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="almoxarifado-tab" data-bs-toggle="tab" data-bs-target="#almoxarifado-tab-pane" type="button" role="tab" aria-controls="almoxarifado-tab-pane" aria-selected="false">Almoxarifado</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="transporte-tab" data-bs-toggle="tab" data-bs-target="#transporte-tab-pane" type="button" role="tab" aria-controls="transporte-tab-pane" aria-selected="false">Transporte</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other-tab-pane" type="button" role="tab" aria-controls="other-tab-pane" aria-selected="false">Outros</button>
      </li>
      <p style="margin-left: 5%; font-size: 20px; color: whitesmoke">Departamento de Logística e Armazenamento</p>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="controle-tab-pane" role="tabpanel" aria-labelledby="controle-tab" tabindex="0"><br>
        <div class="row g-3">
          <!-- coluna para cards de materiais -->
          <div class="col-md-6">
            <h6>Lista de Materiais para Recebimento</h6>
            <div class="row g-1"><?php
              $materiais = $connDB->prepare("SELECT * FROM materiais_compra WHERE ETAPA_PROCESS = 1");
              $materiais->execute();
              while($rowMat = $materiais->fetch(PDO::FETCH_ASSOC)){
                $id = $rowMat['ID_COMPRA']; ?>
                <div class="card text-bg-success mb-3" style="width: 35rem;">
                  <div class="card-body">
                    <div class="row g-1">
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Descrição do Material</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo $rowMat['DESCRICAO']?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Quantidade da Compra</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo number_format($rowMat['QTDE_PEDIDO'], 1, ',', '.') . ' ' . $rowMat['UNIDADE']  ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Data Prevista</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo date('d/m/Y', strtotime($rowMat['DATA_PRAZO'])) ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none; color: orange;" value="<?php echo $rowMat['SITUACAO']?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <button class="btn btn-primary" style="float: right" onclick="location.href='./20EntradaMaterial.php?id=<?php echo $id ?>'">Efetuar Recebimento</button>
                      </div>
                    </div><!-- fim da DIV row g1 -->
                  </div><!-- fim da DIV do corpo do cartão -->
                </div><!-- fim da DIV do cartão --><?php
              } ?>
            </div><!-- fim da DIV row g1 -->
          </div><!-- fim da coluna de cards de materiais -->

          <!-- coluna para cardas de produtos acabados -->
          <div class="col-md-6">
            <h6>Lista de Produtos </h6>
            <div class="row g-1"><?php
              $query_pedido = $connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 4"); $query_pedido->execute(); 
              while($rowPedido = $query_pedido->fetch(PDO::FETCH_ASSOC)){
                $idPed = $rowPedido['ID_PEDIDO'];?>
                <div class="card text-bg-success mb-3" style="width: 35rem;">
                  <div class="card-body">
                    <div class="row g-1">
                      <div class="col-md-8">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Produto</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo $rowPedido['PRODUTO']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Qtde</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; text-align: center; background: none;" value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 1, ',', '.') . ' ' . $rowPedido['UNIDADE']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Cliente</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo $rowPedido['CLIENTE']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Entrega</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; text-align: center; background: none;" value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_ENTREGA'])); ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none; color:orange" value="<?php echo $rowPedido['SITUACAO']; ?>" readonly>
                        </div>
                      </div><?php
                      if($rowPedido['ETAPA_PROCESS'] == 3){ ?>
                        <div class="col-md-12">
                          <button class="btn btn-primary" onclick="location.href='./50SaidaProdutoFinal.php?id=<?php echo $idPed ?>'" style="float: right">Efetuar Saída do Produto</button>
                        </div> <?php 
                      }
                      if($rowPedido['ETAPA_PROCESS'] < 3){ ?>
                        <div class="col-md-12">
                          <button class="btn btn-secondary" onclick="location.href='#'" style="float: right" disabled>Efetuar Saída do Produto</button>
                        </div> <?php 
                      }
                      ?>
                    </div>
                  </div><!-- fim da DIV do corpo do cartão -->
                </div><!-- fim da DIV do cartão --> <?php 
              } ?>
            </div><!-- fim da DIV row g1 -->
          </div><!-- fim da coluna de cards de produtos acabados -->
        </div><!-- fim da row g2 -->
      </div><!-- fim da DIV tab pane manager -->
          
      <div class="tab-pane fade" id="almoxarifado-tab-pane" role="tabpanel" aria-labelledby="almoxarifado-tab" tabindex="0"><br>
        <h5 style="color: aqua">Estoque de Materiais e Insumos</h5><br>
        <div class="row g-0">
          <div class="col-md-4"><p style="font-size:11px; color: grey;">Descrição do Material</p></div>
          <div class="col-md-8">
            <div class="row g-0">
              <div class="col-md-3"><p style="font-size:11px; color: grey">ID Interno/No.Lote</p></div>
              <div class="col-md-3"><p style="font-size:11px; color: grey">Qtde Disponível   </p></div>
              <div class="col-md-6"><p style="font-size:11px; color: grey">Situação do Lote  </p></div>
            </div>
          </div>
        </div>
        <div class="tabela"><?php 
          $query_material = $connDB->prepare("SELECT * FROM materiais_estoque");$query_material->execute();
          while($rowMat = $query_material->fetch(PDO::FETCH_ASSOC)){ ?>
            <div class="row g-0">
              <div class="col-md-4">
                <p style="padding-left: 5px; font-size: 13px;"><?php echo $rowMat['DESCRICAO'] . '<br>' . $rowMat['FORNECEDOR']; ?></p>
              </div>
              <div class="col-md-8">
                <div class="row g-1"><?php
                  $query_lotes = $connDB->prepare("SELECT * FROM materiais_lotes WHERE ID_ESTOQUE = :idEstoque AND QTDE_LOTE >= 1 AND ETAPA_PROCESS = 3 ORDER BY QTDE_LOTE ASC");
                  $query_lotes->bindParam(':idEstoque', $rowMat['ID_ESTOQUE'], PDO::PARAM_INT); $query_lotes->execute(); $nLotes = $query_lotes->rowCount();
                  while($rowLotes = $query_lotes->fetch(PDO::FETCH_ASSOC)){ ?>
                  <div class="col-md-3"><p style="font-size: 20px;"><?php echo $rowLotes['ID_INTERNO']; ?></p></div>
                  <div class="col-md-3"><p style="font-size: 20px;"><?php echo number_format($rowLotes['QTDE_LOTE'], 1, ',', '.') . ' ' . $rowLotes['UNIDADE']; ?></p></div>
                  <div class="col-md-6" style="font-size: 11px;vertical-align:center"><p style="font-size: 11px;vertical-align:center"><?php echo $rowLotes['SITUACAO']; ?></p></div><?php
                  } ?>
                </div>
              </div>
            </div><?php
          } ?>
        </div><!-- fim da classe tabela -->
                

      <div class="tab-pane fade" id="transporte-tab-pane" role="tabpanel" aria-labelledby="transporte-tab" tabindex="0"><br><br>
        <button type="button" class="btn btn-outline-info" style="width:400px" 
                onclick="location.href='./60RastreamentoEntrega.php'">Rastreamento de Entrega</button><br><br>
      </div>

      <div class="tab-pane fade" id="other-tab-pane" role="tabpanel" aria-labelledby="other-tab" tabindex="0" style="color: whitesmoke"><br><br>
        <a href="" class="font-family: aria-current=">
      </div>
    </div><!-- fim da DIV tab content -->
  </div><!-- fim da DIV container-fluid -->
</div><!-- fim da DIV main -->
