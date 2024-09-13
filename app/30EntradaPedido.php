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
<!-- Área Principal ----------------------------------------------------------------------------------------------------------------------------------------------------->
<div class="main">
  <div class="container-fluid">
    <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" 
          role="tab" aria-controls="manage-tab-pane" aria-selected="true">Registrar Pedido de Produto</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="newClient-tab" data-bs-toggle="tab" data-bs-target="#newClient-tab-pane" type="button" 
          role="tab" aria-controls="newClient-tab-pane" aria-selected="false">Cadastrar Novo Cliente</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="newProd-tab" data-bs-toggle="tab" data-bs-target="#newProd-tab-pane" type="button" 
          role="tab" aria-controls="newProd-tab-pane" aria-selected="false">Cadastrar Novo Produto</button>
      </li>
    </ul>
<!-- Registra Pedido ---------------------------------------------------------------------------------------------------------------------------------------------------->    
    <div class="tab-content" id="myTabContent"><br>
      <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0" >
        <form action="" method="POST" id="carregar">
          <div class="accordion" id="Fases">

            <!-- Primeira seção para selecionar produto -->
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Selecione o produto e digite a quantidade desejada
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#Fases">
                <div class="accordion-body row g-4">
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
                  <div class="col-md-4">
                    <input class="btn btn-success" type="submit" class="form-control" id="carregar" name="carregar"
                            value="Carregue os dados e verifique na seção abaixo" style="width:400px">
                  </div>
                  <?php
                    if(!empty($_POST['carregar'])){ ?>
                      <div class="col-md-8">
                        <span style="margin-left: 25%; color: yellow; border-radius: 4px;">Carregado com sucesso!</span>
                      </div>
                  <?php } ?>
                </div>
              </div>
            </div>

            <!-- Segunda seção para visualizar materiais e inserir dados do pedido -->
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Verificar disponibilidade dos materiais ingredientes do produto
                </button>
              </h2>
        </form>
              <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#Fases">
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
                                <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Necessária</th>
                                <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Disponível</th>
                                <th scope="col" style="width: 15%; color: gray; text-align: center;">Condição do Estoque</th>
                              </tr>
                            </thead>
                            <tbody style="height: 30%; font-size: 11px;">
                              <?php while($rowCpt = $componente->fetch(PDO::FETCH_ASSOC)){
                              // verifica o total em estoque do material e quantidade reservada 
                              // por outros pedidos para calcular a disponibilidade
                                $estoque = $connDB->prepare("SELECT SUM(QTDE_ESTOQUE) AS estoque FROM mp_estoque WHERE DESCRICAO_MP = :material");
                                $estoque->bindParam(':material', $rowCpt['COMPONENTE'], PDO::PARAM_STR);
                                $estoque->execute();
                                $result1 = $estoque->fetch(PDO::FETCH_ASSOC);
                                $reservado = $connDB->prepare("SELECT SUM(QTDE_RESERVADA) AS reservado FROM mp_estoque WHERE DESCRICAO_MP = :material");
                                $reservado->bindParam(':material', $rowCpt['COMPONENTE'], PDO::PARAM_STR);
                                $reservado->execute();
                                $result2 = $reservado->fetch(PDO::FETCH_ASSOC);
                                $disponivel = $result1['estoque'] - $result2['reservado'];
                                if($disponivel > $busca['qtdeLote']){
                                  $barra = 'background-color: green; color: yellow;'; $disp = 'Disponível';
                                } else if($disponivel < $busca['qtdeLote']){
                                  $barra = 'background-color: orange; color: red;'; $disp = 'Insuficiente';        
                                }
                              ?>
                                <tr>
                                  <th style="width: 30%;">
                                    <?php echo $rowCpt['COMPONENTE'] . '<br>'; ?>
                                    <?php echo '[ Proporção: ' . $rowCpt['PROPORCAO']  . ' % ]'; ?>  </th>
                                  <td style="width: 10%; text-align: center; font-size: 16px; font-weight: bold;"> 
                                    <?php $proporcao = ($busca['qtdeLote']) * ($rowCpt['PROPORCAO'] / 100); echo $proporcao . ' ' . $rowCpt['UNIDADE_MEDIDA']; ?> </td>
                                  <td style="width: 10%; text-align: center; font-size: 16px; font-weight: bold;"> 
                                    <?php echo $disponivel . ' ' . $rowCpt['UNIDADE_MEDIDA']; ?> </td>
                                  <td style="width: 15%; text-align: center; font-size: 16px; vertical-align: middle"> 
                                    <p style="width: 100%; height: 30px; border-radius: 6px; font-weight: bold; <?php echo $barra ?>"><?php echo $disp ?></p> </td>                                    
                                </tr><?php } ?>
                            </tbody>
                          </table>
                        </div>
                      <?php }
                    ?> 
                  </div>

                  <form action="" method="POST">
                    <div class="row g-4">
                      <div class="col-md-3">
                      <!-- Button trigger modal -->
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#fila" style="width: 180px; height: 80px">Fila de Produção</button>
                      </div>
                      <!-- Modal -->
                      <div class="modal fade" id="fila" tabindex="-1" aria-labelledby="filaLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="filaLabel">Fila de ocupação da planta de fabricação</h1><br>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="height: 575px">
                              <h6><?php $nDias = 30;echo 'Cronograma: de ' . date('d/m/Y') . ' até dia ' .  date('d/m/Y', strtotime("+$nDias days")); ?></h6>
                              <div class="row g-4">
                                <div class="overflow-auto">
                                  <table class="table table-dark table-hover">
                                    <thead style="font-size: 14px">
                                      <tr>
                                        <th scope="col" style="width: 10%; color: gray; text-align: center;">Data</th>
                                        <th scope="col" style="width: 10%; color: gray; text-align: center;">Situação</th>
                                        <th scope="col" style="width: 20%; color: gray; text-align: center;">No.Lote</th>
                                        <th scope="col" style="width: 60%; color: gray;">Produto / Cliente</th>
                                      </tr>
                                    </thead>
                                    <tbody style="height: 30%; font-size: 14px;">
                                      <?php $semana = array("Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab");
                                        for($i = 0; $i <= 30; $i++ ){
                                          $verif = date('Y-m-d', strtotime("+$i days"));
                                          $dia   = date('d/m', strtotime("+$i days"));
                                      ?>
                                      <tr>
                                        <th style="width: 12%; text-align: center">
                                          <?php echo $dia . ' [ ' . $semana[date('w', strtotime("+$i days"))] . ' ]' ?></th>
                                        <td style="width: 12%; text-align: center;">
                                          <?php
                                            $fila = $connDB->prepare("SELECT * FROM fila_ocupacao WHERE DATA_AGENDA = :hoje");
                                            $fila->bindParam(':hoje', $verif, PDO::PARAM_STR);
                                            $fila->execute();
                                            $rowFila = $fila->fetch(PDO::FETCH_ASSOC);
                                            if(!empty($rowFila['DATA_AGENDA'])){
                                              if($verif == $rowFila['DATA_AGENDA']){ echo 'OCUPADO';}else{ echo '';}
                                            }
                                          ?>
                                        </td>
                                        <td style="width: 16%; text-align: center;">
                                          <?php
                                            if(!empty($rowFila['DATA_AGENDA'])){
                                              if($verif == $rowFila['DATA_AGENDA']){ echo 'No.Lote';}else{ echo '';}
                                            }
                                          ?>
                                        </td>
                                        <td style="width: 60%;"></td>                                    
                                      </tr><?php } ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>                            
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                              <button type="button" class="btn btn-primary">Agendar</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <label for="dataEntrega" class="form-label" style="font-size: 10px; color:aqua">Data Reservada na Fila</label>
                        <input style="font-size: 17px; width: 150px" type="date" class="form-control" id="dataEntrega" name="dataEntrega">
                      </div>
                      <div class="col-md-3">
                        <label for="dataEntrega" class="form-label" style="font-size: 10px; color:aqua">Data Estimada para Entrega</label>
                        <input style="font-size: 17px; width: 150px" type="date" class="form-control" id="dataEntrega" name="dataEntrega">
                      </div>
                      <div class="col-md-3">
                        <label for="dataPedido" class="form-label" style="font-size: 10px; color:aqua">Data do Pedido</label>
                        <input style="font-size: 17px; width: 150px" type="date" class="form-control" id="dataPedido" name="dataPedido">
                      </div>
                      <div class="col-md-12">
                        <label for="cliente" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
                        <select style="font-size: 14px;" class="form-select" id="cliente" name="cliente" autofocus>
                          <option style="font-size: 14px" selected>Selecione o Cliente</option>
                            <?php
                            // inclui nome dos produtos como opções de seleção da tag <select>
                              while($cliente = $query_customer->fetch(PDO::FETCH_ASSOC)){?>
                                <option style="font-size: 14px"><?php echo $cliente['RAZAO_SOCIAL']; ?></option> <?php
                              }?>
                          </select>
                      </div>

                      <div class="col-md-2" style="padding: 3px">
                        <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar2" name="salvar2" value="Salvar">
                      </div>
                      <div class="col-md-3" style="padding: 3px">
                        <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar"
                               onclick="location.href='./20EntradaMaterial.php'">
                      </div>
                    </div> 
                  </form>
                </div>
              </div>
            </div>
          </div>          
      </div>    
