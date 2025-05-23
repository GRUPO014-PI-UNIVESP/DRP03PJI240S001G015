<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Quadro de Funcionário'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

//Pesquisa de registros do banco de dados ordenado pelo nome do funcionário em ordem alfabética
$query_nome = $connDB->prepare("SELECT * FROM quadro_funcionarios ORDER BY NOME_FUNCIONARIO ASC"); $query_nome->execute();

//Pesquisa de registros do banco de dados ordenado pelo departamento
$query_depto = $connDB->prepare("SELECT * FROM quadro_funcionarios ORDER BY DEPARTAMENTO ASC"); $query_depto->execute();
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () { let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 600000); }
  }; inactivityTime();
</script>
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
      <button class="btn btn-sm btn-primary" style="margin-left: 30%; width:220px; height: 32px" 
        onclick="location.href='<?php echo $acesso7 ?>'">Cadastrar Novo Funcionario</button><br><br>
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
                  <?php while($nomeLista = $query_nome->fetch(PDO::FETCH_ASSOC)){ $id1 = $nomeLista['ID_FUNCIONARIO']; $_SESSION['id_edit'] = $id1;?>
                  <tr>
                    <th style="width: 10%; text-align: center"> <?php echo $nomeLista['ID_FUNCIONARIO']; ?> </th>
                    <td style="width: 35%"                    > <?php echo $nomeLista['NOME_FUNCIONARIO'] . '<br>'; echo 'Tel: ' . $nomeLista['TELEFONE'] . ' ' .'[ ' . $nomeLista['CIDADE'] . ' ]'; ?> </td>
                    <td style="width: 20%"                    > <?php echo $nomeLista['DEPARTAMENTO'] . '<br>'; echo '[ ' . $nomeLista['CARGO'] . ' ]' ?> </td>
                    <td style="width: 10%"                    > <?php if(!empty($nomeLista['DATA_ADMISSAO'])){$dO = $nomeLista['DATA_ADMISSAO']; $DO = strtotime($dO); echo date('d/m/Y', $DO);}else{ echo 'Logado';} ?> </td>
                    <td style="width: 15%">
                      <a class="btn btn-sm btn-info" href="<?php echo $acesso8 ?>?id=<?php echo $id1 ?>">
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                          <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z'/>
                        </svg></a>
                      <a class="btn btn-sm btn-danger" href="<?php echo $acesso9 ?>?id=<?php echo $id1 ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                          <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                        </svg></a>
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
                <?php while($deptoLista = $query_depto->fetch(PDO::FETCH_ASSOC)){ $id2 = $deptoLista['ID_FUNCIONARIO']; $_SESSION['id_edit'] = $id2;?>
                  <tr>
                    <th style="width: 10%; text-align: center"> <?php echo $deptoLista['ID_FUNCIONARIO']; ?> </th>
                    <td style="width: 20%"                    > <?php echo $deptoLista['DEPARTAMENTO'] . '<br>'; echo '[ ' . $deptoLista['CARGO'] . ' ]' ?> </td>
                    <td style="width: 35%"                    > <?php echo $deptoLista['NOME_FUNCIONARIO'] . '<br>'; echo 'Tel: ' . $deptoLista['TELEFONE'] . ' ' .'[ ' . $deptoLista['CIDADE'] . ' ]'; ?> </td>
                    <td style="width: 10%"                    > <?php if(!empty($deptoLista['DATA_ADMISSAO'])){$dO = $deptoLista['DATA_ADMISSAO']; $DO = strtotime($dO); echo date('d/m/Y', $DO);}else{ echo 'Logado';} ?> </td>
                    <td style="width: 15%">
                      <a class="btn btn-sm btn-info" href="<?php echo $acesso8 ?>?id=<?php echo $id2 ?>">
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                          <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z'/>
                        </svg></a>
                      <a class="btn btn-sm btn-danger" href="<?php echo $acesso9 ?>?id=<?php echo $id2 ?>">
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                          <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z'/>
                        </svg></a>
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