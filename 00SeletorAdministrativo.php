<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once 'ConnectDB.php';
  include_once 'EstruturaPrincipal.php';
?>
    <!-- Área Principal -->
    <div class="main">
      <div class="container-fluid">

        <p style="margin-left: 2%; font-size: 25px; color: whitesmoke">Departamento Administrativo</p>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="rh-tab" data-bs-toggle="tab" data-bs-target="#rh-tab-pane" type="button" 
              role="tab" aria-controls="rh-tab-pane" aria-selected="true">Recursos Humanos</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="prod-tab" data-bs-toggle="tab" data-bs-target="#prod-tab-pane" type="button" 
              role="tab" aria-controls="prod-tab-pane" aria-selected="false">Fábrica</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="comercial-tab" data-bs-toggle="tab" data-bs-target="#comercial-tab-pane" type="button" 
              role="tab" aria-controls="comercial-tab-pane" aria-selected="false">Comercial</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance-tab-pane" type="button" 
              role="tab" aria-controls="finance-tab-pane" aria-selected="false">Financeiro</button>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">
        <?php
          // faz verificação de credencial para acesso de subsistemas 
          if($_SESSION['credencial'] >= 4){$acesso5 = '06QuadroFuncionarios.php';$acesso7 = '11CadastroFuncionario.php';}else{$acesso5 = '';$acesso7 = '';}
          if($_SESSION['credencial'] >= 2){$acesso6 = '05MonitorLogin.php';      }else{$acesso6 = '';}
        ?>
          <div class="tab-pane fade show active" id="rh-tab-pane" role="tabpanel" aria-labelledby="rh-tab" tabindex="0"><br><br>
            <button type="button" class="btn btn-outline-info" style="width:300px" 
              onclick="location.href='<?php echo $acesso5 ?>'">Quadro de Funcionários</button><br><br>

            <button type="button" class="btn btn-outline-info" style="width:300px" 
              onclick="location.href='<?php echo $acesso6 ?>' ">Monitor do Histórico de Login</button><br><br>
              
            <button type="button" class="btn btn-outline-info" style="width:300px" 
              onclick="location.href=''">Estrutura da Organização</button><br><br>
          </div>
          
          <div class="tab-pane fade" id="prod-tab-pane" role="tabpanel" aria-labelledby="prod-tab" tabindex="0"><br><br>
            <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Ordem de Produção</button><br><br>          
          </div>

          <div class="tab-pane fade" id="comercial-tab-pane" role="tabpanel" aria-labelledby="comercial-tab" tabindex="0"><br><br>
            <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Vendas</button><br><br>
          </div>

          <div class="tab-pane fade" id="finance-tab-pane" role="tabpanel" aria-labelledby="finance-tab" tabindex="0" style="color: whitesmoke"><br><br>
            <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Folha de Pagamento</button><br><br>
          </div>

        </div> 
      </div>
    </div>
