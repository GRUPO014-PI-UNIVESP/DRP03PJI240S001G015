<?php
// inclusão do banco de dados e estrutura base da página web
include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

$query_supplier = $connDB->prepare("SELECT FORNECEDOR FROM tabela_mp");
$query_supplier->execute();
$query_material = $connDB->prepare("SELECT DESCRICAO_MATERIAL FROM tabela_mp");
$query_material->execute();
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
    <div class="tab-content" id="myTabContent">
      <!-- Entrada de Material -->
        <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0">
          <form class="row g-2" method="POST" action="#">
            <div class="col-md-2">
              <label for="dataEntrada" class="form-label" style="font-size: 10px; color:aqua">Data de Entrada</label>
              <input style="font-size: 12px" type="date" class="form-control" id="dataEntrada" name="dataEntrada" required autofocus>
            </div>
            <div class="col-md-10">
              <label for="fornecedor" class="form-label" style="font-size: 10px; color:aqua">Fornecedor</label>
              <select style="font-size: 12px;" id="fornecedor" class="form-select" name="fornecedor">
                <option style="font-size: 12px" selected>Selecione um fornecedor</option>
                <?php
                  while($supplier = $query_supplier->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 12px"><?php echo $supplier['FORNECEDOR']; ?></option> <?php
                  }?>
              </select>
            </div>
            <div class="col-md-12">
              <label for="descrMat" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
              <select style="font-size: 12px;" id="descrMat" class="form-select" name="descrMat">
                <option style="font-size: 12px" selected>Selecione o material</option>
                <?php
                  while($material = $query_material->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 12px"><?php echo $material['DESCRICAO_MATERIAL']; ?></option> <?php
                  }?>
              </select>
            </div>
            <div class="col-3">
              <label for="numLoteF" class="form-label" style="font-size: 10px; color:aqua">Número do Lote / Fornecedor</label>
              <input style="font-size: 14px;" type="text" class="form-control" id="numLoteF" name="numLoteF" required>
            </div>
            <div class="col-3">
              <label for="quantidade" class="form-label" style="font-size: 10px; color:aqua">Quantidade Recebida</label>
              <input style="font-size: 14px; text-align:right" type="text" class="form-control" id="valor" name="quantidade" maxlength="9" onkeyup="forMilhar()" required>
            </div>

          </form>
        </div>
      <!-- Novo Cadastro -->  
        <div class="tab-pane fade" id="lab-tab-pane" role="tabpanel" aria-labelledby="lab-tab" tabindex="0">
 
        </div>
    </div>
  </div>
</div>