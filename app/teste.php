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
      $dataHoje = date('Y-m-d H:i');
      echo ''. $dataHoje .'';

        ?>

  </div>
