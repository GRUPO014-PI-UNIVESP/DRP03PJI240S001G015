<?php
// inclusão do banco de dados e estrutura base da página web
include_once 'aplicativo/ConnectDB.php';
include_once 'aplicativo/EstruturaPrincipal.php';

// busca informações da tabela 'departamentos'
$query_depto = $connDB->prepare("SELECT * FROM departamentos");
$query_depto->execute();
// busca informações da tabela 'cargos'
$query_cargo = $connDB->prepare("SELECT * FROM cargos");
$query_cargo->execute();

//verifica identificador do registro para busca no banco de dados
if(!empty($_GET['id'])){

  $id_edit   = $_GET['id'];
  $queryUser = $connDB->prepare("SELECT * FROM quadro_funcionarios WHERE ID_FUNCIONARIO = $id_edit LIMIT 1");
  $queryUser->execute();
  $rowID     = $queryUser->fetch(PDO::FETCH_ASSOC);
}
// capta os dados inseridos no formulário Login
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//verifica se foi digitado dados
if(!empty($dados['submit'])){

  //atribuir valores dos campos para variáveis
  $cargo       = strtoupper($dados['cargo']); $departamento = strtoupper($dados['departamento'])      ; $nomeFunc = strtoupper($dados['nomeFunc']);
  $responsavel = $_SESSION['nome_func']     ; $dataNasc = date('Y-m-d', strtotime($dados['dataNasc'])); $dataAdmi = date('Y-m-d', strtotime($dados['dataAdmi']));
  
  if(!empty($dados['cpfFunc']))  {$cpf      = $dados['cpfFunc'];              }else{$cpf      = 'Nada Consta';}
  if(!empty($dados['rgFunc']))   {$rg       = $dados['rgFunc'];               }else{$rg       = 'Nada Consta';}
  if(!empty($dados['telefone'])) {$telefone = $dados['telefone'];             }else{$telefone = 'Nada Consta';}
  if(!empty($dados['emailFunc'])){$email    = strtolower($dados['emailFunc']);}else{$email    = 'Nada Consta';}
  if(!empty($dados['ruaRes']))   {$ruaRes   = $dados['ruaRes'];               }else{$ruaRes   = 'Nada Consta';}
  if(!empty($dados['numRes']))   {$numRes   = $dados['numRes'];               }else{$numRes   = 'Nada Consta';}
  if(!empty($dados['cplRes']))   {$cplRes   = $dados['cplRes'];               }else{$cplRes   = 'Nada Consta';}
  if(!empty($dados['bairro']))   {$bairro   = strtoupper($dados['bairro']);   }else{$bairro   = 'Nada Consta';}
  if(!empty($dados['cidade']))   {$cidade   = strtoupper($dados['cidade']);   }else{$cidade   = 'Nada Consta';}
  if(!empty($dados['uf']))       {$estado   = strtoupper($dados['uf']);       }else{$estado   = 'NC';}  

  // definir credencial de acordo com cargo
  $query_cred = $connDB->prepare("SELECT CREDENCIAL FROM cargos WHERE CARGO = :cargo LIMIT 1");
  $query_cred->bindParam(':cargo', $dados['cargo'], PDO::PARAM_STR);
  $query_cred->execute();
  $result_cred = $query_cred->fetch(PDO::FETCH_ASSOC);
  $credencial = $result_cred['CREDENCIAL'];

  $registra = $connDB->prepare("UPDATE quadro_funcionarios 
                                SET    NOME_FUNCIONARIO = :nomeFunc, DATA_ADMISSAO = :dataAdmi, CARGO = :cargo, DEPARTAMENTO = :departamento, CREDENCIAL = :credencial, 
                                       DATA_NASCIMENTO = :dataNasc, CPF = :cpf, RG = :rg, TELEFONE = :telefone, EMAIL = :email, RUA_RES = :ruaRes, NUM_RES = :numRes, 
                                       COMPLEMENTO = :cplRes, BAIRRO = :bairro, CIDADE = :cidade, UF = :estado, RESPONSAVEL_CADASTRO = :responsavel 
                                WHERE  ID_FUNCIONARIO = :idFunc");

  $registra->bindParam(':nomeFunc'    , $nomeFunc   , PDO::PARAM_STR);   $registra->bindParam(':dataAdmi'    , $dataAdmi    , PDO::PARAM_STR);
  $registra->bindParam(':cargo'       , $cargo      , PDO::PARAM_STR);   $registra->bindParam(':departamento', $departamento, PDO::PARAM_STR);
  $registra->bindParam(':credencial'  , $credencial , PDO::PARAM_INT);   $registra->bindParam(':dataNasc'    , $dataNasc    , PDO::PARAM_STR);
  $registra->bindParam(':cpf'         , $cpf        , PDO::PARAM_STR);   $registra->bindParam(':rg'          , $rg          , PDO::PARAM_STR);
  $registra->bindParam(':telefone'    , $telefone   , PDO::PARAM_STR);   $registra->bindParam(':email'       , $email       , PDO::PARAM_STR);
  $registra->bindParam(':ruaRes'      , $ruaRes     , PDO::PARAM_STR);   $registra->bindParam(':numRes'      , $numRes      , PDO::PARAM_STR);
  $registra->bindParam(':cplRes'      , $cplRes     , PDO::PARAM_STR);   $registra->bindParam(':bairro'      , $bairro      , PDO::PARAM_STR);
  $registra->bindParam(':cidade'      , $cidade     , PDO::PARAM_STR);   $registra->bindParam(':estado'      , $estado      , PDO::PARAM_STR);
  $registra->bindParam(':responsavel' , $responsavel, PDO::PARAM_STR);   $registra->bindParam(':idFunc'      , $id_edit     , PDO::PARAM_INT);

  $registra->execute();
  
  header('Location: aplicativo/06QuadroFuncionarios.php');
}
//se houver erro de entrada mostra erro na página
if(isset($_SESSION['msg'])){
   echo  $_SESSION['msg'];
   unset($_SESSION['msg']);
}
?>
<!-- Área Principal -->
  <div class="main">
    <div class="container">
      <p style="margin-left: 2%; font-size: 25px; color: whitesmoke">Departamento Administrativo - Atualização de Dados do Funcionário</p>

      <form class="row g-2" method="POST" action="#">

        <div class="col-md-2">
          <label for="idFunc" class="form-label" style="color:aqua; font-size: 10px">Cadastro No.</label>
          <input style="text-align:right; font-size: 12px" type="number" class="form-control" id="idFunc" name="idFunc" value="<?php echo $rowID['ID_FUNCIONARIO'];?>" readonly>
        </div>

        <div class="col-md-2">
          <label for="dataNasc" class="form-label" style="font-size: 10px; color:aqua">Data de Nascimento</label>
          <input style="font-size: 12px" type="date" class="form-control" id="dataNasc" name="dataNasc" value="<?php echo $rowID['DATA_NASCIMENTO'];?>" autofocus>
        </div>

        <div class="col-md-3">
          <label for="cpfFunc" class="form-label" style="font-size: 10px; color:aqua">C.P.F.</label>
          <input style="font-size: 12px" type="text" class="form-control" id="CPFInput" name="cpfFunc" value="<?php echo $rowID['CPF'];?>" maxlength="11" onkeyup="criaMascara('CPF')">
          <p style="font-size: 10px; color: grey">Somente números</p>
        </div>

        <div class="col-md-3">
          <label for="rgFunc" class="form-label" style="font-size: 10px; color:aqua">R.G.</label>
          <input style="font-size: 12px" type="text" class="form-control" id="RGInput" name="rgFunc" value="<?php echo $rowID['RG'];?>" maxlength="9" onkeyup="criaMascara('RG')">
          <p style="font-size: 10px; color: grey">Somente números</p>
        </div>

        <div class="col-12">
          <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Nome Completo</label>
          <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="nomeFunc" name="nomeFunc" value="<?php echo $rowID['NOME_FUNCIONARIO'];?>" maxlength="120">
          <p style="font-size: 10px; color: grey">Tamanho máximo de 120 caracteres</p>
        </div>

        <div class="col-8">
          <label for="emailFunc" class="form-label" style="font-size: 10px; color:aqua">Email</label>
          <input style="font-size: 12px; text-transform:lowercase" type="text" class="form-control" id="emailFunc" name="emailFunc" value="<?php echo $rowID['EMAIL'];?>">
          <p style="font-size: 10px; color: grey">Tamanho máximo de 120 caracteres</p>
        </div>

        <div class="col-md-4">
          <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone de Contato</label>
          <input style="font-size: 12px" type="text" class="form-control" id="CelularInput" name="telefone" value="<?php echo $rowID['TELEFONE'];?>" maxlength="11" onkeyup="criaMascara('Celular')">
          <p style="font-size: 10px; color: grey">Somente números incluindo DDD</p>
        </div>

        <div class="col-md-2">
          <label for="dataAdmi" class="form-label" style="font-size: 10px; color:aqua">Data de Admissão</label>
          <input style="font-size: 12px" type="date" class="form-control" id="dataAdmi" name="dataAdmi" value="<?php echo $rowID['DATA_ADMISSAO'];?>">
        </div>

        <div class="col-md-4">
          <label for="departamento" class="form-label" style="font-size: 10px; color:aqua">Departamento</label>
          <select style="font-size: 12px" id="departamento" class="form-select" name="departamento" value="<?php echo $rowID['DEPARTAMENTO'];?>">
            <option style="font-size: 12px" selected><?php echo $rowID['DEPARTAMENTO'];?></option>
            <?php
              while($selDepto = $query_depto->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 12px"><?php echo $selDepto['DEPARTAMENTO']; ?></option> <?php
              }?>
          </select>
        </div>

        <div class="col-md-4">
          <label for="cargo" class="form-label" style="font-size: 10px; color:aqua">Cargo</label>
          <select style="font-size: 12px" id="cargo" class="form-select" name="cargo" value="<?php echo $rowID['CARGO'];?>">
            <option style="font-size: 12px" selected><?php echo $rowID['CARGO'];?></option>
            <?php
              while($selCargo = $query_cargo->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 12px"><?php echo $selCargo['CARGO']; ?></option> <?php
              }?>               
          </select>
        </div>

        <div class="col-10">
          <label for="ruaRes" class="form-label" style="font-size: 10px; color:aqua">Endereço Residencial: Rua/Avenida</label>
          <input style="font-size: 12px" type="text" class="form-control" id="ruaRes" name="ruaRes" value="<?php echo $rowID['RUA_RES'];?>">
        </div>

        <div class="col-md-2">
          <label for="numRes" class="form-label" style="font-size: 10px; color:aqua">Número da Residência</label>
          <input style="font-size: 12px" type="number" class="form-control" id="numRes" name="numRes" value="<?php echo $rowID['NUM_RES'];?>"maxlength="6">
          <p style="font-size: 10px; color: grey">Somente números</p>
        </div>

        <div class="col-md-3">
          <label for="cplRes" class="form-label" style="font-size: 10px; color:aqua">Complemento</label>
          <input style="font-size: 12px" type="text" class="form-control" id="cplRes" name="cplRes" value="<?php echo $rowID['COMPLEMENTO'];?>">
          <p style="font-size: 10px; color: grey">Apto, Bloco, Casa, Ed. etc</p>
        </div>

        <div class="col-md-3">
          <label for="bairro" class="form-label" style="font-size: 10px; color:aqua">Bairro</label>
          <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="bairro" name="bairro" value="<?php echo $rowID['BAIRRO'];?>">
        </div>

        <div class="col-md-4">
          <label for="cidade" class="form-label" style="font-size: 10px; color:aqua">Cidade</label>
          <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="cidade" name="cidade" value="<?php echo $rowID['CIDADE'];?>">
        </div>

        <div class="col-md-2">
          <label for="uf" class="form-label" style="font-size: 10px; color:aqua">Estado (U.F.)</label>
          <input style="font-size: 12px; text-transform: uppercase" type="text" class="form-control" id="uf" name="uf" value="<?php echo $rowID['UF'];?>" maxlength="2">
        </div>

        <!-- Botão para confirmar -->
        <div class="col-md-4"><br>
          <!-- Aciona Modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 280px">Confirmar Edição</button>
          <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 style="color: aqua" class="modal-title fs-5" id="exampleModalLabel">Atualizar Dados Cadastrais</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p>Confirme clicando em Salvar ou descarte as informações fechando sem salvar</p>
                  </div>
                  <div class="modal-footer">
                    <button style="width: 210px" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar sem Salvar</button>
                    <input style="width: 210px" class="btn btn-primary" type="submit" id="submit" name="submit" value="Salvar">
                  </div>
                </div>
              </div>
            </div>            
        </div>
        <div class="col-md-4"><br>
          <input class="btn btn-secondary" type="reset"  id="reset"  name="reset"  value="Descartar Dados e Sair" style="width: 280px" onclick="location.href='06QuadroFuncionarios.php'">
        </div>
      </form>
    </div>
  </div>
    