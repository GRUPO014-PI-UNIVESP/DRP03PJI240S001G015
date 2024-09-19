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
       time = setTimeout(deslogar, 300000);
     }
  };
  inactivityTime();
</script>
<!-- Área Principal ----------------------------------------------------------------------------------------------------------------------------------------------------->
<div class="main">
  <div class="container-fluid"><br>
    <div class="row g-4">
      <div class="col-md-11">
        <h5>Cadastro de Novo Cliente</h5>
      </div>
      <div class="col-md-1">
        <button class="btn btn-info" onclick="location.href='./00SeletorAdministrativo.php'">Voltar</button>
      </div>
    </div>       
    <form method="POST" action="#" id="cadastroCliente">
      <div class="row g-1">
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
          <p style="font-size: 10px; color: grey">Somente números 14 dígitos</p>                 
        </div>
        <div class="col-md-2">
          <label for="inscrEstadual" class="form-label" style="font-size: 10px; color:aqua">Inscrição Estadual</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="IEInput" name="inscrEstadual" 
            placeholder="" maxlength="12" onkeyup="criaMascara('IE')">
          <p style="font-size: 10px; color: grey">Somente números 12 dígitos</p>
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
          <p style="font-size: 10px; color: grey">Digite corretamente o endereço no formato "endereço@provedor.domínio"</p>
        </div>
        <div class="col-md-3">
          <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone Fixo</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="FixoInput" name="Fixo" 
            placeholder="" maxlength="10" onkeyup="criaMascara('Fixo')">
          <p style="font-size: 10px; color: grey">Somente números: 10 dígitos formato (xx) xxxx-xxxx</p>
        </div>
        <div class="col-md-9">
          <label for="nomeRepresentante" class="form-label" style="font-size: 10px; color:aqua">Nome do Representante</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="nomeRepresentante" name="nomeRepresentante" 
            placeholder="">
          <p style="font-size: 10px; color: grey">Será modificado para caixa alta</p>
        </div>
        <div class="col-md-3">
          <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone Celular</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="CelularInput" name="Celular" 
            placeholder="" maxlength="11" onkeyup="criaMascara('Celular')">
          <p style="font-size: 10px; color: grey">Somente números: 11 dígitos formato (xx) xxxxx-xxxx</p>
        </div>
        <div class="col-md-5">
          <input style="width: 140px; float: right" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar"
            onclick="location.href='./30CadastroCliente.php'">
        </div>
        <div class="col-md-7">
          <!-- Gatilho do Modal -->
          <button style="width: 250px; float: left" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Confirmar Dados
          </button>
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Registro de Novo Cliente</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Caso as informações estejam corretas, salve o registro no banco de dados
                </div>
                <S class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                  <input class="btn btn-primary" type="submit" id="salvar2" name="salvar2" value="Salvar Registro"
                    style="width: 250px">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div> <?php
    $cadCliente = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($cadCliente['salvar2'])){
      $fantasia      = strtoupper($cadCliente['fantasia']);
      $razaoSocial   = strtoupper($cadCliente['razaoSocial']);
      $cnpj          = $cadCliente['cnpj'];
      $inscrEstadual = $cadCliente['inscrEstadual'];
      $cidade        = strtoupper($cadCliente['cidade']);
      $estado        = strtoupper($cadCliente['estado']);
      $endereco      = strtoupper($cadCliente['endereco']);
      $email         = strtolower($cadCliente['email']);
      $telFixo       = $cadCliente['Fixo'];
      $representante = strtoupper($cadCliente['nomeRepresentante']);      
      $telCelular    = $cadCliente['Celular'];

      $query_clienteNovo = $connDB->prepare("INSERT INTO pf_cliente (NOME_FANTASIA, RAZAO_SOCIAL, CNPJ, INSCR_ESTADUAL, CIDADE, ESTADO,
        ENDERECO, EMAIL, FIXO, NOME_REPRESENTANTE, CELULAR) 
        VALUES (:fantasia, :razaoSocial, :cnpj, :inscrEstadual, :cidade, :estado, :endereco, :email, :fixo, :representante, :celular)");
      $query_clienteNovo->bindParam(':fantasia'     , $fantasia     , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':razaoSocial'  , $razaoSocial  , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':cnpj'         , $cnpj         , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':inscrEstadual', $inscrEstadual, PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':cidade'       , $cidade       , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':estado'       , $estado       , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':endereco'     , $endereco     , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':email'        , $email        , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':fixo'         , $telFixo      , PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':representante', $representante, PDO::PARAM_STR);
      $query_clienteNovo->bindParam(':celular'      , $telCelular   , PDO::PARAM_STR);
      $query_ClienteNovo->execute();
      header('Location: ./30EntradaPedido.php');                                          
    } ?>
</div>