<?php
  // faz requisição da estrutura base da págima do sistema
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Dashboard';
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
       time = setTimeout(deslogar, 6000000);
     }
  };
  inactivityTime();
</script>

  <div class="main"><br>
    <p style="font-size: 25px;">Mapa Geral do Sistema</p>
    <div class="row g-2">
      <div class="col md-6">
        <!-- Lista do departamento administrativo -->
        <a style="margin-left: 010px;color:aliceblue"  href="">Painel Administrativo</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Recursos Humanos</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso5 ?>">Quadro de Funcionários</a><br>
        <a style="margin-left: 105px;color:burlywood"  href="<?php echo $acesso7 ?>">Cadastro de Novo Funcionário</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso60 ?>">Monitor do Histórico de Login</a><br>
        <br>
        <a style="margin-left: 045px;color:aquamarine" href="">Setor de Vendas</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso2 ?>">Lista de Pedidos Efetivados</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso11 ?>">Pedido do Produto</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso13 ?>">Cadastro de Novo Cliente</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso14 ?>">Cadastro de Novo Produto</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso17 ?>">Relatório de Vendas</a><br>
        <br>
        <a style="margin-left: 045px;color:aquamarine" href="">Setor de Compras</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso1 ?>">Lista de Compras Agendadas</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso16 ?>">Compra de Material e Insumos</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso15 ?>">Cadastro de Novo Material</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso19 ?>">Cadastro de Fornecedor</a><br>
        <a style="margin-left: 080px;"                 href="<?php echo $acesso18 ?>">Relatório de Compras</a><br>
        <br>
        <a style="margin-left: 045px;color:aquamarine" href="">Desempenho</a><br>
        <a style="margin-left: 080px;"                 href="MonitorProduto.php">Monitor da Execução dos Pedidos</a><br>
        <a style="margin-left: 080px;"                 href="">Desempenho de Vendas</a><br>
        <a style="margin-left: 080px;"                 href="">Desempenho de Produtividade</a><br>
        <a style="margin-left: 080px;"                 href=""></a><br>
      </div> 
      <div class="col md-6">
        <!-- Lista do departamento da garantia da qualidade -->
        <a style="margin-left: 010px;color:aliceblue"  href="">Painel Garantia da Qualidade</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="<?php echo $acesso20 ?>">Laboratório de Análises</a><br>
        <br>
        <a style="margin-left: 045px;color:aquamarine" href="">Estoque de Reagentes</a><br>
        <a style="margin-left: 080px;"                 href="">Reabastecimento de Reagentes</a><br>
        <a style="margin-left: 080px;"                 href="">Relatório do Estoque de Reagentes</a><br>
        <br>
        <a style="margin-left: 045px;color:aquamarine" href="">Relatório das Análises</a><br>
        <br><br>
        <!-- Lista do departamento de logística -->
        <a style="margin-left: 010px;color:aliceblue"  href="">Painel da Logística</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="<?php echo $acesso30 ?>">Controle de Entrada e Saída</a><br>
        <br>
        <a style="margin-left: 045px;color:aquamarine" href="">Estoque de Matéria Prima e Insumos</a><br>
        <br><br>
        <!-- Lista do departamento de produção -->
        <a style="margin-left: 010px;color:aliceblue"  href="">Painel da Produção</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="<?php echo $acesso40 ?>">Gerência</a><br>
        <br>
        <a style="margin-left: 045px;color:aquamarine" href="">Situação da Planta</a><br>
        <br>
        <a style="margin-left: 045px;color:aquamarine" href="">Cronograma de Manutenção</a><br>
      </div>
    </div>
  </div>

