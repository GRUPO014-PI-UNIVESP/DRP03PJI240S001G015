<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor de Produtos'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
$_SESSION['nPedido'] = 1; $nPedido = ''; $nomeProd = ''; $qtdeProd = '';
try{
  $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if(isset($_POST['nPedido'])){
    //faz a busca do pedido solicitado na entrada
      $buscaPedido = $connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :numPed");
      $buscaPedido->bindParam(':numPed', $_POST['nPedido'], PDO::PARAM_INT);
      $buscaPedido->execute();
      $rowPedido = $buscaPedido->fetch(PDO::FETCH_ASSOC);

      $buscaProduto = $connDB->prepare("SELECT N_PRODUTO FROM produtos WHERE PRODUTO = :nomeProd");
      $buscaProduto->bindParam(':nomeProd', $rowPedido['PRODUTO'], pdo::PARAM_STR);
      $buscaProduto->execute(); $rowProduto = $buscaProduto->fetch(PDO::FETCH_ASSOC); $_SESSION['nProduto'] = $rowProduto['N_PRODUTO'];

    // faz a seleção da estrutura da tabela
      $selTab = $connDB->prepare("SELECT * FROM estrutura WHERE PROCEDIMENTO = 4 AND ATIVO = 1");
      $selTab->execute(); $rowTab = $selTab->fetch(PDO::FETCH_ASSOC); $_SESSION['nomeTabela'] = $rowTab['NOME_TABELA'];

  }
}
catch(PDOException $e){ echo 'Ocorreu algum problema durante a criação da tabela, comunique o responsável do TI.'; }   
 ?>
<!-- Área Principal -->
<div class="main">
  <br><p style="font-size: 20px; color: whitesmoke">Adicionar Dados Complementares de Produção</p><br>
  <form action="" id="addProd" method="POST">
    <div class="row g-1">
      <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Número do Pedido</label>
        <input style="font-weight: bold; font-size: 18px; background: rgba(0,0,0,0.3); text-align: center; width: 160px; color: yellow" 
               type="number" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowPedido['NUMERO_PEDIDO'] ?>" onclick="submeterFormulario()" required autofocus>
      </div>
    </div>   
  </form><?php
  if(isset($_POST['nPedido'])){ ?>
    <!-- Button trigger modal -->
    <br>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="margin-left: 25px;" autofocus>
      Confirmar
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel"><?php echo 'Departamento: ' . $rowTab['DEPARTAMENTO'] . ' ' .  ' Tabela DB: ' . $rowTab['NOME_TABELA'] ?></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-2">
              <div class="col-md-4">
                <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Número do Pedido</label>
                <input style="font-weight: bold; font-size: 14px; background: rgba(0,0,0,0.3); text-align: center; width: 120px; color: yellow" 
                      type="number" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowPedido['NUMERO_PEDIDO'] ?>" disabled>
              </div>
              <div class="col-md-4">
                <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Quantidade</label>
                <input style="font-weight: bold; font-size: 14px; background: rgba(0,0,0,0.3); text-align: right; width: 120px; color: yellow" type="text" class="form-control" id="nPedido" name="nPedido" 
                       value="<?php echo number_format($rowPedido['QTDE_PEDIDO'],0,',','.') . ' ' . $rowPedido['UNIDADE'] ?>" disabled>
              </div>
              <div class="col-md-4"></div>
              <div class="col-md-12">
                <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Produto</label>
                <input style="font-weight: bold; font-size: 14px; background: rgba(0,0,0,0.3); color: yellow" 
                      type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowPedido['PRODUTO'] ?>" disabled>
              </div>
            </div>
            <br><br>
            <form id="coleta" method="POST">
            <div class="tabela table-responsive">
              <table class="table table-dark table-bordered">
                <thead style="font-size: 12px">
                  <tr>
                    <th scope="col" style="width: 15%; text-align: center;">Etiqueta do Campo</th>
                    <th scope="col" style="width: 15%; text-align: center;">Etiqueta do Campo</th>
                  </tr>
                </thead>
                <tbody class="table-group-divider" style="height: 75%; font-size: 11px;">
                  <tr>
                    <td scope="col" style="width: 15%;"></td>
                    <td scope="col" style="width: 15%;"></td>
                  </Tr>            
                </tbody>
                <tfoot>
                  <tr>
                    <td scope="col" style="width: 15%;"></td>
                    <td scope="col" style="width: 15%;"></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            </form>
          </div><!-- Fim do Modal -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Descartar e Sair</button>
            <button type="button" class="btn btn-primary">Confirmar e Salvar</button>
          </div>
        </div>
      </div>
    </div>
   <?php
  } ?>
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
</script>
<style>  .tabela{ width: 100%; height: 480px; overflow-y: scroll;} </style>