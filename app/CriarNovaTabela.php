<?php
// inclusão do banco de dados e estrutura base da página web

use PhpParser\Node\Stmt\Echo_;

include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Criar Novas Tabelas'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func']; $novoNome = '';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
  }; inactivityTime();
  // atribui função de enviar formulário
  function submeterFormulario() {
    document.getElementById("criar").submit();
  }
</script>
<style> .tabela{ width: 98%; height: 200px; overflow-y: scroll;} </style>
<!-- Área Principal -->
<div class="main"><br>
  <p style="font-size: 20px; color: whitesmoke;">Criação de Nova Tabela para Coleta de Dados</p><br>
  <form action="" id="criar" method="POST">
    <div class="row g-2">
      <p style="font-size: 14px; color: bisque;">Selecione para qual procedimento será criado a nova tabela</p>
      <div class="col-md-6">
        <p style="font-size: 14px; color: aqua;">Departamento Administrativo</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="nEtapa" id="compra" value="compra" autofocus>
          <label class="form-check-label" for="compra">Procedimento de Compra de Material</label><br>
        </div>
      </div>
      <div class="col-md-6">
        <p style="font-size: 14px; color: aqua;">Departamento de Logística</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="nEtapa" id="recebimento" value="recebimento">
          <label class="form-check-label" for="recebimento">Procedimento de Recebimento de Material</label><br>
          <input class="form-check-input" type="radio" name="nEtapa" id="entrega" value="entrega">
          <label class="form-check-label" for="entrega">Procedimento de Entrega do Produto</label><br>
        </div>
      </div>
    </div>
    <br><br>
    <div class="row g-2">
      <div class="col-md-6">
        <p style="font-size: 14px; color: aqua;">Departamento da Garantia da Qualidade</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="nEtapa" id="anaMat" value="anaMat">
          <label class="form-check-label" for="anaMat">Procedimento de Análise de Material</label><br>
          <input class="form-check-input" type="radio" name="nEtapa" id="anaProd" value="anaProd">
          <label class="form-check-label" for="anaProd">Procedimento de Análise do Produto</label><br>
        </div>
      </div>
      <br><br>
      <div class="col-md-6">
        <p style="font-size: 14px; color: aqua;">Departamento de Produção</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="nEtapa" id="fabric" value="fabric">
          <label class="form-check-label" for="fabric">Procedimento de Operação da Fábrica</label><br>
        </div>
      </div>
    </div>
    <br>
    <div class="row g-2">
      <div class="col-md-4">
        <label for="novoNome" class="form-label" style="font-size: 10px; color:aqua">Nome para Nova Tabela</label>
        <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" 
              type="text" class="form-control" id="novoNome" name="novoNome" value="<?php echo $novoNome ?>" size="25" maxlength="25" required>
        <p style="font-size: 10px; color: grey">Tamanho máximo de 25 caracteres (sem espaço nem acento)</p>
      </div>
      <div class="col-md-6">
        <div class="form-floating">
          <textarea class="form-control" id="descr" name="descr" style="font-size: 14px; height: 100px; width: 650px; background: rgba(0,0,0,0.3);"></textarea>
          <label for="descr" style="color: aqua; font-size: 12px; background: none">Descrição do Objetivo da Tabela</label>
        </div>
        <p style="font-size: 10px; color: grey">Tamanho máximo de 250 caracteres</p>
      </div>
      <div class="col-md-2"></div>
      <div class="col-md-2">
        <input class="btn btn-primary" type="submit" id="criar" name="criar" value="Criar Nova Tabela">
      </div>
    </div>
  </form><?php
  $criarTabela = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  if(!empty($criarTabela)){
    switch($criarTabela['nEtapa']){
      case 'compra'      : $defEtapa = 1; break;
      case 'recebimento' : $defEtapa = 2; break;
      case 'entrega'     : $defEtapa = 3; break;
      case 'anaMat'      : $defEtapa = 4; break;
      case 'anaProd'     : $defEtapa = 5; break;
      case 'fabric'      : $defEtapa = 6; break;
    }
    $nomeTabela = strtoupper($criarTabela['novoNome']); $novoID = 'ID_' . strtoupper($nomeTabela);
    $verificaTabela = $connDB->prepare("SELECT * FROM estrutura WHERE NOME_TABELA = :nomeTabela");
    $verificaTabela->bindParam(':nomeTabela', $nomeTabela, PDO::PARAM_STR);
    $verificaTabela->execute(); $rowEstrutura = $verificaTabela->fetch(PDO::FETCH_ASSOC);
    if($rowEstrutura['NOME_TABELA'] == $nomeTabela){
      echo 'Este nome de tabela não pode ser criado pois já consta da estrutura, tente outro nome'; ?>
      <button class="btn btn-warging" onclick="location.href='./ConfigurarEstrutura.php'">Reiniciar</button><?php
    }
    if($rowEstrutura['NOME_TABELA'] != $nomeTabela){
      try{
        $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $atualizaEstrutura = $connDB->prepare("INSERT INTO estrutura (DEPARTAMENTO, PROCEDIMENTO, NOME_TABELA, DESCRICAO_TABELA,)
                                                      VALUES (:depto, :proced, :nomeTab, :descr)");
        $atualizaEstrutura->bindParam(':depto'  , $_SESSION['depto']   , PDO::PARAM_STR);
        $atualizaEstrutura->bindParam(':proced' , $defEtapa            , PDO::PARAM_INT);
        $atualizaEstrutura->bindParam(':nomeTab', $nomeTabela          , PDO::PARAM_STR);
        $atualizaEstrutura->bindParam(':descr'  , $criarTabela['descr'], PDO::PARAM_STR);
        $atualizaEstrutura->execute();

        $novaEstrutura = 'struct_' . $nomeTabela;
        $criaEstrutura = "CREATE TABLE $novaEstrutura (ID_BASE INT AUTO_INCREMENT PRIMARY KEY, CAMPO VARCHAR(20), TIPO VARCHAR(20))";
        $connDB->exec($criaEstrutura);

        $criaNova = "CREATE TABLE $nomeTabela ($novoID INT AUTO_INCREMENT PRIMARY KEY, NUMERO_PEDIDO INT, ID_PRODUTO INT, DATA_REGISTRO DATETIME);";
        $connDB->exec($criaNova); $_SESSION['nomeTabela'] = $nomeTabela;
        echo 'Tabela criada com sucesso!!'; ?>
        <button class="btn btn-success" onclick="location.href='./AlterarTabela.php'">Clique para continuar e definir os campos para coleta de dados</button><?php
      } catch(PDOException $e) {
        echo 'Ocorreu algum problema durante a criação da tabela, comunique o responsável pelo TI.'; ?>
        <div>
          <br><br>
          <button class="btn btn-danger" onclick="location.href='./ConfigurarEstrutura.php'">Reiniciar</button>
        </div><?php
        }
    }
  }?>
</div>