<!---- Novo Cliente ----------------------------------------------------------------------------------------------------------------------------------------------------->  
      <div class="tab-pane fade" id="newClient-tab-pane" role="tabpanel" aria-labelledby="newClient-tab" tabindex="0">
        <form class="row g-4" method="POST" action="#">
          <div class="col-md-4">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia da Empresa</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-8">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Razão Social</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>

          <div class="col-md-2">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">CNPJ</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-2">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">I.E.</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-6">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Cidade</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-2">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Estado (U.F.)</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-12">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Endereço Completo</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-3">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Telefone de Contato</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-9">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">E-Mail</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-12">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Nome do Representante</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-2" style="padding: 3px">
            <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar2" name="salvar2" value="Salvar">
          </div>

          <div class="col-md-3" style="padding: 3px">
            <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar"
                   onclick="location.href='./30EntradaPedido.php'">
          </div>
        </form>
      </div>

<!----- Novo Produto ---------------------------------------------------------------------------------------------------------------------------------------------------->  
        <div class="tab-pane fade" id="newProd-tab-pane" role="tabpanel" aria-labelledby="newProd-tab" tabindex="0">
          <form class="row g-4" method="POST" action="#">

              <div class="col-md-4">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia do Produto</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" autofocus>
              </div>
              <div class="col-md-8">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>

              <div class="col-md-8">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Material de Composição 1</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>
              <div class="col-md-2">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Proporção na Composição [ % ]</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>
              <div class="col-md-2">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Unidade de Media</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>

              <div class="col-md-8">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Material de Composição n</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>
              <div class="col-md-2">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Proporção na Composição [ % ]</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>
              <div class="col-md-2">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Unidade de Media</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>

              <div class="col-md-2">
                <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Capacidade de Processamento</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                       placeholder="" required>
              </div>

              <div class="col-md-1">
              </div>
              <div class="col-md-9">
                <textarea name="observacoes" id="observacoes" style="width: 100%; height: 100px">Observações</textarea>
              </div>

              <br>
              <div class="col-md-2" style="padding: 3px; text-align: center">
                <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar3" name="salvar3" value="Salvar">
              </div>
              <br>
              <div class="col-md-2" style="padding: 3px; text-align: center">
                <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset3" name="reset3" value="Descartar"
                       onclick="location.href='./30EntradaPedido.php'">
              </div>
            </form>
        </div>
    </div>
  </div>
</div>