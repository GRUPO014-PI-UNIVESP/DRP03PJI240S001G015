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
       time = setTimeout(deslogar, 3600000);
     }
  };
  inactivityTime();
</script>
<!-- Área Principal ----------------------------------------------------------------------------------------------------------------------------------------------------->
<div class="main">
  <div class="container-fluid">     
<!---- Novo Cliente ----------------------------------------------------------------------------------------------------------------------------------------------------->  
      <div class="tab-pane fade" id="newClient-tab-pane" role="tabpanel" aria-labelledby="newClient-tab" tabindex="0">
        <form class="row g-4" method="POST" action="#" id="cadastroCliente">
          <div class="col-md-4">
            <label for="fantasia" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia da Empresa</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="fantasia" name="fantasia" 
                   placeholder="">
            <p style="font-size: 10px; color: grey">Será modificado para caixa alta</p>
          </div>
          <div class="col-md-8">
            <label for="razaoSocial" class="form-label" style="font-size: 10px; color:aqua">Razão Social</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="razaoSocial" name="razaoSocial" 
                   placeholder="">
            <p style="font-size: 10px; color: grey">Será modificado para caixa alta</p>
          </div>

          <div class="col-md-2">
            <label for="cnpj" class="form-label" style="font-size: 10px; color:aqua">CNPJ</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="CNPJInput" name="cnpj" 
                   placeholder="" maxlength="14" onkeyup="criaMascara('CNPJ')">
            <p style="font-size: 10px; color: grey">Somente números</p>                 
          </div>
          <div class="col-md-2">
            <label for="inscrEstadual" class="form-label" style="font-size: 10px; color:aqua">Inscrição Estadual</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="IEInput" name="inscrEstadual" 
                   placeholder="" maxlength="12" onkeyup="criaMascara('IE')">
            <p style="font-size: 10px; color: grey">Somente números</p>
          </div>
          <div class="col-md-6">
            <label for="cidade" class="form-label" style="font-size: 10px; color:aqua">Cidade</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="cidade" name="cidade" 
                   placeholder="">
            <p style="font-size: 10px; color: grey">Será modificado para caixa alta</p>
          </div>
          <div class="col-md-2">
            <label for="estado" class="form-label" style="font-size: 10px; color:aqua">Estado (U.F.)</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="estado" name="estado" 
                   placeholder="" maxlength="2">
            <p style="font-size: 10px; color: grey">Será modificado para caixa alta</p>
          </div>
          <div class="col-md-12">
            <label for="endereco" class="form-label" style="font-size: 10px; color:aqua">Endereço Completo</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="endereco" name="endereco" 
                   placeholder="">
            <p style="font-size: 10px; color: grey">Será modificado para caixa alta</p>
          </div>

          <div class="col-md-9">
            <label for="email" class="form-label" style="font-size: 10px; color:aqua">E-Mail</label>
            <input style="font-size: 12px; text-transform: lowercase" type="email" class="form-control" id="email" name="email" 
                   placeholder="">
          </div>

          <div class="col-md-3">
            <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone Fixo</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="FixoInput" name="telefone" 
                   placeholder="" maxlength="10" onkeyup="criaMascara('Fixo')">
            <p style="font-size: 10px; color: grey">Somente números: formato (xx) xxxx-xxxx</p>
          </div>

          <div class="col-md-9">
            <label for="nomeRepresentante" class="form-label" style="font-size: 10px; color:aqua">Nome do Representante</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="nomeRepresentante" name="nomeRepresentante" 
                   placeholder="">
            <p style="font-size: 10px; color: grey">Será modificado para caixa alta</p>
          </div>

          <div class="col-md-3">
            <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone Celular</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="CelularInput" name="telefone" 
                   placeholder="" maxlength="11" onkeyup="criaMascara('Celular')">
            <p style="font-size: 10px; color: grey">Somente números: formato (xx) xxxxx-xxxx</p>
          </div>

          <div class="col-md-2" style="padding: 3px">
            <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar2" name="salvar2" value="Salvar">
          </div>

          <div class="col-md-3" style="padding: 3px">
            <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar"
                   onclick="location.href='./30EntradaPedido.php'">
          </div>
        </form>
      </div>
        <?php
        $cadCliente = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(!empty($cadCliente['salvar2'])){
          $query_clienteNovo = $connDB->prepare("INSERT INTO pf_cliente (NOME_FANTASIA, RAZAO_SOCIAL, CNPJ, INSCR_ESTADUAL, CIDADE, ESTADO,
                                                                                ENDERECO, TELEFONE, EMAIL, REPRESENTANTE) 
                                                              VALUES (:fantasia, :razaoSocial, :cnpj, :inscrEstadual, :cidade, :estado,
                                                                      :endereco, :telefone, :email, :representante)");
          $query_clienteNovo->bindParam(':fantasia'     , $cadCliente['fantasia']         , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':razaoSocial'  , $cadCliente['razaoSocial']      , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':cnpj'         , $cadCliente['cnpj']             , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':inscrEstadual', $cadCliente['inscrEstadual']    , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':cidade'       , $cadCliente['cidade']           , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':estado'       , $cadCliente['estado']           , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':endereco'     , $cadCliente['endereco']         , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':telefone'     , $cadCliente['telefone']         , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':email'        , $cadCliente['email']            , PDO::PARAM_STR);
          $query_clienteNovo->bindParam(':representante', $cadCliente['nomeRepresentante'], PDO::PARAM_STR);
          $query_ClienteNovo->execute();
          header('Location: ./30EntradaPedido.php');                                          
        }
        ?>
