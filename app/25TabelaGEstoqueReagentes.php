<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
// $_SESSION['posicao'] = 'Rastreamento de Entrega';
include_once './RastreadorAtividades.php';
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
       time = setTimeout(deslogar, 600000);
     }
  };
  inactivityTime();
</script>
<style>
  .scroll-tela {
    position: relative;
    left: 580px;
    width: 50%;
    right: px;
    height: 300px;
    overflow-y: auto;
    border: 1px solid #ccc;
  }
</style>
<php>

</php>
<!doctype html>
<html lang="pt-br" data-bs-theme="dark">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <form method="POST" action="#">
      <div class="container-fluid">
        <div class="col-2 mt-5 mb-3 mx-auto">
          <p style="position: relative; left: 150px; font-size: 20px; color: whitesmoke">Estoque de Reagentes</p>
        </div>
        <div class="scroll-tela">
          
        </div>
      </div>
    </form>
  </body>
</html>