<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Logística';
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
  <div class="container-fluid">
    <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="controle-tab" data-bs-toggle="tab" data-bs-target="#controle-tab-pane" type="button" 
                role="tab" aria-controls="controle-tab-pane" aria-selected="true">Controle</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="almoxarifado-tab" data-bs-toggle="tab" data-bs-target="#almoxarifado-tab-pane" type="button" 
                role="tab" aria-controls="almoxarifado-tab-pane" aria-selected="false">Almoxarifado</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="transporte-tab" data-bs-toggle="tab" data-bs-target="#transporte-tab-pane" type="button" 
                role="tab" aria-controls="transporte-tab-pane" aria-selected="false">Transporte</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other-tab-pane" type="button" 
                role="tab" aria-controls="other-tab-pane" aria-selected="false">Outros</button>
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
              $materiais = $connDB->prepare("SELECT * FROM mp_estoque WHERE ETAPA_PROD < 2");
              $materiais->execute();
              while($rowMat = $materiais->fetch(PDO::FETCH_ASSOC)){
                $id = $rowMat['ID_ESTOQUE_MP']; ?>
                <div class="card text-bg-success mb-3" style="width: 35rem;">
                  <div class="card-body">
                    <div class="row g-1">
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Descrição do Material</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none;"
                                value="<?php echo $rowMat['DESCRICAO_MP']?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade da Compra</label>
                        <input style="font-weight:bold; font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="text" class="form-control"
                              value="<?php echo $rowMat['QTDE_LOTE'] . ' ' . $rowMat['UNIDADE_MEDIDA'] ?>" readonly>
                      </div>
                      <div class="col-md-4">
                        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Data Prevista para Recebimento</label>
                        <input style="font-weight:bold; width: 140px; font-size: 14px; text-align: center; background: rgba(0,0,0,0.3)" type="text" class="form-control"
                              value="<?php echo date('m/d/Y', strtotime($rowMat['DATA_COMPRA'])) ?>" readonly>
                      </div>
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none; color: orange;"
                                value="<?php echo $rowMat['SITUACAO_QUALI']?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <br>
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
              $query_pedido = $connDB->prepare("SELECT * FROM pf_pedido");
              $query_pedido->execute(); 
              while($rowPedido = $query_pedido->fetch(PDO::FETCH_ASSOC)){
                $idPed = $rowPedido['ID_PEDIDO'];?>
                <div class="card text-bg-success mb-3" style="width: 35rem;">
                  <div class="card-body">
                    <div class="row g-1">
                      <div class="col-md-8">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Produto</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold;  background: none;"
                                 value="<?php echo $rowPedido['NOME_PRODUTO']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Quantidade</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                                 value="<?php echo $rowPedido['QTDE_LOTE_PF'] . ' ' . $rowPedido['UNIDADE_MEDIDA']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Cliente</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none;"
                                 value="<?php echo $rowPedido['CLIENTE']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Entrega</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"
                                 value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_ENTREGA'])); ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold;  background: none; color:orange"
                                 value="<?php echo $rowPedido['SITUACAO_QUALI']; ?>" readonly>
                        </div>
                      </div><?php
                      if($rowPedido['ETAPA_PROD'] == 6){ ?>
                        <div class="col-md-12">
                          <button class="btn btn-primary" onclick="location.href='#'" style="float: right">Efetuar Saída do Produto</button>
                        </div> <?php 
                      }
                      if($rowPedido['ETAPA_PROD'] < 6){ ?>
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
        <h5 style="color: aqua">Estoque de Materiais e Insumos</h5>
        <div class="container">
          <div class="overflow-auto"><?php
            $listaEstoque = $connDB->prepare("SELECT * FROM mp_estoque ORDER BY DESCRICAO_MP ASC"); $listaEstoque->execute(); ?>
            <table class="table table-dark table-hover">
              <thead style="font-size: 12px">
                <tr>
                  <th scope="col" style="width: 35%">Descrição do Material</th>
                  <th scope="col" style="width: 10%; text-align: center">ID Interno</th>
                  <th scope="col" style="width: 10%; text-align: center">Qtde Lote</th>
                  <th scope="col" style="width: 10%; text-align: center">Qtde em Estoque</th>
                  <th scope="col" style="width: 10%; text-align: center">Qtde Reservada</th>
                  <th scope="col" style="width: 25%">Situação Atual</th>
                </tr>
              </thead>
              <tbody style="height: 80%; font-size: 11px;">
                <?php while($rowEstoque = $listaEstoque->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                  <td style="width: 35%; font-size: 14px"> 
                    <?php echo $rowEstoque['DESCRICAO_MP'] . '<BR>' . ' [ ' . $rowEstoque['NUMERO_LOTE_FORNECEDOR'] . ' ] ' . $rowEstoque['FORNECEDOR']; ?> </td>
                  <td style="width: 10%; font-size: 18px; text-align: center"> 
                    <?php echo $rowEstoque['NUMERO_LOTE_INTERNO']; ?> </td>
                  <td style="width: 10%; font-size: 18px; text-align: center"> 
                    <?php echo $rowEstoque['QTDE_LOTE'] . ' ' . $rowEstoque['UNIDADE_MEDIDA'] ?> </td>
                  <td style="width: 10%; font-size: 18px; text-align: center"> 
                    <?php echo $rowEstoque['QTDE_ESTOQUE'] . ' ' . $rowEstoque['UNIDADE_MEDIDA'] ?> </td>
                  <td style="width: 10%; font-size: 18px; text-align: center"> 
                    <?php echo $rowEstoque['QTDE_RESERVADA'] . ' ' . $rowEstoque['UNIDADE_MEDIDA'] ?> </td>
                  <td style="width: 25%; font-size: 14px"> 
                    <?php echo $rowEstoque['SITUACAO_QUALI']  ?> </td>
                </tr><?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

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
