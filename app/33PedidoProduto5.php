<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Pedido de Produto'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function(){
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar(){ <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer(){ clearTimeout(time); time = setTimeout(deslogar, 69900000); }
  }; inactivityTime();
</script>
<style> 
.tabela1{ width: 300px ; height: 250px; overflow-y: scroll;}
.tabela2{ width: 980px; height: 250px; overflow-y: scroll;}
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div><h5>Pedido de Produto - Finalização</h5></div>   
    <div class="row g-5">
      <div class="col-md-1">
        <label for="numPedido" style="font-size: 10px; color:aqua;">Pedido No.</label>
        <p style="color:yellow; font-size: 13px; text-align: center; border-bottom: 2px solid whitesmoke"><?php echo $_SESSION['numPedido'] ?></p>
      </div>
      <div class="col-md-7">
        <label for="nomeProduto" style="font-size: 10px; color:aqua">Produto</label>
        <p style="color:yellow; font-size: 13px; border-bottom: 2px solid whitesmoke"><?php echo $_SESSION['nomeProduto'] ?></p>    
      </div>
      <div class="col-md-2">
        <label for="qtdeLote" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
        <p style="color:yellow; font-size: 16px; border-bottom: 2px solid whitesmoke; text-align: center;"><?php echo number_format($_SESSION['qtdeLote'], 0, ',', '.') . ' ' . $_SESSION['unidade'] ?></p> 
      </div>
      <div class="col-md-1"></div>
    </div><!-- Fim da div row -->
    <br>
    <div class="row g-0">
      <div class="col-md-10"><!-- Construção da tabela dos materiais ingredientes e disponibilidades -->
        <p style="color:aqua">Materiais Ingredientes</p>
        <div>
          <table class="table table-dark">
            <thead style="font-size: 12px">
              <tr>
                <th scope="col" style="width: 30%;">Ingrediente/Proporção</th>
                <th scope="col" style="width: 10%; text-align: right">Qtde Exigida</th>
                <th scope="col" style="width: 10%; text-align: right">Qtde Disponível</th>
                <th scope="col" style="width: 20%; text-align: center">Qtde Agendada</th>
                <th scope="col" style="width: 20%; text-align: center">Situação</th>
              </tr>
            </thead>
            <tbody style="height: 25%;">
              <?php
              $query_matDisponivel = $connDB->prepare('SELECT * FROM produtos WHERE PRODUTO = :nomeProd');
              $query_matDisponivel->bindParam(':nomeProd', $_SESSION['nomeProduto'], PDO::PARAM_STR);
              $query_matDisponivel->execute(); 
                while($rowMat = $query_matDisponivel->fetch(PDO::FETCH_ASSOC)){                 
                  $query_matLista = $connDB->prepare('SELECT * FROM materiais_estoque WHERE DESCRICAO = :nomeMat');
                  $query_matLista->bindParam(':nomeMat', $rowMat['MATERIAL_COMPONENTE'], PDO::PARAM_STR);
                  $query_matLista->execute();
                  $proporcao = $_SESSION['qtdeLote'] * ($rowMat['PROPORCAO_MATERIAL'] / 100);
                  while($dataMat = $query_matLista->fetch(PDO::FETCH_ASSOC)){ ?>
                    <tr>
                      <td scope="col" style="width: 30%; font-size: 11px;"> <?php echo $dataMat['DESCRICAO'] . ' [ ' . $rowMat['PROPORCAO_MATERIAL'] . '% ]' ?> </td>
                      <th scope="col" style="width: 10%; text-align: right; font-size: 13px;"> <?php echo number_format($proporcao, 0, ',', '.') . ' ' . $rowMat['UNIDADE'] ?> </th>        
                      <th scope="col" style="width: 10%; text-align: right; font-size: 13px;"> <?php echo number_format($dataMat['QTDE_ESTOQUE'], 0, ',', '.') . ' ' . $dataMat['UNIDADE'] ?> </th>        
                      <th scope="col" style="width: 10%; text-align: right; font-size: 13px;">
                        <?php $compra = ($proporcao + ($proporcao * 0.2)) - $dataMat['QTDE_ESTOQUE'];
                          echo number_format($compra, 0, ',', '.') . ' ' . $dataMat['UNIDADE'] ?>
                      </th>        
                      <?php 
                        $condicao = $dataMat['QTDE_ESTOQUE'] - $proporcao;
                        if($condicao > 0){ ?>
                          <td scope="col" style="width: 10%; text-align: center;">
                          <div class="alert alert-primary" role="alert" style="height: 45px; vertical-align: auto;"><p><?php echo 'SUFICIENTE' ?></p></div>
                          </td><?php
                        }
                        if($condicao < 0){ ?>
                          <td scope="col" style="width: 10%; text-align: center;">
                            <div class="alert alert-success" role="alert" style="height: 45px; vertical-align: auto;"><p><?php echo 'COMPRA AGENDADA!' ?></p></div>
                          </td><?php
                        } ?>
                    </tr><?php $etapa = 0; $situacao = 'COMPRA DO MATERIAL AGENDADO'; /*
                    $agenda = $connDB->prepare("INSERT INTO materiais_compra (ID_ESTOQUE, DESCRICAO, NUMERO_PEDIDO, PRODUTO, ETAPA_PROCESS,
                                                                                     DATA_PEDIDO, DATA_AGENDA, DATA_PRAZO, QTDE_PEDIDO, UNIDADE,
                                                                                     SITUACAO, CAPAC_PROCESS)
                                                        VALUES (:idEstoque, :descrMat, :numPedido, :nomeProd, :etapaPro, :dataPedido, :dataAgenda,
                                                        :dataPrazo, :qtdePedido, :uniMed, :situacao, :capaProcess)");
                    $agenda->bindParam(':idEstoque'  , $dataMat['ID_ESTOQUE']  , PDO::PARAM_INT);
                    $agenda->bindParam(':descrMat'   , $dataMat['DESCRICAO']   , PDO::PARAM_INT);
                    $agenda->bindParam(':numPedido'  , $_SESSION['numPedido']  , PDO::PARAM_INT);
                    $agenda->bindParam(':nomeProd'   , $_SESSION['nomeProduto'], PDO::PARAM_INT);
                    $agenda->bindParam(':etapaPro'   , $ETAPA                  , PDO::PARAM_INT);
                    $agenda->bindParam(':dataPedido' , $_SESSION['dataPedido'] , PDO::PARAM_INT);
                    $agenda->bindParam(':dataAgenda' , $dataMat['dataPedido']  , PDO::PARAM_INT);
                    $agenda->bindParam(':dataPrazo'  , $dataMat['dataEstimada'], PDO::PARAM_INT);
                    $agenda->bindParam(':qtdePedido' , $compra                 , PDO::PARAM_INT);
                    $agenda->bindParam(':uniMed'     , $dataMat['UNIDADE']     , PDO::PARAM_INT);
                    $agenda->bindParam(':situacao'   , $situacao               , PDO::PARAM_INT);
                    $agenda->bindParam(':capaProcess', $dataMat['ID_ESTOQUE']  , PDO::PARAM_INT);
                    $agenda->execute(); */
                  }
                }
              ?> 
            </tbody>               
          </table>
        </div><br>
        <form method="POST" id="dataSelecionada">
      <div class="row g-2">
        <div class="col-md-5" style="text-align: center"><br><!-- Descartar dados -->
          <button class="btn btn-danger" style="float:inline-end" onclick="location.href='./35DescartarPedido.php'">Descartar e Sair</button>
        </div>
        <div class="col-md-7"><br><!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#calendario">Verificar data disponível para Fabricação</button>
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
                    if($verificador == 0){ $media = 0; $nDiasBase = $nDiasBase + $media; $diaMaxi = date('d/m/Y', strtotime("+$nDiasBase days"));
                      $diaCal = date('d/m', strtotime("+$media days")); $diaHoje = date('d/m/Y', strtotime("+$media days"));                             
                    } else if($verificador >= 1){ $media = $xtend - $padrao; $nDiasBase = $nDiasBase + $media; $diaMaxi = date('d/m/Y', strtotime("+$nDiasBase days"));
                      $diaCal = date('d/m', strtotime("+$media days")); $diaHoje = date('d/m/Y', strtotime("+$media days"));                               
                    }?>
                    <table class="table table-dark table-bordered table caption-top">
                      <caption style="color: aqua"><?php echo 'Cronograma disponível: de [ ' . $diaHoje . ' até dia ' . $diaMaxi . ' ]';  ?></caption>
                      <thead><!-- início do cabeçalho da tabela calendario -->
                        <tr>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Domingo</th><th scope="col" style="width: 10%; text-align: center; color: darkgrey">Segunda</th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Terça  </th><th scope="col" style="width: 10%; text-align: center; color: darkgrey">Quarta </th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Quinta </th><th scope="col" style="width: 10%; text-align: center; color: darkgrey">Sexta  </th>
                          <th scope="col" style="width: 10%; text-align: center; color: darkgrey">Sabado </th>
                        </tr>
                      </thead><!-- fim do cabeçalho da tabela calendario -->              
                      <tbody><!-- início do corpo da tabela calendario -->
                        <style> td:hover{background-color: rgba(0, 0, 0, 0.5);} </style> <?php $diaSemana = date('w', strtotime("+$media days")); $nDias = $media + 1; 
                        $vDias = $media + 0; $verificaDataOcupada = date('Y-m-d');
                        for($i = 1; $i <= 5; $i++){ ?><!-- Recursão para => 1: Sem-1, 2: Sem-2, 3: Sem-3, 4: Sem-4, 5: Sem-5.-->
                          <tr> <?php
                            for($j = 0; $j <=6; $j++){ ?><!-- Recursão para => 0: domingo, 1: segunda, 2: terça, 3: quarta, 4: quinta, 5: sexta, 6: sabado. -->
                              <td> <?php
                                if($j < $diaSemana){ ?> <p style="font-size: 20px; "></p><br><p style="font-size: 18px; color: grey; text-align: center">INDISPONÍVEL</p> <?php }
                                if($diaSemana == $j){ ?>
                                  <p style="font-size: 18px; "><?php echo $diaCal; ?> </p><?php
                                    $diaSemana = date('w'  , strtotime("+$nDias days")); $verificaDataOcupada = date('Y-m-d'  , strtotime("+$vDias days"));                                   
                                    $diaCal    = date('d/m', strtotime("+$nDias days")); $nDias = $nDias + 1; $vDias = $vDias +1; ?>
                                  <p style="font-size: 12px; text-align: center"> <?php
                                    $fila = $connDB->prepare("SELECT * FROM pedidos_fila WHERE DATA_AGENDA = :hoje");
                                    $fila->bindParam(':hoje', $verificaDataOcupada, PDO::PARAM_STR); $fila->execute(); $rowFila = $fila->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($rowFila['DATA_AGENDA'])){
                                      if($verificaDataOcupada == $rowFila['DATA_AGENDA']){ ?>
                                        <input type="radio" class="btn-check"><label class="btn btn-outline-warning" for="ocupado">OCUPADO</label><?php }
                                      else if($verificaDataOcupada != $rowFila['DATA_AGENDA']){ ?>
                                        <input type="radio" class="btn-check" id="" name="" value=""><label class="btn btn-outline-success" for="">DISPONÍVEL</label><?php }
                                    } else { ?> 
                                      <input type="radio" class="btn-check" id="" name="" value=""><label class="btn btn-outline-success" for="">DISPONÍVEL</label><?php 
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
                  <input style="font-size: 20px; background: rgba(0,0,0,0.3)" type="date" class="form-control" id="dataSelecionada" name="dataSelecionada" autofocus required>
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
      </div>
      <div class="col-md-12"><br>
        <?php
          $confirmaAgenda = filter_input_array(INPUT_POST, FILTER_DEFAULT);         
          if(!empty($confirmaAgenda)){
            echo 'proximo passo, gravar as compras e finalizar pedido';
          }
        ?>
      </div>
    </div>
  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->