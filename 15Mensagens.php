<?php
//inclusão do banco de dados e estrutura base da página web
include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

$busca_msg = $connDB->prepare("SELECT * FROM mensagens WHERE RECEPTOR_MSG = :user ORDER BY CONFIRMA DESC, DATA_MSG DESC ");
$busca_msg->bindParam(':user', $_SESSION['nome_func'], PDO::PARAM_STR);
$busca_msg->execute();

?>
<div class="main">
  <div class="container">
    <h1 style="font-size: 18px">Caixa de Entrada de Mensagens</h1><br><br>
    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="width: 250px" onclick="location.href='17NovaMsg.php'">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
      </svg> Enviar Nova Mensagem</button><br><br>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 10%">Data/Hora</th>
              <th scope="col" style="width: 15%">Departamento</th>
              <th scope="col" style="width: 25%">Remetente</th>
              <th scope="col" style="width: 25%">Assunto</th>
              <th scope="col" style="width: 10%">Situação</th>
              <th scope="col" style="width: 15%">Ação</th>
            </tr>
          </thead>
          <tbody style="height: 20%; font-size: 11px;">
            <?php while($rowMsg = $busca_msg->fetch(PDO::FETCH_ASSOC)){ $ler = $rowMsg['ID_MENSAGEM']; $_SESSION['ler_msg'] = $ler; ?>
            <tr>
              <th class="text-break" style="width: 10%"> 
                <?php $dIn = $rowMsg['DATA_MSG']; $DiN = strtotime($dIn);
                      $hIn = $rowMsg['HORA_MSG']; $HiN = strtotime($hIn);
                      echo (date('d/m/Y', $DiN) .'  '.date('H:i:s', $HiN)); ?> </th>
              <td class="text-break" style="width: 15%"> <?php echo limitador($rowMsg['DEPTO_EMISSOR'], 25); ?> </td>
              <td class="text-break" style="width: 25%"> <?php echo limitador($rowMsg['EMISSOR_MSG'], 80); ?> </td>
              <td class="text-break" style="width: 25%"> <?php echo limitador($rowMsg['TITULO_MSG'], 80); ?> </td>
              <td style="width: 10%"> 
                <?php $sit = $rowMsg['CONFIRMA'];
                  if($sit === 'READ'){echo 'Lida';}else if($sit === 'UNRE'){echo 'Não Lida';} ?> </td>
              <td style="width: 15%">
                <a class="btn btn-sm btn-info" href="16VisualizarMsg.php?id=<?php echo $ler ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16">
                    <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                  </svg></a>
                <a class="btn btn-sm btn-danger" href="">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                  </svg></a>
              </td>  
            </tr> <?php } ?>
          </tbody>
        </table>
      </div>
  </div>
</div>