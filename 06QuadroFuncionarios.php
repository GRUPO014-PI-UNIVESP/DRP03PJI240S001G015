<?php
// inclusão do banco de dados e estrutura base da página web
include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

//Pesquisa de registros do banco de dados ordenado pelo nome do funcionário em ordem alfabética
$query_nome = $connDB->prepare("SELECT * FROM quadro_funcionarios ORDER BY NOME_FUNCIONARIO ASC");
$query_nome->execute();

//Pesquisa de registros do banco de dados ordenado pelo departamento
$query_depto = $connDB->prepare("SELECT * FROM quadro_funcionarios ORDER BY DEPARTAMENTO ASC");
$query_depto->execute();
?>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" 
          role="tab" aria-controls="manage-tab-pane" aria-selected="true" style="font-size: 13px">Ordenado: por Nome</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="lab-tab" data-bs-toggle="tab" data-bs-target="#lab-tab-pane" type="button" 
          role="tab" aria-controls="lab-tab-pane" aria-selected="false" style="font-size: 13px">por Departamento</button>
      </li>
      <button class="btn btn-sm btn-primary" style="margin-left: 30%; width:220px; height: 32px" onclick="location.href='<?php echo $acesso7 ?>'">Cadastrar Novo Funcionario</button><br><br>
    </ul>
    <div class="tab-content" id="myTabContent"><br>
      <!-- Visualização por Nome do Funcionário -->
        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0">
          <form class="row g-4" method="POST" action="#">
            <div class="overflow-auto">
              <table class="table table-dark table-hover">
                <thead style="font-size: 12px">
                  <tr>
                    <th scope="col" style="width: 10%">Registro No.</th>
                    <th scope="col" style="width: 35%">Nome do Funcionario</th>
                    <th scope="col" style="width: 20%">Departamento/Cargo</th>
                    <th scope="col" style="width: 10%">Admissão</th>
                    <th scope="col" style="width: 15%">Ações</th>
                  </tr>
                </thead>
                <tbody style="height: 75%; font-size: 10px;">
                  <?php while($nomeLista = $query_nome->fetch(PDO::FETCH_ASSOC)){ $id = $nomeLista['ID_FUNCIONARIO']; $_SESSION['id_edit'] = $id;?>
                  <tr>
                    <th style="width: 10%; text-align: center"> <?php echo $nomeLista['ID_FUNCIONARIO']; ?> </th>
                    <td style="width: 35%"                    > <?php echo $nomeLista['NOME_FUNCIONARIO'] . '<br>'; echo 'Tel: ' . $nomeLista['TELEFONE'] . ' ' .'[ ' . $nomeLista['CIDADE'] . ' ]'; ?> </td>
                    <td style="width: 20%"                    > <?php echo $nomeLista['DEPARTAMENTO'] . '<br>'; echo '[ ' . $nomeLista['CARGO'] . ' ]' ?> </td>
                    <td style="width: 10%"                    > <?php if(!empty($nomeLista['DATA_ADMISSAO'])){$dO = $nomeLista['DATA_ADMISSAO']; $DO = strtotime($dO); echo date('d/m/Y', $DO);}else{ echo 'Logado';} ?> </td>
                    <td style="width: 15%">
                      <a class="btn btn-sm btn-info" href="<?php echo $acesso8 ?>?id=<?php echo $id ?>">
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                          <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z'/>
                        </svg></a>
                      <!-- Ativador do Modal -->
                      <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                          <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
                        </svg>
                      </button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Deletar Registro</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <p style="font-size: 15px; color: pink">Confirme a deleção do registro de <?php echo '[ ' . $nomeLista['NOME_FUNCIONARIO'] . ' ]' ?> ?</p>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="location.href='<?php echo $acesso9 ?>?id=<?php echo $id ?>'">Confirmar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                    </td>            
                  </tr><?php } ?>
                </tbody>
              </table>
            </div>
          </form>
        </div>
      <!-- Visualização por Departamento -->  
        <div class="tab-pane fade" id="lab-tab-pane" role="tabpanel" aria-labelledby="lab-tab" tabindex="0">
          <form class="row g-4" method="POST" action="#">
            <div class="overflow-auto">
              <table class="table table-dark table-hover">
                <thead style="font-size: 12px">
                  <tr>
                    <th scope="col" style="width: 10%">Registro No.</th>
                    <th scope="col" style="width: 20%">Departamento/Cargo</th>
                    <th scope="col" style="width: 35%">Nome do Funcionario</th>
                    <th scope="col" style="width: 10%">Admissão</th>
                    <th scope="col" style="width: 15%">Ações</th>
                  </tr>
                </thead>
                <tbody style="height: 75%; font-size: 10px;">
                <?php while($deptoLista = $query_depto->fetch(PDO::FETCH_ASSOC)){ $id = $deptoLista['ID_FUNCIONARIO']; $_SESSION['id_edit'] = $id;?>
                  <tr>
                    <th style="width: 10%; text-align: center"> <?php echo $deptoLista['ID_FUNCIONARIO']; ?> </th>
                    <td style="width: 20%"                    > <?php echo $deptoLista['DEPARTAMENTO'] . '<br>'; echo '[ ' . $deptoLista['CARGO'] . ' ]' ?> </td>
                    <td style="width: 35%"                    > <?php echo $deptoLista['NOME_FUNCIONARIO'] . '<br>'; echo 'Tel: ' . $deptoLista['TELEFONE'] . ' ' .'[ ' . $deptoLista['CIDADE'] . ' ]'; ?> </td>
                    <td style="width: 10%"                    > <?php if(!empty($deptoLista['DATA_ADMISSAO'])){$dO = $deptoLista['DATA_ADMISSAO']; $DO = strtotime($dO); echo date('d/m/Y', $DO);}else{ echo 'Logado';} ?> </td>
                    <td style="width: 15%">
                      <a class="btn btn-sm btn-info" href="<?php echo $acesso8 ?>?id=<?php echo $id ?>">
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                          <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z'/>
                        </svg></a>
                      <!-- Ativador do Modal -->
                      <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                          <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
                        </svg>
                      </button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Deletar Registro</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <p style="font-size: 15px; color: pink">Confirme a deleção do registro de <?php echo '[ ' . $deptoLista['NOME_FUNCIONARIO'] . ' ]' ?> ?</p>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="location.href='<?php echo $acesso9 ?>?id=<?php echo $id ?>'">Confirmar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                    </td>            
                  </tr><?php } ?>
                </tbody>
              </table>
            </div>             
          </form>
        </div>
    </div>
  </div>
</div>