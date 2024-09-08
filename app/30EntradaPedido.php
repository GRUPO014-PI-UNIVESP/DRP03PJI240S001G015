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

//verifica quadro de funcionários para seleção do encarregado pela tarefa de recebimento do material
$query_encarregado1 = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = 'PRODUÇÃO' OR CREDENCIAL >= 4");
$query_encarregado1->execute();
$query_encarregado2 = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = 'PRODUÇÃO' OR CREDENCIAL >= 4");
$query_encarregado2->execute();

// capta dados do formulário
$confirma = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//verifica se foi preenchido formulário de entrada de material já existente no banco de dados

if(!empty($confirma['salvar1'])){

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
}
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
       time = setTimeout(deslogar, 60000);
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
          role="tab" aria-controls="manage-tab-pane" aria-selected="true">Entrada do Pedido</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="lab-tab" data-bs-toggle="tab" data-bs-target="#lab-tab-pane" type="button" 
          role="tab" aria-controls="lab-tab-pane" aria-selected="false">Cadastro de Novo Produto</button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent"><br>

      <!-- Entrada de Material -->
        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0">
          <form class="row g-4" method="POST" action="#">
            <div class="col-md-2">
              <label for="dataPedido" class="form-label" style="font-size: 10px; color:aqua">Data do Pedido</label>
              <input style="font-size: 12px;" type="date" class="form-control" id="dataPedido" name="dataPedido" required autofocus>
            </div>

            <div class="col-md-10">
              <label for="cliente" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
              <select style="font-size: 12px;" class="form-select" id="cliente" name="cliente">
                <option style="font-size: 12px" selected>Selecione o cliente</option>
                <?php
                  while($customer = $query_customer->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 12px"><?php echo $customer['RAZAO_SOCIAL']; ?></option> <?php
                  }?>
              </select>
            </div>

            <div class="col-md-12">
              <label for="descrProd" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
              <select style="font-size: 12px;" class="form-select" id="descrProd" name="descrProd">
                <option style="font-size: 12px" selected>Selecione o produto</option>
                <?php
                  // 
                  while($produto = $query_produto->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 12px"><?php echo $produto['DESCRICAO_PF']; ?></option> <?php
                  }?>
              </select>
            </div>

            <div class="col-md-3">
              <label for="numLote" class="form-label" style="font-size: 10px; color:aqua">No. do Lote</label>
              <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="numLote" name="numLote" required>
            </div>

            <div class="col-md-2">
              <label for="dataFabr" class="form-label" style="font-size: 10px; color:aqua">Data de Fabricação</label>
              <input style="font-size: 12px;" type="date" class="form-control" id="dataFabr" name="dataFabr">
            </div>

            <div class="col-md-2">
              <label for="dataVali" class="form-label" style="font-size: 10px; color:aqua">Data de Validade</label>
              <input style="font-size: 12px;" type="date" class="form-control" id="dataVali" name="dataVali">
            </div>

            <div class="col-md-3">
              <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Lote</label>
              <input style="font-size: 16px; text-align:right" type="number" class="form-control" id="valor1" name="qtdeLote" required>
            </div>

            <div class="col-md-2">
              <label for="uniMed" class="form-label" style="font-size: 10px; color:aqua">Unidade de Medida</label>
              <select style="font-size: 12px;" class="form-select" id="uniMed" name="uniMed">
                <option selected>Selecione</option>
                <option value="KG">KG</option>
                <option value="LT">LT</option>
                <option value="UN">UNIDADE</option>
              </select>
            </div>
           
            <div class="col-md-3">
              <label for="notaFiscal" class="form-label" style="font-size: 10px; color:aqua">Nota Fiscal</label>
              <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="notaFiscal" name="notaFiscal" required>
            </div>

            <div class="col-md-9" style="padding: 3px;"></div>

            <div class="col-md-2" style="padding: 3px;">
              <input style="width: 140px; text-align:center" class="btn btn-primary" type="submit" id="salvar1" name="salvar1" value="Confirmar">
            </div>
            
            <div class="col-md-3" style="padding: 3px;">
              <input style="width: 140px; text-align:center" class="btn btn-secondary" type="reset" id="reset1" name="reset1" value="Descartar" onclick="location.href='./30EntradaPedido.php'">
            </div>
          </form>
        </div>
        
      <!-- Novo Cadastro -->  
        <div class="tab-pane fade" id="lab-tab-pane" role="tabpanel" aria-labelledby="lab-tab" tabindex="0">
          <form class="row g-4" method="POST" action="#">

              <div class="col-md-2">
                <label for="dataPedido2" class="form-label" style="font-size: 10px; color:aqua">Data do Pedido</label>
                <input style="font-size: 12px" type="date" class="form-control" id="dataPedido2" name="dataPedido2" required autofocus>
              </div>

              <div class="col-md-10">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>

              <div class="col-md-12">
                <label for="descrProduto2" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
                <input style="font-size: 12px; text-transform: uppercase;" type="text" class="form-control" id="descrProduto2" name="descrProduto2" 
                       placeholder="" required>
              </div>

              <div class="col-md-3">
                <label for="numLote2" class="form-label" style="font-size: 10px; color:aqua">No. do Lote</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="numLote2" name="numLote2" required>
              </div>

              <div class="col-md-2">
                <label for="dataFabr2" class="form-label" style="font-size: 10px; color:aqua">Data de Fabricação</label>
                <input style="font-size: 12px;" type="date" class="form-control" id="dataFabr2" name="dataFabr2">
              </div>

              <div class="col-md-2">
                <label for="dataVali2" class="form-label" style="font-size: 10px; color:aqua">Data de Validade</label>
                <input style="font-size: 12px;" type="date" class="form-control" id="dataVali2" name="dataVali2">
              </div>

              <div class="col-md-3">
                <label for="qtdeLote2" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Lote</label>
                <input style="font-size: 16px; text-align:right" type="number" class="form-control" id="valor2" name="qtdeLote2" required>
              </div>

              <div class="col-md-2">
                <label for="uniMed2" class="form-label" style="font-size: 10px; color:aqua">Unidade de Medida</label>
                <select style="font-size: 12px;" class="form-select" id="uniMed2" name="uniMed2">
                  <option selected>Selecione</option>
                  <option value="KG">KG</option>
                  <option value="LT">LT</option>
                  <option value="UN">UNIDADE</option>
                </select>
              </div>

              <div class="col-md-3">
                <label for="notaFiscal2" class="form-label" style="font-size: 10px; color:aqua">Nota Fiscal</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="notaFiscal2" name="notaFiscal2">
              </div>

              <div class="col-md-9" style="padding: 3px"></div>

              <div class="col-md-2" style="padding: 3px">
                <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar2" name="salvar2" value="Confirmar">
              </div>

              <div class="col-md-3" style="padding: 3px">
                <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar" onclick="location.href='./20EntradaMaterial.php'">
              </div>
            </form>
        </div>
    </div>
  </div>
</div>