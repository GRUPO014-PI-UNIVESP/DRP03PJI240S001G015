<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once 'ConnectDB.php';
  include_once 'EstruturaPrincipal.php';

  // pesquisa de MP para serem analisadas
  $query_MP = $connDB->prepare("SELECT ID_ESTOQUE_MP, FORNECEDOR, DESCRICAO_MP FROM mp_estoque WHERE SITUACAO_QUALI = 'AGUARDANDO'");
  $query_MP->execute();

  //$query_PF = $connDB->prepare("SELECT ");
?>
<!-- Área Principal -->
  <div class="main">
    <div class="container-fluid">
      <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" 
            role="tab" aria-controls="manage-tab-pane" aria-selected="true">Laboratório</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="lab-tab" data-bs-toggle="tab" data-bs-target="#lab-tab-pane" type="button" 
            role="tab" aria-controls="lab-tab-pane" aria-selected="false">Estoque</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other-tab-pane" type="button" 
            role="tab" aria-controls="other-tab-pane" aria-selected="false">Outros</button>
        </li>
        <p style="margin-left: 15%; font-size: 20px; color: whitesmoke">Garantia da Qualidade</p>
      </ul>

      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0"><br><br>
          <?php while($rowMP = $query_MP->fetch(PDO::FETCH_ASSOC)){ $idmp = $rowMP['ID_ESTOQUE_MP'];?>
            <div class="row">
              <!-- Cards de Análises de MP -->
              <div class="col-sm-6 mb-3 mb-sm-0">
              <h6>Análise de Matéria Prima</h6>
                <div class="card border-primary">
                  <div class="card-body">
                  <h6 class="card-title" style="color: yellow; font-size: 12px">Amostra: <?php echo $rowMP['DESCRICAO_MP'] . ' [ ' . $rowMP['FORNECEDOR'] . ' ]' ?></h6>
                    <p class="card-text" style="font-size: 12px">Prazo para conclusão: </p>
                    <p class="card-text" style="font-size: 12px">linha 2</p>
                    <a href="<?php echo $acesso12 ?>?id=<?php echo $idmp ?>" class="btn btn-primary" style="float: right">
                      <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                      </svg>
                    </a>
                  </div>
                </div><br>
              </div>
              <!-- Cards de Análises de PF -->
              <div class="col-sm-6">
              <h6>Análise de Produto Final</h6>
                <div class="card border-primary">
                  <div class="card-body">
                  <h6 class="card-title" style="color: yellow; font-size: 12px">Amostra: Descrição do Produto Final analisado</h6>
                    <p class="card-text" style="font-size: 12px">Prazo para conclusão: </p>
                    <p class="card-text" style="font-size: 12px">linha 2</p>
                    <a href="#" class="btn btn-primary" style="float: right">
                      <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
        </div>         
        <div class="tab-pane fade" id="lab-tab-pane" role="tabpanel" aria-labelledby="lab-tab" tabindex="0"><br><br>
          <button type="button" class="btn btn-outline-info" style="width:350px" onclick="">Reabastecimento de Estoque de Reagentes</button><br><br>
          <button type="button" class="btn btn-outline-info" style="width:350px" onclick="">Tabela Geral do Estoque de Reagentes</button><br><br>       
        </div>
        <div class="tab-pane fade" id="other-tab-pane" role="tabpanel" aria-labelledby="other-tab" tabindex="0" style="color: whitesmoke"><br><br>
          <a href="" class="font-family: aria-current=">
        </div>
      </div> 
    </div>
  </div>
