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
<?php if($_SESSION['departamento'] === 'ADMINISTRATIVO' && $_SESSION['credencial'] >= 6){ ?>
  <div class="main"><br>
    <p style="font-size: 25px;">Mapa Geral do Sistema</p>
    <div class="row g-2">
      <div class="col md-6">
        <!-- Lista do departamento administrativo -->
        <a style="margin-left: 010px;color:aliceblue"  href="00SeletorAdministrativo.php">Painel Administrativo</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Recursos Humanos</a><br>
        <a style="margin-left: 080px;"                 href="06QuadroFuncionarios.php">Quadro de Funcionários</a><br>
        <a style="margin-left: 105px;color:burlywood"  href="07CadastroFuncionario.php">Cadastro de Novo Funcionário</a><br>
        <a style="margin-left: 080px;"                 href="05MonitorLogin.php">Monitor do Histórico de Login</a><br>
        <a style="margin-left: 080px;"                 href="">Estrutura da Organização</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Setor de Vendas</a><br>
        <a style="margin-left: 080px;"                 href="33PedidoProduto.php">Pedido do Produto</a><br>
        <a style="margin-left: 080px;"                 href="30CadastroCliente.php">Cadastro de Novo Cliente</a><br>
        <a style="margin-left: 080px;"                 href="31CadastroProduto.php">Cadastro de Novo Produto</a><br>
        <a style="margin-left: 080px;"                 href="39RelatorioVendas.php">Relatório de Vendas</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Setor de Compras</a><br>
        <a style="margin-left: 080px;"                 href="21CompraMaterial.php">Compra de Material e Insumos</a><br>
        <a style="margin-left: 080px;"                 href="23CadastroMaterial.php">Cadastro de Novo Material</a><br>
        <a style="margin-left: 080px;"                 href="24RelatorioCompras.php">Relatório de Compras</a><br>
      </div> 
      <div class="col md-6">
        <!-- Lista do departamento da garantia da qualidade -->
        <a style="margin-left: 010px;color:aliceblue"  href="01SeletorGQualidade.php">Painel Garantia da Qualidade</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="01SeletorGQualidade.php">Laboratório de Análises</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Estoque de Reagentes</a><br>
        <a style="margin-left: 080px;"                 href="">Reabastecimento de Reagentes</a><br>
        <a style="margin-left: 080px;"                 href="">Relatório do Estoque de Reagentes</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Relatório das Análises</a><br>
        <br><br>
        <!-- Lista do departamento de logística -->
        <a style="margin-left: 010px;color:aliceblue"  href="app/02SeletorLogistica.php">Painel da Logística</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="app/02SeletorLogistica.php">Controle de Entrada e Saída</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Estoque de Matéria Prima e Insumos</a><br>
        <br><br>
        <!-- Lista do departamento de produção -->
        <a style="margin-left: 010px;color:aliceblue"  href="">Painel da Produção</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Gerência</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Situação da Planta</a><br>
        <a style="margin-left: 045px;color:aquamarine" href="">Cronograma de Manutenção</a><br>
      </div>
    </div>
  </div>
<?php } ?>
