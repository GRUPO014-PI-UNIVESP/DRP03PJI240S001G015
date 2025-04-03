<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor Login'; include_once './RastreadorAtividades.php';

$listar = $connDB->prepare("SELECT * FROM historico_login ORDER BY ID_LOGIN DESC"); $listar->execute();
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php';?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);}
  }; inactivityTime();
</script>
<!-- Área Principal -->
  <div class="main">
    <div class="container"><br>
      <div class="row g-2">
        <div class="col-md-5">
          <p style="margin-left: 2%; font-size: 20px; color: whitesmoke">Departamento Administrativo - Histórico de LogIn </p>
        </div>
        <div class="col-md-5">
          <button type="button" class="btn btn-info" style="width: 80px; float:inline-end" onclick="location.href='./MapaGeral.php'">Voltar</button>
        </div>
      </div>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 10%">Log-In</th>
              <th scope="col" style="width: 10%">Hora</th>
              <th scope="col" style="width: 10%">Log-Out</th>
              <th scope="col" style="width: 10%">Hora</th>
              <th scope="col" style="width: 30%">Nome do Funcionário</th>
              <th scope="col" style="width: 10%">Departamento</th>
              <th scope="col" style="width: 10%">Detalhes</th>
            </tr>
          </thead>
          <tbody style="height: 80%; font-size: 11px;">
            <form class="row g-1" method="POST" action="#">
              <?php while($rowLog = $listar->fetch(PDO::FETCH_ASSOC)){ $id1 = $rowLog['ID_LOGIN'];?>
              <tr>
                <th style="width: 10%"> 
                  <?php $dIn = $rowLog['DATA_LOGIN']; $DiN = strtotime($dIn); echo date('d/m/Y', $DiN); ?> </th>
                <td style="width: 10%"> 
                  <?php $hIn = $rowLog['HORA_LOGIN']; $HiN = strtotime($hIn); echo date('H:i:s', $HiN); ?> </td>
                <td style="width: 10%"> 
                  <?php if(!empty($rowLog['DATA_LOGOUT'])){$dO = $rowLog['DATA_LOGOUT']; $DO = strtotime($dO); echo date('d/m/Y', $DO);}else{ echo 'Logado';} ?> </td>
                <td style="width: 10%"> 
                  <?php if(!empty($rowLog['HORA_LOGOUT'])){$hO = $rowLog['HORA_LOGOUT']; $HO = strtotime($hO); echo date('H:i:s', $HO);}else{ echo '';} ?> </td>
                <td style="width: 30%"> 
                  <?php echo limitador($rowLog['NOME_FUNCIONARIO'], 80); ?> </td>
                <td style="width: 10%"> 
                  <?php echo limitador($rowLog['DEPARTAMENTO'], 25); ?> </td>
                <td style="width: 15%">
                  <a class="btn btn-sm btn-info" href="<?php echo $acesso4 ?>?id=<?php echo $id1 ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                      <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                      <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                      <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                      </svg> Detalhado</a>
                </td>
              </tr><?php } ?>
            </form>
          </tbody>
        </table>
      </div>
    </div>
  </div>
