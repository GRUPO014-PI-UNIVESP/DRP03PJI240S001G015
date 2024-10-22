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
              $materiais = $connDB->prepare("SELECT * FROM materiais_lotes WHERE ETAPA_PROCESS < 2"); // alterado compras -> lotes
              $materiais->execute();
              while($rowMat = $materiais->fetch(PDO::FETCH_ASSOC)){
                $id = $rowMat['ID_ESTOQUE']; ?>
                <div class="card text-bg-success mb-3" style="width: 35rem;">
                  <div class="card-body">
                    <div class="row g-1">
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Descrição do Material</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;"
                                 value="<?php echo $rowMat['DESCRICAO']?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Quantidade da Compra</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;"
                                 value="<?php echo number_format($rowMat['QTDE_LOTE'], 0, ',', '.') . ' ' . $rowMat['UNIDADE']  ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Data Prevista</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;"
                                 value="<?php echo date('d/m/Y', strtotime($rowMat['DATA_PRAZO'])) ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none; color: orange;"
                                 value="<?php echo $rowMat['SITUACAO']?>" readonly>
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
              $query_pedido = $connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 4");
              $query_pedido->execute(); 
              while($rowPedido = $query_pedido->fetch(PDO::FETCH_ASSOC)){
                $idPed = $rowPedido['ID_PEDIDO'];?>
                <div class="card text-bg-success mb-3" style="width: 35rem;">
                  <div class="card-body">
                    <div class="row g-1">
                      <div class="col-md-8">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Produto</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;"
                                 value="<?php echo $rowPedido['PRODUTO']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Qtde</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; text-align: center; background: none;"
                                 value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Cliente</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;"
                                 value="<?php echo $rowPedido['CLIENTE']; ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Entrega</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; text-align: center; background: none;"
                                 value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_ENTREGA'])); ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 11px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                          <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none; color:orange"
                                 value="<?php echo $rowPedido['SITUACAO']; ?>" readonly>
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
        <h5 style="color: aqua">Estoque de Materiais e Insumos</h5>
        <div class="container">
          <div class="overflow-auto"><?php
            $listaEstoque = $connDB->prepare("SELECT * FROM materiais_estoque ORDER BY DESCRICAO ASC"); $listaEstoque->execute(); ?>
            <table class="table table-dark table-hover">
              <thead style="font-size: 12px">
                <tr>
                  <th scope="col" style="width: 30%"                    >Descrição do Material</th>
                  <th scope="col" style="width: 15%; text-align: center">Total em Estoque     </th>
                  <th scope="col" style="width: 15%; text-align: center">ID Interno           </th>
                  <th scope="col" style="width: 15%; text-align: center">Qtde do Lote         </th>
                  <th scope="col" style="width: 25%"                    >Situação Atual       </th>
                </tr>
              </thead>
              <tbody style="height: 80%; font-size: 11px;"><?php
              while($rowEstoque = $listaEstoque->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                  <td style="width: 35%; font-size: 14px"><?php echo $rowEstoque['DESCRICAO'] . '<br>' . $rowEstoque['FORNECEDOR']; ?></td>
                  <td style="width: 10%; font-size: 15px; text-align: center"><?php echo $rowEstoque['QTDE_ESTOQUE'] . ' ' . $rowEstoque['UNIDADE'] ?></td>
                  <?php
                  $listaLotes = $connDB->prepare("SELECT NUMERO_LOTE, ID_INTERNO, QTDE_LOTE, UNIDADE, SITUACAO FROM materiais_lotes WHERE ID_ESTOQUE = :idLote");
                  $listaLotes->bindParam(':idLote', $rowEstoque['ID_ESTOQUE'], PDO::PARAM_INT); $listaLotes->execute();
                  while($rowLotes = $listaLotes->fetch(PDO::FETCH_ASSOC)){ ?>
                    <td style="width: 10%; font-size: 15px; text-align: center"><?php
                      echo $rowLotes['ID_INTERNO'] . '<br>' . '[ ' . $rowLotes['NUMERO_LOTE'] . ' ]'; ?>
                    </td>
                    <td style="width: 10%; font-size: 15px; text-align: center"><?php
                      echo number_format($rowLotes['QTDE_LOTE'], 0, ',', '.') . ' ' . $rowLotes['UNIDADE']; ?>                   
                    </td>
                    <td style="width: 25%; font-size: 12px"><?php
                      if($rowLotes['QTDE_LOTE'] == null){ echo 'LOTE ESGOTADO';}
                      if($rowLotes['QTDE_LOTE'] > 0){ echo $rowLotes['SITUACAO'];} ?>                      
                    </td> <?php 
                  } ?>
                </tr><?php 
              } ?>
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
