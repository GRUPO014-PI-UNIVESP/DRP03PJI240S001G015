<?php
  // faz requisição da estrutura base da págima do sistema
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Dashboard';
  include_once './RastreadorAtividades.php';

  $CurrentHour = date('H');

  if ($CurrentHour >= 6 && $CurrentHour < 12) {
    $Greeting = 'Tenha um Bom Dia.';
  }
  elseif ($CurrentHour >= 12 && $CurrentHour < 18) {
    $Greeting = 'Tenha uma Boa Tarde.';
  }
  else {
    $Greeting = 'Tenha uma Boa Noite.';
  }
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time;
    window.onload        = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress  = resetTimer;
    function deslogar() {
      <?php
        $_SESSION['posicao'] = 'Encerrado por inatividade';
        include_once './RastreadorAtividades.php';
      ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() {
      clearTimeout(time);
       time = setTimeout(deslogar, 300000);
     }
  };
  inactivityTime();
</script>
<div class="main">
<br><br><br>
      <p style="margin-left: 20%; font-size: 25px; color: yellow">Selecione o ambiente de trabalho na barra lateral</p>
      <br><br>
        <img style="margin-left: 20%;" src="./Abertura.jpg" width="600" height="400"/>
        <br><br><br>
      <p style="margin-left: 20%; font-size: 30px; color: yellow"><?php echo $Greeting; ?></p>
</div>