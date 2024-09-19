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
    <form method="POST" action="#" id="cadastroProduto">
      <div class="row g-1">
        <div class="col-md-5">
          <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia do Produto</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="nomeProduto" name="nomeProduto" 
            placeholder="" autofocus>
        </div>
        <div class="col-md-7">
          <label for="descrProduto" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="descrProduto" name="descrProduto" 
            placeholder="" onchange="this.form.submit()">
        </div>
      </div>
    </form> <?php
      $primeira = filter_input_array(INPUT_POST, FILTER_DEFAULT); $continua = '1';
      if(!empty($primeira['descrProduto'])){ $fantasia = $primeira['nomeProduto']; $descricao = $primeira['descrProduto']; } ?>
      <div class="row">
        <div class="overflow-auto">
          <table class="table table-dark table-hover">
            <thead style="font-size: 12px">
              <tr>
                <th scope="col" style="width: 40%; text-align: center">Descrição do Material</th>
                <th scope="col" style="width: 10%; text-align: center">Proporção (%)</th>
                <th scope="col" style="width: 10%; text-align: center">Unidade</th>
                <th scope="col" style="width: 10%; text-align: center">Capacidade de Processamento</th>
              </tr>
            </thead>
            <tbody style="height: 80%; font-size: 11px;"> <?php 
              for($i = 0; $i < 5; $i++){ ?>
                <tr>
                  <td scope="col" style="width: 40%">
                    <select style="font-size: 14px;" class="form-select" id="material" name="material">
                      <option style="font-size: 14px" selected>Selecione o Material</option>
                      <?php
                        $query_material = $connDB->prepare("SELECT DESCRICAO_MP FROM mp_estoque");
                        $query_material->execute();
                        // inclui nome dos materiais como opções de seleção da tag <select>
                        while($rowMaterial = $query_material->fetch(PDO::FETCH_ASSOC)){?>
                          <option style="font-size: 14px"><?php echo $rowMaterial['DESCRICAO_MP']; ?></option> <?php
                        }  ?>
                    </select>
                  </td>
                  <td scope="col" style="width: 10%">
                    <input style="font-size: 12px;" type="number" class="form-control" 
                      id="proporcao" name="proporcao">
                  </td>
                  <td scope="col" style="width: 10%">
                    <select style="font-size: 14px;" class="form-select" id="uniMed" name="uniMed">
                      <option value="" selected>Selecione</option>
                      <option value="KG">KG</option>
                      <option value="LT">LT</option>
                      <option value="UN">Unidade</option>
                    </select>
                  </td>
                  <td scope="col" style="width: 10%">
                    <input style="font-size: 12px;" type="number" class="form-control" 
                      id="capacidade" name="capacidade">
                  </td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-1"></div>

      <div class="col-md-9"><textarea name="observacoes" id="observacoes" style="width: 100%; height: 100px">Observações</textarea></div><br>

      <div class="col-md-2" style="padding: 3px; text-align: center">
        <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar3" name="salvar3" value="Salvar">
      </div><br>
      <div class="col-md-2" style="padding: 3px; text-align: center">
        <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset3" name="reset3" value="Descartar"
          onclick="location.href='./31CadastroProduto.php'">
      </div>
    <?php
    $cadProdutoNovo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($cadProdutoNovo['salvar3'])){
      $query_produtoNovo = $connDB->prepare("INSERT INTO pf_tabela (NOME_PRODUTO, DESCRICAO_PRODUTO, MATERIAL_COMPONENTE,
        PROPORCAO_MATERIAL, UNIDADE_MEDIDA, CAPACIDADE_PROCESS, OBSERVACOES) VALUES (:nomeProduto, :descrProduto, :materialComponente, :proporcao, :unidade, :capacidade, :observacoes)");
      $query_clienteNovo->bindParam(':nomeProduto'       , $cadProdutoNovo['nomeProduto'] , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':descrProduto'      , $cadProdutoNovo['descrProduto'], PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':materialComponente', $cadProdutoNovo['matComp']     , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':proporcao'         , $cadProdutoNovo['proporcao']   , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':unidade'           , $cadProdutoNovo['uniMed']      , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':capacidade'        , $cadProdutoNovo['capProcess']  , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':observacoes'       , $cadProdutoNovo['observacoes']  , PDO::PARAM_STR);
      $query_ClienteNovo->execute();
      header('Location: ./31CadastroProduto.php');                                          
    } ?>
  </div>
</div>