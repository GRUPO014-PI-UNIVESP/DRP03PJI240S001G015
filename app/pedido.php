<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Administrativo';
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
       time = setTimeout(deslogar, 300000);
     }
  };
  inactivityTime();
</script>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
                  Passo 1: Digite a quantidade e selecione o produto desejado
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#FasesPedido">
                <div class="accordion-body">
                  <form action="" method="POST" id="carregar">
                    <div class="row g-2">
                      <div class="col-md-3">
                        <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
                        <input style="font-size: 14px; text-align:right" type="number" class="form-control" id="qtdeLote" name="qtdeLote" required autofocus>
                      </div>
                      <div class="col-md-9">
                        <label for="nomeFantasia" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
                        <select style="font-size: 14px;" class="form-select" id="nomeFantasia" name="nomeFantasia" autofocus>
                          <option style="font-size: 14px" selected>Selecione o Produto</option>
                            <?php
                              //Pesquisa de descrição do PRODUTO para seleção
                              $query_produto = $connDB->prepare("SELECT NOME_FANTASIA FROM pf_tabela");
                              $query_produto->execute();
                              // inclui nome dos produtos como opções de seleção da tag <select>
                              while($produto = $query_produto->fetch(PDO::FETCH_ASSOC)){?>
                                <option style="font-size: 14px"><?php echo $produto['NOME_FANTASIA']; ?></option> <?php
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
                    </div><!-- fim da div class = row g-4 -->
                  </form>
                </div><!-- fim da div class = accordion body -->
              </div><!-- fim da div class = collapseOne -->
            </div><!-- fim da div class = accordion item 1 -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Passo 2: Verificar a disponibilidade dos materiais necessários
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#FasesPedido">
              <div class="accordion-body">
                <div class="row g-2"> <?php 
                  $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                  if(!empty($busca['carregar'])){
                    $nomeFantasia = $busca['nomeFantasia']; $qtdeLote = $busca['qtdeLote'];?>
                    <div class="col-md-9">
                      <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Nome do Produto</label>
                      <input style="font-size: 14px;" type="text" class="form-control" id="nomeFantasia" name="nomeFantasia" value="<?php echo $nomeFantasia ?>" readonly>
                    </div>
                    <div class="col-md-3">
                      <label for="qtdeLote" class="form-label" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
                      <input style="font-size: 14px; text-align:right" type="text" class="form-control" id="qtdeLote" name="qtdeLote" value="<?php echo $qtdeLote . ' Kg' ?>" readonly>
                    </div><?php
                  }
                  if(isset($busca['carregar'])){ $prod = $busca['nomeFantasia'];
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
                            <th scope="col" style="width: 10%; color: gray; text-align: center;">Condição do Estoque</th>
                            <th scope="col" style="width: 10%; color: gray; text-align: center;">Ação</th>
                          </tr>
                        </thead>

                        <tbody style="height: 30%; font-size: 11px;"><?php $insuficiente = '';
                          while($rowCpt = $componente->fetch(PDO::FETCH_ASSOC)){
                            // verifica o total em estoque do material e quantidade reservada por outros pedidos para calcular a disponibilidade
                            $estoque = $connDB->prepare("SELECT SUM(QTDE_ESTOQUE) AS estoque FROM mp_estoque WHERE DESCRICAO_MP = :material");
                            $estoque->bindParam(':material', $rowCpt['COMPONENTE'], PDO::PARAM_STR); $estoque->execute(); 
                            $result1 = $estoque->fetch(PDO::FETCH_ASSOC);

                            $reservado = $connDB->prepare("SELECT SUM(QTDE_RESERVADA) AS reservado FROM mp_estoque WHERE DESCRICAO_MP = :material");
                            $reservado->bindParam(':material', $rowCpt['COMPONENTE'], PDO::PARAM_STR); $reservado->execute(); 
                            $result2 = $reservado->fetch(PDO::FETCH_ASSOC);

                            $matNecessario = $busca['qtdeLote'] * ($rowCpt['PROPORCAO'] / 100);

                            $disponivel = $result1['estoque'] - $result2['reservado'];
                            if($disponivel > $matNecessario){ 
                              $barra = 'class="btn btn-success"'; 
                              $disp = 'Disponível'; 
                              $insuficiente = 0;
                            } else if($disponivel < $matNecessario){ 
                              $barra = 'class="btn btn-danger"'; 
                              $disp = 'Insuficiente'; $id = $rowCpt['COMPONENTE'];
                              $insuficiente = 1;}?>
                            <tr>
                              <td style="width: 30%;"><?php 
                                echo $rowCpt['COMPONENTE'] . '<br>'; 
                                echo '[ Proporção: ' . $rowCpt['PROPORCAO']  . ' % ]'; ?>
                              </td>
                              <td style="width: 10%; text-align: center; font-size: 16px; font-weight: bold;"><?php 
                                $proporcao = ($busca['qtdeLote']) * ($rowCpt['PROPORCAO'] / 100); 
                                echo $proporcao . ' ' . $rowCpt['UNIDADE_MEDIDA']; ?>
                              </td>
                              <td style="width: 10%; text-align: center; font-size: 16px; font-weight: bold;"><?php 
                                echo $disponivel . ' ' . $rowCpt['UNIDADE_MEDIDA']; ?>
                              </td>
                              <td style="text-align:center" >
                                <button <?php echo $barra ?> style="color: yellow"  disabled><?php echo $disp ?></button>
                              </td>
                              <td style="text-align:center" > <?php
                                if($disp == 'Disponível'){ ?>
                                  <button class="btn btn-secondary" disabled>Comprar</button> <?php
                                }
                                if($disp == 'Insuficiente'){
                                  //algoritmo provisório para estabelecer uma rotina de trabalho
                                  $condCompra = 'COMPRADO';
                                  $dHoje = date('Y-m-d', strtotime("+5 days"));
                                  $query_buy = $connDB->prepare("INSERT INTO mp_estoque (DATA_COMPRA, DESCRICAO_MP, SITUACAO_QUALI) 
                                                                        VALUES (:dataCompra, :descricao, :situacao)");
                                  $query_buy->bindParam(':dataCompra' , $dHoje               , PDO::PARAM_STR);
                                  $query_buy->bindParam(':descricao'  , $rowCpt['COMPONENTE'], PDO::PARAM_STR);
                                  $query_buy->bindParam(':situacao'   , $condCompra          , PDO::PARAM_STR);
                                  $query_buy->execute(); 
                                  // fim do algoritmo provisório ?>
                                  <button class="btn btn-warning" disabled>Comprar</button> <?php 
                                } ?>
                              </td>                                   
                            </tr><?php 
                          } ?>
                        </tbody>
                      </table>
                    </div><?php
                  } 
                  if($insuficiente == 1){ ?><div class="alert alert-warning" role="alert">Há componentes insuficientes para cobrir a quantidade necessária para produção! Faça a compra também.</div><?php
                  } else { ?><div class="alert alert-success" role="alert">Tudo OK! As quantidades são satisfatórias. </div><?php } ?> 
                </div><!-- fim da div class = row g-4 --> 
              </div><!-- fim da div class = accordion body -->
            </div><!-- fim da div class = collapse Two -->
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
                <div class="row g-2">
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
                        <form id="calendario" method="POST" action="#"> <?php  $diaSemana = date('w'); $nDias = 1; $vDias = 0; $verificaDataOcupada = date('Y-m-d'); $diaCal = date('d/m');
                          for($i = 1; $i <= 5; $i++){ ?><!-- Recursão para => 1: Sem-1, 2: Sem-2, 3: Sem-3, 4: Sem-4, 5: Sem-5.-->
                            <tr> <?php
                              for($j = 0; $j <=6; $j++){ ?><!-- Recursão para => 0: domingo, 1: segunda, 2: terça, 3: quarta, 4: quinta, 5: sexta, 6: sabado. -->
                                <td> <?php
                                  if($j < $diaSemana){ ?> <p style="font-size: 20px; "></p><br>
                                    <p style="font-size: 18px; color: grey; text-align: center">INDISPONÍVEL</p> <?php
                                  }
                                  if($diaSemana == $j){ ?>
                                    <p style="font-size: 20px; "><?php echo $diaCal; ?> </p><?php
                                      $diaSemana           = date('w'    , strtotime("+$nDias days"));
                                      $verificaDataOcupada = date('Y-m-d', strtotime("+$vDias days"));
                                      $diaCal              = date('d/m'    , strtotime("+$nDias days"));
                                      $nDias               = $nDias + 1; $vDias = $vDias +1; ?>
                                    <p style="font-size: 12px; text-align: center"> <?php  
                                      $fila = $connDB->prepare("SELECT * FROM fila_ocupacao WHERE DATA_AGENDA = :hoje");
                                      $fila->bindParam(':hoje', $verificaDataOcupada, PDO::PARAM_STR); $fila->execute();
                                      $rowFila = $fila->fetch(PDO::FETCH_ASSOC);
                                      if(!empty($rowFila['DATA_AGENDA'])){
                                        if($verificaDataOcupada == $rowFila['DATA_AGENDA']){ ?>
                                          <input type="checkbox" class="btn-check">
                                          <label class="btn btn-outline-warning" for="ocupado">OCUPADO</label><?php }
                                        else if($verificaDataOcupada != $rowFila['DATA_AGENDA']){ ?>
                                          <input type="checkbox" class="btn-check" id="agendar" name="agendar" onchange="this.form.submit()" autocomplete="on">
                                          <label class="btn btn-outline-success" for="agendar">AGENDAR</label><?php }
                                      } else { ?> <input type="checkbox" class="btn-check" id="agendar" name="agendar" onchange="this.form.submit()" autocomplete="on">
                                                  <label class="btn btn-outline-success" for="agendar">AGENDAR</label><?php }  ?>  
                                    </p> <?php  
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
                <div class="row g-2">
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
                    <?php
                      //Pesquisa por CLIENTES para seleção
                      $query_customer = $connDB->prepare("SELECT NOME_FANTASIA FROM pf_cliente");
                      $query_customer->execute();
                    ?>
                    <label for="cliente" class="form-label" style="font-size: 10px; color:aqua">Cliente</label>
                    <select style="font-size: 14px;" class="form-select" id="cliente" name="cliente" autofocus>
                      <option style="font-size: 14px" selected>Selecione o Cliente</option><?php
                        // inclui nome dos produtos como opções de seleção da tag <select>
                        while($cliente = $query_customer->fetch(PDO::FETCH_ASSOC)){?>
                          <option style="font-size: 14px"> <?php echo $cliente['NOME_FANTASIA']; ?></option> <?php
                        }?>
                    </select>
                  </div>
                </div> 
              </div>
            </div>
          </div><!-- fim da div class = accordion item 4 -->
        </div><!-- fim da div class = accordion id=FasesPedido -->
      </div><!-- fim da div class = tab-pane fade show... -->
      <div class="tab-pane fade" id="comercial-tab-pane" role="tabpanel" aria-labelledby="comercial-tab" tabindex="0"><br><br>
        <button type="button" class="btn btn-outline-info" style="width:300px" 
          onclick=""></button><br><br>
      </div>
      <div class="tab-pane fade" id="finance-tab-pane" role="tabpanel" aria-labelledby="finance-tab" tabindex="0" style="color: whitesmoke"><br><br>
        <button type="button" class="btn btn-outline-info" style="width:300px" 
          onclick=""></button><br><br>
      </div>
    </div> 
  </div>
</div>
