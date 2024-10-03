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
          <form method="POST">
            <div class="row g-2">
              <div class="col-md-5">
                <div class="row g-2">
                  <h6>Dados Analisados</h6>
                  <div class="col-md-5">
                    <p style="color: aqua">Aspecto</p>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="aspecto" id="aspecto" value="Regular" checked>
                      <label class="form-check-label" for="aspecto"> Regular </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="aspecto" id="aspecto" value="Irregular">
                      <label class="form-check-label" for="aspecto"> Irregular </label>
                    </div>           
                  </div>
                  <div class="col-md-6">
                    <p style="color: aqua">Coloração</p>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="cor" id="cor" value="Normal" checked>
                      <label class="form-check-label" for="cor"> Normal </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="cor" id="cor">
                      <label class="form-check-label" for="cor"> Anormal </label>
                    </div>           
                  </div>
                  <div class="col-md-5"><br>
                    <p style="color: aqua">Odor</p>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="odor" id="odor" value="Normal" checked>
                      <label class="form-check-label" for="odor"> Normal </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="odor" id="odor">
                      <label class="form-check-label" for="odor"> Anormal </label>
                    </div>           
                  </div>
                  <div class="col-md-6"><br>
                    <p style="color: aqua">Contaminantes</p> 
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="contaminantes" id="contaminantes" value="Não Detectado" checked>
                      <label class="form-check-label" for="contaminantes"> Não Detectado </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="contaminantes" id="contaminantes" value="Detectado">
                      <label class="form-check-label" for="contaminantes"> Detectado </label>
                    </div>           
                  </div>
                </div>
              </div>              
              <div class="col-md-7">
                <div class="row g-2">
                  <div class="col-md-4"><br>
                    <div class="input-group mb-1">
                      <div class="form-floating mb-0">
                        <input type="number" class="form-control" id="perdaMassa" name="perdaMassa" style="font-weight: bolder; background: rgba(0,0,0,0.3);">
                        <label for="perdaMassa" style="color: aqua; font-size: 12px; background: none">Perda de Massa</label>
                      </div>
                      <span class="input-group-text">%</span>
                    </div>
                  </div>  
                  <div class="col-md-4"><br>
                    <div class="input-group mb-1">
                      <div class="form-floating mb-0">
                        <input type="number" class="form-control" id="escalaPH" name="escalaPH" style="font-weight: bolder; background: rgba(0,0,0,0.3);">
                        <label for="escalaPH" style="color: aqua; font-size: 12px; background: none">Escala de pH</label>
                      </div>
                      <span class="input-group-text"></span>
                    </div>
                  </div>
                  <div class="col-md-4"><br>
                    <div class="input-group mb-1">
                      <div class="form-floating mb-0">
                        <input type="number" class="form-control" id="pureza" name="pureza" style="font-weight: bolder; background: rgba(0,0,0,0.3);">
                        <label for="pureza" style="color: aqua; font-size: 12px; background: none">Pureza</label>
                      </div>
                      <span class="input-group-text">%</span>
                    </div>
                  </div>
                  <div class="form-floating">
                    <textarea class="form-control" id="observacao" name="observacao" style="font-size: 14px; height: 100px; width: 650px; background: rgba(0,0,0,0.3);"></textarea>
                    <label for="observacao" style="color: aqua; font-size: 12px; background: none">Observações</label>
                  </div>
                </div>
                <div class="col-md-3"><br>
                  <input class="btn btn-primary" type="submit" id="confirma" name="confirma" value="Confirmar Dados" style="width: 200px">
                </div>
                <div class="col-md-3"><br>
                  <input class="btn btn-danger" type="reset" id="descarta" name="descarta" value="Descartar e Sair" style="width: 200px" onclick="location.href='./01SeletorGQualidade.php'">
                </div>
              </div>                           
            </div>
          </form><?php
          $confirma = filter_input_array(INPUT_POST, FILTER_DEFAULT);
          if(!empty($confirma['confirma'])){
            $_SESSION['confirma']      = $confirma['confirma'];
            $_SESSION['aspecto']       = $confirma['aspecto'];
            $_SESSION['cor']           = $confirma['cor'];
            $_SESSION['odor']          = $confirma['odor'];
            $_SESSION['contaminantes'] = $confirma['contaminantes'];
            $_SESSION['perdaMassa']    = $confirma['perdaMassa'];
            $_SESSION['escalaPH']      = $confirma['escalaPH'];
            $_SESSION['pureza']        = $confirma['pureza'];
            $_SESSION['observacao']    = $confirma['observacao'];

            header('Location: ./41RegistroAnalise.php');
          }

        ?></div><!-- fim da div row g1 -->
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