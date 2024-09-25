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
        time = setTimeout(deslogar, 36000000);
    }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
  <div class="main">
    <div class="container-fluid">
      <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="rh-tab" data-bs-toggle="tab" data-bs-target="#rh-tab-pane" type="button" 
                  role="tab" aria-controls="rh-tab-pane" aria-selected="true">Recursos Humanos</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="comercial-tab" data-bs-toggle="tab" data-bs-target="#comercial-tab-pane" type="button" 
                  role="tab" aria-controls="comercial-tab-pane" aria-selected="false">Setor Comercial</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras-tab-pane" type="button" 
                  role="tab" aria-controls="compras-tab-pane" aria-selected="false">Setor de Compras</button>
        </li>
        <p style="margin-left: 15%; font-size: 20px; color: whitesmoke">Departamento Administrativo</p>
      </ul>
      <div class="tab-content" id="myTabContent">
        <!-- Menu do Recursos Humanos -->
        <div class="tab-pane fade show active" id="rh-tab-pane" role="tabpanel" aria-labelledby="rh-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" 
                  onclick="location.href='<?php echo $acesso5; ?>'">Quadro de Funcionários</button><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" 
                  onclick="location.href='<?php echo $acesso6 ?>' ">Monitor do Histórico de Login</button><br><br>             
          <button type="button" class="btn btn-outline-info" style="width:300px" 
                  onclick="location.href=''">Estrutura da Organização</button><br><br>
        </div><!-- fim da div id = rh-tab-pane -->

        <!-- Menu do Setor Comercial -->
        <div class="tab-pane fade" id="comercial-tab-pane" role="tabpanel" aria-labelledby="prod-tab" tabindex="0"><br>
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
              $produtos = $connDB->prepare("SELECT * FROM pf_pedido");
              $produtos->execute();

              while($rowPedido = $produtos->fetch(PDO::FETCH_ASSOC)){ ?>
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
                                 value="<?php echo $rowPedido['NOME_PRODUTO'] ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Quantidade</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: right; background: none"
                                 value="<?php echo $rowPedido['QTDE_LOTE_PF'] . ' ' . $rowPedido['UNIDADE_MEDIDA'] ?>" readonly>
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
                          <?php $tempo = $rowPedido['QTDE_LOTE_PF'] / $rowPedido['CAPACIDADE_PROCESS']; $id = $rowPedido['NUMERO_PEDIDO'];?>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"
                                 value="<?php echo round($tempo) . ' horas'?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Fabricação</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"
                                 value="<?php echo date('d/m/Y',strtotime($rowPedido['DATA_FABRI'])) ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Entrega</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"
                                 value="<?php echo date('d/m/Y',strtotime($rowPedido['DATA_ENTREGA'])) ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <button class="btn btn-danger" style="font-size: 14px; float: right" onclick="location.href='./36CancelaPedido.php?id=<?php echo $id ?>'">Cancelar Pedido</button>
                      </div>
                      <div class="col-md-12" style="background: rgba(0,0,0,0.3); border-radius: 5px;">
                        <h6 style="color: orange;">Situação : <?php echo $rowPedido['SITUACAO_QUALI'] ?></h6>
                      </div>
                    </div><!-- fim da DIV row g2 -->
                  </div><!-- fim da DIV do corpo do cartão -->
                </div><!-- fim da DIV do cartão --><?php
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
              $material = $connDB->prepare("SELECT DISTINCT DESCRICAO_MP FROM agenda_compra");
              $material->execute();

              while($rowMaterial = $material->fetch(PDO::FETCH_ASSOC)){
                $descrMat  = $rowMaterial['DESCRICAO_MP'];

                $dados = $connDB->prepare("SELECT * FROM agenda_compra WHERE DESCRICAO_MP = :descrMat");
                $dados->bindParam(':descrMat', $descrMat, PDO::PARAM_STR);
                $dados->execute();
                $rowDados = $dados->fetch(PDO::FETCH_ASSOC);

                $situacao   = $rowDados['SITUACAO_QUALI']; 
                $numPedido  = $rowDados['PEDIDO_NUM'];
                $dataAgenda = $rowDados['DATA_AGENDA'];
                $uniMed     = $rowDados['UNIDADE_MEDIDA'];

                $prazo = $connDB->prepare("SELECT DATA_FABRI FROM pf_pedido WHERE NUMERO_PEDIDO = :numPedido");
                $prazo->bindParam(':numPedido', $numPedido, PDO::PARAM_INT);
                $prazo->execute();
                $dataMax = $prazo->fetch(PDO::FETCH_ASSOC);
                $dataLimite = date('Y-m-d', strtotime($dataMax['DATA_FABRI']."- 2 days"));

                $compras = $connDB->prepare("SELECT SUM(QTDE_PEDIDO) AS QTDETOTAL FROM agenda_compra WHERE DESCRICAO_MP = :descrMat");
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
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Data da Agenda</span>
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
                      <div class="col-md-3">
                        <button class="btn btn-primary" style="font-size: 14px; float: right" onclick="location.href='./21CompraMaterial.php?id=<?php echo $descrMat ?>'">Autorizar Compra</button>
                      </div>
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
