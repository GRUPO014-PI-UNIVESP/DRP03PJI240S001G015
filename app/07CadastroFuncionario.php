<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Cadastro de Funcionário'; include_once './RastreadorAtividades.php';

// verifica o identificador do último registro
$queryLast = $connDB->prepare("SELECT MAX(ID_FUNCIONARIO) AS ID_FUNCIONARIO FROM quadro_funcionarios");
$queryLast->execute(); $rowID = $queryLast->fetch(PDO::FETCH_ASSOC); $novoID = $rowID['ID_FUNCIONARIO'] + 1;

// busca dos departamentos da tabela 'departamentos'
$query_depto = $connDB->prepare("SELECT * FROM departamentos");
$query_depto->execute();

// busca todos os cargos registrados na tabela 'cargos'
$query_cargo = $connDB->prepare("SELECT * FROM cargos");
$query_cargo->execute();

// gerador de usuário e senha provisórios 
$geraIDuser   = substr(str_shuffle("abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
$geraPassword = substr(str_shuffle("0123456789"), 0, 6);

// capta os dados inseridos no formulário Login
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// verifica se foi digitado dados
if(!empty($dados['submit'])){
  // busca dados cadastrados
  $result = $connDB->prepare("SELECT NOME_FUNCIONARIO, DATA_NASCIMENTO FROM   quadro_funcionarios WHERE  NOME_FUNCIONARIO = :nomeFunc AND DATA_NASCIMENTO = :dataNasc LIMIT 1");
  // atribui valor do campo para o link de dados :usuario
  $result->bindParam(':nomeFunc', $dados['nomeFunc'], PDO::PARAM_STR);
  $result->bindParam(':dataNasc', $dados['dataNasc'], PDO::PARAM_STR);
  $result->execute();

  // verifica se foi encontrado algum registro contendo nome e data de nascimento idênticos
  if(($result) AND ($result->rowCount() == 1)){
  // abre caixa de alerta com mensagem de erro
    $mensagemErro = 'Erro: O nome e data nascimento correspondente já existe no banco de dados!';
    echo "<script type='text/javascript'>alert('$mensagemErro');</script>";

  }else{
  // atribuir valores dos campos para variáveis
    $cargo       = $dados['cargo'];        $departamento = $dados['departamento']; $nomeFunc = strtoupper($dados['nomeFunc']);
    $idUser      = $geraIDuser;            $passUser     = $geraPassword;          $dataNasc = date('Y-m-d', strtotime($dados['dataNasc']));
    $responsavel = $_SESSION['nome_func']; $dataAdmi     = date('Y-m-d', strtotime($dados['dataAdmi']));
  
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
        
  // criptografar senha e usuário geradas
    $usuario      = password_hash($geraIDuser  , PASSWORD_DEFAULT);
    $senha        = password_hash($geraPassword, PASSWORD_DEFAULT);

  // definir credencial de acordo com cargo
    $query_cred = $connDB->prepare("SELECT CREDENCIAL FROM cargos WHERE CARGO = :cargo LIMIT 1");
    $query_cred->bindParam(':cargo', $dados['cargo'], PDO::PARAM_STR);
    $query_cred->execute();
    $result_cred = $query_cred->fetch(PDO::FETCH_ASSOC);
    $credencial  = $result_cred['CREDENCIAL'];

    $registra = $connDB->prepare("INSERT INTO quadro_funcionarios (NOME_FUNCIONARIO, DATA_ADMISSAO, CARGO, DEPARTAMENTO,
                                  CREDENCIAL, USUARIO, SENHA, ID_USUARIO, SENHA_USUARIO, DATA_NASCIMENTO, CPF, RG, TELEFONE,
                                  EMAIL, RUA_RES, NUM_RES, COMPLEMENTO, BAIRRO, CIDADE, UF, RESPONSAVEL_CADASTRO)
                                VALUES (:nomeFunc, :dataAdmi, :cargo, :departamento, :credencial, :usuario, :senha, :idUser, :passUser, 
                                  :dataNasc, :cpf, :rg, :telefone, :email, :ruaRes, :numRes, :cplRes, :bairro, :cidade, :estado, :responsavel)");

    $registra->bindParam(':nomeFunc'    , $nomeFunc,     PDO::PARAM_STR);   $registra->bindParam(':dataAdmi'    , $dataAdmi,     PDO::PARAM_STR);
    $registra->bindParam(':cargo'       , $cargo,        PDO::PARAM_STR);   $registra->bindParam(':departamento', $departamento, PDO::PARAM_STR);
    $registra->bindParam(':credencial'  , $credencial,   PDO::PARAM_INT);   $registra->bindParam(':usuario'     , $idUser,       PDO::PARAM_STR);
    $registra->bindParam(':senha'       , $passUser,     PDO::PARAM_STR);   $registra->bindParam(':idUser'      , $usuario,      PDO::PARAM_STR);
    $registra->bindParam(':passUser'    , $senha,        PDO::PARAM_STR);   $registra->bindParam(':dataNasc'    , $dataNasc,     PDO::PARAM_STR);
    $registra->bindParam(':cpf'         , $cpf,          PDO::PARAM_STR);   $registra->bindParam(':rg'          , $rg,           PDO::PARAM_STR);
    $registra->bindParam(':telefone'    , $telefone,     PDO::PARAM_STR);   $registra->bindParam(':email'       , $email,        PDO::PARAM_STR);
    $registra->bindParam(':ruaRes'      , $ruaRes,       PDO::PARAM_STR);   $registra->bindParam(':numRes'      , $numRes,       PDO::PARAM_STR);
    $registra->bindParam(':cplRes'      , $cplRes,       PDO::PARAM_STR);   $registra->bindParam(':bairro'      , $bairro,       PDO::PARAM_STR);
    $registra->bindParam(':cidade'      , $cidade,       PDO::PARAM_STR);   $registra->bindParam(':estado'      , $estado,       PDO::PARAM_STR);
    $registra->bindParam(':responsavel' , $responsavel,  PDO::PARAM_STR);
    $registra->execute();
    
    header('Location: ./07CadastroFuncionario.php');
  }
}
//se houver erro de entrada mostra erro na página
if(isset($_SESSION['msg'])){ echo  $_SESSION['msg']; unset($_SESSION['msg']); }
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () { let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php';  }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 600000); }
  }; inactivityTime();
</script>
<!-- Área Principal -->
  <div class="main">
    <div class="container">
      <p style="margin-left: 2%; font-size: 20px; color: whitesmoke">Departamento Administrativo - Cadastro de Novo Funcionário</p>
      <form class="row g-2" method="POST" action="#">
        <div class="col-md-2">
          <label for="idFunc" class="form-label" style="color:aqua; font-size: 10px">Cadastro No.</label>
          <input style="text-align: center; font-size: 12px; background: rgba(0,0,0,0.3)" type="number" class="form-control" id="idFunc" name="idFunc" value="<?php echo $novoID?>" readonly>
          <p style="font-size: 10px; color: grey">Campo não editável</p>
        </div>

        <div class="col-md-2">
          <label for="dataNasc" class="form-label" style="font-size: 10px; color:aqua">Data de Nascimento</label>
          <input style="font-size: 12px; background: rgba(0,0,0,0.3)" type="date" class="form-control" id="dataNasc" name="dataNasc" required autofocus>
        </div>

        <div class="col-md-3">
          <label for="cpfFunc" class="form-label" style="font-size: 10px; color:aqua">C.P.F.</label>
          <input style="font-size: 12px; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="CPFInput" name="cpfFunc" placeholder="Opcional, somente números" maxlength="11" onkeyup="criaMascara('CPF')">
          <p style="font-size: 10px; color: grey">Somente números</p>
        </div>

        <div class="col-md-3">
          <label for="rgFunc" class="form-label" style="font-size: 10px; color:aqua">R.G.</label>
          <input style="font-size: 12px; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="RGInput" name="rgFunc" placeholder="Opcional, somente números" maxlength="9" onkeyup="criaMascara('RG')">
          <p style="font-size: 10px; color: grey">Somente números</p>
        </div>

        <div class="col-12">
          <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Nome Completo</label>
          <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="nomeFunc" name="nomeFunc" placeholder="" maxlength="120" required>
          <p style="font-size: 10px; color: grey">Tamanho máximo de 120 caracteres</p>
        </div>

        <div class="col-8">
          <label for="emailFunc" class="form-label" style="font-size: 10px; color:aqua">Email</label>
          <input style="font-size: 12px; text-transform:lowercase; background: rgba(0,0,0,0.3)" type="email" class="form-control" id="emailFunc" name="emailFunc" placeholder="Opcional">
          <p style="font-size: 10px; color: grey">Tamanho máximo de 120 caracteres</p>
        </div>

        <div class="col-md-4">
          <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone de Contato</label>
          <input style="font-size: 12px; background: rgba(0,0,0,0.3) " type="text" class="form-control" id="CelularInput" name="telefone" placeholder="Opcional, somente números" maxlength="11" onkeyup="criaMascara('Celular')">
          <p style="font-size: 10px; color: grey">Somente números incluindo DDD</p>
        </div>

        <div class="col-md-2">
          <label for="dataAdmi" class="form-label" style="font-size: 10px; color:aqua">Data de Admissão</label>
          <input style="font-size: 12px; background: rgba(0,0,0,0.3)" type="date" class="form-control" id="dataAdmi" name="dataAdmi" required>
        </div>

        <div class="col-md-4">
          <label for="departamento" class="form-label" style="font-size: 10px; color:aqua">Departamento</label>
          <select style="font-size: 12px; background: rgba(0,0,0,0.3)" id="departamento" class="form-select" name="departamento">
            <option style="font-size: 12px; background: rgba(0,0,0,0.3); color: black" selected>Selecione uma opção</option>
            <?php
              while($selDepto = $query_depto->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 12px; background: rgba(0,0,0,0.3); color: black"><?php echo $selDepto['DEPARTAMENTO']; ?></option> <?php
              }?>
          </select>
        </div>

        <div class="col-md-4">
          <label for="cargo" class="form-label" style="font-size: 10px; color:aqua">Cargo</label>
          <select style="font-size: 12px; background: rgba(0,0,0,0.3)" id="cargo" class="form-select" name="cargo" >
            <option style="font-size: 12px; background: rgba(0,0,0,0.3); color: black" selected>Selecione uma opção</option>
            <?php
              while($selCargo = $query_cargo->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 12px; background: rgba(0,0,0,0.3); color: black"><?php echo $selCargo['CARGO']; ?></option> <?php
              }?>               
          </select>
        </div>

        <div class="col-10">
          <label for="ruaRes" class="form-label" style="font-size: 10px; color:aqua">Endereço Residencial: Rua/Avenida</label>
          <input style="font-size: 12px; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="ruaRes" name="ruaRes" placeholder="Opcional">
        </div>

        <div class="col-md-2">
          <label for="numRes" class="form-label" style="font-size: 10px; color:aqua">Número da Residência</label>
          <input style="font-size: 12px; background: rgba(0,0,0,0.3)" type="number" class="form-control" id="numRes" name="numRes" maxlength="6">
          <p style="font-size: 10px; color: grey">Somente números</p>
        </div>

        <div class="col-md-3">
          <label for="cplRes" class="form-label" style="font-size: 10px; color:aqua">Complemento</label>
          <input style="font-size: 12px; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="cplRes" name="cplRes" placeholder="Opcional">
          <p style="font-size: 10px; color: grey">Apto, Bloco, Casa, Ed. etc</p>
        </div>

        <div class="col-md-3">
          <label for="bairro" class="form-label" style="font-size: 10px; color:aqua">Bairro</label>
          <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="bairro" name="bairro" placeholder="Opcional">
        </div>

        <div class="col-md-4">
          <label for="cidade" class="form-label" style="font-size: 10px; color:aqua">Cidade</label>
          <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="cidade" name="cidade" placeholder="Opcional">
        </div>

        <div class="col-md-2">
          <label for="uf" class="form-label" style="font-size: 10px; color:aqua">Estado (U.F.)</label>
          <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="uf" name="uf" placeholder="Opcional" maxlength="2">
        </div>

        <!-- Botão para confirmar -->
        <div class="col-md-4"><br>
          <!-- Aciona Modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 280px; float: right">Confirmar Dados</button>
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 style="color: aqua" class="modal-title fs-5" id="exampleModalLabel">Informações de Acesso do novo funcionário</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p style="font-size: 20px;color:yellow">
                    <?php 
                      // gerar ID de usuário provisória
                      echo('Usuário: ' .  $geraIDuser .'<br>'. 'Senha: ' . $geraPassword); 
                    ?>
                  </p>
                  <p style="color: grey; font-size: 12px">Chaves provisórias. Anote e informe ao funcionário. Pode ser alterado posteriormente</p>
                </div>
                <div class="modal-footer">
                  <button style="width: 210px;" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar sem Salvar</button>
                  <input style="width: 210px" class="btn btn-primary" type="submit" id="submit" name="submit" value="Salvar">
                </div>
              </div>
            </div>
          </div>            
        </div>

        <div class="col-md-4"><br>
          <input class="btn btn-secondary" type="reset"  id="reset"  name="reset"  value="Descartar Dados e Sair" style="width: 280px" onclick="location.href='./06QuadroFuncionarios.php'">
        </div>
      </form>
    </div>
  </div>
    