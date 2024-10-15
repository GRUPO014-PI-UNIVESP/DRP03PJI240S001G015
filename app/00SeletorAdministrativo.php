<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Administrativo';
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
<!-- Área Principal -->
  <div class="main">
    <div class="container-fluid">
      <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="rh-tab" data-bs-toggle="tab" data-bs-target="#rh-tab-pane" type="button" 
                  role="tab" aria-controls="rh-tab-pane" aria-selected="true">Recursos Humanos</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="comercial-tab" data-bs-toggle="tab" data-bs-target="#comercial-tab-pane" type="button" 
                  role="tab" aria-controls="comercial-tab-pane" aria-selected="false">Setor de Vendas</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras-tab-pane" type="button" 
                  role="tab" aria-controls="compras-tab-pane" aria-selected="false">Setor de Compras</button>
        </li>
        <p style="margin-left: 15%; font-size: 20px; color: whitesmoke">Departamento Administrativo</p>
      </ul>
      <div class="tab-content" id="myTabContent">
        <!-- Menu do Recursos Humanos -->
        <div class="tab-pane fade" id="rh-tab-pane" role="tabpanel" aria-labelledby="rh-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" 
                  onclick="location.href='<?php echo $acesso5; ?>'">Quadro de Funcionários</button><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" 
                  onclick="location.href='<?php echo $acesso60 ?>' ">Monitor do Histórico de Login</button><br><br>             
          <button type="button" class="btn btn-outline-info" style="width:300px" 
                  onclick="location.href=''">Estrutura da Organização</button><br><br>
        </div><!-- fim da div id = rh-tab-pane -->

        <!-- Menu do Setor Comercial -->
        <div class="tab-pane fade show active" id="comercial-tab-pane" role="tabpanel" aria-labelledby="prod-tab" tabindex="0"><br>
          <div class="row g-3">
            <div class="col-md-3"><br>
              <button type="button" class="btn btn-outline-light" style="width:250px" 
                      onclick="location.href='<?php echo $acesso11 ?>'">Pedido de Produto</button><br><br>
              <button type="button" class="btn btn-outline-light" style="width:250px" 
                      onclick="location.href='<?php echo $acesso13 ?>' ">Cadastro de Novo Cliente</button><br><br>  
              <button type="button" class="btn btn-outline-light" style="width:250px" 
                      onclick="location.href='<?php echo $acesso14 ?>'">Cadastro de Novo Produto</button><br><br>
            </div>

            <div class="col-md-9">
              <h5>Lista dos Pedidos em Execução</h5><?php
              $produtos = $connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 4");
              $produtos->execute();

              while($rowPedido = $produtos->fetch(PDO::FETCH_ASSOC)){
                if(!empty($rowPedido['NUMERO_PEDIDO'])){ ?>
                  <div class="card text-bg-success mb-3" style="width: 50rem;">
                    <div class="card-body">
                      <div class="row g-2">
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Pedido No.</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                                   value="<?php echo $rowPedido['NUMERO_PEDIDO']?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-9">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Produto</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none"
                                   value="<?php echo $rowPedido['PRODUTO'] ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Quantidade</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: right; background: none"
                                   value="<?php echo $rowPedido['QTDE_PEDIDO'] . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-9">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Cliente</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none"
                                   value="<?php echo $rowPedido['CLIENTE'] ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Uso da Planta</span>
                            <?php 
                            if($rowPedido['CAPAC_PROCESS'] != 0 || $rowPedido['CAPAC_PROCESS'] != null){
                              $tempo = $rowPedido['QTDE_PEDIDO'] / $rowPedido['CAPAC_PROCESS']; $id = $rowPedido['NUMERO_PEDIDO'];?>
                              <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"
                                     value="<?php echo round($tempo) . ' horas'?>" readonly><?php
                            } else { $tempo = 0; ?> 
                              <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"
                              value="0" readonly>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Fabricação</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"
                                   value="" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Entrega</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"
                                   value="<?php echo date('d/m/Y',strtotime($rowPedido['DATA_ENTREGA'])) ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-3"><?php
                          if($rowPedido['ETAPA_PROCESS'] > 1){ ?>
                            <button class="btn btn-secondary" style="font-size: 14px; float: right" onclick="" disabled>Cancelar Pedido</button><?php
                          }
                          if($rowPedido['ETAPA_PROCESS'] <= 1){ ?>
                            <button class="btn btn-danger" style="font-size: 14px; float: right" onclick="location.href='./36CancelaPedido.php?id=<?php echo $id ?>'">Cancelar Pedido</button><?php
                          } ?>
                        </div>
                        <div class="col-md-12">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none; color: orange"
                                   value="<?php echo $rowPedido['SITUACAO'] ?>" readonly>
                          </div>
                        </div>
                      </div><!-- fim da DIV row g2 -->
                    </div><!-- fim da DIV do corpo do cartão -->
                  </div><!-- fim da DIV do cartão --><?php
                }
              } ?><!-- fim da recursão -->
            </div><!-- fim da coluna exclusiva dos cartões -->
          </div><!-- fim da DIV row do setor comercial -->
        </div><!-- fim da div id = prod-tab-pane -->

        <!-- Menu do Setor de Compras -->
        <div class="tab-pane fade" id="compras-tab-pane" role="tabpanel" aria-labelledby="comercial-tab" tabindex="0"><br><br>
          <div class="row g-3">
            <div class="col-md-3">
              <button type="button" class="btn btn-outline-warning" style="width:250px" 
                      onclick="location.href='<?php echo $acesso12 ?>'">Compra de Material</button><br><br>
              <button type="button" class="btn btn-outline-warning" style="width:250px" 
                      onclick="location.href='<?php echo $acesso15 ?>'">Cadastro de Novo Material</button><br><br>
            </div>

            <div class="col-md-9">
              <h5>Lista de Compra Agendada</h5><?php
              $material = $connDB->prepare("SELECT DISTINCT DESCRICAO FROM materiais_compra ORDER BY SITUACAO ASC");
              $material->execute();

              while($rowMaterial = $material->fetch(PDO::FETCH_ASSOC)){
                $descrMat = $rowMaterial['DESCRICAO'];

                $dados = $connDB->prepare("SELECT * FROM materiais_compra WHERE DESCRICAO = :descrMat");
                $dados->bindParam(':descrMat', $descrMat, PDO::PARAM_STR);
                $dados->execute();
                $rowDados = $dados->fetch(PDO::FETCH_ASSOC);

                $situacao   = $rowDados['SITUACAO']; 
                $numPedido  = $rowDados['NUMERO_PEDIDO'];
                $dataAgenda = $rowDados['DATA_PEDIDO'];
                $uniMed     = $rowDados['UNIDADE'];

                $prazo = $connDB->prepare("SELECT DATA_AGENDA FROM materiais_compra WHERE NUMERO_PEDIDO = :numPedido");
                $prazo->bindParam(':numPedido', $numPedido, PDO::PARAM_INT);
                $prazo->execute();
                $dataMax = $prazo->fetch(PDO::FETCH_ASSOC);
                $dataLimite = date('Y-m-d', strtotime($dataMax['DATA_AGENDA']."- 3 days"));

                $compras = $connDB->prepare("SELECT SUM(QTDE_PEDIDO) AS QTDETOTAL FROM materiais_compra WHERE DESCRICAO = :descrMat");
                $compras->bindParam(':descrMat', $descrMat, PDO::PARAM_STR);
                $compras->execute();
                $totalQtde = $compras->fetch(PDO::FETCH_ASSOC);
                $totalCompra = $totalQtde['QTDETOTAL'];?>
                <div class="card text-bg-success mb-3" style="width: 50rem;">
                  <div class="card-body">
                    <div class="row g-2">
                      <div class="col-md-3">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Pedido No.</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                                 value="<?php echo $numPedido ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-9">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Material</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                                 value="<?php echo $descrMat ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Qtde.Mínima</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                                 value="<?php echo $totalCompra . ' ' . $uniMed ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Data do Pedido</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                                 value="<?php echo date('d/m/Y', strtotime($dataAgenda)) ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Prazo de Recebimento</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                                 value="<?php echo date('d/m/Y', strtotime($dataLimite)) ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-9">
                      </div>
                      <?php
                      // verifica se compra já foi efetuada para desativar botão de compra
                      $sitCompra = 'COMPRA AGENDADA';
                      if($rowDados['SITUACAO'] == $sitCompra){ ?>
                        <div class="col-md-3">
                          <button class="btn btn-primary" style="font-size: 14px; float: right" onclick="location.href='./21CompraMaterial.php?id=<?php echo $descrMat ?>'">Autorizar Compra</button>
                        </div> <?php                        
                      } else { ?>
                        <div class="col-md-3">
                          <button class="btn btn-secondary" style="font-size: 14px; float: right">Autorizar Compra</button>
                      </div> <?php
                      } ?>
                      <div class="col-md-12" style="background: rgba(0,0,0,0.3); border-radius: 5px;">
                        <h6 style="color: orange;">Situação : <?php echo $situacao ?></h6>
                      </div>
                    </div><!-- fim da DIV row do cartão -->
                  </div><!-- fim da DIV do corpo do cartão -->
                </div><!-- fim da DIV do cartão --><?php
              } ?>
            </div><!-- fim da div col md 9 -->
          </div><!-- fim da div row g-3 -->
        </div><!-- fim da div id = compras-tab-pane -->
      </div><!-- fim da class tab content -->
    </div><!-- fim da div container --> 
  </div><!-- fim da div main -->
