<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Entrada de Pedido';
include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

//Pesquisa por CLIENTES para seleção
$query_customer = $connDB->prepare("SELECT RAZAO_SOCIAL FROM pf_cliente");
$query_customer->execute();

//Pesquisa de descrição do PRODUTO para seleção
$query_produto = $connDB->prepare("SELECT DESCRICAO_PF FROM pf_tabela");
$query_produto->execute();

$descrProd = ''; $qtdeLote = '';


/* capta dados do formulário
$confirma = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//verifica se foi preenchido formulário de entrada de material já existente no banco de dados

if(!empty($confirma['descrProd'])){

  // atribui valores em conformidade com banco de dados
  $dataPedido = date('Y-m-d', strtotime($confirma['dataPedido']));
  $dataFabr   = date('Y-m-d', strtotime($confirma['dataFabr']));
  $dataVali   = date('Y-m-d', strtotime($confirma['dataVali']));
  $lote = strtoupper($confirma['numLote']);
  
  // ordem de inserção de dados
  $salvar1 = $connDB->prepare("INSERT INTO pf_pedido (DATA_PEDIDO, CLIENTE, DESCRICAO_PF, NUMERO_LOTE_PF, DATA_FABRICACAO, DATA_VALIDADE,
                                                      QTDE_LOTE_PF, UNIDADE_MEDIDA, NOTA_FISCAL_PF, RESPONSAVEL_REGISTRO)
                               VALUES (:dataPedido, :cliente, :descrProduto, :numLote, :dataFabr, :dataVali, :qtdeLote, :uniMed, :notaF, :responsavel)");
  $salvar1->bindParam(':dataPedido'  , $dataPedido              , PDO::PARAM_STR);
  $salvar1->bindParam(':cliente'     , $confirma['cliente']     , PDO::PARAM_STR);
  $salvar1->bindParam(':descrProduto', $confirma['descrProduto'], PDO::PARAM_STR);
  $salvar1->bindParam(':numLote'     , $lote                    , PDO::PARAM_STR);
  $salvar1->bindParam(':dataFabr'    , $dataFabr                , PDO::PARAM_STR);
  $salvar1->bindParam(':dataVali'    , $dataVali                , PDO::PARAM_STR);
  $salvar1->bindParam(':qtdeLote'    , $confirma['qtdeLote']    , PDO::PARAM_INT);
  $salvar1->bindParam(':uniMed'      , $confirma['uniMed']      , PDO::PARAM_STR);
  $salvar1->bindParam(':notaF'       , $confirma['notaFiscal']  , PDO::PARAM_STR);
  $salvar1->bindParam(':responsavel' , $responsavel             , PDO::PARAM_STR);
  $salvar1->execute();

  //redireciona para início 
  header('Location: ./30EntradaPedido.php');

  //verifica se foi feito o cadastramento de novo material
} else if(!empty($confirma['salvar2'])){

  $dataPedido    = date('Y-m-d', strtotime($confirma['dataEntr2']));
  $dataFabr2     = date('Y-m-d', strtotime($confirma['dataFabr2']));
  $dataVali2     = date('Y-m-d', strtotime($confirma['dataVali2']));
  $lote2         = strtoupper($confirma['numLote2']);
  $cliente2      = strtoupper($confirma['cliente2']);
  $descrProduto2 = strtoupper($confirma['descrProduto2']);
  
  $salvar2      = $connDB->prepare("INSERT INTO pf_pedido (DATA_PEDIDO, CLIENTE, DESCRICAO_PF, NUMERO_LOTE_PF, DATA_FABRICACAO,
                                                           DATA_VALIDADE, QTDE_LOTE_PF, UNIDADE_MEDIDA, NOTA_FISCAL_PF, RESPONSAVEL_REGISTRO)
                                    VALUES (:dataPedido, :cliente, :descrProduto, :numLote, :dataFabr, :dataVali, :qtdeLote, :uniMed, :notaF, :responsavel)");
  $salvar2->bindParam(':dataPedido'  , $dataPedido             , PDO::PARAM_STR);
  $salvar2->bindParam(':cliente'     , $cliente2               , PDO::PARAM_STR);
  $salvar2->bindParam(':descrProduto', $descrProduto2          , PDO::PARAM_STR);
  $salvar2->bindParam(':numLote'     , $confirma['numLote2']   , PDO::PARAM_STR);
  $salvar2->bindParam(':dataFabr'    , $dataFabr2              , PDO::PARAM_STR);
  $salvar2->bindParam(':dataVali'    , $dataVali2              , PDO::PARAM_STR);
  $salvar2->bindParam(':qtdeLote'    , $confirma['qtdeLote2']  , PDO::PARAM_STR);
  $salvar2->bindParam(':uniMed'      , $confirma['uniMed2']    , PDO::PARAM_STR);
  $salvar2->bindParam(':notaF'       , $confirma['notaFiscal2'], PDO::PARAM_STR);
  $salvar2->bindParam(':responsavel' , $responsavel            , PDO::PARAM_STR);
  $salvar2->execute();

  $saveFornecedor = $connDB->prepare("INSERT INTO pf_cliente (RAZAO_SOCIAL) VALUES (:cliente)");
  $saveFornecedor->bindParam(':cliente', $cliente2, PDO::PARAM_STR);
  $saveFornecedor->execute();

  $saveMP = $connDB->prepare("INSERT INTO pf_tabela (CLIENTE, DESCRICAO_PF) VALUES (:cliente, :descrProduto)");
  $saveMP->bindParam(':cliente'     , $cliente2     , PDO::PARAM_STR);
  $saveMP->bindParam(':descrProduto', $descrProduto2, PDO::PARAM_STR);
  $saveMP->execute();

  header('Location: ./30EntradaPedido.php');
}*/
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
       time = setTimeout(deslogar, 3600000);
     }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" 
          role="tab" aria-controls="manage-tab-pane" aria-selected="true">Registrar Pedido de Produto</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="newClient-tab" data-bs-toggle="tab" data-bs-target="#newClient-tab-pane" type="button" 
          role="tab" aria-controls="newClient-tab-pane" aria-selected="false">Cadastro de Novo Cliente</button>
      </li>
        <button class="nav-link" id="newProd-tab" data-bs-toggle="tab" data-bs-target="#newProd-tab-pane" type="button" 
          role="tab" aria-controls="newProd-tab-pane" aria-selected="false">Cadastro de Novo Produto</button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent"><br>
      <!-- Registra Pedido -->
      <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0" >
        <form action="" method="POST">
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" 
                  aria-expanded="true" aria-controls="flush-collapseOne">
                  Clique aqui para selecionar produto e inserir a quantidade
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body row g-4"><br>
                  <div class="col-md-9">
                    <label for="descrProd" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
                    <select style="font-size: 14px;" class="form-select" id="descrProd" name="descrProd" autofocus>
                      <option style="font-size: 14px" selected>Selecione o Produto</option>
                        <?php
                        // inclui nome dos produtos como opções de seleção da tag <select>
                          while($produto = $query_produto->fetch(PDO::FETCH_ASSOC)){?>
                            <option style="font-size: 14px"><?php echo $produto['DESCRICAO_PF']; ?></option> <?php
                          }?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
                    <input style="font-size: 14px; text-align:right" type="text" class="form-control" id="qtdeLote" name="qtdeLote" required>
                  </div>
                  <div class="col-md-3">
                    <input class="btn btn-primary" type="submit" class="form-control" id="carregar" name="carregar" value="Carregar dados">
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" 
                  aria-expanded="false" aria-controls="flush-collapseTwo">
                  Clique aqui para visualizar dados dos materiais ingredientes e inserir dados do pedido
                </button>
              </h2>
        </form>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <div class="row g-4">
                    <?php
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if(!empty($busca['carregar'])){
                      $descrProd = $busca['descrProd']; $qtdeLote = $busca['qtdeLote'];?>
                      <div class="col-md-9">
                        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
                        <input style="font-size: 14px;" type="text" class="form-control" id="descrProd" name="descrProd" value="<?php echo $descrProd ?>" readonly>
                      </div>
                      <div class="col-md-3">
                        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
                        <input style="font-size: 14px; text-align:right" type="text" class="form-control" id="valor1" name="qtdeLote" 
                               value="<?php echo $qtdeLote . ' Kg' ?>" readonly>
                      </div><?php } ?>
                    <?php
                      if(isset($busca['carregar'])){
                        $prod = $busca['descrProd'];
                        $componente = $connDB->prepare("SELECT * FROM composicao_produto WHERE DESCRICAO_PRODUTO = :prod");
                        $componente->bindParam(':prod', $prod, PDO::PARAM_STR);
                        $componente->execute();
                      }
                      if(!empty($busca['carregar'])){ ?>
                        <div class="overflow-auto">
                          <p style="color: aqua">Componentes do Produto</p>
                          <table class="table table-dark table-hover">
                            <thead style="font-size: 12px">
                              <tr>
                                <th scope="col" style="width: 30%; color: gray">Descrição do Material</th>
                                <th scope="col" style="width: 15%; color: gray; text-align: center;">Qtde Necessária</th>
                                <th scope="col" style="width: 15%; color: gray; text-align: center;">Qtde Disponível</th>
                                <th scope="col" style="width: 15%; color: gray; text-align: center;">Observações</th>
                              </tr>
                            </thead>
                            <tbody style="height: 30%; font-size: 11px;">
                              <?php while($rowCpt = $componente->fetch(PDO::FETCH_ASSOC)){?>
                                <tr>
                                  <th style="width: 30%;"> 
                                    <?php echo $rowCpt['COMPONENTE'] . '<br>'; ?>
                                    <?php echo '[ Proporção: ' . $rowCpt['PROPORCAO']  . ' % ]'; ?>  </th>
                                  <td style="width: 15%; text-align: center; font-size: 14px"> 
                                    <?php $proporcao = ($busca['qtdeLote']) * ($rowCpt['PROPORCAO'] / 100); echo $proporcao . ' ' . $rowCpt['UNIDADE_MEDIDA']; ?> </td>
                                  <td style="width: 10%; text-align: center; font-size: 14px"> 
                                    <?php echo '2.500,00 ' . $rowCpt['UNIDADE_MEDIDA']; ?> </td>
                                </tr><?php } ?>
                            </tbody>
                          </table>
                        </div>
                      <?php }
                    ?>
                  </div>
                </div>
                <form action="" method="POST">
                  <div class="col-md-12">
                    <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
                    <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                     placeholder="" required autofocus>
                  </div>
                </form> 
              </div>
            </div>
           
        </div>

      </div>     



      <!-- Novo Cliente -->  
      <div class="tab-pane fade" id="newClient-tab-pane" role="tabpanel" aria-labelledby="newClient-tab" tabindex="0">
        <form class="row g-4" method="POST" action="#">
          <div class="col-md-12">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>

          <div class="col-md-2" style="padding: 3px">
            <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar2" name="salvar2" value="Salvar">
          </div>

          <div class="col-md-3" style="padding: 3px">
            <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar"
                   onclick="location.href='./20EntradaMaterial.php'">
          </div>
        </form>
      </div>

      <!-- Novo Produto -->  
        <div class="tab-pane fade" id="newProd-tab-pane" role="tabpanel" aria-labelledby="newProd-tab" tabindex="0">
          <form class="row g-4" method="POST" action="#">

              <div class="col-md-12">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Produto</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>

              <div class="col-md-2" style="padding: 3px">
                <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar2" name="salvar2" value="Salvar">
              </div>

              <div class="col-md-3" style="padding: 3px">
                <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar"
                       onclick="location.href='./20EntradaMaterial.php'">
              </div>
            </form>
        </div>
    </div>
  </div>
</div>