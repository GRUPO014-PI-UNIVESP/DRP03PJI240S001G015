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

$descrProd = ''; $qtdeLote = ''; $verify1 = '';


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
        <div class="accordion" id="FasesPedido">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
                Passo 1: Digite a quantidade e selecione o produto desejado
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#FasesPedido">
              <div class="accordion-body">
                <form action="" method="POST" id="carregar">
                  <div class="row g-4">
                    <div class="col-md-3">
                      <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
                      <input style="font-size: 14px; text-align:right" type="text" class="form-control" id="qtdeLote" name="qtdeLote" onkeypress="formatarMilhar(e)" pattern="[0-9]{4,8}" required autofocus>
                    </div>
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

                    <div class="col-md-4">
                      <input class="btn btn-success" type="submit" class="form-control" id="carregar" name="carregar" value="Carregue os dados e verifique na seção abaixo" style="width:400px">
                    </div> <?php
                      if(!empty($_POST['carregar'])){?>
                        <div class="col-md-8">
                          <span style="margin-left: 25%; color: yellow; border-radius: 4px;">Carregado com sucesso! Siga para o próximo passo</span>
                        </div><?php 
                      } ?>
                  </div>
                </form>
              </div>
            </div>
          </div><!-- fim da div class = accordion item 1 -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Passo 2: Verificar a disponibilidade dos materiais necessários
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#FasesPedido">
              <div class="accordion-body">
                <div class="row g-4"> <?php $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                  if(!empty($busca['carregar'])){
                    $descrProd = $busca['descrProd']; $qtdeLote = $busca['qtdeLote'];?>
                    <div class="col-md-9">
                      <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
                      <input style="font-size: 14px;" type="text" class="form-control" id="descrProd" name="descrProd" value="<?php echo $descrProd ?>" readonly>
                    </div>
                    <div class="col-md-3">
                      <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
                      <input style="font-size: 14px; text-align:right" type="text" class="form-control" id="qtdeLote" name="qtdeLote" value="<?php echo $qtdeLote . ' Kg' ?>" readonly>
                    </div><?php
                  }
                  if(isset($busca['carregar'])){ $prod = $busca['descrProd'];
                    $componente = $connDB->prepare("SELECT * FROM composicao_produto WHERE DESCRICAO_PRODUTO = :prod");
                    $componente->bindParam(':prod', $prod, PDO::PARAM_STR); $componente->execute();
                  }
                  if(!empty($busca['carregar'])){ ?>
                    <div class="overflow-auto"> <p style="color: aqua">Componentes do Produto</p>
                      <table class="table table-dark table-hover">
                        <thead style="font-size: 12px">
                          <tr>
                            <th scope="col" style="width: 30%; color: gray">Descrição do Material</th>
                            <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Necessária</th>
                            <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Disponível</th>
                            <th scope="col" style="width: 15%; color: gray; text-align: center;">Condição do Estoque</th>
                          </tr>
                        </thead>
                        <tbody style="height: 30%; font-size: 11px;"><?php 
                          while($rowCpt = $componente->fetch(PDO::FETCH_ASSOC)){
                            // verifica o total em estoque do material e quantidade reservada por outros pedidos para calcular a disponibilidade
                            $estoque = $connDB->prepare("SELECT SUM(QTDE_ESTOQUE) AS estoque FROM mp_estoque WHERE DESCRICAO_MP = :material");
                            $estoque->bindParam(':material', $rowCpt['COMPONENTE'], PDO::PARAM_STR); $estoque->execute(); $result1 = $estoque->fetch(PDO::FETCH_ASSOC);
                            $reservado = $connDB->prepare("SELECT SUM(QTDE_RESERVADA) AS reservado FROM mp_estoque WHERE DESCRICAO_MP = :material");
                            $reservado->bindParam(':material', $rowCpt['COMPONENTE'], PDO::PARAM_STR); $reservado->execute(); $result2 = $reservado->fetch(PDO::FETCH_ASSOC);
                            $disponivel = $result1['estoque'] - $result2['reservado'];
                            if($disponivel > $busca['qtdeLote']){ $barra = 'background-color: green; color: yellow;'; $disp = 'Disponível'; $insuficiente = 0;
                            } else if($disponivel < $busca['qtdeLote']){ $barra = 'background-color: orange; color: red;'; $disp = 'Insuficiente'; $insuficiente = 1;}?>
                            <tr>
                              <th style="width: 30%;"><?php echo $rowCpt['COMPONENTE'] . '<br>'; echo '[ Proporção: ' . $rowCpt['PROPORCAO']  . ' % ]'; ?></th>
                              <td style="width: 10%; text-align: center; font-size: 16px; font-weight: bold;"><?php $proporcao = ($busca['qtdeLote']) * ($rowCpt['PROPORCAO'] / 100); echo $proporcao . ' ' . $rowCpt['UNIDADE_MEDIDA']; ?></td>
                              <td style="width: 10%; text-align: center; font-size: 16px; font-weight: bold;"><?php echo $disponivel . ' ' . $rowCpt['UNIDADE_MEDIDA']; ?></td>
                              <td style="width: 15%; text-align: center; font-size: 16px; vertical-align: middle"><p style="width: 100%; height: 30px; border-radius: 6px; font-weight: bold; <?php echo $barra ?>"><?php echo $disp ?></p></td>                                    
                            </tr><?php 
                          } ?>
                        </tbody>
                      </table>
                    </div><?php
                  } 
                  if($insuficiente == 1){ ?><div class="alert alert-warning" role="alert">Há componentes insuficientes para cobrir a quantidade necessária para produção! Faça a compra também.</div><?php
                  } else { ?><div class="alert alert-success" role="alert">Tudo OK! Sem problemas com os materiais. </div><?php } ?> 
                </div>  
              </div>
            </div>
          </div><!-- fim da div class = accordion item 2 -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" 
                      aria-expanded="false" aria-controls="collapseThree">
                Passo 3: Verificar a fila de ocupação e agendar uma data de produção
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#FasesPedido">
              <div class="accordion-body">
                <div class="row g-4">
                  <div class="overflow-auto"> <?php
                    $nDias = 31; $diaHoje = date('d/m/Y'); $diaMaxi = date('d/m/Y', strtotime("+$nDias days"));?>
                    <table class="table table-dark table-bordered table caption-top">
                      <caption style="color: aqua">Ocupação da Planta <?php echo ' - Cronograma: de ' . $diaHoje . ' até dia ' . $diaMaxi;  ?></caption>
                      <thead><!-- início do cabeçalho da tabela calendario -->
                        <tr>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Domingo</th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Segunda</th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Terça  </th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Quarta </th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Quinta </th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Sexta  </th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Sabado </th>
                        </tr>
                      </thead><!-- fim do cabeçalho da tabela calendario -->
                      <?php function verificaVazio(){ } ?>

                      <tbody><!-- início do corpo da tabela calendario -->
                        <style>td:hover{background-color: rgba(0, 0, 0, 0.5);}</style>
                        <form id="calendario" method="#" action="#"> <?php $diaSemana = date('w'); $nDias = 1; $verificaDataOcupada = date('Y-m-d');  $a = date('d');
                          for($i = 1; $i <= 5; $i++){ ?><!-- Recursão para => 1: Sem-1, 2: Sem-2, 3: Sem-3, 4: Sem-4, 5: Sem-5.-->
                            <tr> <?php
                              for($j = 0; $j <=6; $j++){ ?><!-- Recursão para => 0: domingo, 1: segunda, 2: terça, 3: quarta, 4: quinta, 5: sexta, 6: sabado. -->
                                <td> <?php
                                    if($diaSemana == $j){ 
                                      echo $a ; $diaSemana = date('w', strtotime("+$nDias days"));
                                      $a = date('d', strtotime("+$nDias days")); $nDias = $nDias + 1;
                                    } ?>
                                </td> <?php 
                              } ?>    
                            </tr> <?php
                          } ?>
                        </form><!-- fim do form calendario -->
                      </tbody><!-- fim do corpo da tabela calendario -->
                    </table><!-- fim da tabela calendario -->
                  </div><!-- fim da div overflow -->
                </div><!-- fim da div row g-4 -->
              </div><!-- fim da div accordion body 3 -->
            </div><!-- fim da div CollapseThree -->
          </div><!-- fim da div class = accordion item 3 -->      

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Passo 4: Concluir pedido, confirmar e Salvar
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#FasesPedido">
              <div class="accordion-body">
                <div class="row g-4">
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
                      <option style="font-size: 14px" selected>Selecione o Cliente</option><?php
                        // inclui nome dos produtos como opções de seleção da tag <select>
                        while($cliente = $query_customer->fetch(PDO::FETCH_ASSOC)){?>
                          <option style="font-size: 14px"> <?php echo $cliente['RAZAO_SOCIAL']; ?></option> <?php
                        }?>
                    </select>
                  </div>
                </div> 
              </div>
            </div>
          </div><!-- fim da div class = accordion item 4 -->
        </div><!-- fim da div class = accordion id=FasesPedido -->
      </div><!-- fim da div class = tab-pane fade show... -->
 
