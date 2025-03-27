<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Setor de Vendas'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() {
      <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>
      window.location.href = 'LogOut.php';
    }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000); }
  }; inactivityTime();
</script>
<!-- Área Principal -->
  <div class="main">
    <div class="container-fluid">
      <br>
      <p style="font-size: 20px; color: whitesmoke">Departamento Administrativo - Setor de Vendas</p>
      <!-- Menu do Setor de Vendas -->
      <div class="row g-3">
        <div class="col-md-3"><br><br>
          <button type="button" class="btn btn-outline-light" style="width:250px" onclick="location.href='<?php echo $acesso11 ?>'">Pedido de Produto</button><br><br>
          <button type="button" class="btn btn-outline-light" style="width:250px" onclick="location.href='<?php echo $acesso13 ?>'">Cadastro de Novo Cliente</button><br><br>  
          <button type="button" class="btn btn-outline-light" style="width:250px" onclick="location.href='<?php echo $acesso14 ?>'">Cadastro de Novo Produto</button><br><br>
          <button type="button" class="btn btn-outline-light" style="width:250px" onclick="location.href='<?php echo $acesso17 ?>'">Relatório de Vendas</button><br><br>
        </div>
        <div class="col-md-9">
          <h5 style="text-align: center; color:aqua">Fila dos Pedidos Efetivados</h5><?php
          $produtos = $connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 4"); $produtos->execute();
          while($rowPedido = $produtos->fetch(PDO::FETCH_ASSOC)){
            if(!empty($rowPedido['NUMERO_PEDIDO'])){ ?>
              <div class="card text-bg-success mb-3" style="width: 50rem;">
                <div class="card-body">
                  <div class="row g-2">
                    <div class="col-md-3">
                      <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Pedido No.</span>
                        <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none;" value="<?php echo $rowPedido['NUMERO_PEDIDO']?>" readonly>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Produto</span>
                        <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none" value="<?php echo $rowPedido['PRODUTO'] ?>" readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Quantidade</span>
                        <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: right; background: none" value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 1, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Cliente</span>
                        <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; background: none" value="<?php echo $rowPedido['CLIENTE'] ?>" readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Uso da Planta</span>
                        <?php 
                          if($rowPedido['CAPAC_PROCESS'] != 0 || $rowPedido['CAPAC_PROCESS'] != null){
                            $tempo = $rowPedido['QTDE_PEDIDO'] / $rowPedido['CAPAC_PROCESS']; $id = $rowPedido['NUMERO_PEDIDO'];?>
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none" value="<?php echo round($tempo) . ' horas'?>" readonly><?php
                          } else { $tempo = 0; ?> 
                            <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none" value="0" readonly>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Fabricação</span>
                        <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none" value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_AGENDA'])) ?>" readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group mb-3"><span class="input-group-text" id="basic-addon1" style="font-size: 12px; background: rgba(0,0,0,0.3); color: aqua">Entrega</span>
                        <input type="text" class="form-control" aria-label="" aria-describedby="basic-addon1" style="font-weight:bold; font-size: 14px; text-align: center; background: none" value="<?php echo date('d/m/Y',strtotime($rowPedido['DATA_ENTREGA'])) ?>" readonly>
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
    </div><!-- fim da div container --> 
  </div><!-- fim da div main -->
