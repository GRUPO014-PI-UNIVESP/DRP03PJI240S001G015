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
  $salvar1 = $connDB->prepare("INSERT INTO pf_pedido (DATA_PEDIDO, CLIENTE, DESCRICAO_PF, NUMERO_LOTE_PF, DATA_FABRICACAO, DATA_VALIDADE, QTDE_LOTE_PF, UNIDADE_MEDIDA, NOTA_FISCAL_PF, RESPONSAVEL_REGISTRO)
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
  
  $salvar2      = $connDB->prepare("INSERT INTO pf_pedido (DATA_PEDIDO, CLIENTE, DESCRICAO_PF, NUMERO_LOTE_PF, DATA_FABRICACAO, DATA_VALIDADE, QTDE_LOTE_PF, UNIDADE_MEDIDA, NOTA_FISCAL_PF, RESPONSAVEL_REGISTRO)
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
       time = setTimeout(deslogar, 1800000);
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

      <!-- Entrada de Material -->
        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0" >
          <div class="buscaProduto" id="buscaProduto">
            <form class="row g-4" method="POST" action="#" onsubmit="submeter();">
              <div class="col-md-9">
                <label for="descrProd" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
                <select style="font-size: 16px;" class="form-select" id="descrProd" name="descrProd" value="" autofocus>
                  <option style="font-size: 16px" selected>Selecione o Produto</option>
                  <?php
                    // 
                    while($produto = $query_produto->fetch(PDO::FETCH_ASSOC)){?>
                      <option style="font-size: 16px"><?php echo $produto['DESCRICAO_PF']; ?></option> <?php
                    }?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
                <input style="font-size: 16px; text-align:right" type="text" class="form-control" id="valor1" name="qtdeLote" value="" required>
              </div>

              <div class="col-md-2" style="padding: 3px;">
                <input style="width: 140px; text-align:center" class="btn btn-primary" type="submit" id="pesquisar" name="pesquisar" 
                  value="Pesquisar" onclick="<script> </script>">
              </div>
            </form>
          </div>
            <br>
            <?php
              $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
              if(isset($busca['pesquisar'])){
                $prod = $busca['descrProd'];
                $componente = $connDB->prepare("SELECT * FROM composicao_produto WHERE DESCRICAO_PRODUTO = :prod");
                $componente->bindParam(':prod', $prod, PDO::PARAM_STR);
                $componente->execute();
              }
              if(!empty($busca['pesquisar'])){ ?>
              <div class="overflow-auto">
                <p style="color: aqua">Componentes do Produto</p>
                <table class="table table-dark table-hover">
                  <thead style="font-size: 12px">
                    <tr>
                      <th scope="col" style="width: 30%; color: gray">Descrição do Material</th>
                      <th scope="col" style="width: 15%; color: gray; text-align: center;">Quantidade Necessária</th>
                      <th scope="col" style="width: 15%; color: gray; text-align: center;">Quantidade Disponível</th>
                      <th scope="col" style="width: 10%; color: gray; text-align: center;">Unidade</th>
                      <th scope="col" style="width: 15%; color: gray; text-align: center;">Observações</th>
                    </tr>
                  </thead>
                  <tbody style="height: 30%; font-size: 11px;">
                    <?php while($rowCpt = $componente->fetch(PDO::FETCH_ASSOC)){?>
                    <tr>
                      <th style="width: 30%;"> 
                        <?php echo $rowCpt['COMPONENTE'] . '<br>'; ?>
                        <?php echo 'Proporção: ' . $rowCpt['PROPORCAO']  . ' %'; ?>  </th>
                      <td style="width: 15%; text-align: right; font-size: 14px"> 
                        <?php $proporcao = ($busca['qtdeLote']) * ($rowCpt['PROPORCAO'] / 100); echo $proporcao; ?> </td>
                      <td style="width: 10%; text-align: center; font-size: 14px"> 
                        <?php echo '2.500,00'; ?> </td>
                      <td style="width: 10%; text-align: center; font-size: 14px"> 
                        <?php echo $rowCpt['UNIDADE_MEDIDA']; ?> </td>
                      <td style="width: 15%">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          Observações
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Observações sobre o material</h1>
                              </div>
                              <div class="modal-body">
                                <?php echo $rowCpt['OBSERVACOES'] ?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Fechar</button>
                              </div>
                            </div>
                          </div>
                        </div>              
                      </td>
                    </tr><?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } ?>
          <div>
          <form action="" method="POST">
            <div class="col-md-8">
              <label for="cliente" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
              <select style="font-size: 12px;" class="form-select" id="cliente" name="cliente" value="">
                <option style="font-size: 12px" selected>Selecione o cliente</option>
                <?php
                  while($customer = $query_customer->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 12px"><?php echo $customer['RAZAO_SOCIAL']; ?></option> <?php
                  }?>
              </select>
            </div>
           
            <div class="col-md-2">
              <label for="dataPedido" class="form-label" style="font-size: 10px; color:aqua">Data do Pedido</label>
              <input style="font-size: 12px;" type="date" class="form-control" id="dataPedido" name="dataPedido" value="" required autofocus>
            </div>

            <div class="col-md-2">
              <label for="uniMed" class="form-label" style="font-size: 10px; color:aqua">Unidade de Medida</label>
              <select style="font-size: 12px;" class="form-select" id="uniMed" name="uniMed" value="<?php echo $uniMed ?>" >
                <option selected>Selecione</option>
                <option value="KG">KG</option>
                <option value="LT">LT</option>
                <option value="UN">UNIDADE</option>
              </select>
            </div>

            <div class="col-md-2" style="padding: 3px;"><br>
              <input style="width: 140px; text-align:center" class="btn btn-primary" type="submit" id="salvar1" name="salvar1" value="Verificar">
            </div>

            <div class="col-md-3" style="padding: 3px;"><br>
              <input style="width: 140px; text-align:center" class="btn btn-secondary" type="reset" id="reset1" name="reset1" value="Descartar" onclick="location.href='./30EntradaPedido.php'">
            </div>
          </form>
          <?php
            $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if(isset($busca['salvar1'])){
              $prod = $busca['descrProd'];
              $componente = $connDB->prepare("SELECT * FROM composicao_produto WHERE DESCRICAO_PRODUTO = :prod");
              $componente->bindParam(':prod', $prod, PDO::PARAM_STR);
              $componente->execute();
            }
          ?>
          <br>
          <?php if(!empty($busca['salvar1'])){ ?>
            <div class="overflow-auto">
              <p style="color: aqua">Componentes do Produto</p>
              <table class="table table-dark table-hover">
                <thead style="font-size: 12px">
                  <tr>
                    <th scope="col" style="width: 40%; color: gray">Descrição do Material</th>
                    <th scope="col" style="width: 10%; color: gray; text-align: center;">Proporção (%)</th>
                    <th scope="col" style="width: 15%; color: gray; text-align: center;">Quantidade Necessária</th>
                    <th scope="col" style="width: 10%; color: gray; text-align: center;">Unidade</th>
                    <th scope="col" style="width: 15%; color: gray; text-align: center;">Observações</th>
                  </tr>
                </thead>
                <tbody style="height: 30%; font-size: 11px;">
                  <?php while($rowCpt = $componente->fetch(PDO::FETCH_ASSOC)){?>
                  <tr>
                    <th style="width: 40%;"> 
                      <?php echo $rowCpt['COMPONENTE']; ?> </th>
                    <td style="width: 10%; text-align: center; font-size: 14px"> 
                      <?php echo $rowCpt['PROPORCAO']  . ' %'; ?> </td>
                    <td style="width: 15%; text-align: right; font-size: 14px"> 
                      <?php $proporcao = ($busca['qtdeLote']) * ($rowCpt['PROPORCAO'] / 100); echo $proporcao; ?> </td>
                    <td style="width: 10%; text-align: center; font-size: 14px"> 
                      <?php echo $rowCpt['UNIDADE_MEDIDA']; ?> </td>
                    <td style="width: 15%">
                      <!-- Button trigger modal -->
                      <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Observações
                      </button>
                      <!-- Modal -->
                      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Observações sobre o material</h1>
                            </div>
                            <div class="modal-body">
                              <?php echo $rowCpt['OBSERVACOES'] ?>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-info" data-bs-dismiss="modal">Fechar</button>
                            </div>
                          </div>
                        </div>
                      </div>              
                    </td>
                  </tr><?php } ?>
                </tbody>
              </table>
            </div>
          <?php } ?>
          <div class="col-md-9" style="padding: 3px;"></div>
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
                <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar" onclick="location.href='./20EntradaMaterial.php'">
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
                <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar" onclick="location.href='./20EntradaMaterial.php'">
              </div>
            </form>
        </div>
    </div>
  </div>
</div>