<?php
  // ConnectDB.php
  // faz a conexão com o banco de dados MySQL

  $dbHost = 'localHost';
  $dbUser = 'root';
  $dbPass = '';
  $dbBase = 'drp03pji240s001g015';
  $dbPort = 3306;

  try{
    //conexão com porta: não está ativo
    //$connDB = new PDO('mysqli:host=$dbHost; port=$dbPort; dbname=' . $dbBase, $dbUser, $dbPass);

    //conexão sem porta
    $connDB = new PDO("mysql:host=$dbHost; dbname=" . $dbBase, $dbUser, $dbPass);

    //echo "Conexão realizada com sucesso!";

  } catch(PDOException $err){
    die('Erro de conexão!' . $err->getMessage());
  }