<!----- Novo Produto ---------------------------------------------------------------------------------------------------------------------------------------------------->  
      <div class="tab-pane fade" id="newProd-tab-pane" role="tabpanel" aria-labelledby="newProd-tab" tabindex="0">
        <form class="row g-4" method="POST" action="#" id="cadastroProduto">

          <div class="col-md-4">
            <label for="nomeProduto" class="form-label" style="font-size: 10px; color:aqua">Nome Fantasia do Produto</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="nomeProduto" name="nomeProduto" 
                  placeholder="" autofocus>
          </div>
          <div class="col-md-8">
            <label for="descrProduto" class="form-label" style="font-size: 10px; color:aqua">Descrição do Produto</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="descrProduto" name="descrProduto" 
                  placeholder="" required>
          </div>

          <div class="col-md-8">
            <label for="matComp" class="form-label" style="font-size: 10px; color:aqua">Material de Composição 1</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="matComp" name="matComp" 
                  placeholder="" required>
          </div>
          <div class="col-md-2">
            <label for="proporcao" class="form-label" style="font-size: 10px; color:aqua">Proporção na Composição [ % ]</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="proporcao" name="proporcao" 
                  placeholder="" required>
          </div>
          <div class="col-md-2">
            <label for="uniMed" class="form-label" style="font-size: 10px; color:aqua">Unidade de Media</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="uniMed" name="uniMed" 
                  placeholder="" required>
          </div>

          <div class="col-md-2">
            <label for="capProcess" class="form-label" style="font-size: 10px; color:aqua">Capacidade de Processamento</label>
            <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="capProcess" name="capProcess" 
                  placeholder="" required>
          </div>

          <div class="col-md-1">
          </div>
          <div class="col-md-9">
            <textarea name="observacoes" id="observacoes" style="width: 100%; height: 100px">Observações</textarea>
          </div>

          <br>
          <div class="col-md-2" style="padding: 3px; text-align: center">
            <input style="width: 140px;" class="btn btn-primary" type="submit" id="salvar3" name="salvar3" value="Salvar">
          </div>
          <br>
          <div class="col-md-2" style="padding: 3px; text-align: center">
            <input style="width: 140px;" class="btn btn-secondary" type="reset" id="reset3" name="reset3" value="Descartar"
                  onclick="location.href='./30EntradaPedido.php'">
          </div>
        </form>
<?php
$cadProdutoNovo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if(!empty($cadProdutoNovo['salvar3'])){
  $query_produtoNovo = $connDB->prepare("INSERT INTO pf_tabela (NOME_PRODUTO, DESCRICAO_PRODUTO, MATERIAL_COMPONENTE,
                                                                       PROPORCAO_MATERIAL, UNIDADE_MEDIDA, CAPACIDADE_PROCESS, OBSERVACOES) 
                                                       VALUES (:nomeProduto, :descrProduto, :materialComponente, :proporcao, :unidade,
                                                               :capacidade, :observacoes)");
  $query_clienteNovo->bindParam(':nomeProduto'       , $cadProdutoNovo['nomeProduto'] , PDO::PARAM_STR);
  $query_clienteNovo->bindParam(':descrProduto'      , $cadProdutoNovo['descrProduto'], PDO::PARAM_STR);
  $query_clienteNovo->bindParam(':materialComponente', $cadProdutoNovo['matComp']     , PDO::PARAM_STR);
  $query_clienteNovo->bindParam(':proporcao'         , $cadProdutoNovo['proporcao']   , PDO::PARAM_STR);
  $query_clienteNovo->bindParam(':unidade'           , $cadProdutoNovo['uniMed']      , PDO::PARAM_STR);
  $query_clienteNovo->bindParam(':capacidade'        , $cadProdutoNovo['capProcess']  , PDO::PARAM_STR);
  $query_clienteNovo->bindParam(':observacoes'       , $cadProdutoNovo['observacoes']  , PDO::PARAM_STR);
  $query_ClienteNovo->execute();
  header('Location: ./30EntradaPedido.php');                                          
}
?>
      </div>
    </div>
  </div>
</div>