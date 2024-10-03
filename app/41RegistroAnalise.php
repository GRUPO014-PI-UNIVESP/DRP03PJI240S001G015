<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Registro de Análise';
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
       time = setTimeout(deslogar, 3000000);
     }
  };
  inactivityTime();
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
        <button class="nav-link" id="ET-tab" data-bs-toggle="tab" data-bs-target="#ET-tab-pane" type="button" 
            role="tab" aria-controls="ET-tab-pane" aria-selected="false">Especificações</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="MA-tab" data-bs-toggle="tab" data-bs-target="#MA-tab-pane" type="button" 
            role="tab" aria-controls="MA-tab-pane" aria-selected="false">Metodologia</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="PQ-tab" data-bs-toggle="tab" data-bs-target="#PQ-tab-pane" type="button" 
            role="tab" aria-controls="PQ-tab-pane" aria-selected="false">Procedimentos</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="RE-tab" data-bs-toggle="tab" data-bs-target="#RE-tab-pane" type="button" 
            role="tab" aria-controls="RE-tab-pane" aria-selected="false">Referências</button>
      </li>        
    </ul>

    <!-- Análises de Matérias Primas e Insumos -->
    <div class="tab-content" id="myTabContent">

      <!-- Entrada de Dados --><?php
      if(!empty($_GET['id'])){
        $dadosMaterial = $connDB->prepare("SELECT * FROM mp_estoque WHERE ID_ESTOQUE_MP = :idMat");
        $dadosMaterial->bindParam(':idMat', $_GET['id'], PDO::PARAM_INT);
        $dadosMaterial->execute(); $rowMaterial = $dadosMaterial->fetch(PDO::FETCH_ASSOC);
        $dataRegistro = date('Y-m-d');
      } ?>
      <div class="tab-pane fade show active" id="entrada-tab-pane" role="tabpanel" aria-labelledby="entrada-tab" tabindex="0"><br>
        <div class="row g-1">
          <h6>Informações do Material Analisado</h6>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="dataRegistro" name="dataRegistro" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo date('d/m/Y', strtotime($dataRegistro)) ?>" readonly>
              <label for="dataRegistro" style="color: aqua; font-size: 12px; background: none">Data de Registro</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nLoteInterno" name="nLoteInterno" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowMaterial['NUMERO_LOTE_INTERNO'] ?>" readonly>
              <label for="nLoteInterno" style="color: aqua; font-size: 12px; background: none">No.de Lote Interno</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nLoteFornecedor" name="nLoteFornecedor" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowMaterial['NUMERO_LOTE_FORNECEDOR'] ?>" readonly>
              <label for="nLoteFornecedor" style="color: aqua; font-size: 12px; background: none">No.de Lote do Fornecedor</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="fornecedor" name="fornecedor" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowMaterial['FORNECEDOR'] ?>" readonly>
              <label for="fornecedor" style="color: aqua; font-size: 12px; background: none">Fornecedor</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="dataFabri" name="dataFabri" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo date('d/m/Y', strtotime($rowMaterial['DATA_FABRICACAO'])) ?>" readonly>
              <label for="dataFabri" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="dataVali" name="dataVali" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo date('d/m/Y', strtotime($rowMaterial['DATA_VALIDADE'])) ?>" readonly>
              <label for="dataVali" style="color: aqua; font-size: 12px; background: none">Data de Validade</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="notaFiscal" name="notaFiscal" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowMaterial['NOTA_FISCAL_LOTE'] ?>" readonly>
              <label for="notaFiscal" style="color: aqua; font-size: 12px; background: none">Nota Fiscal</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="descrMat" name="descrMat" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowMaterial['DESCRICAO_MP'] ?>" readonly>
              <label for="descrMat" style="color: aqua; font-size: 12px; background: none">Descrição do Material</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <h6>Dados Analisados</h6>
          <div class="col-md-3">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="aspecto" name="aspecto" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['aspecto'] ?>" readonly>
              <label for="aspecto" style="color: aqua; font-size: 12px; background: none">Aspecto</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="cor" name="cor" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['cor'] ?>" readonly>
              <label for="cor" style="color: aqua; font-size: 12px; background: none">Coloração</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="odor" name="odor" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['odor'] ?>" readonly>
              <label for="odor" style="color: aqua; font-size: 12px; background: none">Odor</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="contaminantes" name="contaminantes" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['contaminantes'] ?>" readonly>
              <label for="contaminantes" style="color: aqua; font-size: 12px; background: none">Presença de Contaminantes</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="perdaMassa" name="perdaMassa" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['perdaMassa'] ?>" readonly>
              <label for="perdaMassa" style="color: aqua; font-size: 12px; background: none">Perda de Massa</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="escalaPH" name="escalaPH" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['escalaPH'] ?>" readonly>
              <label for="escalaPH" style="color: aqua; font-size: 12px; background: none">Escala do pH</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="pureza" name="pureza" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $_SESSION['pureza'] ?>" readonly>
              <label for="pureza" style="color: aqua; font-size: 12px; background: none">Pureza</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-floating">
              <textarea class="form-control" id="observacao" name="observacao" style="height: 75px"><?php echo $_SESSION['observacao'] ?></textarea>
              <label for="floatingTextarea2">Observações da análise</label>
            </div>
          </div><?php
          if(!empty($_SESSION['confirma'])){ $c = 0;
            if($_SESSION['aspecto']       == 'Regular')      { $c = $c + 1;} if($_SESSION['aspecto']       == 'Irregular') { $c = $c - 1;} 
            if($_SESSION['cor']           == 'Normal')       { $c = $c + 1;} if($_SESSION['cor']           == 'Anormal')   { $c = $c - 1;}
            if($_SESSION['odor']          == 'Normal')       { $c = $c + 1;} if($_SESSION['odor']          == 'Anormal')   { $c = $c - 1;}
            if($_SESSION['contaminantes'] == 'Não Detectado'){ $c = $c + 1;} if($_SESSION['contaminantes'] == 'Detectado') { $c = $c - 1;}
            if($_SESSION['perdaMassa'] < 5 )                 { $c = $c + 1;} if($_SESSION['perdaMassa'] > 5 )              { $c = $c - 1;}
            if($_SESSION['pureza']     > 95 )                { $c = $c + 1;} if($_SESSION['pureza']     < 95 )             { $c = $c - 1;}         
            if($_SESSION['escalaPH'] <= 9 && $$_SESSION['escalaPH'] >= 5){ $c = $c + 1;} if($_SESSION['escalaPH'] <= 5 && $_SESSION['escalaPH'] >= 9){ $c = $c - 1;}
            if($c > 6){ $condicao = 'Aprovado'; ?>
              <div class="col-md-1"><br>
                <h6>Condição:</h6>
              </div>
              <div class="col-md-3"><br>
                <img src="./aprovado.jpg" class="img-thumbnail" style="width: 150px; height: 150px;" alt="...">
              </div>
              <div class="col-md-5"><br><br>
                <div class="alert alert-success" role="alert">
                  O material pode ser liberado para uso na planta!
                </div>
              </div><?php              
            }
            if($c < 7){ $condicao = 'Reprovado'; ?>
              <div class="col-md-1"><br>
                <h6>Condição:</h6>
              </div>
              <div class="col-md-3"><br>
                <img src="./reprovado.jpg" class="img-thumbnail" style="width: 150px; height: 150px;" alt="...">
              </div> 
              <div class="col-md-5"><br><br>
                <div class="alert alert-danger" role="alert">
                  O material não foi aprovado! Comunique o responsável!
                </div>
              </div><?php 
            }
          } ?>
        </div><?php
          $registra = filter_input_array(INPUT_POST, FILTER_DEFAULT);
          if(!empty($registra['registra'])){
            $dataAnalise = date('Y-m-d');
            $regAnalise = $connDB->prepare("INSERT INTO analise_mp (NUMERO_LOTE_MP, DESCRICAO_MP, QTDE_LOTE_MP, ASPECTO, COLORACAO, ODOR, CONTAMINANTES, PERDA_MASSA, ESCALA_PH,
                                                                          PUREZA, CONDICAO, OBSERVACOES, DATA_ANALISE, ANALISTA, RESPONSAVEL)
                                                   VALUES (:nLote, :descrMat, :qtdeLote, :aspecto, :cor, :odor, :contam, :perda, :ph, :pureza, :condicao, :analista, :observ)");
            $regAnalise->bindParam(':nLote'      , $rowMaterial['NUMERO_LOTE_INTERNO'], PDO::PARAM_STR);
            $regAnalise->bindParam(':descrMat'   , $rowMaterial['DESCRICAO_MP']       , PDO::PARAM_STR);
            $regAnalise->bindParam(':qtdeLote'   , $rowMaterial['QTDE_LOTE']          , PDO::PARAM_STR);
            $regAnalise->bindParam(':aspecto'    , $_SESSION['aspecto']               , PDO::PARAM_STR);
            $regAnalise->bindParam(':cor'        , $_SESSION['cor']                   , PDO::PARAM_STR);
            $regAnalise->bindParam(':odor'       , $_SESSION['odor']                  , PDO::PARAM_STR);
            $regAnalise->bindParam(':contam'     , $_SESSION['contaminantes']         , PDO::PARAM_STR);
            $regAnalise->bindParam(':perda'      , $_SESSION['perdaMassa']            , PDO::PARAM_STR);
            $regAnalise->bindParam(':ph'         , $_SESSION['escalaPH']              , PDO::PARAM_STR);
            $regAnalise->bindParam(':pureza'     , $_SESSION['pureza']                , PDO::PARAM_STR);
            $regAnalise->bindParam(':condicao'   , $condicao                          , PDO::PARAM_STR);
            $regAnalise->bindParam(':observ'     , $_SESSION['observacao']            , PDO::PARAM_STR);
            $regAnalise->bindParam(':dataAnalise', $dataAnalise                       , PDO::PARAM_STR);
            $regAnalise->bindParam(':analista'   , $_SESSION['nome_func']             , PDO::PARAM_STR);
            $regAnalise->bindParam(':responsavel', $_SESSION['nome_func']             , PDO::PARAM_STR);
            $regAnalise->execute();

            $atualiza = $connDB->prepare("UPDATE mp_estoque SET ");

          } ?>
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