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
      <p style="margin-left: 2%; font-size: 20px; color: whitesmoke">Departamento Administrativo - Relatório de Compra de Material </p>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 10%; text-align:center;">Compra</th>
              <th scope="col" style="width: 20%; text-align:left;">Fornecedor</th>
              <th scope="col" style="width: 25%; text-align:left;">Material</th>
              <th scope="col" style="width: 10%; text-align:center;">Quantidade</th>
              <th scope="col" style="width: 10%; text-align:center;">No.Lote</th>
              <th scope="col" style="width: 10%; text-align:center;">D.Fabr.</th>
              <th scope="col" style="width: 10%; text-align:center;">D.Validade</th>
            </tr>
          </thead>
          <tbody style="height: 80%; font-size: 11px;">
            <?php $listaCompra = $connDB->prepare("SELECT * FROM materiais_lotes ORDER BY DESCRICAO ASC"); $listaCompra->execute();
            while($rowLista = $listaCompra->fetch(PDO::FETCH_ASSOC)){
               $idLote = $connDB->prepare("SELECT QTDE_PEDIDO, UNIDADE FROM materiais_compra WHERE ID_COMPRA = :idCompra");
               $idLote->bindParam(':idCompra', $rowLista['ID_COMPRA'], PDO::PARAM_INT);
               $idLote->execute(); $idCompra = $idLote->fetch(PDO::FETCH_ASSOC); ?>
            <tr>
              <th style="width: 10%; text-align:center;"><?php echo $rowLista['ID_COMPRA'] ?></th>
              <td style="width: 20%; text-align:left;"><?php echo $rowLista['FORNECEDOR'] ?></td>
              <td style="width: 25%; text-align:left;"><?php echo $rowLista['DESCRICAO'] ?></td>
              <td style="width: 10%; text-align:center;"><?php echo number_format($idCompra['QTDE_PEDIDO']) . ' ' . $idCompra['UNIDADE'] ?></td>
              <td style="width: 10%; text-align:center;"><?php echo $rowLista['ID_INTERNO'] ?></td>
              <td style="width: 10%; text-align:center;"><?php echo date('d/m/Y', strtotime($rowLista['DATA_FABRI'])) ?></td>
              <td style="width: 10%; text-align:center;"><?php echo date('d/m/Y', strtotime($rowLista['DATA_VALI'])) ?></td>
            </tr><?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
