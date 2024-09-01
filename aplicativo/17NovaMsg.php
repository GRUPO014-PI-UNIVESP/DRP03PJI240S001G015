<?php

include_once 'aplicativo/ConnectDB.php';
include_once 'aplicativo/EstruturaPrincipal.php';

$depto_query = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$setDepto = 'Selecione o depto e clique Pesquisar';
$setFunc  = '';

if(!empty($depto_query['pesquisa'])){

  $resultDepto = strtoupper($depto_query['departamento']);
  $setDepto = strtoupper($depto_query['departamento']);

  $desti = $connDB->prepare("SELECT NOME_FUNCIONARIO, CARGO, DEPARTAMENTO FROM quadro_funcionarios WHERE DEPARTAMENTO = :depto");
  $desti->bindParam(':depto', $resultDepto, PDO::PARAM_STR);

  $desti->execute();

  $setFunc  = 'Selecione o nome do destinatário e clique Confirmar';
}
$msg_query = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(!empty($msg_query['enviar'])){

  $destinatario = $connDB->prepare("SELECT NOME_FUNCIONARIO, DEPARTAMENTO FROM quadro_funcionarios WHERE NOME_FUNCIONARIO = :destinatario LIMIT 1");
  $destinatario->bindParam(':destinatario', $msg_query['receptor'], PDO::PARAM_STR);
  $destinatario->execute();
  $rowName = $destinatario->fetch(PDO::FETCH_ASSOC);

  $emissor = $_SESSION['nome_func'];
  $deptoEmissor = $_SESSION['departamento'];
  $confirma = 'UNRE';
  $data = date('Y-m-d');
  $hora = date('H:i:s');

  $envia = $connDB->prepare("INSERT INTO mensagens (EMISSOR_MSG, DEPTO_EMISSOR, DATA_MSG, HORA_MSG, RECEPTOR_MSG, DEPTO_RECEPTOR, TITULO_MSG, MENSAGEM, CONFIRMA) 
                               VALUES (:emissor, :deptoEmissor, :dataMsg, :horaMsg, :receptor, :deptoReceptor, :titulo, :mensagem, :confirma) ");
  $envia->bindParam(':emissor'      , $emissor                    , PDO::PARAM_STR);
  $envia->bindParam(':deptoEmissor' , $deptoEmissor               , PDO::PARAM_STR);
  $envia->bindParam(':dataMsg'      , $data                       , PDO::PARAM_STR);
  $envia->bindParam(':horaMsg'      , $hora                       , PDO::PARAM_STR);
  $envia->bindParam(':receptor'     , $rowName['NOME_FUNCIONARIO'], PDO::PARAM_STR);
  $envia->bindParam(':deptoReceptor', $rowName['DEPARTAMENTO']    , PDO::PARAM_STR);
  $envia->bindParam(':titulo'       , $msg_query['assunto']       , PDO::PARAM_STR);
  $envia->bindParam(':mensagem'     , $msg_query['mensagem']      , PDO::PARAM_STR);
  $envia->bindParam(':confirma'     , $confirma                   , PDO::PARAM_STR);

  $envia->execute();

  header('Location: aplicativo/15Mensagens.php');
}
?>
<div class="main">
  <div class="contain">
    <p style="font-size: 20px">Nova Mensagem</p><br>
    <form class="row g-3" method="POST" action="">
      <div class="col-md-4">
        <label for="departamento" class="form-label" style="font-size: 10px; color:aqua">Departamento</label>
        <select style="font-size: 12px" id="departamento" class="form-select" name="departamento">
          <option style="font-size: 12px" selected><?php echo $setDepto ?></option>
          <option style="font-size: 12px">ADMINISTRATIVO</option> 
          <option style="font-size: 12px">GARANTIA DA QUALIDADE</option>
          <option style="font-size: 12px">LOGÍSTICA</option>
          <option style="font-size: 12px">PRODUÇÃO</option>
        </select>
      </div>
      <div class="col-md-3"><br>
        <input style="width: 120px" class="btn btn-primary" type="submit" id="pesquisa" name="pesquisa" value="Pesquisar">
      </div><br>
    </form>
    <form class="row g-3" method="POST" action="">
      <div class="col-md-8">
        <label for="departamento" class="form-label" style="font-size: 10px; color:aqua">Destinatário</label>
        <select style="font-size: 12px; width: 600px" id="receptor" class="form-select" name="receptor">
          <option style="font-size: 12px" selected><?php echo $setFunc ?></option>
        <?php
        while($rowLog = $desti->fetch(PDO::FETCH_ASSOC)){?>
        <option style="font-size: 12px"><?php echo $rowLog['NOME_FUNCIONARIO']; ?></option> <?php
        }?>
        </select>
      </div>
      <div class="col-12">
        <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Assunto a tratar</label>
        <input style="font-size: 12px;" type="text" class="form-control" id="assunto" name="assunto" placeholder="até 120 caracteres" maxlength="120" required>
      </div><br><br>
      <div class="col-12">
        <textarea class="form-control" style="background-color: rgba(0,0,0,0.1); font-size: 11px" name="mensagem" id="mensagem" cols="160" rows="10"
                  placeholder="Mensagem em até 500 caracteres"></textarea>
      </div>
      <div class="col-md-2">
        <input style="width: 140px" class="btn btn-primary" type="submit" id="enviar" name="enviar" value="Enviar">
      </div><br>
      <div class="col-md-3">
        <input style="width: 150px" class="btn btn-secondary" type="reset" id="reset" name="reset" value="Descartar e Sair" onclick="location.href='aplicativo/15Mensagens.php'">
      </div><br>   
    </form>
  </div>
</div>