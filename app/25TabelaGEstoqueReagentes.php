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
    height: 500px;
    overflow-y: auto;
    border: 1px solid #ccc;
    background-color: black;
    padding: 10px;
    border-radius: 5px;
  }
</style>
<?php
  $query = "SELECT * FROM reagentes_estoque";
  $stmt = $connDB->prepare($query);
  $stmt->execute();
  $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (count($dados) > 0) {
    $cabecalho = '<tr><th style="color: #00FFFF; padding-left: 80px;">Tipo</th><th style="color: #00FFFF; padding-left: 150px;">Desrição</th><th style="color: #00FFFF; padding-left: 150px;">Quantidade Estoque</th><th style="color: #00FFFF; padding-left: 140px;">Unidade</th></tr>';
    $linhas = '';
    foreach ($dados as $dado) {
      $linhas .= '<tr>
          <td style="color: #00FF00; padding-left: 75px;">' . $dado['TIPO'] . '</td><td style="color: #00FF00; padding-left: 155px;">' . $dado['DESCRICAO'] . '</td><td style="color: #00FF00; padding-left: 215px;">' . $dado['QTDE_ESTOQUE'] . '</td><td style="color: #00FF00; padding-left: 160px;">' . $dado['UNIDADE'] . '</td>
      </tr>';
    }
    $resultado = $cabecalho . $linhas;
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
        <p style="position: relative; left: 150px; font-size: 20px; color: whitesmoke">Estoque de Reagentes</p>
      </div>
      <div class="scroll-tela" style="margin-top: 20px">
        <div class="table-rounded" style="margin-top: 1px;">
          <table>
            <?php if (isset($resultado)) { echo $resultado; } ?>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>