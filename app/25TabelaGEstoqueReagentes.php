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
  .table-rounded {
    position: relative;
    border-collapse: collapse;
    display: block;
    width: fit-content;
    margin: 0 auto;
    grid-template-columns: auto;
    grid-gap: 10px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    background-color: dimgray;
    padding-top: 20px;
    padding-bottom: 20px;
    overflow-y: auto;
    max-height: 500px;
}
  .table-rounded table {
    border-collapse: separate;
    border-spacing: 10px 0;
    border-radius: 10px;
    overflow: hidden;
  }
  .table-rounded th {
    padding: 5px 10px;
    border: 1px solid black;
    margin-right: 10px;
    white-space: nowrap;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
    color: #333;
    font-size: 12px;
  }
  .table-rounded th, .table-rounded td {
    padding: 5px 10px;
    border: 1px solid black;
    text-align: center;
    background-color: darkgray;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
    color: black;
    font-size: 12px;
  }
</style>
<?php
  $query = "SELECT * FROM reagentes_estoque";
  $stmt = $connDB->prepare($query);
  $stmt->execute();
  $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (count($dados) > 0) {
    $cabecalho = '<tr><th style="color: #00FFFF; paddding-left: 150px; padding-right: 10px;">Tipo</th><th style="color: #00FFFF; padding-left: 100px; padding-right: 100px;">Desrição</th><th style="color: #00FFFF; padding-left: 20px; padding-right: 20px;">Quantidade Estoque</th><th style="color: #00FFFF; padding-left: 50px; padding-right: 50px;">Unidade</th></tr>';
    $linhas = '';
    foreach ($dados as $dado) {
      $linhas .= '<tr>
          <td style="color: #00FF00; padding-left: 80px; padding-right: 80px;">' . $dado['TIPO'] . '</td><td style="color: #00FF00; padding-left: 125px; padding-right: 125px;">' . $dado['DESCRICAO'] . '</td><td style="color: #00FF00;">' . $dado['QTDE_ESTOQUE'] . '</td><td style="color: #00FF00;">' . $dado['UNIDADE'] . '</td>
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
        <p style="position: relative; left: 60px; font-size: 20px; color: whitesmoke">Estoque de Reagentes</p>
      </div>
        <div class="table-rounded" style="margin-top: 1px;">
          <table>
            <?php if (isset($resultado)) { echo $resultado; } ?>
          </table>
        </div>
    </div>
  </body>
</html>