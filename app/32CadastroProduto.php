<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Entrada de Pedido';
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
       time = setTimeout(deslogar, 3000000);
     }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br> 
    <h5>Cadastro de Novo Produto</h5>         
      <div class="row g-1">
        <div class="col-md-5">
          <label for="" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia do Produto</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="" name=""
            value="<?php echo $_SESSION['nomeProduto']; ?>" readonly>
        </div>
        <div class="col-md-5">
          <label for="" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="" name=""
            value="<?php echo $_SESSION['descrProduto']; ?>" readonly>
        </div>
        <div class="col-md-2">
          <label for="" class="form-label" style="font-size: 10px; color:aqua">Capacidade Produtiva</label>
          <input style="font-size: 12px; text-align: right" type="text" class="form-control" id="" name=""
            value="<?php echo $_SESSION['capacidade'] . ' Kg/hora'; ?>" readonly>
        </div>
      </div><br>
      <!-- Verifica se é primeira entrada ou mostra componentes já inseridos -->
      <?php
        if($_SESSION['ciclo'] == 2){
          $nomeProduto = $_SESSION['nomeProduto'];
          $mostraMat = $connDB->prepare("SELECT * FROM pf_tabela WHERE NOME_FANTASIA = :nomeProduto");
          $mostraMat->bindParam(':nomeProduto', $nomeProduto, PDO::PARAM_STR);
          $mostraMat->execute();
          while($rowMaterial = $mostraMat->fetch(PDO::FETCH_ASSOC)){ ?>
            <div class="row g-1">
              <div class="col-md-5">
                <label for="" class="form-label" style="font-size: 10px; color:aqua">Componente</label>
                <input style="font-size: 12px; color: yellow" type="text" class="form-control" id="" name=""
                  value="<?php echo $rowMaterial['MATERIAL_COMPONENTE']; ?>" readonly>
              </div>
              <div class="col-md-2">
                <label for="" class="form-label" style="font-size: 10px; color:aqua">Proporção</label>
                <input type="text" class="form-control" id="" name="" style="font-size: 13px" value="<?php echo $rowMaterial['PROPORCAO_MATERIAL'] . ' %'; ?>" readonly>
              </div>
              <div class="col-md-3">
                <label for="adicionar" class="form-label" style="font-size: 10px; color:aqua;">Ação</label><br>
                <input type="submit" class="btn btn-black" id="" name="" value="ADICIONAR" style="font-size: 12px; width: 100px; color: black" disabled>
                <input type="reset"  class="btn btn-black" id="" name="" value="DESCARTAR" style="font-size: 12px; width: 100px; color: black" disabled>
          </div>             
            </div> <?php 
          }
        } ?>
      <form action="" method="POST"><br>
        <div class="row g-1">
          <div class="col-md-5">
            <label for="material" class="form-label" style="font-size: 10px; color:aqua">Material Componente</label>
            <select style="font-size: 13px;" class="form-select" id="material" name="material" autofocus>
              <option style="font-size: 13px" selected>Selecione o material a ser utilizado</option> <?php
                //Pesquisa de material
                $listaMateriais = $connDB->prepare("SELECT DISTINCT DESCRICAO_MP FROM mp_estoque");
                $listaMateriais->execute();
                // inclui nome dos materiais disponíveis como opções de seleção da tag <select>
                while($rowLista = $listaMateriais->fetch(PDO::FETCH_ASSOC)){?>
                  <option style="font-size: 13px"><?php echo $rowLista['DESCRICAO_MP']; ?></option> <?php
                }?>
            </select>          
          </div>
          <div class="col-md-2">
            <label for="proporcao" class="form-label" style="font-size: 10px; color:aqua">Proporção</label>
            <div class="input-group mb-2">
              <input type="number" class="form-control" id="proporcao" name="proporcao" style="font-size: 13px">
                <span class="input-group-text" style="font-size: 13px">%</span>
            </div>
          </div>
          <div class="col-md-3">
            <label for="adicionar" class="form-label" style="font-size: 10px; color:aqua;">Ação</label><br>
            <input type="submit" class="btn btn-success" id="adicionar" name="adicionar" value="ADICIONAR" style="font-size: 12px; width: 100px;">
            <input type="reset" class="btn btn-warning" id="descartar" name="descartar" value="DESCARTAR" style="font-size: 12px; width: 100px;"
              onclick="location.href='./31CadastroProduto.php'">
          </div>
        </div>
      </form> <?php
      // salva dados inseridos como um registro a cada componente
      $novoMaterial = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(!empty($novoMaterial['adicionar'])){
        $material    = strtoupper($novoMaterial['material']); $nomeProduto  = $_SESSION['nomeProduto'] ;
        $proporcao   = $novoMaterial['proporcao']                   ; $descrProduto = $_SESSION['descrProduto'];
        $capacidade  = $_SESSION['capacidade']                      ;
        $regMaterial = $connDB->prepare("INSERT INTO pf_tabela (NOME_FANTASIA, DESCRICAO_PRODUTO, MATERIAL_COMPONENTE, PROPORCAO_MATERIAL, CAPACIDADE_PROCESS)
                                                VALUES (:nomeProduto, :descrProduto, :materialComp, :proporcao, :capacidade)");
        $regMaterial->bindParam(':nomeProduto' , $nomeProduto , PDO::PARAM_STR);
        $regMaterial->bindParam(':descrProduto', $descrProduto, PDO::PARAM_STR);
        $regMaterial->bindParam(':materialComp', $material    , PDO::PARAM_STR);
        $regMaterial->bindParam(':proporcao'   , $proporcao   , PDO::PARAM_STR);
        $regMaterial->bindParam(':capacidade'  , $capacidade  , PDO::PARAM_STR);
        $regMaterial->execute(); $_SESSION['ciclo'] = 2;?><br>
        <div class="row g-1">
          <div class="col-md-3">
            <button class="btn btn-primary"   onclick="location.href='./32CadastroProduto.php'" style="width: 200px; float: right">Adicionar mais material</button>
          </div>
          <div class="col-md-3">
            <button class="btn btn-secondary" onclick="location.href='./31CadastroProduto.php'" style="width: 200px">Finalizar e Sair</button>
          </div>
        </div>
      <?php }?>
  </div>
</div>