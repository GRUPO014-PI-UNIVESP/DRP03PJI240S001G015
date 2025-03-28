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
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 600000);}
  }; inactivityTime();
</script>
<!-- Área Principal -->
  <div class="main">
    <div class="container">
      <p style="margin-left: 2%; font-size: 20px; color: whitesmoke">Departamento Administrativo - Histórico de LogIn </p>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 10%">Log-In</th>
              <th scope="col" style="width: 10%">Hora</th>
              <th scope="col" style="width: 10%">Log-Out</th>
              <th scope="col" style="width: 10%">Hora</th>
              <th scope="col" style="width: 40%">Nome do Funcionário</th>
              <th scope="col" style="width: 10%">Departamento</th>
            </tr>
          </thead>
          <tbody style="height: 80%; font-size: 11px;">
            <?php while($rowLog = $listar->fetch(PDO::FETCH_ASSOC)){ ?>
            <tr>
              <th style="width: 10%"> 
                <?php $dIn = $rowLog['DATA_LOGIN']; $DiN = strtotime($dIn); echo date('d/m/Y', $DiN); ?> </th>
              <td style="width: 10%"> 
                <?php $hIn = $rowLog['HORA_LOGIN']; $HiN = strtotime($hIn); echo date('H:i:s', $HiN); ?> </td>
              <td style="width: 10%"> 
                <?php if(!empty($rowLog['DATA_LOGOUT'])){$dO = $rowLog['DATA_LOGOUT']; $DO = strtotime($dO); echo date('d/m/Y', $DO);}else{ echo 'Logado';} ?> </td>
              <td style="width: 10%"> 
                <?php if(!empty($rowLog['HORA_LOGOUT'])){$hO = $rowLog['HORA_LOGOUT']; $HO = strtotime($hO); echo date('H:i:s', $HO);}else{ echo '';} ?> </td>
              <td style="width: 40%"> 
                <?php echo limitador($rowLog['NOME_FUNCIONARIO'], 80); ?> </td>
              <td style="width: 20%"> 
                <?php echo limitador($rowLog['DEPARTAMENTO'], 25); ?> </td>
            </tr><?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
