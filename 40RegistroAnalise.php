<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once 'ConnectDB.php';
  include_once 'EstruturaPrincipal.php';
?>
<!-- Área Principal -->
<div class="main">
    <div class="container-fluid">
      <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
      <!-- Etiqueta das Abas -->
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="entrada-tab" data-bs-toggle="tab" data-bs-target="#entrada-tab-pane" type="button" role="tab" aria-controls="entrada-tab-pane" aria-selected="true">Entrada de Dados</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="ET-tab" data-bs-toggle="tab" data-bs-target="#ET-tab-pane" type="button" 
            role="tab" aria-controls="ET-tab-pane" aria-selected="false">E.T.</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="MA-tab" data-bs-toggle="tab" data-bs-target="#MA-tab-pane" type="button" 
            role="tab" aria-controls="MA-tab-pane" aria-selected="false">M.A.</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="PQ-tab" data-bs-toggle="tab" data-bs-target="#PQ-tab-pane" type="button" 
            role="tab" aria-controls="PQ-tab-pane" aria-selected="false">P.Q.</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="RE-tab" data-bs-toggle="tab" data-bs-target="#RE-tab-pane" type="button" 
            role="tab" aria-controls="RE-tab-pane" aria-selected="false">R.E.</button>
        </li>        
      </ul>
      <!-- Conteúdos das Abas -->
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="entrada-tab-pane" role="tabpanel" aria-labelledby="entrada-tab" tabindex="0">
          <p>Espaço para entrada de dados</p>
        </div>
        <div class="tab-pane fade" id="ET-tab-pane" role="tabpanel" aria-labelledby="ET-tab" tabindex="0">
          <p>Espaço para documentação de especificação técnica</p>
        </div>
        <div class="tab-pane fade" id="MA-tab-pane" role="tabpanel" aria-labelledby="MA-tab" tabindex="0">
          <p>Espaço para documentação MA</p>
        </div>
        <div class="tab-pane fade" id="PQ-tab-pane" role="tabpanel" aria-labelledby="PQ-tab" tabindex="0">
          <p>Espaço para documentação PQ</p>
        </div>
        <div class="tab-pane fade" id="RE-tab-pane" role="tabpanel" aria-labelledby="RE-tab" tabindex="0">
          <p>Espaço para documentação RE</p>
        </div>
      </div>      
    </div>
  </div>