<!---- Novo Cliente ----------------------------------------------------------------------------------------------------------------------------------------------------->  
      <div class="tab-pane fade" id="newClient-tab-pane" role="tabpanel" aria-labelledby="newClient-tab" tabindex="0">
        <form class="row g-4" method="POST" action="#" id="cadastroCliente">
          <div class="col-md-4">
            <label for="cliente2" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia da Empresa</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cliente2" name="cliente2" 
                   placeholder="" required>
          </div>
          <div class="col-md-8">
            <label for="razaoSocial" class="form-label" style="font-size: 10px; color:aqua">Razão Social</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="razaoSocial" name="razaoSocial" 
                   placeholder="" required>
          </div>

          <div class="col-md-2">
            <label for="cnpj" class="form-label" style="font-size: 10px; color:aqua">CNPJ</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cnpj" name="cnpj" 
                   placeholder="" required>
          </div>
          <div class="col-md-2">
            <label for="inscrEstadual" class="form-label" style="font-size: 10px; color:aqua">I.E.</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="inscrEstadual" name="inscrEstadual" 
                   placeholder="" required>
          </div>
          <div class="col-md-6">
            <label for="cidade" class="form-label" style="font-size: 10px; color:aqua">Cidade</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cidade" name="cidade" 
                   placeholder="" required>
          </div>
          <div class="col-md-2">
            <label for="estado" class="form-label" style="font-size: 10px; color:aqua">Estado (U.F.)</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="estado" name="estado" 
                   placeholder="" required>
          </div>
          <div class="col-md-12">
            <label for="endereco" class="form-label" style="font-size: 10px; color:aqua">Endereço Completo</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="endereco" name="endereco" 
                   placeholder="" required>
          </div>
          <div class="col-md-3">
            <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone de Contato</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="telefone" name="telefone" 
                   placeholder="" required>
          </div>
          <div class="col-md-9">
            <label for="email" class="form-label" style="font-size: 10px; color:aqua">E-Mail</label>
            <input style="font-size: 12px;" type="text" class="form-control" id="email" name="email" 
                   placeholder="" required>
          </div>
          <div class="col-md-12">
            <label for="nomeRepresentante" class="form-label" style="font-size: 10px; color:aqua">Nome do Representante</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="nomeRepresentante" name="nomeRepresentante" 
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
            <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia do Produto</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="nomeProduto" name="nomeProduto" 
                  placeholder="" autofocus>
          </div>
          <div class="col-md-8">
            <label for="descrProduto" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="descrProduto" name="descrProduto" 
                  placeholder="" required>
          </div>

          <div class="col-md-8">
            <label for="matComp" class="form-label" style="font-size: 10px; color:aqua">Material de Composição 1</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="matComp" name="matComp" 
                  placeholder="" required>
          </div>
          <div class="col-md-2">
            <label for="proporcao" class="form-label" style="font-size: 10px; color:aqua">Proporção na Composição [ % ]</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="proporcao" name="proporcao" 
                  placeholder="" required>
          </div>
          <div class="col-md-2">
            <label for="uniMed" class="form-label" style="font-size: 10px; color:aqua">Unidade de Media</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="uniMed" name="uniMed" 
                  placeholder="" required>
          </div>

          <div class="col-md-2">
            <label for="capProcess" class="form-label" style="font-size: 10px; color:aqua">Capacidade de Processamento</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="capProcess" name="capProcess" 
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