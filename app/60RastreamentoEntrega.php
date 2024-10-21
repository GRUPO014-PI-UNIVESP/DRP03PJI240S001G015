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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_pedido = $_POST['rastreamento'];
    if (is_numeric($numero_pedido)) {
        $query = "SELECT NUMERO_PEDIDO, CLIENTE, DATA_PEDIDO, DATA_ENTREGA, TRANSPORTADORA, ETAPA_PROCESS, SITUACAO FROM pedidos WHERE NUMERO_PEDIDO = :numero_pedido";
        $stmt = $connDB->prepare($query);
        $stmt->bindParam(':numero_pedido', $numero_pedido);
        $stmt->execute();
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($dados) > 0) {
            $cabecalho = '<tr><th style="color: #00FFFF;">Número do Pedido</th><th style="color: #00FFFF;">Cliente</th><th style="color: #00FFFF;">Data do Pedido</th><th style="color: #00FFFF;">Data de Entrega</th><th style="color: #00FFFF;">Transportadora</th><th style="color: #00FFFF;">Etapa do Processo</th><th style="color: #00FFFF;">Situação</th></tr>';
            $linhas = '';
            foreach ($dados as $dado) {
                $dpdmY = date('d/m/Y', strtotime($dado['DATA_PEDIDO']));
                $dedmY = date('d/m/Y', strtotime($dado['DATA_ENTREGA']));
                $linhas .= '<tr>
                    <td style="color: #00FF00;">' . $dado['NUMERO_PEDIDO'] . '</td>
                    <td style="color: #00FF00;">' . $dado['CLIENTE'] . '</td>
                    <td style="color: #00FF00;">' . $dpdmY . '</td>
                    <td style="color: #00FF00;">' . $dedmY . '</td>
                    <td style="color: #00FF00;">' . $dado['TRANSPORTADORA'] . '</td>
                    <td style="color: #00FF00;">' . $dado['ETAPA_PROCESS'] . '</td>
                    <td style="color: #00FF00;">' . $dado['SITUACAO'] . '</td>
                </tr>';
            }
            $resultado = $cabecalho . $linhas;
        } else {
          $resultado = '<div class="text-center" style="display: table-cell; vertical-align: middle; height: 100%; width: 500px; text-alingn: center;"><h2>Nenhum registro encontrado.</h2></div>';
        }
    }
}
?>
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
          <div class="card text-bg-primary mb-3" style="margin-left: -2.5rem; width: 25rem;">
            <div class="card-body">
              <div class="row g-1">
                <h5 class="card-title">Instruções</h5>
                <p class="card-text">Insira o número do pedido e clique em "Rastrear".</p>
              </div>
            </div>
          </div>
          <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-pin-map-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8z"/>
                <path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z"/>
              </svg>
            </span>
            <input type="text" id="rastreamento" name="rastreamento" class="form-control" placeholder="Número do Pedido" maxlength="20" aria-label="text" aria-describedby="addon-wrapping" pattern="[0-9]*">
          </div><br>
          <div class="d-grid gap-2">
            <input class="btn btn-primary" type="submit" id="rastrear" name="rastrear" value="Rastrear" require>
          </div>
        </div>
      </div>
    </form><br>
    <div class="table-rounded">
      <table>
        <?php if (isset($resultado)) { echo $resultado; } ?>
      </table>
    </div>
  </body>
</html>



