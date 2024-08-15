<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once 'ConnectDB.php';
  include_once 'EstruturaPrincipal.php';
?>
<!-- Área Principal -->
  <div class="main">
    <div class="container-fluid">

      <p style="margin-left: 2%; font-size: 25px; color: whitesmoke">Garantia da Qualidade</p>

      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" 
            role="tab" aria-controls="manage-tab-pane" aria-selected="true">Gerente</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="lab-tab" data-bs-toggle="tab" data-bs-target="#lab-tab-pane" type="button" 
            role="tab" aria-controls="lab-tab-pane" aria-selected="false">Laboratório</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="warehouse-tab" data-bs-toggle="tab" data-bs-target="#warehouse-tab-pane" type="button" 
            role="tab" aria-controls="warehouse-tab-pane" aria-selected="false">Estoque</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other-tab-pane" type="button" 
            role="tab" aria-controls="other-tab-pane" aria-selected="false">Outros</button>
        </li>
      </ul>

      <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Ordem para Serviço de Análise</button><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Monitor dos Serviços de Laboratório</button><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Cadastro de Novas Amostras</button><br><br>
        </div>
          
        <div class="tab-pane fade" id="lab-tab-pane" role="tabpanel" aria-labelledby="lab-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Registro de Análise Concluída</button><br><br>
          <button type="button" class="btn btn-outline-info" style="width:300px" onclick="">Cronograma de Execução de Análises</button><br><br>       
        </div>

        <div class="tab-pane fade" id="warehouse-tab-pane" role="tabpanel" aria-labelledby="warehouse-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:400px" onclick="">Reabastecimento de Estoque de Reagentes</button><br><br>
          <button type="button" class="btn btn-outline-info" style="width:400px" onclick="">Tabela Geral do Estoque de Reagentes</button><br><br>
        </div>

        <div class="tab-pane fade" id="other-tab-pane" role="tabpanel" aria-labelledby="other-tab" tabindex="0" style="color: whitesmoke"><br><br>
          <a href="" class="font-family: aria-current=">

        </div>
      </div> 
    </div>
  </div>
