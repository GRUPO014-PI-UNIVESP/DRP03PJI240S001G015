<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Pedido de Produto';
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
        time = setTimeout(deslogar, 36000000);
    }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div>
      <h5>Pedido de Produto</h>
    </div>
    <div class="row g-1" id="entradaProduto">
      <div class="col-md-2">
        <label for="pedidoNum" class="form-label" style="font-size: 10px; color:aqua">Pedido No.</label>
        <input style="font-size: 14px; text-align: center; color:yellow" type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['numPedido'] ?>" readonly>
      </div>
      <div class="col-md-2">
        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
        <input style="font-size: 14px; color:yellow; text-align:right" type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['qtdeLote'] . ' Kg' ?>" readonly>
      </div>
      <div class="col-md-8">
        <label for="" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
        <input style="font-size: 14px; color:yellow;" type="text" class="form-control" id="" name="" value="<?php echo $_SESSION['nomeProduto'] ?>" readonly>
      </div>
    </div><!-- Fim da div row entradaProduto --><br>

    <div class="row g-1" id="tabelaMateriais">
      <h6>Lista dos Compostos do Produto e Disponibilidade</h6>
      <div class="overflow-auto">
        <table class="table table-dark table-hover">
          <thead style="font-size: 12px">
            <tr>
              <th scope="col" style="width: 30%; color: gray">Descrição do Material</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Necessária</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Qtde Disponível</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Condição do Estoque</th>
              <th scope="col" style="width: 10%; color: gray; text-align: center;">Ação</th>
            </tr>
          </thead> <?php
            $nomeProduto = $_SESSION['nomeProduto'];
            $qtdeLote    = $_SESSION['qtdeLote'];
            $numPedido   = $_SESSION['numPedido'];
            $verificador = 0;
            $query_material = $connDB->prepare("SELECT * FROM pf_tabela WHERE NOME_PRODUTO = :nomeProduto");
            $query_material->bindParam(':nomeProduto', $nomeProduto, PDO::PARAM_STR); $query_material->execute(); ?>
          <tbody> <?php
            while($rowLista = $query_material->fetch(PDO::FETCH_ASSOC)){ ?>
              <tr>
                <td scope="col" style="width: 30%; font-size: 13px; color: yellow"> <?php
                  $descrMaterial = $rowLista['DESCRICAO_MP'];
                  echo $rowLista['DESCRICAO_MP'] . '<br>';
                  echo 'Proporção: [ ' . $rowLista['PROPORCAO_MATERIAL'] . ' %]'; ?>
                </td>

                <td scope="col" style="width: 10%; text-align: center; font-size: 18px; color: yellow"> <?php
                  $qtdeMaterial = $qtdeLote * ($rowLista['PROPORCAO_MATERIAL'] / 100);
                  echo $qtdeMaterial . ' ' . $rowLista['UNIDADE_MEDIDA']; ?>
                </td>

                <td scope="col" style="width: 10%; text-align: center; font-size: 18px; color:yellow"> <?php
                  $query_disponivel = $connDB->prepare("SELECT SUM(QTDE_ESTOQUE) AS estoque, SUM(QTDE_RESERVADA) AS reservado 
                                                               FROM mp_estoque WHERE DESCRICAO_MP = :material");
                  $query_disponivel->bindParam(':material', $descrMaterial, PDO::PARAM_STR);
                  $query_disponivel->execute(); $resultado = $query_disponivel->fetch(PDO::FETCH_ASSOC);
                  $qtdeDisponivel = $resultado['estoque'] - $resultado['reservado'];
                  echo $qtdeDisponivel . ' ' . $rowLista['UNIDADE_MEDIDA']; ?>
                </td>
                <?php
                  if($qtdeDisponivel >= $qtdeMaterial){
                    $barra = 'alert alert-success';
                    $alerta = 'DISPONÍVEL';
                  } else if($qtdeDisponivel < $qtdeMaterial){
                    $barra = 'alert alert-danger';
                    $alerta = 'INSUFICIENTE';                    
                  }
                ?>
                <td scope="col" style="width: 10%; text-align: center; font-size: 13px">
                  <div class="<?php echo $barra ?>" role="alert">
                    <?php echo $alerta ?>
                  </div>
                </td>

                <td scope="col" style="width: 15%; text-align: center;"><?php
                  if($alerta == 'DISPONIVEL'){ ?>
                    <div class="alert alert-success" role="alert">
                      <?php echo 'Tudo OK!' ?>
                    </div> <?php
                  } else if($alerta == 'INSUFICIENTE'){ ?>
                    <div class="alert alert-warning" role="alert">
                      <?php echo 'Compra Agendada!' ?>
                    </div> <?php
                    $dataAgenda = date('Y-m-d');
                    $situacao   = 'COMPRA AGENDADA';
                    $compra = $connDB->prepare("INSERT INTO agenda_compra (DESCRICAO_MP, PEDIDO_NUM, NOME_PRODUTO, DATA_AGENDA, QTDE_PEDIDO, SITUACAO_QUALI)
                                                                          VALUES (:descrMaterial, :numPedido, :nomeProduto, :dataAgenda, :qtdePedido, :situacao)");
                    $compra->bindParam(':descrMaterial', $descrMaterial, PDO::PARAM_STR);
                    $compra->bindParam(':numPedido'    , $numPedido    , PDO::PARAM_STR);
                    $compra->bindParam(':nomeProduto'  , $nomeProduto  , PDO::PARAM_STR);
                    $compra->bindParam(':dataAgenda'   , $dataAgenda   , PDO::PARAM_STR);
                    $compra->bindParam(':qtdePedido'   , $qtdeMaterial , PDO::PARAM_STR);
                    $compra->bindParam(':situacao'     , $situacao     , PDO::PARAM_STR);
                    $compra->execute();
                  } ?>
                </td>
              </tr> <?php
              if($alerta == 'INSUFICIENTE'){
                $verificador = $verificador + 1;
              }
            } ?>
          </tbody>
        </table>
      </div><!-- Fim da div overflow da tabela -->
    </div><!-- Fim da div row da tabela -->

    <form method="POST" id="dataSelecionada">
      <div class="row g-2">
        <div class="col-md-5"><br>
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Verificar Disponibilidade na fila da planta de fabricação
          </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Calendário de disponibilidade da planta de fabricação</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <!-- Calendário da Fila de Ocupação da Planta de Fabricação -->
                <div class="row g-1">
                  <div class="overflow-auto"> <?php $nDiasBase = 31;
                    if($verificador == 0){
                      $xtend       = 0;
                      $nDiasBase   = $nDiasBase + $xtend;
                      $diaCal      = date('d/m'  , strtotime("+$xtend days"));
                      $diaHoje     = date('d/m/Y', strtotime("+$xtend days"));
                      $diaMaxi     = date('d/m/Y', strtotime("+$nDiasBase days"));        
                    } else if($verificador >= 1){
                      $xtend       = 10;
                      $nDiasBase   = $nDiasBase + $xtend;
                      $diaCal      = date('d/m'  , strtotime("+$xtend days"));
                      $diaHoje     = date('d/m/Y', strtotime("+$xtend days"));
                      $diaMaxi     = date('d/m/Y', strtotime("+$nDiasBase days"));         
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
                        <style>td:hover{background-color: rgba(0, 0, 0, 0.5);}</style> <?php
                        $diaSemana = date('w', strtotime("+$xtend days")); 
                        $nDias = $xtend + 1; 
                        $vDias = $xtend + 0; 
                        $verificaDataOcupada = date('Y-m-d');

                        for($i = 1; $i <= 5; $i++){ ?><!-- Recursão para => 1: Sem-1, 2: Sem-2, 3: Sem-3, 4: Sem-4, 5: Sem-5.-->
                          <tr> <?php
                            for($j = 0; $j <=6; $j++){ ?><!-- Recursão para => 0: domingo, 1: segunda, 2: terça, 3: quarta, 4: quinta, 5: sexta, 6: sabado. -->
                              <td> <?php
                                if($j < $diaSemana){ ?> <p style="font-size: 20px; "></p><br>
                                  <p style="font-size: 18px; color: grey; text-align: center">INDISPONÍVEL</p> <?php
                                }
                                if($diaSemana == $j){ ?>
                                  <p style="font-size: 18px; "><?php echo $diaCal; ?> </p><?php
                                    $diaSemana           = date('w'      , strtotime("+$nDias days"));
                                    $verificaDataOcupada = date('Y-m-d'  , strtotime("+$vDias days"));
                                    $diaCal              = date('d/m'    , strtotime("+$nDias days"));
                                    $nDias               = $nDias + 1; $vDias = $vDias +1; ?>

                                  <p style="font-size: 12px; text-align: center"> <?php
                                    $fila = $connDB->prepare("SELECT * FROM fila_ocupacao WHERE DATA_AGENDA = :hoje");
                                    $fila->bindParam(':hoje', $verificaDataOcupada, PDO::PARAM_STR); $fila->execute();
                                    $rowFila = $fila->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($rowFila['DATA_AGENDA'])){
                                      if($verificaDataOcupada == $rowFila['DATA_AGENDA']){ ?>
                                        <input type="radio" class="btn-check">
                                        <label class="btn btn-outline-warning" for="ocupado">OCUPADO</label><?php }
                                      else if($verificaDataOcupada != $rowFila['DATA_AGENDA']){ ?>
                                        <input type="radio" class="btn-check" id="" name="" value="">
                                        <label class="btn btn-outline-success" for="">DISPONÍVEL</label><?php }
                                    } else { ?> 
                                      <input type="radio" class="btn-check" id="" name="" value="">
                                      <label class="btn btn-outline-success" for="">DISPONÍVEL</label><?php 
                                    }  ?>  
                                  </p> <?php                         
                                } ?>
                              </td> <?php 
                            } ?>    
                          </tr> <?php 
                        } ?>
                      </tbody><!-- fim do corpo da tabela calendario -->
                    </table><!-- fim da tabela calendario -->
                  </div><!-- fim da div overflow -->
                </div><!-- fim da div row g-4 -->
              </div><!-- fim do modal body -->

              <div class="modal-footer">
                <div class="col-md-2">
                  <label for="dataSelecionada" class="form-label" style="font-size: 10px; color:aqua">Selecione a Data na Fila</label>
                  <input style="font-size: 14px;" type="date" class="form-control" id="dataSelecionada" name="dataSelecionada" required autofocus>
                </div>
                <input class="btn btn-primary" type="submit" id="agendar" name="agendar" value="Confirmar Data" >         
              </div><!-- fim do Footer -->
            </div><!-- fim do modal content -->
          </div><!-- fim do modal dialog -->
        </div><!-- fim do modal fade -->
      </div><!-- fim da div row do calendário -->       
    </form><?php
    $dataLivre = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($dataLivre['dataSelecionada'])){
      $prazo = $xtend + 7;
      $dataEntrega  = date('d/m/Y', strtotime("+$prazo days"));?>
      <div class="row g-3">
        <div class="col-md-2"><br>
          <label for="dataAgendada" class="form-label" style="font-size: 10px; color:aqua">Data Agendada</label>
          <input style="font-size: 14px; text-align: center" type="text" class="form-control" id="dataAgendada" name="dataAgendada"
                 value="<?php echo $dataLivre['dataSelecionada'] ?>">
        </div>
        <div class="col-md-2"><br>
          <label for="dataPrazo" class="form-label" style="font-size: 10px; color:aqua">Data Estimada de Entrega</label>
          <input style="font-size: 14px; text-align: center" type="text" class="form-control" id="dataPrazo" name="dataPrazo"
                 value="<?php echo $dataEntrega ?>">
        </div>
      </div>
    <?php
    }
    ?>

  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->