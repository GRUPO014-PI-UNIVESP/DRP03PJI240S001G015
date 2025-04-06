<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Produção'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time;window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() {
      <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php';?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 6000000);}
  }; inactivityTime();
</script>
  <!-- Área Principal -->
  <div class="main">
    <div class="container-fluid">
      <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" role="tab" aria-controls="manage-tab-pane" aria-selected="true">Gerente</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="plant-tab" data-bs-toggle="tab" data-bs-target="#plant-tab-pane" type="button" role="tab" aria-controls="plant-tab-pane" aria-selected="false">Planta</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="manutention-tab" data-bs-toggle="tab" data-bs-target="#manutention-tab-pane" type="button" role="tab" aria-controls="manutention-tab-pane" aria-selected="false">Manutenção</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other-tab-pane" type="button" role="tab" aria-controls="other-tab-pane" aria-selected="false">Outros</button>
        </li>
        <p style="margin-left: 15%; font-size: 20px; color: whitesmoke">Departamento de Produção</p>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0"><br><br>
          <div class="row g-3">
            <div class="col-md-3"><br>
              <button type="button" class="btn btn-outline-danger" style="width:250px" onclick="">Relatório de Produção</button><br><br>  
            </div>
            <div class="col-md-9"><?php
              $listaPedido = $connDB->prepare("SELECT * FROM pedidos"); $listaPedido->execute();
              while($rowPedido = $listaPedido->fetch(PDO::FETCH_ASSOC)){
                if(!empty($rowPedido['NUMERO_PEDIDO'])){ ?>
                  <div class="card text-bg-success mb-3" style="width: 50rem;">
                    <div class="card-body">
                      <div class="row g-2">
                        <div class="col-md-6">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Produto</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none"value="<?php echo $rowPedido['PRODUTO'] ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Cliente</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none"value="<?php echo $rowPedido['CLIENTE'] ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Pedido No.</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;"value="<?php echo $rowPedido['NUMERO_PEDIDO']?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Quantidade</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: right; background: none"value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Uso da Planta</span><?php 
                            if($rowPedido['CAPAC_PROCESS'] != 0 || $rowPedido['CAPAC_PROCESS'] != null){
                              $tempo = $rowPedido['QTDE_PEDIDO'] / $rowPedido['CAPAC_PROCESS']; $id = $rowPedido['NUMERO_PEDIDO'];?>
                              <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"value="<?php echo round($tempo) . ' horas'?>" readonly><?php
                              } else { $tempo = 0; ?> 
                              <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"value="0" readonly><?php 
                            } ?>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Entrega</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"value="<?php echo date('d/m/Y',strtotime($rowPedido['DATA_ENTREGA'])) ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-12"><h6 style="color:aqua">Condição dos Materiais Ingredientes</h6>
                          <div class="row g-0"><?php
                            $query_material = $connDB->prepare("SELECT * FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
                            $query_material->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT); $query_material->execute(); $nTipos = $query_material->rowCount();
                            while($rowMat = $query_material->fetch(PDO::FETCH_ASSOC)){ $qtdeReserva = $rowMat['QTDE_RESERVA'];
                              $query_lotes = $connDB->prepare("SELECT DESCRICAO, ID_INTERNO, QTDE_LOTE, UNIDADE, ETAPA_PROCESS FROM materiais_lotes WHERE ID_ESTOQUE = :idEstoque AND ETAPA_PROCESS < 4");
                              $query_lotes->bindParam(':idEstoque', $rowMat['ID_ESTOQUE'], PDO::PARAM_INT); $query_lotes->execute();
                              while($rowLote = $query_lotes->fetch(PDO::FETCH_ASSOC)){ ?>
                                <div class="col-md-5"><?php echo $rowLote['DESCRICAO'] ?></div>
                                <div class="col-md-2"><?php echo $rowLote['ID_INTERNO'] ?></div>
                                <div class="col-md-2"><?php echo number_format($rowLote['QTDE_LOTE'], 1, ',', '.') . ' ' . $rowLote['UNIDADE'] ?></div>
                                <div class="col-md-3"><?php
                                  if($rowLote['ETAPA_PROCESS'] == 3){?><p style="text-align:center; background:green; color:yellow; font-weight: bold"><?php echo 'LIBERADO!'    ; ?></p><?php }
                                  if($rowLote['ETAPA_PROCESS'] <  3){?><p style="text-align:center; background:red  ; color:yellow; font-weight: bold"><?php echo 'NÃO LIBERADO!'; ?></p><?php } ?>
                                </div><?php
                              }
                            } ?>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none; color: orange"value="<?php echo $rowPedido['SITUACAO']; ?>" readonly>
                          </div>
                        </div>
                        <div class="col-md-3"><?php
                          $verifica = $nTipos * 3;
                          $verificaDisp = $connDB->prepare("SELECT SUM(DISPONIBILIDADE) AS TOTAL FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
                          $verificaDisp->bindParam(':numPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
                          $verificaDisp->execute(); $libera = $verificaDisp->fetch(PDO::FETCH_ASSOC);
                          if($libera['TOTAL'] != $verifica){ ?><button class="btn btn-secondary" style="font-size: 14px; float: right" onclick="" disabled>Registro da Fabricação</button><?php }
                          if($libera['TOTAL'] == $verifica){ ?><button class="btn btn-primary"   style="font-size: 14px; float: right" onclick="location.href='./37ProcessaPedido.php?id=<?php echo $id ?>'">Registro da Fabricação</button><?php } ?>
                        </div>
                      </div><!-- fim da DIV row g2 -->
                    </div><!-- fim da DIV do corpo do cartão -->
                  </div><!-- fim da DIV do cartão --><?php
                }
              } ?><!-- fim da recursão -->
            </div><!-- fim da div coluna direita para cartões -->
          </div><!-- fim da div row g3 -->

        </div><!-- fim da tab manager -->          
        <div class="tab-pane fade" id="plant-tab-pane" role="tabpanel" aria-labelledby="plant-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Calendário de Ocupação</button><br><br>   
        </div><!-- fim da tab planta -->
        <div class="tab-pane fade" id="manutention-tab-pane" role="tabpanel" aria-labelledby="manutention-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Cronograma de Manutenção</button><br><br>
        </div><!-- fim da tab manutenção -->
        <div class="tab-pane fade" id="other-tab-pane" role="tabpanel" aria-labelledby="other-tab" tabindex="0" style="color: whitesmoke"><br><br></div>
      </div><!-- fim da tab content -->
    </div><!-- fim da container fluid -->
  </div><!-- fim da main -->