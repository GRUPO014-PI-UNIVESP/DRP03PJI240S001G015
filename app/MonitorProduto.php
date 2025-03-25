<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Monitor de Produtos';
include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

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
       time = setTimeout(deslogar, 69900000);
     }
  };
  inactivityTime();
</script>
<style>
  .tabela{ width: 100%; height: 500px; overflow-y: scroll;}
</style>
<!-- Área Principal -->
<div class="main">
  <div class="row g-1">
    <div class="col md-6">
      <br>
      <p style="font-size: 25px; color:cyan">Monitor da Execução dos Pedidos</p>
    </div>
    <div class="col md-6" style="text-align:center;">
      <br>
      <img src="./legenda de cores.jpg" />
    </div>
  </div>
  <div class="overflow-y: scroll">
    <table class="table table-dark table-hover">
      <thead style="font-size: 12px">
        <tr>
          <th scope="col" style="width: 10%"><?php echo 'Data do Pedido' . '<br>' . 'Pedido No.'; ?></th>
          <th scope="col" style="width: 30%"><?php echo 'Produto' . '<br>' . 'Cliente'; ?></th>
          <th scope="col" style="width: 10%"><?php echo 'Quantidade' . '<br>' . 'Data de Entrega'; ?></th>
          <th scope="col" style="width: 50%; text-align: center">Progresso</th>
        </tr>
      </thead>
      <tbody style="height: 75%; font-size: 11px;">
        <tr>
          <th style="width: 10%;"><?php echo '01/03/2025' . '<br>' . '00001'; ?></th>
          <td style="width: 30%;"><?php echo 'Produto A' . '<br>' . 'Food Truck da Dri'; ?></td>
          <td style="width: 10%;"><?php echo '1.000 Kg' . '<br>' . '30/03/2025'; ?></td>
          <td style="width: 50%;">
            <div class="row g-1">
              <div class="col md-2" style="font-size:12px; text-align:center; color:black; background-color:limegreen;">
                Compra
              </div>
              <div class="col md-2" style="font-size:12px; text-align:center; color:black; background-color:limegreen;">
                Recebida
              </div>
              <div class="col md-2" style="font-size:12px; text-align:center; color:whitesmoke; background-color:dodgerblue;">
                Análise
              </div>
              <div class="col md-2" style="font-size:12px; text-align:center; color:whitesmoke; background-color:lightslategrey;">
                Fabricação
              </div>
              <div class="col md-2" style="font-size:12px; text-align:center; color:whitesmoke; background-color:lightslategrey;">
                Análise
              </div>
              <div class="col md-2" style="font-size:12px; text-align:center; color:whitesmoke; background-color:lightslategrey;">
                Entrega
              </div>
            </div>
            <div class="row g-1">
              <div class="col md-6" style="font-size: 11px;">
                Matéria Prima
              </div>
              <div class="col md-6" style="font-size: 11px;">
                Produto Final
              </div>
            </div>
          </td>          
        </tr>
      </tbody>
    </table>
  </div>
</div>