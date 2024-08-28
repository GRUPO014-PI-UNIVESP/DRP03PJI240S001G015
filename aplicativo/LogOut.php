<?php

  // inicia sessão de trabalho
  session_start(); 
  // limpa buffer de saída
    ob_start();

  //definição de hora local
  date_default_timezone_set('America/Sao_Paulo');

  //Chama conexão com banco de dados que está em outro programa
  include_once './ConnectDB.php';

  //atribui valores de login e logout para variáveis
  $dataOut = date('Y-m-d');
  $horaOut = date('H:i:s');
  $dataLog = $_SESSION['dataLog'];
  $horaLog = $_SESSION['horaLog'];
  $nomeLog = $_SESSION['nome_func'];

  //registra data e hora de logout de usuário antes de desconectar e finalizar sessão 
  $closeLog = $connDB->prepare("UPDATE historico_login SET DATA_LOGOUT = :dout, HORA_LOGOUT = :hout
                                 WHERE NOME_FUNCIONARIO = :nomeFunc AND DATA_LOGIN = :din AND HORA_LOGIN = :hin");
  $closeLog->bindParam(':dout', $dataOut);
  $closeLog->bindParam(':hout', $horaOut);
  $closeLog->bindParam(':nomeFunc', $nomeLog);
  $closeLog->bindParam(':din', $dataLog);
  $closeLog->bindParam(':hin', $horaLog);
  $closeLog->execute();

  // destroi dados de variáveis da sessão e finaliza sessão
  session_unset();
  session_destroy();

  // redireciona fluxo para index.php
  header("Location: index.php");