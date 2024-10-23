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
<?php
  if (isset($_POST['novo'])) {
    
  }
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="dark">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <div class="container-fluid">
      <div class="col-2 mt-5 mb-3 mx-auto">
        <p style="position: relative; left: 30px; font-size: 20px; color: whitesmoke">Reabastecimento de Reagentes</p>
      </div>
    </div>
    <form method="POST" action="#">
      <div class="col-2 mt-5 mb-3 mx-auto">
        <input class="btn btn-primary" type="submit" id="novo" name="novo" value="novo">
      </div>
    <div class="row">
      <div class="col-md-2" style="padding-left: 720px;">
        <label for="tipo" class="form-label" style="font-size: 20px; color:aqua">Tipo</label>
        <input id="tipo" name="tipo" style="font-size: 12px; padding-left: 200px; text-align: left; background: rgba(0,0,0,0.3)" type="text" class="form-control">
      </div>
      <div class="col-md-2" style="padding-left: 300px;">
        <label for="descricao" class="form-label" style="font-size: 20px; color:aqua">Descrição</label>
        <input id="descricao" name="descricao" style="font-size: 12px; padding-left: 300px; text-align: left; background: rgba(0,0,0,0.3)" type="text" class="form-control">
      </div>
    </div>
  </body>
</html>