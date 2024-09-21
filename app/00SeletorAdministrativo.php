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
          <div class="tab-pane fade" id="comercial-tab-pane" role="tabpanel" aria-labelledby="prod-tab" tabindex="0"><br><br>
            <button type="button" class="btn btn-outline-success" style="width:300px" 
                    onclick="location.href='<?php echo $acesso11 ?>'">Pedido de Produto</button><br><br>
            <button type="button" class="btn btn-outline-success" style="width:300px" 
                    onclick="location.href='<?php echo $acesso13 ?>' ">Cadastro de Novo Cliente</button><br><br>             
            <button type="button" class="btn btn-outline-success" style="width:300px" 
                    onclick="location.href='<?php echo $acesso14 ?>'">Cadastro de Novo Produto</button><br><br>
          </div><!-- fim da div id = prod-tab-pane -->

          <!-- Menu do Setor de Compras -->
          <div class="tab-pane fade" id="compras-tab-pane" role="tabpanel" aria-labelledby="comercial-tab" tabindex="0"><br><br>
            <button type="button" class="btn btn-outline-warning" style="width:300px" 
                    onclick="location.href='<?php echo $acesso12 ?>'">Compra de Material</button><br><br>
            <button type="button" class="btn btn-outline-warning" style="width:300px" 
                    onclick="location.href='<?php echo $acesso15 ?>'">Cadastro de Novo Material</button><br><br>
          </div><!-- fim da div id = comercial-tab-pane -->

          <!-- Menu do Financeiro -->
          <div class="tab-pane fade" id="finance-tab-pane" role="tabpanel" aria-labelledby="finance-tab" tabindex="0" style="color: whitesmoke"><br><br>
            <button type="button" class="btn btn-outline-secondary" style="width:300px" 
                    onclick="location.href='<?php ?>'">Folha de Pagamento</button><br><br>
          </div><!-- fim da div id = finance-tab-pane -->
      </div> 
    </div>
