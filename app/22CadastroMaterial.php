<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Cadastro de Material';
include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

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
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br> 
    <div class="row g-4">
      <div class="col-md-6">
        <h5>Cadastro de Novo Material</h5>
      </div>
      <div class="col-md-4"></div>
      <div class="col-md-2">
        <button class="btn btn-warning" style="width: 100px" onclick="location.href='./00SeletorAdministrativo.php'">Sair</button>
      </div>
    </div>
    <form method="POST" action="#" id="cadastroMaterial">
      <div class="col-md-10">
        <label for="fornecedor" class="form-label" style="font-size: 10px; color:aqua">Fornecedor</label>
        <select style="font-size: 12px;" class="form-select" id="fornecedor" name="fornecedor">
          <option style="font-size: 12px" selected>Selecione o fornecedor</option><?php
            //Pesquisa por fornecedores para seleção
            //
            // criar algoritmo para novos fornecedores
            //
            $query_supplier = $connDB->prepare("SELECT DISTINCT FORNECEDOR FROM mp_tabela");
            $query_supplier->execute();
            while($supplier = $query_supplier->fetch(PDO::FETCH_ASSOC)){?>
              <option style="font-size: 12px"><?php echo $supplier['FORNECEDOR']; ?></option> <?php
            }?>
        </select>
      </div>
      <div class="col-md-10">
        <label for="descrMaterial" class="form-label" style="font-size: 10px; color:aqua">Descrição do Material</label>
        <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="descrMaterial" name="descrMaterial" required>
      </div>
      <div class="row g-2">
        <div class="col-md-3">
          <label for="uniMed" class="form-label" style="font-size: 10px; color:aqua">Unidade de Medida</label>
          <select style="font-size: 12px;" class="form-select" id="uniMed" name="uniMed">
            <option selected>Selecione</option>
            <option value="KG">KG</option>
            <option value="LT">LT</option>
            <option value="UN">UNIDADE</option>
          </select>
        </div>
        <div class="col-md-3" style="padding: 3px;"><br>
          <input style="width: 140px; float: right" class="btn btn-primary" type="submit" id="salvar" name="salvar" value="Confirmar">
        </div>
        <div class="col-md-3" style="padding: 3px;"><br>
          <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset" name="reset" value="Descartar"
                 onclick="location.href='./22CadastroMaterial.php'">
        </div>
      </div>
    </form><?php
    $salvar = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($salvar['salvar'])){
      $descrMaterial = strtoupper($salvar['descrMaterial']);
      $fornecedor    = strtoupper($salvar['fornecedor']);
      $uniMed        = strtoupper($salvar['uniMed']);

      $regMaterial = $connDB->prepare("INSERT INTO mp_tabela (FORNECEDOR, DESCRICAO_MP, UNIDADE_MEDIDA)
                                                             VALUES (:fornecedor, :descrMaterial, :uniMed)");
      $regMaterial->bindParam(':fornecedor'   , $fornecedor   , PDO::PARAM_STR);
      $regMaterial->bindParam(':descrMaterial', $descrMaterial, PDO::PARAM_STR);
      $regMaterial->bindParam(':uniMed'       , $uniMed       , PDO::PARAM_STR);
      $regMaterial->execute();
      header('Location: ./22CadastroMaterial.php');
    } ?>
  </div>
</div>