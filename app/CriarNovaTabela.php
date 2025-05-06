<?php
// inclusão do banco de dados e estrutura base da página web

use PhpParser\Node\Stmt\Echo_;

include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Criar Novas Tabelas'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func']; $novoNome = '';

$verificaID = $connDB->prepare("SELECT MAX(ID_ESTRUTURA) AS ULTIMO FROM estrutura");
$verificaID->execute(); $numeroID = $verificaID->fetch(PDO::FETCH_ASSOC); $_SESSION['ID_TABELA'] = $numeroID['ULTIMO'] + 1;
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php';}
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);}
  }; inactivityTime();
  // atribui função de enviar formulário
  function submeterFormulario() { document.getElementById("selectProced").submit(); }
</script>
<style> .tabela{ width: 98%; height: 200px; overflow-y: scroll;} </style>
<!-- Área Principal -->
<div class="main"><br>
  <p style="font-size: 20px; color: whitesmoke;">Criação de Nova Tabela para Coleta de Dados</p><br>
  <form action="" id="selectProced" method="POST">
    <?php $valueC = 'compra'; $valueR = 'recebimento'; $valueE = 'entrega'; $valueM = 'anaMat'; $valueP = 'anaProd'; $valueF = 'fabric'; ?>
    <div class="row g-2">
      <p style="font-size: 14px; color: bisque;">Selecione para qual procedimento será criado a nova tabela</p>
      <div class="col-md-4">
        <p style="font-size: 14px; color: aqua;">Departamento Administrativo</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="nEtapa" id="compra" value="<?php echo $valueC ?>" autofocus onclick="submeterFormulario()">
          <label class="form-check-label" for="compra">Procedimento de Compra de Material</label><br>
        </div>
      </div>
      <div class="col-md-4">
        <p style="font-size: 14px; color: aqua;">Departamento de Logística</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="nEtapa" id="recebimento" value="<?php echo $valueR ?>" onclick="submeterFormulario()">
          <label class="form-check-label" for="recebimento">Procedimento de Recebimento de Material</label><br>
          <input class="form-check-input" type="radio" name="nEtapa" id="entrega" value="<?php echo $valueE ?>" onclick="submeterFormulario()">
          <label class="form-check-label" for="entrega">Procedimento de Entrega do Produto</label><br>
        </div>
      </div>
    </div>
    <br><br>
    <div class="row g-2">
      <div class="col-md-4">
        <p style="font-size: 14px; color: aqua;">Departamento da Garantia da Qualidade</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="nEtapa" id="anaMat" value="<?php echo $valueM ?>" onclick="submeterFormulario()">
          <label class="form-check-label" for="anaMat">Procedimento de Análise de Material</label><br>
          <input class="form-check-input" type="radio" name="nEtapa" id="anaProd" value="<?php echo $valueP ?>" onclick="submeterFormulario()">
          <label class="form-check-label" for="anaProd">Procedimento de Análise do Produto</label><br>
        </div>
      </div>
      <br><br>
      <div class="col-md-4">
        <p style="font-size: 14px; color: aqua;">Departamento de Produção</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="nEtapa" id="fabric" value="<?php echo $valueF ?>" onclick="submeterFormulario()">
          <label class="form-check-label" for="fabric">Procedimento de Operação da Fábrica</label><br>
        </div>
      </div>
    </div>
    <br>
  </form><?php
    $procedimento = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(isset($_POST['nEtapa'])){
      if(!empty($procedimento)){
        switch($procedimento['nEtapa']){
          case 'compra'      : $_SESSION['procedimento'] = 1; break;
          case 'recebimento' : $_SESSION['procedimento'] = 2; break;
          case 'entrega'     : $_SESSION['procedimento'] = 3; break;
          case 'anaMat'      : $_SESSION['procedimento'] = 4; break;
          case 'anaProd'     : $_SESSION['procedimento'] = 5; break;
          case 'fabric'      : $_SESSION['procedimento'] = 6; break;
        } 
      }
    } ?>
  <form action="" id="criar" method="POST">
    <div class="row g-2">
      <div class="col-md-4">
        <label for="novoNome" class="form-label" style="font-size: 10px; color:aqua">Nome para Nova Tabela</label>
        <input style="width: 75%; font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" 
              type="text" class="form-control" id="novoNome" name="novoNome" value="<?php echo $novoNome ?>" size="25" maxlength="25" required>
        <p style="font-size: 10px; color: grey">Tamanho máximo de 25 caracteres (sem espaço nem acento)</p>
      </div>
      <div class="col-md-8">
        <?php
          $verifAtivo = $connDB->prepare("SELECT * FROM estrutura WHERE DEPARTAMENTO = :depto AND PROCEDIMENTO = :proced AND ATIVO = 1");
          $verifAtivo->bindParam(':depto' , $_SESSION['depto']       , PDO::PARAM_STR);
          $verifAtivo->bindParam(':proced', $_SESSION['procedimento'], PDO::PARAM_INT);
          $verifAtivo->execute(); $rowAtivo = $verifAtivo->fetch(PDO::FETCH_ASSOC);
          if(!empty($rowAtivo['ID_ESTRUTURA'])){
            $_SESSION['idStruc'] = $rowAtivo['ID_ESTRUTURA']; ?>
            <p style="color: red;"><?php echo 'Já existe uma tabela para o procedimento selecionado. [' . strtoupper($rowAtivo['NOME_TABELA']) . '] Deseja substituí-la?'; ?></p>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="ativar" id="positivo" value="positivo">
              <label class="form-check-label" for="positivo">Sim, substituir!</label><br>
              <input class="form-check-input" type="radio" name="ativar" id="negativo" value="negativo">
              <label class="form-check-label" for="negativo">Não, manter o atual!</label><br>
            </div><?php
          } ?>   
      </div>
      <div class="col-md-4">
        <div class="form-floating">
          <textarea class="form-control" id="descr" name="descr" style="font-size: 14px; height: 150px; width: 450px; background: rgba(0,0,0,0.3);"></textarea>
          <label for="descr" style="color: aqua; font-size: 12px; background: none">Descrição do Objetivo da Tabela</label>
        </div>
        <p style="font-size: 10px; color: grey">Tamanho máximo de 250 caracteres</p>
      </div>
      <div class="col-md-8"></div>
      <div class="col-md-2">
        <input class="btn btn-primary" type="submit" id="criar" name="criar" value="Criar Nova Tabela"><br>
      </div>
    </div>
  </form><?php
  $criarTabela = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  if(isset($_POST['criar'])){
    if(!empty($criarTabela)){
      $nomeTabela = 'e_' . strtolower($criarTabela['novoNome']); $novoID = 'ID_' . strtoupper($nomeTabela); $descr = $criarTabela['descr'];
      $verificaTabela = $connDB->prepare("SELECT * FROM estrutura");
      $verificaTabela->execute(); $rowEstrutura = $verificaTabela->fetch(PDO::FETCH_ASSOC);
      if(!empty($rowEstrutura['NOME_TABELA'])){
        if($rowEstrutura['NOME_TABELA'] == $nomeTabela){
          echo 'Este nome de tabela não pode ser criado pois já consta da estrutura, tente outro nome'; ?>
          <button class="btn btn-warging" onclick="location.href='./ConfigurarEstrutura.php'">Reiniciar</button><?php
        }
        if($rowEstrutura['NOME_TABELA'] != $nomeTabela){
          if(!empty($rowAtivo['ID_ESTRUTURA'])){
            if($criarTabela['ativar'] == 'positivo'){
              $troca = 0; $ativo = 1;
              $desativa = $connDB->prepare("UPDATE estrutura SET ATIVO = :troca WHERE ID_ESTRUTURA = :estrutura");
              $desativa->bindParam(':estrutura', $_SESSION['idStruc'], PDO::PARAM_INT);
              $desativa->bindParam(':troca', $troca, PDO::PARAM_INT);
              $desativa->execute();
            }
            if($criarTabela['ativar'] == 'negativo'){
              $ativo = 0;
            }
          }
          if(empty($rowAtivo['ID_ESTRUTURA'])){
            $ativo = 1;
          }
          try{
            $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $atualizaEstrutura = $connDB->prepare("INSERT INTO estrutura (ATIVO, PROCEDIMENTO, DEPARTAMENTO, NOME_TABELA, DESCRICAO_TABELA) VALUES (:ativo, :proced, :depto, :nomeTab, :descr)");
            $atualizaEstrutura->bindParam(':ativo'  , $ativo                   , PDO::PARAM_INT);
            $atualizaEstrutura->bindParam(':proced' , $_SESSION['procedimento'], PDO::PARAM_INT);
            $atualizaEstrutura->bindParam(':depto'  , $_SESSION['depto']       , PDO::PARAM_STR);
            $atualizaEstrutura->bindParam(':nomeTab', $nomeTabela              , PDO::PARAM_STR);
            $atualizaEstrutura->bindParam(':descr'  , $descr                   , PDO::PARAM_STR);
            $atualizaEstrutura->execute();

            $i = 1;
            $e[1] = 'NUMERO DO PEDIDO'; $c[1] = 'NUMERO_PEDIDO'; $t[1] = 'INT'     ; $d[1] = 'Numero de pedido de um produto';
            $e[2] = 'ID DO PEDIDO'    ; $c[2] = 'ID_PEDIDO'    ; $t[2] = 'INT'     ; $d[2] = 'Numero de identificação de um produto';
            $e[3] = 'DATA DO REGISTRO'; $c[3] = 'DATA_REGISTRO'; $t[3] = 'DATETIME'; $d[3] = 'Coleta data do sistema automaticamente sobre a data de registro';

            for($i = 1; $i <=3; $i++){
              $regCampo = $connDB->prepare("INSERT INTO estrutura_campos (ID_ESTRUTURA, ETIQUETA, TIPO, CAMPO, DESCRICAO) 
                                                   VALUES (:idStruc, :etiq, :tipo, :campo, :descr)");
              $regCampo->bindParam(':idStruc', $_SESSION['ID_TABELA'], PDO::PARAM_INT);
              $regCampo->bindParam(':etiq'   , $e[$i]                , PDO::PARAM_STR);
              $regCampo->bindParam(':tipo'   , $t[$i]                , PDO::PARAM_STR);
              $regCampo->bindParam(':campo'  , $c[$i]                , PDO::PARAM_STR);
              $regCampo->bindParam(':descr'  , $d[$i]                , PDO::PARAM_STR);
              $regCampo->execute();
            }
    
            $criaNova = "CREATE TABLE $nomeTabela ($novoID INT AUTO_INCREMENT PRIMARY KEY, NUMERO_PEDIDO INT, ID_PRODUTO INT, DATA_REGISTRO DATETIME);";
            $connDB->exec($criaNova); $_SESSION['nomeTabela'] = $nomeTabela; ?>
            <div class="col-md-3">
              <?php echo 'Tabela criada com sucesso!! <br><br>'; ?>
            </div>
            <button class="btn btn-success" onclick="location.href='./AlterarTabela.php?id=<?php echo $_SESSION['ID_TABELA'] ?>'">Clique para continuar e definir os campos para coleta de dados</button><?php
          }
          catch(PDOException $e){
            echo 'Ocorreu algum problema durante a criação da tabela, comunique o responsável do TI.' . $e; ?>
            <div>
              <br><br>
              <button class="btn btn-danger" onclick="location.href='./ConfigurarEstrutura.php'">Reiniciar</button>
            </div><?php
          }
        }
      }
      if(empty($rowEstrutura['NOME_TABELA'] )){
        $ativo = 1;
        try{
          $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $atualizaEstrutura = $connDB->prepare("INSERT INTO estrutura (ATIVO, PROCEDIMENTO, DEPARTAMENTO, NOME_TABELA, DESCRICAO_TABELA) VALUES (:ativo, :proced, :depto, :nomeTab, :descr)");
          $atualizaEstrutura->bindParam(':ativo'  , $ativo                   , PDO::PARAM_INT);
          $atualizaEstrutura->bindParam(':proced' , $_SESSION['procedimento'], PDO::PARAM_INT);
          $atualizaEstrutura->bindParam(':depto'  , $_SESSION['depto']       , PDO::PARAM_STR);
          $atualizaEstrutura->bindParam(':nomeTab', $nomeTabela              , PDO::PARAM_STR);
          $atualizaEstrutura->bindParam(':descr'  , $descr                   , PDO::PARAM_STR);
          $atualizaEstrutura->execute();

          $i = 1;
            $c[1] = 'NUMERO_PEDIDO'; $t[1] = 'INT'     ; $d[1] = 'Numero de pedido de um produto';
            $c[2] = 'ID_PRODUTO'   ; $t[2] = 'INT'     ; $d[2] = 'Numero de identificação de um produto';
            $c[3] = 'DATA_REGISTRO'; $t[3] = 'DATETIME'; $d[3] = 'Coleta data do sistema automaticamente sobre a data de registro';

            for($i = 1; $i <=3; $i++){
              $regCampo = $connDB->prepare("INSERT INTO estrutura_campos (ID_ESTRUTURA, CAMPO, TIPO, DESCRICAO) VALUES (:idStruc, :campo, :tipo, :descr)");
              $regCampo->bindParam(':idStruc', $_SESSION['ID_TABELA'], PDO::PARAM_INT);
              $regCampo->bindParam(':campo'  , $c[$i]                , PDO::PARAM_STR);
              $regCampo->bindParam(':tipo'   , $t[$i]                , PDO::PARAM_STR);
              $regCampo->bindParam(':descr'  , $d[$i]                , PDO::PARAM_STR);
              $regCampo->execute();
            }
  
          $criaNova = "CREATE TABLE $nomeTabela ($novoID INT AUTO_INCREMENT PRIMARY KEY, NUMERO_PEDIDO INT, ID_PRODUTO INT, DATA_REGISTRO DATETIME);";
          $connDB->exec($criaNova); $_SESSION['nomeTabela'] = $nomeTabela; ?>

          <div class="col-md-3">
            <?php echo 'Tabela criada com sucesso!! <br><br>'; ?>
          </div>
          <button class="btn btn-success" onclick="location.href='./AlterarTabela.php?id=<?php echo $_SESSION['ID_TABELA'] ?>'">Clique para continuar e definir os campos para coleta de dados</button><?php
        }
        catch(PDOException $e){
          echo 'Ocorreu algum problema durante a criação da tabela, comunique o responsável do TI.' . $e; ?>
          <div>
            <br><br>
            <button class="btn btn-danger" onclick="location.href='./ConfigurarEstrutura.php'">Reiniciar</button>
          </div><?php
        }   
      }   
    }
  } ?>
</div>