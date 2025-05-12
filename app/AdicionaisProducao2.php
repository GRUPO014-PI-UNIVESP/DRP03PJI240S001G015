<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor de Produtos'; include_once './RastreadorAtividades.php';

  //atribui usuário como responsável por registro de entrada do material ou cadastramento
  $responsavel = $_SESSION['nome_func'];

  try{
    $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //faz a busca do pedido solicitado na entrada
        $buscaPedido = $connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :numPed");
        $buscaPedido->bindParam(':numPed', $_SESSION['nPedido'], PDO::PARAM_INT);
        $buscaPedido->execute();
        $rowPedido = $buscaPedido->fetch(PDO::FETCH_ASSOC);
      // faz a busca dos dados da fabricação do produto
        $buscaProd = $connDB->prepare("SELECT * FROM producao WHERE NUMERO_LOTE = :numLote");
        $buscaProd->bindParam(':numLote', $rowPedido['NUMERO_LOTE'], PDO::PARAM_INT);
        $buscaProd->execute();
        $rowProd = $buscaProd->fetch(PDO::FETCH_ASSOC);
      // faz a busca do numero identificador do produto
        $buscaProduto = $connDB->prepare("SELECT N_PRODUTO FROM produtos WHERE PRODUTO = :nomeProd");
        $buscaProduto->bindParam(':nomeProd', $rowPedido['PRODUTO'], pdo::PARAM_STR);
        $buscaProduto->execute(); 
        $rowProduto = $buscaProduto->fetch(PDO::FETCH_ASSOC);
        $_SESSION['nProduto'] = $rowProduto['N_PRODUTO'];

      // faz a seleção da estrutura da tabela
        $selTab = $connDB->prepare("SELECT * FROM estrutura WHERE PROCEDIMENTO = 4 AND ATIVO = 1");
        $selTab->execute();
        $rowTab = $selTab->fetch(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e){ echo 'Ocorreu algum problema durante a criação da tabela, comunique o responsável do TI.'; }   
?>
<!-- Área Principal -->
<div class="main">
  <br><p style="font-size: 20px; color: whitesmoke">Adicionar Dados Complementares de Produção</p><br>
  <div class="row g-2">
    <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Número do Pedido</label>
        <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: center; width: 160px; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowPedido['NUMERO_PEDIDO'] ?>" disabled>
    </div>
    <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Quantidade</label>
        <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: right; width: 160px; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido"
               value="<?php echo number_format($rowPedido['QTDE_PEDIDO'],0,',','.') . ' ' . $rowPedido['UNIDADE'] ?>" disabled>
    </div>
    <div class="col-md-7">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Produto</label>
        <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: left; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowPedido['PRODUTO'] ?>" disabled>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Numero do Lote</label>
        <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: center; width: 160px; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowProd['NUMERO_LOTE'] ?>" disabled>
    </div>
    <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Data de Fabricação</label>
        <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: center; width: 160px; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo date('d.m.Y',strtotime($rowProd['DATA_FABRI'])) ?>" disabled>
    </div>
  </div><br>
  <div class="row g-2">
    <div class="col-md-2"></div>
    <di class="col-md-6">
      <div class="tabela table-responsive">
        <table class="table table-dark table-bordered">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 15%; text-align: center;">Etiqueta do Campo</th>
              <th scope="col" style="width: 15%; text-align: center;">Valor</th>
            </tr>
          </thead>
          <tbody class="table-group-divider" style="height: 75%; font-size: 11px;"><?php
            $buscaStruc = $connDB->prepare("SELECT * FROM estrutura_campos WHERE ID_ESTRUTURA = :strucColumn AND N_CAMPO > 2");
            $buscaStruc->bindParam(':strucColumn', $rowTab['ID_ESTRUTURA'], PDO::PARAM_INT);
            $buscaStruc->execute();
            while($rowStruc = $buscaStruc->fetch(PDO::FETCH_ASSOC)){ $i = 1;
              $sql0 = 'SELECT ' . $rowStruc['CAMPO'] . ' FROM ' . $rowTab['NOME_TABELA'] . ' WHERE NUMERO_PEDIDO = :nPedido';
              $buscaValor = $connDB->prepare($sql0);
              $buscaValor->bindParam(':nPedido', $_SESSION['nPedido'], PDO::PARAM_INT);
              $buscaValor->execute();
              $rowValor = $buscaValor->fetch(PDO::FETCH_ASSOC); ?>
              <tr>
                <td scope="col" style="width: 15%;"><?php echo $rowStruc['ETIQUETA'] ?></td>
                <td scope="col" style="width: 15%;"><?php echo $rowValor[$rowStruc['CAMPO']] ?></td>
              </Tr> <?php
            } ?>              
          </tbody>
          <tfoot>
            <tr>
              <td scope="col" style="width: 15%;"></td>
              <td scope="col" style="width: 15%;"></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <input class="btn btn-outline-primary" type="submit" name="adicionar" id="adicionar" value="Adicionar Novo Campo">
      <button class="btn btn-outline-danger" onclick="location.href='./MapaGeral.php'">Descartar e Sair</button>
    </div>
  </div>
</div>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
  }; inactivityTime();
  // atribui função de enviar formulário
  function submeterFormulario() { document.getElementById("enviar").submit(); }

  function receberValor(){ var tipo = document.getElementById("campo").value; }
</script>
<style>  .tabela{ width: 100%; height: auto ; overflow-y: scroll;} </style>