<?php
// inclusão de banco de dados e estrutura base da página web
include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

// verifica dados do usuário para edição dos dados pessoais
$idUser    = $_SESSION['idFunc'];
$editDados = $connDB->prepare("SELECT * FROM quadro_funcionarios WHERE ID_FUNCIONARIO = :usuario");
$editDados->bindParam(':usuario', $idUser, PDO::PARAM_INT);
$editDados->execute();
$dados     = $editDados->fetch(PDO::FETCH_ASSOC);

// capta os dados inseridos no formulário Login
$atualizado = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//verifica se foi digitado dados
if(!empty($atualizado['submit'])){

  //atribuir valores dos campos para variáveis
  $nomeFunc = strtoupper($atualizado['nomeFunc']); 
  $dataNasc = date('Y-m-d', strtotime($atualizado['dataNasc'])); 
            
  if(!empty($atualizado['cpfFunc']))  {$cpf      = $atualizado['cpfFunc'];              }else{$cpf      = 'Nada Consta';}
  if(!empty($atualizado['rgFunc']))   {$rg       = $atualizado['rgFunc'];               }else{$rg       = 'Nada Consta';}
  if(!empty($atualizado['telefone'])) {$telefone = $atualizado['telefone'];             }else{$telefone = 'Nada Consta';}
  if(!empty($atualizado['emailFunc'])){$email    = strtolower($atualizado['emailFunc']);}else{$email    = 'Nada Consta';}
  if(!empty($atualizado['ruaRes']))   {$ruaRes   = $atualizado['ruaRes'];               }else{$ruaRes   = 'Nada Consta';}
  if(!empty($atualizado['numRes']))   {$numRes   = $atualizado['numRes'];               }else{$numRes   = 'Nada Consta';}
  if(!empty($atualizado['cplRes']))   {$cplRes   = $atualizado['cplRes'];               }else{$cplRes   = 'Nada Consta';}
  if(!empty($atualizado['bairro']))   {$bairro   = strtoupper($atualizado['bairro']);   }else{$cidade   = 'Nada Consta';}
  if(!empty($atualizado['cidade']))   {$cidade   = strtoupper($atualizado['cidade']);   }else{$cidade   = 'Nada Consta';}
  if(!empty($atualizado['uf']))       {$estado   = strtoupper($atualizado['uf']);       }else{$estado   = 'NC';}

  $registra = $connDB->prepare("UPDATE quadro_funcionarios 
                                SET NOME_FUNCIONARIO  = :nomeFunc, DATA_NASCIMENTO = :dataNasc, CPF     = :cpf   , RG          = :rg    , TELEFONE = :telefone,
                                               EMAIL  = :email   , RUA_RES         = :ruaRes  , NUM_RES = :numRes, COMPLEMENTO = :cplRes, BAIRRO   = :bairro,
                                               CIDADE = :cidade  , UF              = :estado
                                WHERE ID_FUNCIONARIO  = :idUser");

  $registra->bindParam(':idUser'  , $idUser  , PDO::PARAM_INT);   $registra->bindParam(':nomeFunc', $nomeFunc, PDO::PARAM_STR);
  $registra->bindParam(':dataNasc', $dataNasc, PDO::PARAM_STR);   $registra->bindParam(':cpf'     , $cpf     , PDO::PARAM_STR);
  $registra->bindParam(':rg'      , $rg      , PDO::PARAM_STR);   $registra->bindParam(':telefone', $telefone, PDO::PARAM_STR);
  $registra->bindParam(':email'   , $email   , PDO::PARAM_STR);   $registra->bindParam(':ruaRes'  , $ruaRes  , PDO::PARAM_STR);
  $registra->bindParam(':numRes'  , $numRes  , PDO::PARAM_STR);   $registra->bindParam(':cplRes'  , $cplRes  , PDO::PARAM_STR);
  $registra->bindParam(':bairro'  , $bairro  , PDO::PARAM_STR);   $registra->bindParam(':cidade'  , $cidade  , PDO::PARAM_STR);
  $registra->bindParam(':estado'  , $estado  , PDO::PARAM_STR); 

  $registra->execute();
  
  header('Location: 00SeletorAdministrativo.php');
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
    <p style="margin-left: 2%; font-size: 25px; color: whitesmoke">Área Privada  - Atualização de Dados Pessoais</p>
    <!-- Formulário de edição com valores resgatados do banco de dados incluso -->
    <form class="row g-2" method="POST" action="#">

      <div class="col-md-2">
        <label for="idFunc" class="form-label" style="color:aqua; font-size: 10px">Cadastro No.</label>
        <input style="text-align:right; font-size: 12px" type="number" class="form-control" id="idFunc" name="idFunc" value="<?php echo $dados['ID_FUNCIONARIO']; ?>" readonly>
      </div>

      <div class="col-md-2">
        <label for="dataNasc" class="form-label" style="font-size: 10px; color:aqua">Data de Nascimento</label>
        <input style="font-size: 12px" type="date" class="form-control" id="dataNasc" name="dataNasc" value="<?php echo $dados['DATA_NASCIMENTO']; ?>" autofocus>
      </div>

      <div class="col-md-3">
        <label for="cpfFunc" class="form-label" style="font-size: 10px; color:aqua">C.P.F.</label>
        <input style="font-size: 12px" type="text" class="form-control" id="CPFInput" name="cpfFunc" value="<?php echo $dados['CPF']; ?>" maxlength="11" onkeyup="criaMascara('CPF')">
        <p style="font-size: 10px; color: grey">Somente números</p>
      </div>

      <div class="col-md-3">
        <label for="rgFunc" class="form-label" style="font-size: 10px; color:aqua">R.G.</label>
        <input style="font-size: 12px" type="text" class="form-control" id="RGInput" name="rgFunc" value="<?php echo $dados['RG']; ?>" maxlength="9" onkeyup="criaMascara('RG')">
        <p style="font-size: 10px; color: grey">Somente números</p>
      </div>

      <div class="col-12">
        <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Nome Completo</label>
        <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="nomeFunc" name="nomeFunc" value="<?php echo $dados['NOME_FUNCIONARIO']; ?>" maxlength="120">
        <p style="font-size: 10px; color: grey">Tamanho máximo de 120 caracteres</p>
      </div>

      <div class="col-8">
        <label for="emailFunc" class="form-label" style="font-size: 10px; color:aqua">Email</label>
        <input style="font-size: 12px; text-transform:lowercase" type="email" class="form-control" id="emailFunc" name="emailFunc" value="<?php echo $dados['EMAIL']; ?>">
        <p style="font-size: 10px; color: grey">Tamanho máximo de 120 caracteres</p>
      </div>

      <div class="col-md-4">
        <label for="telefone" class="form-label" style="font-size: 10px; color:aqua">Telefone de Contato</label>
        <input style="font-size: 12px" type="text" class="form-control" id="CelularInput" name="telefone" value="<?php echo $dados['TELEFONE']; ?>" maxlength="11" onkeyup="criaMascara('Celular')">
        <p style="font-size: 10px; color: grey">Somente números incluindo DDD</p>
      </div>

      <div class="col-10">
        <label for="ruaRes" class="form-label" style="font-size: 10px; color:aqua">Endereço Residencial: Rua/Avenida</label>
        <input style="font-size: 12px" type="text" class="form-control" id="ruaRes" name="ruaRes" value="<?php echo $dados['RUA_RES']; ?>">
      </div>

      <div class="col-md-2">
        <label for="numRes" class="form-label" style="font-size: 10px; color:aqua">Número da Residência</label>
        <input style="font-size: 12px" type="text" class="form-control" id="numRes" name="numRes" value="<?php echo $dados['NUM_RES']; ?>" maxlength="6">
        <p style="font-size: 10px; color: grey">Somente números</p>
      </div>

      <div class="col-md-3">
        <label for="cplRes" class="form-label" style="font-size: 10px; color:aqua">Complemento</label>
        <input style="font-size: 12px" type="text" class="form-control" id="cplRes" name="cplRes" value="<?php echo $dados['COMPLEMENTO']; ?>">
        <p style="font-size: 10px; color: grey">Apto, Bloco, Casa, Ed. etc</p>
      </div>

      <div class="col-md-3">
        <label for="bairro" class="form-label" style="font-size: 10px; color:aqua">Bairro</label>
        <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="bairro" name="bairro" value="<?php echo $dados['BAIRRO']; ?>">
      </div>

      <div class="col-md-4">
        <label for="cidade" class="form-label" style="font-size: 10px; color:aqua">Cidade</label>
        <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="cidade" name="cidade" value="<?php echo $dados['CIDADE']; ?>">
      </div>

      <div class="col-md-2">
        <label for="uf" class="form-label" style="font-size: 10px; color:aqua">Estado (U.F.)</label>
        <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" id="uf" name="uf" value="<?php echo $dados['UF']; ?>" maxlength="2">
      </div>

      <!-- Botão para acessar -->
      <div class="col-md-4"><br>
        <input class="btn btn-primary" type="submit" id="submit" name="submit" value="Confirmar e Salvar" style="width: 280px">
      </div><br>

      <!-- Botão para recarregar e começar nova entrada caso ocorra algum erro -->
      <div class="col-md-4"><br>
        <input class="btn btn-secondary" type="reset" id="reset" name="reset" value="Descartar e Sair" style="width: 280px" onclick="location.href='Dashboard.php'">
      </div>
    </form>
  </div>
</div>