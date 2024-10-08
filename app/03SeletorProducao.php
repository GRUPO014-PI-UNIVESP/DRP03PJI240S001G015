<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Produção';
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
          <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" 
            role="tab" aria-controls="manage-tab-pane" aria-selected="true">Gerente</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="plant-tab" data-bs-toggle="tab" data-bs-target="#plant-tab-pane" type="button" 
            role="tab" aria-controls="plant-tab-pane" aria-selected="false">Plantas</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="manutention-tab" data-bs-toggle="tab" data-bs-target="#manutention-tab-pane" type="button" 
            role="tab" aria-controls="manutention-tab-pane" aria-selected="false">Manutenção</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other-tab-pane" type="button" 
            role="tab" aria-controls="other-tab-pane" aria-selected="false">Outros</button>
        </li>
        <p style="margin-left: 15%; font-size: 20px; color: whitesmoke">Departamento de Produção</p>
      </ul>

      <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0"><br><br>
          <div class="row g-3">
            <div class="col-md-3"><?php
            // verifica se materiais estão disponíveis para iniciar fabricação
            $pedido = $connDB->prepare("SELECT ETAPA_PROD, NUMERO_PEDIDO, NOME_PRODUTO, QTDE_LOTE_PF 
                                               FROM pf_pedido"); $pedido->execute();
            while($rowPedido = $pedido->fetch(PDO::FETCH_ASSOC)){
              $componentes = $connDB->prepare("SELECT * FROM pf_componentes WHERE NUMERO_PEDIDO = :nPedido");
              $componentes->bindParam(':nPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT); $componentes->execute();
              $nComp = $componentes->rowCount(); echo 'Total de componentes: ' . $nComp . '<br>'; $lib = 0;
              while($comp = $componentes->fetch(PDO::FETCH_ASSOC)){
                $estoque = $connDB->prepare("SELECT ETAPA_PROD, DESCRICAO_MP, QTDE_ESTOQUE, SUM(QTDE_ESTOQUE) AS TOTAL_ESTOQUE, QTDE_RESERVADA, NUMERO_LOTE_INTERNO 
                                                    FROM mp_estoque 
                                                    WHERE DESCRICAO_MP = :descrMat AND ETAPA_PROD = 3 ORDER BY QTDE_ESTOQUE DESC");
                $estoque->bindParam(':descrMat', $comp['DESCRICAO_MP'], pdo::PARAM_STR);
                $estoque->execute(); $rowEstoque = $estoque->fetch(PDO::FETCH_ASSOC);
                
                if(($rowEstoque['TOTAL_ESTOQUE'] >= $comp['QTDE_USO']) && $rowPedido['ETAPA_PROD'] == 0){
                  $lib = $lib + 1; echo $rowEstoque['DESCRICAO_MP'] . ' LIBERADO <BR>';
                  $novoEstoque = $rowEstoque['QTDE_ESTOQUE']   - $comp['QTDE_USO'];
                  $reserva     = $rowEstoque['QTDE_RESERVADA'] + $comp['QTDE_USO'];     // DESMEMBRAR ESTOQUE EM DUAS TABELAS, UMA COM TODOS OS MATERIAIS E
                                                                                        // OUTRO COM OS LOTES INDIVIDUAIS DE CADA MATERIAL
                  $ajusta = $connDB->prepare("UPDATE mp_estoque 
                                                     SET QTDE_ESTOQUE = :ajuste, QTDE_RESERVADA = :reserva 
                                                     WHERE DESCRICAO_MP = :descrMat");
                  $ajusta->bindParam(':descrMat', $comp['DESCRICAO_MP'], PDO::PARAM_STR);
                  $ajusta->bindParam(':ajuste'  , $novoEstoque         , PDO::PARAM_STR);
                  $ajusta->bindParam(':reserva' , $reserva             , PDO::PARAM_STR);
                  $ajusta->execute();
                }
                if(($rowEstoque['QTDE_ESTOQUE'] < $comp['QTDE_USO']) || $rowEstoque['ETAPA_PROD'] != 3){
                  echo $rowEstoque['DESCRICAO_MP'] . ' não LIBERADO <BR>';
                }
              }             
              if($nComp == $lib){
                $situacao = 'MATERIAIS LIBERADOS E DISPONÍVEIS PARA FABRICAÇÃO';
                $etapa = 1; echo 'material liberado <br>';
                $atualiza = $connDB->prepare("UPDATE pf_pedido SET ETAPA_PROD = :etapa, SITUACAO_QUALI = :situacao WHERE NOME_PRODUTO = :nomeProd");
                $atualiza->bindParam(':etapa'   , $etapa   , PDO::PARAM_INT);
                $atualiza->bindParam(':situacao', $situacao, PDO::PARAM_STR);
                $atualiza->bindParam(':nomeProd', $rowPedido['NOME_PRODUTO'], PDO::PARAM_STR);
                $atualiza->execute();
              }
            }?>
            </div><!-- fim da div coluna esquerda para botões -->
            <div class="col-md-9">
              <?php
                $listaPedido = $connDB->prepare("SELECT * FROM pf_pedido WHERE ETAPA_PROD < 2");
                $listaPedido->execute();
                while($rowPedido = $listaPedido->fetch(PDO::FETCH_ASSOC)){
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
                              <?php 
                              if($rowPedido['CAPACIDADE_PROCESS'] != 0 || $rowPedido['CAPACIDADE_PROCESS'] != null){
                                $tempo = $rowPedido['QTDE_LOTE_PF'] / $rowPedido['CAPACIDADE_PROCESS']; $id = $rowPedido['NUMERO_PEDIDO'];?>
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
                                     value="<?php echo date('d/m/Y',strtotime($rowPedido['DATA_FABRI'])) ?>" readonly>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Entrega</span>
                              <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none"
                                     value="<?php echo date('d/m/Y',strtotime($rowPedido['DATA_ENTREGA'])) ?>" readonly>
                            </div>
                          </div>
                          <div class="col-md-3"><?php
                            if($rowPedido['ETAPA_PROD'] != 1){ ?>
                              <button class="btn btn-secondary" style="font-size: 14px; float: right" onclick="" disabled>Registro da Fabricação</button> <?php 
                            }
                            if($rowPedido['ETAPA_PROD'] == 1){ ?>
                              <button class="btn btn-primary" style="font-size: 14px; float: right" onclick="location.href='./37ProcessaPedido.php?id=<?php echo $id ?>'">Registro da Fabricação</button><?php
                            } ?>
                          </div>
                          <div class="col-md-12">
                            <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Situação</span>
                              <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none; color: orange"
                                     value="<?php echo $rowPedido['SITUACAO_QUALI']?>" readonly>
                            </div>
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
          <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Ocorrências</button><br><br>   
        </div><!-- fim da tab planta -->

        <div class="tab-pane fade" id="manutention-tab-pane" role="tabpanel" aria-labelledby="manutention-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:400px" onclick="">Cronograma de Manutenção</button><br><br>
        </div><!-- fim da tab manutenção -->

        <div class="tab-pane fade" id="other-tab-pane" role="tabpanel" aria-labelledby="other-tab" tabindex="0" style="color: whitesmoke"><br><br></div>
      </div><!-- fim da tab content -->
    </div><!-- fim da container fluid -->
  </div><!-- fim da main -->
