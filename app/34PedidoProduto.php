<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Pedido de Produto'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time;window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php';?> window.location.href = 'LogOut.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);}
  }; inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div><h5>Pedido de Produto</h></div><?php
      $query_produto = $connDB->prepare("SELECT * FROM produtos WHERE PRODUTO = :produto");
      $query_produto->bindParam(':produto', $_SESSION['nomeProduto'], PDO::PARAM_STR); 
      $query_produto->execute(); $rowProduto = $query_produto->fetch(PDO::FETCH_ASSOC); ?>
    <div class="row g-1" id="entradaProduto">
      <div class="col-md-2">
        <label for="pedidoNum" class="form-label" style="font-size: 10px; color:aqua">Pedido No.</label>
        <input style="font-size: 16px; text-align: center; color:yellow; background: rgba(0,0,0,0.3)" 
        type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['numPedido'] ?>" readonly>
      </div>
      <div class="col-md-2">
        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
        <input style="font-size: 16px; color:yellow; text-align:right; background: rgba(0,0,0,0.3)" 
        type="text" class="form-control" id="" name="" 
        value="<?php echo number_format($_SESSION['qtdeLote'], 0,  ',', '.')
                          . ' ' . $rowProduto['UNIDADE'] ?>" readonly>
      </div>
      <div class="col-md-8">
        <label for="" class="form-label" style="font-size: 10px; color:aqua">Produto</label>
        <input style="font-size: 16px; color:yellow; background: rgba(0,0,0,0.3)" 
        type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['nomeProduto'] ?>" readonly>
      </div>
    </div><!-- Fim da div row entradaProduto --><br>
    <div class="row g-1" id="tabelaMateriais"><h6>Lista dos Compostos do Produto e Disponibilidade</h6>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 30%; color: gray"                     >Descrição do Material</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Necessária    </th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Disponível      </th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Condição do Estoque</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Ação                 </th>
            </tr>
          </thead> <?php 
            $nomeProduto = $_SESSION['nomeProduto']; 
            $numPedido = $_SESSION['numPedido']; 
            $qtdeLote = $_SESSION['qtdeLote']; 
            $padrao = 1; 
            $xtend = 2; 
            $verificador = 0;
            $query_material = $connDB->prepare("SELECT * FROM produtos WHERE PRODUTO = :nomeProduto"); 
            $query_material->bindParam(':nomeProduto', $nomeProduto, PDO::PARAM_STR); 
            $query_material->execute(); ?>  
          <tbody> <?php            
            while($rowLista = $query_material->fetch(PDO::FETCH_ASSOC)){ 
              $contador = 1; $capacidadeProcess = $rowLista['CAPAC_PROCESS'];?>
              <tr>
                <td scope="col" style="width: 30%; font-size: 13px; color: yellow"><?php 
                  $descrMaterial = $rowLista['MATERIAL_COMPONENTE'];
                  echo $rowLista['MATERIAL_COMPONENTE'] . '<br>'; echo 'Proporção: [ ' . $rowLista['PROPORCAO_MATERIAL'] . ' %]'; ?>
                </td>
                <td scope="col" style="width: 10%; text-align: center; font-size: 18px; color: yellow"><?php 
                  $qtdeMaterial = $qtdeLote * ($rowLista['PROPORCAO_MATERIAL'] / 100);
                  echo number_format($qtdeMaterial, 0, ',', '.') . ' ' . $rowLista['UNIDADE']; ?>
                </td>
                <td scope="col" style="width: 10%; text-align: center; font-size: 18px; color:yellow"><?php
                  $query_Estoque = $connDB->prepare("SELECT ID_ESTOQUE, SUM(QTDE_ESTOQUE) AS TOTAL_ESTOQUE 
                                                            FROM materiais_estoque WHERE DESCRICAO = :descrMat");
                  $query_Estoque->bindParam(':descrMat', $descrMaterial, PDO::PARAM_STR); 
                  $query_Estoque->execute(); 
                  $resultEstoque = $query_Estoque->fetch(PDO::FETCH_ASSOC);

                  $query_reserva = $connDB->prepare("SELECT SUM(QTDE_RESERVA) AS TOTAL_RESERVA 
                                                            FROM materiais_reserva WHERE ID_ESTOQUE = :idEstoque");
                  $query_reserva->bindParam(':idEstoque', $resultEstoque['ID_ESTOQUE'], PDO::PARAM_INT); 
                  $query_reserva->execute(); 
                  $resultReserva = $query_reserva->fetch(PDO::FETCH_ASSOC);

                  $qtdeDisponivel = $resultEstoque['TOTAL_ESTOQUE'] - $resultReserva['TOTAL_RESERVA'];
                  echo number_format($qtdeDisponivel, 0, ',', '.') . ' ' . $rowLista['UNIDADE']; ?>
                </td> <?php
                  if($qtdeDisponivel >= $qtdeMaterial){
                    $barra = 'alert alert-success'; $alerta = 'DISPONÍVEL';
                  } else if($qtdeDisponivel < $qtdeMaterial){
                    $barra = 'alert alert-danger'; $alerta = 'INSUFICIENTE';                    
                  } ?>
                <td scope="col" style="width: 10%; text-align: center; font-size: 13px">
                  <div class="<?php echo $barra ?>" role="alert"><?php echo $alerta ?></div></td>
                <td scope="col" style="width: 15%; text-align: center;"><?php
                  if($alerta == 'DISPONIVEL'){ ?>
                    <div class="alert alert-success" role="alert" style="height: 50px"><p><?php echo 'Tudo OK!' ?></p></div> <?php
                  }
                  if($alerta == 'INSUFICIENTE' && $contador <= 1){ ?>
                    <div class="alert alert-warning" role="alert" style="height: 50px;"><p><?php echo 'Compra Agendada!' ?></p></div> <?php
                  } ?>
                </td>
              </tr> <?php 
              if($alerta == 'INSUFICIENTE'){ $verificador = $verificador + 1; }
            } ?>
          </tbody>
        </table>
      </div><!-- Fim da div overflow da tabela -->
    </div><!-- Fim da div row da tabela -->

    <form method="POST" id="dataSelecionada">
      <div class="row g-2">
        <div class="col-md-5" style="text-align: center"><br><!-- Descartar dados -->
          <button class="btn btn-danger" style="float:inline-end" onclick="location.href='./35DescartarPedido.php'">Descartar e Sair</button>
        </div>
        <div class="col-md-7"><br><!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calendario">
            Verificar Disponibilidade na fila da planta de fabricação</button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="calendario" tabindex="-1" aria-labelledby="calendarioLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="calendarioLabel">Calendário de disponibilidade da planta de fabricação</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" style="height: 400px"><!-- Calendário da Fila de Ocupação da Planta de Fabricação -->
                <div class="row g-1">
                  <div class="overflow-auto"> <?php $nDiasBase = 31;
                    if($verificador == 0){ 
                      $media = 0;
                      $nDiasBase = $nDiasBase + $media; 
                      $diaMaxi = date('d/m/Y', strtotime("+$nDiasBase days"));
                      $diaCal = date('d/m', strtotime("+$media days")); 
                      $diaHoje = date('d/m/Y', strtotime("+$media days"));                             
                    } else if($verificador >= 1){
                      $media = $xtend - $padrao; 
                      $nDiasBase = $nDiasBase + $media; $diaMaxi = date('d/m/Y', strtotime("+$nDiasBase days"));
                      $diaCal = date('d/m', strtotime("+$media days"));
                      $diaHoje = date('d/m/Y', strtotime("+$media days"));                               
                    }?>
                    <table class="table table-dark table-bordered table caption-top">
                      <caption style="color: aqua"><?php echo 'Cronograma disponível: de [ ' . $diaHoje . ' até dia ' . $diaMaxi . ' ]';  ?></caption>
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
                      <tbody><!-- início do corpo da tabela calendario -->
                        <style> td:hover{background-color: rgba(0, 0, 0, 0.5);} </style> 
                        <?php $diaSemana = date('w', strtotime("+$media days")); $nDias = $media + 1; 
                        $vDias = $media + 0; $verificaDataOcupada = date('Y-m-d');
                        for($i = 1; $i <= 5; $i++){ ?><!-- Recursão para => 1: Sem-1, 2: Sem-2, 3: Sem-3, 4: Sem-4, 5: Sem-5.-->
                          <tr> <?php
                            for($j = 0; $j <=6; $j++){ ?><!-- Recursão para => 0: domingo, 1: segunda, 2: terça, 3: quarta, 4: quinta, 5: sexta, 6: sabado. -->
                              <td> <?php
                                if($j < $diaSemana){ ?> 
                                  <p style="font-size: 20px; "></p><br>
                                  <p style="font-size: 18px; color: grey; text-align: center">INDISPONÍVEL</p> <?php }
                                if($diaSemana == $j){ ?>
                                  <p style="font-size: 18px; "><?php echo $diaCal; ?> </p><?php
                                    $diaSemana = date('w'  , strtotime("+$nDias days")); 
                                    $verificaDataOcupada = date('Y-m-d'  , strtotime("+$vDias days"));                                   
                                    $diaCal    = date('d/m', strtotime("+$nDias days")); 
                                    $nDias = $nDias + 1; $vDias = $vDias +1; ?>
                                  <p style="font-size: 12px; text-align: center"> <?php
                                    $fila = $connDB->prepare("SELECT * FROM pedidos_fila WHERE DATA_AGENDA = :hoje");
                                    $fila->bindParam(':hoje', $verificaDataOcupada, PDO::PARAM_STR); 
                                    $fila->execute(); $rowFila = $fila->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($rowFila['DATA_AGENDA'])){
                                      if($verificaDataOcupada == $rowFila['DATA_AGENDA']){ ?>
                                        <input type="radio" class="btn-check"><label class="btn btn-outline-warning" 
                                          for="ocupado">OCUPADO</label><?php }
                                      else if($verificaDataOcupada != $rowFila['DATA_AGENDA']){ ?>
                                        <input type="radio" class="btn-check" id="" name="" value="">
                                        <label class="btn btn-outline-success" for="">DISPONÍVEL</label><?php }
                                    } else { ?> 
                                      <input type="radio" class="btn-check" id="" name="" value="">
                                      <label class="btn btn-outline-success" for="">DISPONÍVEL</label><?php 
                                    } ?>  
                                  </p><?php                         
                                } ?>
                              </td><?php 
                            } ?>    
                          </tr><?php 
                        } ?>
                      </tbody><!-- fim do corpo da tabela calendario -->
                    </table><!-- fim da tabela calendario -->
                  </div><!-- fim da div overflow -->
                </div><!-- fim da div row g-4 -->
              </div><!-- fim do modal body -->
              <div class="modal-footer">
                <div class="col-md-2">
                  <label for="dataSelecionada" class="form-label" style="font-size: 10px; color:aqua">Selecione a Data na Fila</label>
                  <input style="font-size: 20px; background: rgba(0,0,0,0.3)" type="date" class="form-control" id="dataSelecionada" 
                         name="dataSelecionada" autofocus required>
                </div>
                <div class="col-md-2"><br>
                  <input class="btn btn-primary" type="submit" id="agendar" name="agendar" value="Confirmar Data" style="float: right">   
                </div>      
              </div><!-- fim do Footer -->
            </div><!-- fim do modal content -->
          </div><!-- fim do modal dialog -->
        </div><!-- fim do modal fade -->
      </div><!-- fim da div row do calendário -->       
    </form><br><?php $dataLivre = filter_input_array(INPUT_POST, FILTER_DEFAULT); ?>
    <form method="POST">
      <div class="row g-2"><?php
        if(!empty($dataLivre['agendar']) || !empty($dataLivre['dataSelecionada'])){
          $dataConvert = date('Y-m-d', strtotime($dataLivre['dataSelecionada']));
          $convert1 = time(); $convert2 = strtotime($dataConvert);  
          $_SESSION['diff'] = round(($convert2 - $convert1) / 86400);
          $_SESSION['dataAgendada'] = date('Y-m-d', strtotime($dataLivre['dataSelecionada']));
          $_SESSION['dataEntrega']  = date('Y-m-d', strtotime($_SESSION['dataAgendada']."+ 3 days")); ?>
          <div class="col-md-1"></div>
          <div class="col-md-2">
            <label for="dataAgenda" class="form-label" style="font-size: 10px; color:aqua">Data Agendada</label>
            <input style="font-size: 16px; text-align: center; color:yellow; background: rgba(0,0,0,0.3)" 
                   type="text" class="form-control" id="dataAgenda" name="dataAgenda" 
                   value="<?php echo date('d/m/Y', strtotime($_SESSION['dataAgendada'])) ?>" readonly>
          </div>
          <div class="col-md-8">
            <label for="cliente" class="form-label" style="font-size: 10px; color:aqua">Nome do Cliente</label>
            <select style="font-size: 16px;color:whitesmoke; background: rgba(0,0,0,0.3)" class="form-select" id="cliente" 
                    name="cliente" required>
              <option style="font-size: 16px; background: rgba(0,0,0,0.3); color: black" selected>
                Selecione o Cliente, caso não esteja relacionado será necessário fazer o cadastramento
              </option><?php
                //Pesquisa de descrição do PRODUTO para seleção
                $query_cliente = $connDB->prepare("SELECT DISTINCT NOME_FANTASIA FROM cliente");
                $query_cliente->execute();
                // inclui nome dos produtos como opções de seleção da tag <select>
                while($rowCliente = $query_cliente->fetch(PDO::FETCH_ASSOC)){?>
                  <option style="font-size: 16px; color:black; background: rgba(0,0,0,0.3)">
                    <?php echo $rowCliente['NOME_FANTASIA']; ?>
                  </option> <?php
                } ?>
            </select>
          </div>
          <div class="col-md-1"></div><br>
          <div class="col-md-2">
            <label for="dataPrazo" class="form-label" style="font-size: 10px; color:aqua">Data Estimada de Entrega</label>
            <input style="font-size: 16px; text-align: center; color: yellow; background: rgba(0,0,0,0.3)" 
                   type="text" class="form-control" id="dataPrazo" name="dataPrazo" 
                   value="<?php echo date('d/m/Y', strtotime($_SESSION['dataEntrega'])) ?>" readonly>
          </div>
          <div class="col-md-3"><br>
            <input style="width: 140px; float:inline-end" class="btn btn-secondary" type="reset" id="reset2" name="reset2" 
                   value="Descartar" onclick="location.href='./35DescartarPedido.php'">
          </div>
          <div class="col-md-3"><br>
            <input style="width: 180px;" class="btn btn-primary" type="submit" id="salvar3" name="salvar3" value="Confirmar e Salvar">
          </div>
          <div class="col-md-6"><br></div><div class="col-md-12"><br></div><div class="col-md-12"><br></div><div class="col-md-12"><br></div><?php
        }?>        
      </div>
    </form><?php
    $confirmaPedido = filter_input_array(INPUT_POST, FILTER_DEFAULT);    
    if(!empty($confirmaPedido['salvar3'])){ 
      $etapaProcess = 0; 
      $dataLimite   = date('Y-m-d', strtotime($_SESSION['dataAgendada']."- 3 days"));
      $nomeCliente  = $confirmaPedido['cliente']; 
      $dataPedido   = date('Y-m-d');
      $responsavel  = $_SESSION['nome_func'];
      $situacao     = 'PEDIDO REGISTRADO, PROVIDENCIANDO MATERIAIS';

      $registraPedido = $connDB->prepare("INSERT INTO pedidos (NUMERO_PEDIDO, CLIENTE, PRODUTO, QTDE_PEDIDO, UNIDADE, CAPAC_PROCESS,
                                                  DATA_PEDIDO, DATA_AGENDA, DATA_ENTREGA, ENCARREGADO_PEDIDO, ETAPA_PROCESS, SITUACAO)
                                                 VALUES (:numPedido, :nomeCliente, :nomeProduto, :qtdePedido, :uniMed, :capacidade,
                                                  :dataPedido, :dataAgenda, :dataEntrega, :responsavel, :etapa, :situacao)");      
      $registraPedido->bindParam(':numPedido'  , $numPedido               , PDO::PARAM_INT);
      $registraPedido->bindParam(':nomeCliente', $nomeCliente             , PDO::PARAM_STR);
      $registraPedido->bindParam(':nomeProduto', $nomeProduto             , PDO::PARAM_STR);
      $registraPedido->bindParam(':qtdePedido' , $qtdeLote                , PDO::PARAM_STR);
      $registraPedido->bindParam(':uniMed'     , $rowProduto['UNIDADE']   , PDO::PARAM_STR);
      $registraPedido->bindParam(':capacidade' , $_SESSION['capacidade']  , PDO::PARAM_INT);
      $registraPedido->bindParam(':dataPedido' , $dataPedido              , PDO::PARAM_STR);
      $registraPedido->bindParam(':dataAgenda' , $_SESSION['dataAgendada'], PDO::PARAM_STR);
      $registraPedido->bindParam(':dataEntrega', $_SESSION['dataEntrega'] , PDO::PARAM_STR); 
      $registraPedido->bindParam(':responsavel', $responsavel             , PDO::PARAM_STR);    
      $registraPedido->bindParam(':etapa'      , $etapaProcess            , PDO::PARAM_INT);      
      $registraPedido->bindParam(':situacao'   , $situacao                , PDO::PARAM_STR);
      $registraPedido->execute();

      $alocaFila = $connDB->prepare("INSERT INTO pedidos_fila (NUMERO_PEDIDO, DATA_AGENDA, PRODUTO, QTDE_LOTE, CAPAC_PROCESS, SITUACAO)
                                            VALUES (:numPedido, :dataFabri, :nomeProduto, :qtdeLote, :capaProcess, :situacao)");
      $alocaFila->bindParam(':numPedido'  , $numPedido               , PDO::PARAM_INT);
      $alocaFila->bindParam(':dataFabri'  , $_SESSION['dataAgendada'], PDO::PARAM_STR);      
      $alocaFila->bindParam(':nomeProduto', $nomeProduto             , PDO::PARAM_STR);
      $alocaFila->bindParam(':qtdeLote'   , $qtdeLote                , PDO::PARAM_STR);
      $alocaFila->bindParam(':capaProcess', $_SESSION['capacidade']  , PDO::PARAM_STR);
      $alocaFila->bindParam(':situacao'   , $situacao                , PDO::PARAM_STR);      
      $alocaFila->execute();
     
      $completaCompra = $connDB->prepare("UPDATE materiais_compra SET DATA_AGENDA = :dataAgenda , DATA_PRAZO = :dataLimite
                                                 WHERE NUMERO_PEDIDO = :numPedido");       
      $completaCompra->bindParam('dataAgenda', $_SESSION['dataAgendada'], PDO::PARAM_STR);
      $completaCompra->bindParam('dataLimite', $dataLimite              , PDO::PARAM_STR);
      $completaCompra->bindParam(':numPedido', $numPedido               , PDO::PARAM_INT);
      $completaCompra->execute();

      $buscaCode = $connDB->prepare("SELECT N_PRODUTO FROM produtos WHERE PRODUTO = :nomeProd");
      $buscaCode->bindParam(':nomeProd', $nomeProduto, PDO::PARAM_STR);
      $buscaCode->execute();
      $code = $buscaCode->fetch(PDO::FETCH_ASSOC);

      //definição de hora local
      date_default_timezone_set('America/Sao_Paulo');
      $dataPedido = date('Y-m-d H:i');
      $marcaData = $connDB->prepare("INSERT INTO historico_tempo (ID_PRODUTO, NUMERO_PEDIDO, INICIO) VALUES (:idProd, :numPed, :dataPe)");
      $marcaData->bindParam(':idProd', $code['N_PRODUTO'], PDO::PARAM_INT);
      $marcaData->bindParam(':numPed', $numPedido, PDO::PARAM_INT);
      $marcaData->bindParam(':dataPe', $dataPedido, PDO::PARAM_STR);
      $marcaData->execute();

      header('Location: ./33PedidoProduto.php');
    } ?>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->