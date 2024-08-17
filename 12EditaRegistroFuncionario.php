<?php
// inclusão do banco de dados e estrutura base da página web
include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

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
  $credencial = '';
  switch($cargo){
    case 'DIRETOR(A) EXECUTIVO(A)': $credencial = 7; break;  case 'GERENTE'               : $credencial = 6; break;
    case 'SUPERVISOR(A)'          : $credencial = 5; break;  case 'ANALISTA SR'           : $credencial = 4; break;
    case 'ANALISTA JR'            : $credencial = 3; break;  case 'ASSISTENTE'            : $credencial = 2; break;
    case 'LÍDER DE PRODUÇÃO'      : $credencial = 3; break;  case 'OPERADOR(A) DE MÁQUINA': $credencial = 2; break;
    case 'SERVENTE'               : $credencial = 1; break;  case 'MECÂNICO(A) CHEFE'     : $credencial = 4; break;
    case 'MECÂNICO'               : $credencial = 3; break;  case 'ENGENHEIRO(A)'         : $credencial = 5; break;
    case 'TECNICO(A)'             : $credencial = 3; break;  case 'PROGRAMADOR(A)'        : $credencial = 2; break;
    case 'MOTORISTA'              : $credencial = 1; break;  case 'FAXINEIRO'             : $credencial = 1; break;
  }

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
  
  header('Location: 06QuadroFuncionarios.php');
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
          <input style="font-size: 12px" type="text" class="form-control" id="CPFInput" name="cpfFunc" value="<?php echo $rowID['CPF'];?>" maxlength="11" oninput="criaMascara('CPF')">
        </div>

        <div class="col-md-3">
          <label for="rgFunc" class="form-label" style="font-size: 10px; color:aqua">R.G.</label>
          <input style="font-size: 12px" type="text" class="form-control" id="RGInput" name="rgFunc" value="<?php echo $rowID['RG'];?>" maxlength="9" oninput="criaMascara('RG')">
        </div>

        <div class="col-12">
          <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Nome Completo</label>
          <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="nomeFunc" name="nomeFunc" value="<?php echo $rowID['NOME_FUNCIONARIO'];?>" maxlength="120">
        </div>

        <div class="col-8">
          <label for="emailFunc" class="form-label" style="font-size: 10px; color:aqua">Email</label>
          <input style="font-size: 12px; text-transform:lowercase" type="text" class="form-control" id="emailFunc" name="emailFunc" value="<?php echo $rowID['EMAIL'];?>">
        </div>

        <div class="col-md-4">
          <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone de Contato</label>
          <input style="font-size: 12px" type="text" class="form-control" id="CelularInput" name="telefone" value="<?php echo $rowID['TELEFONE'];?>" maxlength="11" oninput="criaMascara('Celular')">
        </div>

        <div class="col-md-2">
          <label for="dataAdmi" class="form-label" style="font-size: 10px; color:aqua">Data de Admissão</label>
          <input style="font-size: 12px" type="date" class="form-control" id="dataAdmi" name="dataAdmi" value="<?php echo $rowID['DATA_ADMISSAO'];?>">
        </div>

        <div class="col-md-4">
          <label for="departamento" class="form-label" style="font-size: 10px; color:aqua">Departamento</label>
          <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="departamento" name="departamento" value="<?php echo $rowID['DEPARTAMENTO'];?>">
        </div>

        <div class="col-md-4">
          <label for="cargo" class="form-label" style="font-size: 10px; color:aqua">Cargo Ocupado</label>
          <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="cargo" name="cargo" value="<?php echo $rowID['CARGO'];?>">
        </div>

        <div class="col-10">
          <br><br>
          <label for="ruaRes" class="form-label" style="font-size: 10px; color:aqua">Endereço Residencial: Rua/Avenida</label>
          <input style="font-size: 12px" type="text" class="form-control" id="ruaRes" name="ruaRes" value="<?php echo $rowID['RUA_RES'];?>">
        </div>

        <div class="col-md-2">
          <br><br>
          <label for="numRes" class="form-label" style="font-size: 10px; color:aqua">Número da Residência</label>
          <input style="font-size: 12px" type="text" class="form-control" id="numRes" name="numRes" value="<?php echo $rowID['NUM_RES'];?>"maxlength="6">
        </div>

        <div class="col-md-2">
          <label for="cplRes" class="form-label" style="font-size: 10px; color:aqua">Complemento</label>
          <input style="font-size: 12px" type="text" class="form-control" id="cplRes" name="cplRes" value="<?php echo $rowID['COMPLEMENTO'];?>">
        </div>

        <div class="col-md-4">
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
    