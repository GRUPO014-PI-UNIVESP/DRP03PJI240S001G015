<?php
 // ConnectDB.php
  // faz a conexão com o banco de dados MySQL

  // Conexão ao banco de Dados alocado na AWS
  $dbHost = 'projetointegradordb.c5i6gk06k3tm.sa-east-1.rds.amazonaws.com';
  $dbUser = 'EdsOn';
  $dbPass = 'pji240G015';
  $dbBase = 'drp03pji240s001g015';
  $dbPort = 3306;

  // Conexão ao banco de dados local
  //$dbHost = 'localhost';
  //$dbUser = 'root';
  //$dbPass = '';
  //$dbBase = 'drp03pji240s001g015';
  //$dbPort = 3306;

  try{
    //conexão com porta: não está ativo
    //$connDB = new PDO('mysqli:host=$dbHost; port=$dbPort; dbname=' . $dbBase, $dbUser, $dbPass);

    //conexão sem porta
    $connDB = new PDO("mysql:host=$dbHost; port=$dbPort; dbname=" . $dbBase, $dbUser, $dbPass);

    //echo "Conexão realizada com sucesso!";

  } catch(PDOException $err){
    die('Erro de conexão!Verifique!!' . $err->getMessage());
  }

  //definição de hora local
date_default_timezone_set('America/Sao_Paulo');
?>
<!-- Área Principal -->
  <div class="main">
    <?php
      $busca = $connDB->prepare("SELECT * FROM historico_tempo WHERE ID_TEMPO = 4");
      $busca->execute(); $row = $busca->fetch(PDO::FETCH_ASSOC);

      $dataC = new datetime($row['T_COMPRA']); $dataI = new datetime($row['INICIO']); $diferenca = $dataI->diff($dataC);
      echo $diferenca->y . 'anos ' . $diferenca->m . 'meses ' . $diferenca->d . 'dias ' . $diferenca->h . 'horas ' . $diferenca->i . 'minutos <br>';
      $compra = ($dataC->getTimestamp() - $dataI->getTimestamp()) / 60;
      echo 'a compra foi entre ' . date('d/m/Y H:i', strtotime($row['INICIO'])) . ' até ' . date('d/m/Y H:i', strtotime($row['T_COMPRA'])) . ' são ' . $compra . 'minutos <br><br>'; 

      $dataR = new datetime($row['T_RECEBE']); $dataC = new datetime($row['T_COMPRA']); $diferenca = $dataC->diff($dataR);
      echo $diferenca->y . 'anos ' . $diferenca->m . 'meses ' . $diferenca->d . 'dias ' . $diferenca->h . 'horas ' . $diferenca->i . 'minutos <br>';
      $recebe = ($dataR->getTimestamp() - $dataC->getTimestamp()) / 60;
      echo 'o recebimento foi entre ' . date('d/m/Y H:i', strtotime($row['T_COMPRA'])) . ' até ' . date('d/m/Y H:i', strtotime($row['T_RECEBE'])) . ' são ' . $recebe . 'minutos <br><br>'; 

      $dataAM = new datetime($row['T_ANAMAT']); $dataR = new datetime($row['T_RECEBE']); $diferenca = $dataR->diff($dataAM);
      echo $diferenca->y . 'anos ' . $diferenca->m . 'meses ' . $diferenca->d . 'dias ' . $diferenca->h . 'horas ' . $diferenca->i . 'minutos <br>';
      $anaMat = ($dataAM->getTimestamp() - $dataR->getTimestamp()) / 60;
      echo 'a análise de material foi entre ' . date('d/m/Y H:i', strtotime($row['T_RECEBE'])) . ' até ' . date('d/m/Y H:i', strtotime($row['T_ANAMAT'])) . ' são ' . $anaMat . 'minutos <br><br>'; 

      $dataF = new datetime($row['T_FABRI']); $dataAM = new datetime($row['T_ANAMAT']); $diferenca = $dataAM->diff($dataF);
      echo $diferenca->y . 'anos ' . $diferenca->m . 'meses ' . $diferenca->d . 'dias ' . $diferenca->h . 'horas ' . $diferenca->i . 'minutos <br>';
      $fabri = ($dataF->getTimestamp() - $dataAM->getTimestamp()) / 60;
      echo 'a fabricação foi entre ' . date('d/m/Y H:i', strtotime($row['T_ANAMAT'])) . ' até ' . date('d/m/Y H:i', strtotime($row['T_FABRI'])) . ' são ' . $fabri . 'minutos <br><br>'; 

      $dataF = new datetime($row['T_FABRI']); $dataAP = new datetime($row['T_ANAPRO']); $diferenca = $dataF->diff($dataAP);
      echo $diferenca->y . 'anos ' . $diferenca->m . 'meses ' . $diferenca->d . 'dias ' . $diferenca->h . 'horas ' . $diferenca->i . 'minutos <br>';
      $anaPro = ($dataAP->getTimestamp() - $dataF->getTimestamp()) / 60;
      echo 'a análise do produto foi entre ' . date('d/m/Y H:i', strtotime($row['T_FABRI'])) . ' até ' . date('d/m/Y H:i', strtotime($row['T_ANAPRO'])) . ' são ' . $anaPro . 'minutos <br><br>'; 
      
      $dataE = new datetime($row['T_ENTREGA']); $dataAP = new datetime($row['T_ANAPRO']); $diferenca = $dataAP->diff($dataE);
      echo $diferenca->y . 'anos ' . $diferenca->m . 'meses ' . $diferenca->d . 'dias ' . $diferenca->h . 'horas ' . $diferenca->i . 'minutos <br>';
      $entrega = ($dataE->getTimestamp() - $dataAP->getTimestamp()) / 60;
      echo 'a entrega foi entre ' . date('d/m/Y H:i', strtotime($row['T_ANAPRO'])) . ' até ' . date('d/m/Y H:i', strtotime($row['T_ENTREGA'])) . ' são ' . $entrega . 'minutos <br><br>';

      $total = $compra + $recebe + $anaMat + $fabri + $anaPro + $entrega;

      $grava = $connDB->prepare("UPDATE historico_tempo SET COMPRA = :compra, RECEBIMENTO = :recebe, ANALISE_MATERIAL = :anaMat, FABRICACAO = :fabri, ANALISE_PRODUTO = :anaPro, ENTREGA = :entrega, TOTAL = :total 
                                        WHERE ID_TEMPO = 4");
      $grava->bindParam(":compra" , $compra , PDO::PARAM_INT);
      $grava->bindParam(":recebe" , $recebe , PDO::PARAM_INT);
      $grava->bindParam(":anaMat" , $anaMat , PDO::PARAM_INT);
      $grava->bindParam(":fabri"  , $fabri  , PDO::PARAM_INT);
      $grava->bindParam(":anaPro" , $anaPro , PDO::PARAM_INT);
      $grava->bindParam(":entrega", $entrega, PDO::PARAM_INT);
      $grava->bindParam(":total"  , $total  , PDO::PARAM_INT);
      $grava->execute();
      ?>

  </div>
