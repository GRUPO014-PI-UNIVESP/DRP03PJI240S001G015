<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Qualidade'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time;window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() {
      <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php';?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() {clearTimeout(time); time = setTimeout(deslogar, 600000);}
  }; inactivityTime();
</script>
<!-- Área Principal -->
  <div class="main">
    <div class="container-fluid">
      <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="laboratorio-tab" data-bs-toggle="tab" data-bs-target="#laboratorio-tab-pane" type="button" role="tab" aria-controls="laboratorio-tab-pane" aria-selected="true">Laboratório</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="reagentes-tab" data-bs-toggle="tab" data-bs-target="#reagentes-tab-pane" type="button" role="tab" aria-controls="reagentes-tab-pane" aria-selected="false">Reagentes</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other-tab-pane" type="button" role="tab" aria-controls="other-tab-pane" aria-selected="false">Relatórios</button>
        </li>
        <p style="margin-left: 15%; font-size: 20px; color: whitesmoke">Garantia da Qualidade</p>
      </ul>

      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="laboratorio-tab-pane" role="tabpanel" aria-labelledby="laboratorio-tab" tabindex="0"><br>
          <div class="row g-3">
            <!-- coluna para cards de materiais -->
            <div class="col-md-6">
              <h6>Lista de Materiais para Análise</h6>
              <div class="row g-1"><?php
                $query_materiais = $connDB->prepare("SELECT * FROM materiais_lotes WHERE ETAPA_PROCESS = 2 ");$query_materiais->execute();
                while($rowMat = $query_materiais->fetch(PDO::FETCH_ASSOC)){ $id = $rowMat['ID_INTERNO']; $dataLimite = date('Y-m-d', strtotime($rowMat['DATA_RECEBIMENTO']."+ 2 days"))?>
                  <div class="card text-bg-success mb-2" style="width: 45rem;">
                    <div class="card-body">
                      <div class="row g-1">
                        <div class="col-md-12">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Descrição do Material</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo $rowMat['DESCRICAO']?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">No.Lote</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo $rowMat['ID_INTERNO']?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Qtde</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo number_format($rowMat['QTDE_LOTE'], 0, ',', '.') . ' ' . $rowMat['UNIDADE'] ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Limite</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo date('d/m/Y', strtotime($dataLimite)) ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none; color: orange" value="<?php echo $rowMat['SITUACAO']?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <button class="btn btn-primary" style="float: right" onclick="location.href='./40RegistroAnalise.php?id=<?php echo $id ?>'">Registro da Análise</button>
                        </div>
                      </div><!-- fim da DIV row g1 -->
                    </div><!-- fim da DIV do corpo do cartão -->
                  </div><!-- fim da DIV do cartão --><?php
                } ?>
              </div><!-- fim da DIV row g1 -->
            </div><!-- fim da coluna de cards de materiais -->

            <!-- coluna para cards de produtos -->
            <div class="col-md-6"><h6>Lista de Produtos para Análise</h6>
              <div class="row g-1"><?php
                $pedido = $connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 5"); $pedido->execute();
                while($rowPedido = $pedido->fetch(PDO::FETCH_ASSOC)){ $id = $rowPedido['ID_PEDIDO']; // recursão de cards de pedidos ?>
                  <div class="card text-bg-success mb-2" style="width: 45rem;">
                    <div class="card-body">
                      <div class="row g-1">
                        <div class="col-md-12">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Nome do Produto</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo $rowPedido['PRODUTO']; ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">No.Lote</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo $rowPedido['NUMERO_LOTE']; ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Qtde</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Limite</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none;" value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_ENTREGA']."- 2 days")) ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="input-group mb-2"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 13px; background: none; color: orange" value="<?php echo $rowPedido['SITUACAO']; ?>" readonly>
                          </div>
                        </div><?php
                        if($rowPedido['ETAPA_PROCESS'] < 4){ ?>
                          <div class="col-md-12">
                            <button class="btn btn-secondary" style="float: right" disabled>Registro da Análise</button>
                          </div> <?php                                                  
                        }
                        if($rowPedido['ETAPA_PROCESS'] == 4){ ?>
                          <div class="col-md-12">
                            <button class="btn btn-primary" style="float: right" onclick="location.href='./42RegistroAnalise.php?id=<?php echo $id ?>'">Registro da Análise</button>
                          </div> <?php                                                  
                        } ?>
                      </div><!-- fim da DIV row g1 -->
                    </div><!-- fim da DIV do corpo do cartão -->
                  </div><!-- fim da DIV do cartão --><?php
                } ?>
              </div><!-- fim da DIV row g1 -->
            </div><!-- fim da coluna de cards de materiais -->  
          </div><!-- fim da DIV row g3 -->  
        </div><!-- fim tab -->         
        <div class="tab-pane fade" id="reagentes-tab-pane" role="tabpanel" aria-labelledby="reagentes-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:350px" onclick="location.href='./24ReabastecimentoEReagentes.php'">Reabastecimento de Estoque de Reagentes</button><br><br>
          <button type="button" class="btn btn-outline-info" style="width:350px" onclick="location.href='./25TabelaGEstoqueReagentes.php'">Tabela Geral do Estoque de Reagentes</button><br><br>       
        </div>
        <div class="tab-pane fade" id="other-tab-pane" role="tabpanel" aria-labelledby="other-tab" tabindex="0" style="color: whitesmoke"><br><br>
          <a href="" class="font-family: aria-current=">
        </div>
      </div> 
    </div>
  </div>
