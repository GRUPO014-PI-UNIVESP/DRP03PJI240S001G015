<?php

include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

$listar = $connDB->prepare("SELECT * FROM quadro_funcionarios ORDER BY CARGO ASC");
$listar->execute();
?>
<!-- Área Principal -->
  <div class="main">
    <div class="container">
      <p style="margin-left: 2%; font-size: 25px; color: whitesmoke">Departamento Administrativo - Quadro de Funcionários</p>
      <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Reordenar por</button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="06QuadroFuncionarios.php">Nome</a></li>
        <li><a class="dropdown-item" href="08QuadroFuncionarios.php">Departamento</a></li>
        <li><a class="dropdown-item" href="09QuadroFuncionarios.php">Data de Admissão</a></li>
      </ul>
      <button class="btn btn-sm btn-primary" style="float:right" onclick="location.href='<?php echo $acesso7 ?>'">Cadastrar Novo Funcionario</button><br><br>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
        <p style="font-size: 12px; color: bisque">Ordenado por Cargo</p>
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 10%">Registro No.</th>
              <th scope="col" style="width: 10%">Admissão</th>
              <th scope="col" style="width: 15%">Departamento</th>
              <th scope="col" style="width: 20%">Cargo</th>
              <th scope="col" style="width: 35%">Nome do Funcionario</th>
              <th scope="col" style="width: 10%">Ações</th>
            </tr>
          </thead>
          <tbody style="height: 75%; font-size: 10px;">
            <?php while($rowLog = $listar->fetch(PDO::FETCH_ASSOC)){ $id = $rowLog['ID_FUNCIONARIO'];?>
            <tr>
              <th style="width: 10%; text-align: center"> 
                <?php echo $rowLog['ID_FUNCIONARIO']; ?> </th>
              <td style="width: 10%"> 
                <?php if(!empty($rowLog['DATA_ADMISSAO'])){$dO = $rowLog['DATA_ADMISSAO']; $DO = strtotime($dO); echo date('d/m/Y', $DO);}else{ echo 'Logado';} ?> </td>
              <td style="width: 15%">
                <?php echo $rowLog['DEPARTAMENTO']; ?> </td>
              <td style="width: 15%">
                <?php echo $rowLog['CARGO']; ?> </td>
              <td style="width: 25%"> 
                <?php echo limitador($rowLog['NOME_FUNCIONARIO'], 80); ?> </td>
              <td style="width: 15%">
                <a class="btn btn-sm btn-info" href="08EditaDadosFuncionario.php?id=<?php echo $id ?>">
                  <svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
                    <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z'/>
                  </svg></a>
                <a class="btn btn-sm btn-danger" href="">
                  <svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                    <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
                  </svg>
                </a>
              </td>            
            </tr><?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
