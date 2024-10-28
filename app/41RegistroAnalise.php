<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Registro de Análise'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time;window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php';?> window.location.href = 'LogOut.php';}
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 600000);}
  }; inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
      <!-- Etiqueta das Abas -->
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="entrada-tab" data-bs-toggle="tab" data-bs-target="#entrada-tab-pane" type="button" role="tab" aria-controls="entrada-tab-pane" aria-selected="true">Entrada de Dados</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="ET-tab" data-bs-toggle="tab" data-bs-target="#ET-tab-pane" type="button" role="tab" aria-controls="ET-tab-pane" aria-selected="false">Especificações</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="MA-tab" data-bs-toggle="tab" data-bs-target="#MA-tab-pane" type="button" role="tab" aria-controls="MA-tab-pane" aria-selected="false">Metodologia</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="PQ-tab" data-bs-toggle="tab" data-bs-target="#PQ-tab-pane" type="button" role="tab" aria-controls="PQ-tab-pane" aria-selected="false">Procedimentos</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="RE-tab" data-bs-toggle="tab" data-bs-target="#RE-tab-pane" type="button" role="tab" aria-controls="RE-tab-pane" aria-selected="false">Referências</button>
      </li>        
    </ul>

    <!-- Análises de Matérias Primas e Insumos -->
    <div class="tab-content" id="myTabContent">
      <!-- Entrada de Dados -->
      <div class="tab-pane fade show active" id="entrada-tab-pane" role="tabpanel" aria-labelledby="entrada-tab" tabindex="0"><br>
        <div class="row g-1"> <h6>Informações do Material Analisado</h6>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="dataRegistro" name="dataRegistro" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo date('d/m/Y') ?>" readonly>
              <label for="dataRegistro" style="color: aqua; font-size: 12px; background: none">Data de Registro</label> <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nLoteInterno" name="nLoteInterno" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['nLoteI'] ?>" readonly>
              <label for="nLoteInterno" style="color: aqua; font-size: 12px; background: none">No.de Lote Interno</label> <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nLoteFornecedor" name="nLoteFornecedor" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['nLoteF'] ?>" readonly>
              <label for="nLoteFornecedor" style="color: aqua; font-size: 12px; background: none">No.de Lote do Fornecedor</label> <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="fornecedor" name="fornecedor" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['fornecedor']  ?>" readonly>
              <label for="fornecedor" style="color: aqua; font-size: 12px; background: none">Fornecedor</label> <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="dataFabri" name="dataFabri" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo date('d/m/Y', strtotime($_SESSION['dataFabri'] )) ?>" readonly>
              <label for="dataFabri" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label> <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="dataVali" name="dataVali" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo date('d/m/Y', strtotime($_SESSION['dataVali'] )) ?>" readonly>
              <label for="dataVali" style="color: aqua; font-size: 12px; background: none">Data de Validade</label> <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="notaFiscal" name="notaFiscal" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['notaFiscal']  ?>" readonly>
              <label for="notaFiscal" style="color: aqua; font-size: 12px; background: none">Nota Fiscal</label> <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="descrMat" name="descrMat" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['descrMat']  ?>" readonly>
              <label for="descrMat" style="color: aqua; font-size: 12px; background: none">Descrição do Material</label> <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <h6>Dados Analisados</h6>
          <div class="col-md-6">
            <div class="row g-1">
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="aspecto" name="aspecto" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center;" value="<?php echo $_SESSION['aspecto'] ?>" readonly>
                  <label for="aspecto" style="color: aqua; font-size: 12px; background: none">Aspecto</label> <p style="font-size: 11px; color: grey"></p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="cor" name="cor" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center;" value="<?php echo $_SESSION['cor'] ?>" readonly>
                  <label for="cor" style="color: aqua; font-size: 12px; background: none">Coloração</label> <p style="font-size: 11px; color: grey"></p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="odor" name="odor" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center;" value="<?php echo $_SESSION['odor'] ?>" readonly>
                  <label for="odor" style="color: aqua; font-size: 12px; background: none">Odor</label> <p style="font-size: 11px; color: grey"></p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="contaminantes" name="contaminantes" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center;" value="<?php echo $_SESSION['contami'] ?>" readonly>
                  <label for="contaminantes" style="color: aqua; font-size: 12px; background: none">Contaminantes</label> <p style="font-size: 11px; color: grey"></p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="perdaMassa" name="perdaMassa" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center;" value="<?php echo $_SESSION['perdaMassa'] . ' %' ?>" readonly>
                  <label for="perdaMassa" style="color: aqua; font-size: 12px; background: none">Perda de Massa</label> <p style="font-size: 11px; color: grey"></p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="escalaPH" name="escalaPH" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center;" value="<?php echo $_SESSION['escalaPH'] ?>" readonly>
                  <label for="escalaPH" style="color: aqua; font-size: 12px; background: none">Escala do pH</label> <p style="font-size: 11px; color: grey"></p>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" id="pureza" name="pureza" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center;" value="<?php echo $_SESSION['pureza'] . ' %' ?>" readonly>
                  <label for="pureza" style="color: aqua; font-size: 12px; background: none">Pureza</label> <p style="font-size: 11px; color: grey"></p>
                </div>
              </div><?php
              if(!empty($_SESSION['confirma'])){ $c = 0;
                if($_SESSION['aspecto']    == 'Regular')      { $c = $c + 1;} if($_SESSION['aspecto']    == 'Irregular'){ $c = $c - 1;} 
                if($_SESSION['cor']        == 'Normal')       { $c = $c + 1;} if($_SESSION['cor']        == 'Anormal')  { $c = $c - 1;}
                if($_SESSION['odor']       == 'Normal')       { $c = $c + 1;} if($_SESSION['odor']       == 'Anormal')  { $c = $c - 1;}
                if($_SESSION['contami']    == 'Não Detectado'){ $c = $c + 1;} if($_SESSION['contami']    == 'Detectado'){ $c = $c - 1;}
                if($_SESSION['perdaMassa'] <  5 )             { $c = $c + 1;} if($_SESSION['perdaMassa'] > 5 )          { $c = $c - 1;}
                if($_SESSION['pureza']     >  95 )            { $c = $c + 1;} if($_SESSION['pureza']     < 95 )         { $c = $c - 1;}         
                if($_SESSION['escalaPH']   <= 9 && $_SESSION['escalaPH'] >= 5){ $c = $c + 1;} if($_SESSION['escalaPH'] <= 5 && $_SESSION['escalaPH'] >= 9){ $c = $c - 1;}
                if($c > 6){ $condicao = 'Aprovado'; ?>
                  <div class="col-md-1"></div>
                  <h6>Condição:</h6>
                  <div class="col-md-3">
                    <img src="./aprovado.jpg" class="img-thumbnail" style="width: 120px; height: 120px;" alt="...">
                  </div>
                  <div class="col-md-8">
                    <div class="alert alert-success" role="alert">
                      O material pode ser liberado para uso na planta!
                    </div>
                  </div><?php              
                }
                if($c < 7){ $condicao = 'Reprovado'; ?>
                  <div class="col-md-1"><br>
                    <h6>Condição:</h6>
                  </div>
                  <div class="col-md-3">
                    <img src="./reprovado.jpg" class="img-thumbnail" style="width: 120px; height: 120px;" alt="...">
                  </div> 
                  <div class="col-md-8">
                    <div class="alert alert-danger" role="alert">
                      O material não foi aprovado! Comunique o responsável!
                    </div>
                  </div><?php 
                }
              } ?>
            </div>
          </div>           
          <div class="col-md-6">
            <div class="form-floating">
              <textarea class="form-control" id="observacao" name="observacao" style="height: 135px; background: rgba(0,0,0,0.3); color: yellow;"><?php echo $_SESSION['observacao'] ?></textarea>
              <label for="observacao" style="color: aqua; font-size: 12px; background: none">Observações da análise</label>
            </div>
            <form method="POST">
              <div class="col-md-12"><br>
                <div class="form-floating"><?php $depto = 'GARANTIA DA QUALIDADE';
                  $query_analista = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = :depto");
                  $query_analista->bindParam(':depto', $depto, PDO::PARAM_STR); $query_analista->execute(); ?>
                  <select class="form-select" id="analista" name="analista" aria-label="Floating label select example" style="background: rgba(0,0,0,0.3);">
                    <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o nome do analista responsável</option><?php
                      while($rowAna = $query_analista->fetch(PDO::FETCH_ASSOC)){ ?>
                        <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $rowAna['NOME_FUNCIONARIO']; ?></option><?php 
                      } ?>
                  </select>
                  <label for="analista" style="font-size: 12px; color:aqua">Analista</label>
                </div>
              </div>           
              <div class="col-md-3"><br>
                <input class="btn btn-primary" type="submit" id="registra" name="registra" value="Salvar Registro" style="width: 200px">
              </div>
              <div class="col-md-3"><br>
                <input class="btn btn-danger" type="reset" id="descarta" name="descarta" value="Descartar e Sair" style="width: 200px" onclick="location.href='./01SeletorGQualidade.php'">
              </div>            
            </form>
          </div>        
          <?php
          $registra = filter_input_array(INPUT_POST, FILTER_DEFAULT);
          if(!empty($registra['registra'])){ $dataAnalise = date('Y-m-d');
            $regAnalise = $connDB->prepare("INSERT INTO materiais_analise (ID_INTERNO, DESCRICAO, QTDE_LOTE, ASPECTO, COLORACAO, ODOR, CONTAMINANTES, PERDA_MASSA, ESCALA_PH, PUREZA, CONDICAO, OBSERVACOES, DATA_ANALISE, ANALISTA, RESPONSAVEL)
                                                   VALUES (:nLote, :descrMat, :qtdeLote, :aspecto, :cor, :odor, :contam, :perda, :ph, :pureza, :condicao, :observ, :dataAnali, :analista, :respons)");
            $regAnalise->bindParam(':nLote'    , $_SESSION['nLoteI']  , PDO::PARAM_STR); $regAnalise->bindParam(':contam'  , $_SESSION['contami']   , PDO::PARAM_STR);                        
            $regAnalise->bindParam(':aspecto'  , $_SESSION['aspecto'] , PDO::PARAM_STR); $regAnalise->bindParam(':descrMat', $_SESSION['descrMat']  , PDO::PARAM_STR);
            $regAnalise->bindParam(':cor'      , $_SESSION['cor']     , PDO::PARAM_STR); $regAnalise->bindParam(':perda'   , $_SESSION['perdaMassa'], PDO::PARAM_STR);
            $regAnalise->bindParam(':odor'     , $_SESSION['odor']    , PDO::PARAM_STR); $regAnalise->bindParam(':observ'  , $_SESSION['observacao'], PDO::PARAM_STR);            
            $regAnalise->bindParam(':pureza'   , $_SESSION['pureza']  , PDO::PARAM_STR); $regAnalise->bindParam(':ph'      , $_SESSION['escalaPH']  , PDO::PARAM_STR);
            $regAnalise->bindParam(':condicao' , $condicao            , PDO::PARAM_STR); $regAnalise->bindParam(':respons' , $_SESSION['nome_func'] , PDO::PARAM_STR);            
            $regAnalise->bindParam(':dataAnali', $dataAnalise         , PDO::PARAM_STR); $regAnalise->bindParam(':analista', $registra['analista']  , PDO::PARAM_STR);
            $regAnalise->bindParam(':qtdeLote' , $_SESSION['qtdeLote'], PDO::PARAM_STR); $regAnalise->execute();
                        
            $etapa = 3; $situacao = 'MATERIAL LIBERADO PARA USO';
            $atualiza = $connDB->prepare("UPDATE materiais_lotes SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao WHERE ID_INTERNO = :nLoteInterno");
            $atualiza->bindParam(':etapa'   , $etapa   , PDO::PARAM_INT); $atualiza->bindParam(':nLoteInterno', $_SESSION['nLoteI'], PDO::PARAM_STR);
            $atualiza->bindParam(':situacao', $situacao, PDO::PARAM_STR); $atualiza->execute();
            
            if($c > 6){ $etapa = 3;
              $buscaEstoque = $connDB->prepare("SELECT QTDE_ESTOQUE FROM materiais_estoque WHERE ID_ESTOQUE = :idEstoque"); $buscaEstoque->bindParam(':idEstoque', $_SESSION['idEstoque'], PDO::PARAM_INT);
              $buscaEstoque->execute(); $rowEstoque = $buscaEstoque->fetch(PDO::FETCH_ASSOC); $estoque = $rowEstoque['QTDE_ESTOQUE'] + $_SESSION['qtdeLote'];

              $atualEstoque = $connDB->prepare("UPDATE materiais_estoque SET QTDE_ESTOQUE = :estoque WHERE ID_ESTOQUE = :idEstoque"); $atualEstoque->bindParam(':estoque', $estoque, PDO::PARAM_STR);
              $atualEstoque->bindParam(':idEstoque', $_SESSION['idEstoque'], PDO::PARAM_STR); $atualEstoque->execute(); 
            }

            $buscaReserva = $connDB->prepare("SELECT NUMERO_PEDIDO FROM materiais_reserva WHERE DESCRICAO = :descMat ");

            $atualizaReserva = $connDB->prepare("UPDATE materiais_reserva SET DISPONIBILIDADE = :disp WHERE ID_COMPRA = :idCompra");
            $atualizaReserva->bindParam(':disp'     , $etapa               , PDO::PARAM_STR);
            $atualizaReserva->bindParam(':idCompra' , $_SESSION['idCompra'], PDO::PARAM_STR);
            $atualizaReserva->execute();

            header('Location: ./01SeletorGQualidade.php');

          }?>
        </div><!-- fim da div row g1 -->
      </div><!-- fim da tab entrada -->

      <!-- Especificações -->
      <div class="tab-pane fade" id="ET-tab-pane" role="tabpanel" aria-labelledby="ET-tab" tabindex="0">
        <br><br><br>
        <p>Espaço para documentação de especificação técnica</p>
      </div>

      <!-- Metodologias -->
      <div class="tab-pane fade" id="MA-tab-pane" role="tabpanel" aria-labelledby="MA-tab" tabindex="0">
        <br><br><br>
        <p>Espaço para documentação MA</p>
      </div>

      <!-- Peocedimentos -->
      <div class="tab-pane fade" id="PQ-tab-pane" role="tabpanel" aria-labelledby="PQ-tab" tabindex="0">
        <br><br><br>
        <p>Espaço para documentação PQ</p>
      </div>

      <!-- Referências -->
      <div class="tab-pane fade" id="RE-tab-pane" role="tabpanel" aria-labelledby="RE-tab" tabindex="0">
        <br><br><br>
        <p>Espaço para documentação RE</p>
      </div>
    </div><!-- container tab -->     
  </div><!-- container fluid -->
</div><!-- Entrada de Dados -->