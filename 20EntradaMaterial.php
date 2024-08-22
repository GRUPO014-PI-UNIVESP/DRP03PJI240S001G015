<?php
// inclusão do banco de dados e estrutura base da página web
include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

//Pesquisa por fornecedores para seleção
$query_supplier = $connDB->prepare("SELECT RAZAO_SOCIAL FROM mp_fornecedor");
$query_supplier->execute();

//Pesquisa de descrição do material para seleção
$query_material = $connDB->prepare("SELECT DESCRICAO_MP FROM mp_tabela");
$query_material->execute();

//verifica quadro de funcionários para seleção do encarregado pela tarefa de recebimento do material
$query_encarregado1 = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = 'LOGÍSTICA' OR CREDENCIAL >= 4");
$query_encarregado1->execute();
$query_encarregado2 = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = 'LOGÍSTICA' OR CREDENCIAL >= 4");
$query_encarregado2->execute();

// capta dados do formulário
$confirma = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//verifica se foi preenchido formulário de entrada de material já existente no banco de dados
if(!empty($confirma['salvar1'])){

  // atribui valores em conformidade com banco de dados
  $dataEntr1 = date('Y-m-d', strtotime($confirma['dataEntr1']));
  $dataFabr1 = date('Y-m-d', strtotime($confirma['dataFabr1']));
  $dataVali1 = date('Y-m-d', strtotime($confirma['dataVali1']));
  $loteF1 = strtoupper($confirma['numLoteF1']);
  $loteI1 = strtoupper($confirma['numLoteI1']);
  
  // ordem de inserção de dados
  $salvar1 = $connDB->prepare("INSERT INTO mp_estoque (FORNECEDOR, DESCRICAO_MP, NUMERO_LOTE_FORNECEDOR, DATA_FABRICACAO, DATA_VALIDADE, NOTA_FISCAL_LOTE, QTDE_LOTE,
                                                       QTDE_ESTOQUE, UNIDADE_MEDIDA, NUMERO_LOTE_INTERNO, DATA_ENTRADA, ENCARREGADO_RECEBIMENTO, RESPONSAVEL_REGISTRO)
                               VALUES (:fornecedor,  :descrMat, :numLoteF, :dataFabr, :dataVali, :notaFiscal, :qtdeLote, :qtdeStock, :uniMed, :numLoteI, :dataEntr,
                                       :encarregado, :responsavel)");
  $salvar1->bindParam(':fornecedor'  , $confirma['fornecedor1'] , PDO::PARAM_STR);
  $salvar1->bindParam(':descrMat'    , $confirma['descrMat1']   , PDO::PARAM_STR);
  $salvar1->bindParam(':numLoteF'    , $loteF1                  , PDO::PARAM_STR);
  $salvar1->bindParam(':dataFabr'    , $dataFabr1               , PDO::PARAM_STR);
  $salvar1->bindParam(':dataVali'    , $dataVali1               , PDO::PARAM_STR);
  $salvar1->bindParam(':notaFiscal'  , $confirma['notaFiscal1'] , PDO::PARAM_STR);
  $salvar1->bindParam(':qtdeLote'    , $confirma['qtdeLote1']   , PDO::PARAM_INT);
  $salvar1->bindParam(':qtdeStock'   , $confirma['qtdeLote1']   , PDO::PARAM_INT);
  $salvar1->bindParam(':uniMed'      , $confirma['uniMed1']     , PDO::PARAM_STR);
  $salvar1->bindParam(':numLoteI'    , $loteI1                  , PDO::PARAM_STR);
  $salvar1->bindParam(':dataEntr'    , $dataEntr1               , PDO::PARAM_STR);
  $salvar1->bindParam(':encarregado' , $confirma['encarregado1'], PDO::PARAM_STR);
  $salvar1->bindParam(':responsavel' , $responsavel             , PDO::PARAM_STR);
  $salvar1->execute();

  //redireciona para início 
  header('Location: 20EntradaMaterial.php');

  //verifica se foi feito o cadastramento de novo material
} else if(!empty($confirma['salvar2'])){

  $dataEntr2   = date('Y-m-d', strtotime($confirma['dataEntr2']));
  $dataFabr2   = date('Y-m-d', strtotime($confirma['dataFabr2']));
  $dataVali2   = date('Y-m-d', strtotime($confirma['dataVali2']));
  $loteF2      = strtoupper($confirma['numLoteF2']);
  $loteI2      = strtoupper($confirma['numLoteI2']);
  $fornecedor2 = strtoupper($confirma['fornecedor2']);
  $descrMat2   = strtoupper($confirma['descrMat2']);
  
  $salvar2      = $connDB->prepare("INSERT INTO mp_estoque (FORNECEDOR, DESCRICAO_MP, NUMERO_LOTE_FORNECEDOR, DATA_FABRICACAO, DATA_VALIDADE, NOTA_FISCAL_LOTE, QTDE_LOTE,
                                                            QTDE_ESTOQUE, UNIDADE_MEDIDA, NUMERO_LOTE_INTERNO, DATA_ENTRADA, ENCARREGADO_RECEBIMENTO, RESPONSAVEL_REGISTRO)
                                    VALUES (:fornecedor, :descrMat, :numLoteF, :dataFabr, :dataVali, :notaFiscal, :qtdeLote, :qtdeStock, :uniMed, :numLoteI, :dataEntr,
                                            :encarregado, :responsavel)");
  $salvar2->bindParam(':fornecedor'  , $fornecedor2             , PDO::PARAM_STR);
  $salvar2->bindParam(':descrMat'    , $descrMat2               , PDO::PARAM_STR);
  $salvar2->bindParam(':numLoteF'    , $loteF2                  , PDO::PARAM_STR);
  $salvar2->bindParam(':dataFabr'    , $dataFabr2               , PDO::PARAM_STR);
  $salvar2->bindParam(':dataVali'    , $dataVali2               , PDO::PARAM_STR);
  $salvar2->bindParam(':notaFiscal'  , $confirma['notaFiscal2'] , PDO::PARAM_STR);
  $salvar2->bindParam(':qtdeLote'    , $confirma['qtdeLote2']   , PDO::PARAM_STR);
  $salvar2->bindParam(':qtdeStock'   , $confirma['qtdeLote2']   , PDO::PARAM_STR);
  $salvar2->bindParam(':uniMed'      , $confirma['uniMed2']     , PDO::PARAM_STR);
  $salvar2->bindParam(':numLoteI'    , $loteI2                  , PDO::PARAM_STR);
  $salvar2->bindParam(':dataEntr'    , $dataEntr2               , PDO::PARAM_STR);
  $salvar2->bindParam(':encarregado' , $confirma['encarregado2'], PDO::PARAM_STR);
  $salvar2->bindParam(':responsavel' , $responsavel             , PDO::PARAM_STR);
  $salvar2->execute();

  $saveFornecedor = $connDB->prepare("INSERT INTO mp_fornecedor (RAZAO_SOCIAL) VALUES (:fornecedor)");
  $saveFornecedor->bindParam(':fornecedor', $fornecedor2, PDO::PARAM_STR);
  $saveFornecedor->execute();

  $saveMP = $connDB->prepare("INSERT INTO mp_tabela (FORNECEDOR, DESCRICAO_MP) VALUES (:fornecedor, :descrMat)");
  $saveMP->bindParam(':fornecedor', $fornecedor2, PDO::PARAM_STR);
  $saveMP->bindParam(':descrMat'  , $descrMat2  , PDO::PARAM_STR);
  $saveMP->execute();

  header('Location: 20EntradaMaterial.php');
}
?>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <ul style="padding:5px" class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" 
          role="tab" aria-controls="manage-tab-pane" aria-selected="true">Entrada de Material</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="lab-tab" data-bs-toggle="tab" data-bs-target="#lab-tab-pane" type="button" 
          role="tab" aria-controls="lab-tab-pane" aria-selected="false">Cadastro de Novo Material</button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent"><br>

      <!-- Entrada de Material -->
        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0">
          <form class="row g-4" method="POST" action="#">
            <div class="col-md-2">
              <label for="dataEntr1" class="form-label" style="font-size: 10px; color:aqua">Data de Entrada</label>
              <input style="font-size: 12px;" type="date" class="form-control" id="dataEntr1" name="dataEntr1" required autofocus>
            </div>

            <div class="col-md-10">
              <label for="fornecedor1" class="form-label" style="font-size: 10px; color:aqua">Fornecedor</label>
              <select style="font-size: 12px;" class="form-select" id="fornecedor1" name="fornecedor1">
                <option style="font-size: 12px" selected>Selecione o fornecedor</option>
                <?php
                  while($supplier = $query_supplier->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 12px"><?php echo $supplier['RAZAO_SOCIAL']; ?></option> <?php
                  }?>
              </select>
            </div>

            <div class="col-md-12">
              <label for="descrMat1" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
              <select style="font-size: 12px;" class="form-select" id="descrMat1" name="descrMat1">
                <option style="font-size: 12px" selected>Selecione o material</option>
                <?php
                  // 
                  while($material = $query_material->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 12px"><?php echo $material['DESCRICAO_MP']; ?></option> <?php
                  }?>
              </select>
            </div>

            <div class="col-md-3">
              <label for="numLoteF1" class="form-label" style="font-size: 10px; color:aqua">No. do Lote / Fornecedor</label>
              <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="numLoteF1" name="numLoteF1" required>
            </div>

            <div class="col-md-3">
              <label for="notaFiscal1" class="form-label" style="font-size: 10px; color:aqua">Nota Fiscal</label>
              <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="notaFiscal1" name="notaFiscal1" required>
            </div>

            <div class="col-md-2">
              <label for="numLoteI1" class="form-label" style="font-size: 10px; color:aqua">No. do Lote / Interno</label>
              <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="numLoteI1" name="numLoteI1" required>
            </div>

            <div class="col-md-2">
              <label for="dataFabr1" class="form-label" style="font-size: 10px; color:aqua">Data de Fabricação</label>
              <input style="font-size: 12px;" type="date" class="form-control" id="dataFabr1" name="dataFabr1">
            </div>

            <div class="col-md-2">
              <label for="dataVali1" class="form-label" style="font-size: 10px; color:aqua">Data de Validade</label>
              <input style="font-size: 12px;" type="date" class="form-control" id="dataVali1" name="dataVali1">
            </div>

            <div class="col-md-2">
              <label for="qtdeLote1" class="form-label" style="font-size: 10px; color:aqua">Quantidade Recebida</label>
              <input style="font-size: 16px; text-align:right" type="number" class="form-control" id="valor1" name="qtdeLote1" required>
            </div>

            <div class="col-md-2">
              <label for="uniMed1" class="form-label" style="font-size: 10px; color:aqua">Unidade de Medida</label>
              <select style="font-size: 12px;" class="form-select" id="uniMed1" name="uniMed1">
                <option selected>Selecione</option>
                <option value="KG">KG</option>
                <option value="LT">LT</option>
                <option value="UN">UNIDADE</option>
              </select>
            </div>

            <div class="col-md-8">
              <label for="encarregado1" class="form-label" style="font-size: 10px; color:aqua">Nome do Encarregado pelo Recebimento</label>
              <select style="font-size: 12px;" class="form-select" id="encarregado1" name="encarregado1">
                <option style="font-size: 12px" selected>Selecione um nome</option>
                <?php
                  while($nomeEncarregado = $query_encarregado1->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 12px"><?php echo $nomeEncarregado['NOME_FUNCIONARIO']; ?></option> <?php
                  }?>
              </select>
            </div>

            <div class="col-md-2" style="padding: 3px;">
              <input style="width: 140px; text-align:center" class="btn btn-primary" type="submit" id="salvar1" name="salvar1" value="Confirmar">
            </div>

            <div class="col-md-3" style="padding: 3px;">
              <input style="width: 140px; text-align:center" class="btn btn-secondary" type="reset" id="reset1" name="reset1" value="Descartar" onclick="location.href='20EntradaMaterial.php'">
            </div>
          </form>
        </div>
        
      <!-- Novo Cadastro -->  
        <div class="tab-pane fade" id="lab-tab-pane" role="tabpanel" aria-labelledby="lab-tab" tabindex="0">
          <form class="row g-4" method="POST" action="#">

              <div class="col-md-2">
                <label for="dataEntr2" class="form-label" style="font-size: 10px; color:aqua">Data de Entrada</label>
                <input style="font-size: 12px" type="date" class="form-control" id="dataEntr2" name="dataEntr2" required autofocus>
              </div>

              <div class="col-md-10">
                <label for="fornecedor2" class="form-label" style="font-size: 10px; color:aqua">Fornecedor</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="fornecedor2" name="fornecedor2" 
                       placeholder="" required>
              </div>

              <div class="col-md-12">
                <label for="descrMat2" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
                <input style="font-size: 12px; text-transform: uppercase;" type="text" class="form-control" id="descrMat2" name="descrMat2" 
                       placeholder="" required>
              </div>

              <div class="col-md-3">
                <label for="numLoteF2" class="form-label" style="font-size: 10px; color:aqua">No. do Lote / Fornecedor</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="numLoteF2" name="numLoteF2" required>
              </div>

              <div class="col-md-3">
                <label for="notaFiscal2" class="form-label" style="font-size: 10px; color:aqua">Nota Fiscal</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="notaFiscal2" name="notaFiscal2" required>
              </div>

              <div class="col-md-2">
                <label for="numLoteI2" class="form-label" style="font-size: 10px; color:aqua">No. do Lote / Interno</label>
                <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="numLoteI2" name="numLoteI2" required>
              </div>

              <div class="col-md-2">
                <label for="dataFabr2" class="form-label" style="font-size: 10px; color:aqua">Data de Fabricação</label>
                <input style="font-size: 12px;" type="date" class="form-control" id="dataFabr2" name="dataFabr2">
              </div>

              <div class="col-md-2">
                <label for="dataVali2" class="form-label" style="font-size: 10px; color:aqua">Data de Validade</label>
                <input style="font-size: 12px;" type="date" class="form-control" id="dataVali2" name="dataVali2">
              </div>

              <div class="col-md-2">
                <label for="qtdeLote2" class="form-label" style="font-size: 10px; color:aqua">Quantidade Recebida</label>
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

              <div class="col-md-8">
                <label for="encarregado2" class="form-label" style="font-size: 10px; color:aqua">Nome do Encarregado pelo Recebimento</label>
                <select style="font-size: 12px;" class="form-select" id="encarregado2" name="encarregado2">
                  <option style="font-size: 12px" selected>Selecione um nome</option>
                  <?php
                    while($nomeEncarregado = $query_encarregado2->fetch(PDO::FETCH_ASSOC)){?>
                      <option style="font-size: 12px"><?php echo $nomeEncarregado['NOME_FUNCIONARIO']; ?></option> <?php
                    }?>
                </select>
              </div>
              <div class="col-md-2" style="padding: 3px">
                <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar2" name="salvar2" value="Confirmar">
              </div>
              <div class="col-md-3" style="padding: 3px">
                <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar" onclick="location.href='20EntradaMaterial.php'">
              </div>
            </form>
        </div>
    </div>
  </div>
</div>