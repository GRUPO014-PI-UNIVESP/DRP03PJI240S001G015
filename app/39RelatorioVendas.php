<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor Login'; include_once './RastreadorAtividades.php';
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
      <p style="margin-left: 2%; font-size: 20px; color: whitesmoke">Departamento Administrativo - Relatório de Vendas </p>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 05%; text-align:center;">Pedido</th>
              <th scope="col" style="width: 20%; text-align:left;">Cliente</th>
              <th scope="col" style="width: 25%; text-align:left;">Produto</th>
              <th scope="col" style="width: 10%; text-align:center;">Quantidade</th>
              <th scope="col" style="width: 10%; text-align:center;">No.Lote</th>
              <th scope="col" style="width: 10%; text-align:center;">D.Fabr.</th>
              <th scope="col" style="width: 10%; text-align:center;">D.Entrega</th>
            </tr>
          </thead>
          <tbody style="height: 80%; font-size: 11px;">
            <?php $listaVendas = $connDB->prepare("SELECT * FROM pedidos"); $listaVendas->execute();
            while($rowLista = $listaVendas->fetch(PDO::FETCH_ASSOC)){ ?>
            <tr>
              <th style="width: 05%; text-align:center;"><?php echo $rowLista['NUMERO_PEDIDO'] ?></th>
              <td style="width: 20%; text-align:left;"><?php echo $rowLista['CLIENTE'] ?></td>
              <td style="width: 25%; text-align:left;"><?php echo $rowLista['PRODUTO'] ?></td>
              <td style="width: 10%; text-align:center;"><?php echo number_format($rowLista['QTDE_PEDIDO']) . ' ' . $rowLista['UNIDADE'] ?></td>
              <td style="width: 10%; text-align:center;"><?php echo $rowLista['NUMERO_LOTE'] ?></td>
              <td style="width: 10%; text-align:center;"><?php echo date('d/m/Y', strtotime($rowLista['DATA_FABRI'])) ?></td>
              <td style="width: 10%; text-align:center;"><?php echo date('d/m/Y', strtotime($rowLista['DATA_ENTREGA'])) ?></td>
            </tr><?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
