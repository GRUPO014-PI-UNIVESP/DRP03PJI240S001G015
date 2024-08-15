<?php

include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

$depto_query = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(!empty($depto_query['submit1']) && $depto_query['departamento'] != 'Todos'){ 
  $resultDepto = strtoupper($depto_query['departamento']);

  $desti = $connDB->prepare("SELECT NOME_FUNCIONARIO, DEPARTAMENTO FROM quadro_funcionarios WHERE DEPARTAMENTO = :depto");
  $desti->bindParam(':depto', $resultDepto, PDO::PARAM_STR);
  $desti->execute();
} else if(!empty($depto_query['submit1']) && $depto_query['departamento'] == 'Todos'){

  $desti = $connDB->prepare("SELECT NOME_FUNCIONARIO, DEPARTAMENTO FROM quadro_funcionarios");
  $desti->execute();
}



?>
<div class="main">
  <div class="contain">
    <p style="font-size: 15px">Nova Mensagem</p><br>
    <form class="row g-3" method="POST" action="">
    <div class="col-md-4">
          <label for="departamento" class="form-label" style="font-size: 10px; color:aqua">Departamento</label>
          <select style="font-size: 12px" id="departamento" class="form-select" name="departamento">
            <option style="font-size: 12px" selected>Selecione o Departamento</option>
            <option style="font-size: 12px">Todos</option>
            <option style="font-size: 12px">ADMINISTRATIVO</option> 
            <option style="font-size: 12px">GARANTIA DA QUALIDADE</option>
            <option style="font-size: 12px">LOGÍSTICA</option>      <option style="font-size: 12px">PRODUÇÃO</option>
          </select>
        </div>
        <div class="col-md-6"><br>
          <input style="width: 120px" class="btn btn-primary" type="submit" id="submit1" name="submit1" value="Pesquisar">
          <input style="width: 120px" class="btn btn-secondary" type="reset"  id="reset"  name="reset"  value="Recarregar" style="width: 300px"
                  onclick="location.href='17NovaMsg.php'">
        </div><br>
    </form>
    <form class="row g-3" method="POST" action="">

    <?php
      while($rowLog = $desti->fetch(PDO::FETCH_ASSOC)){
        
      }
    ?>
    </form>
  </div>
</div>