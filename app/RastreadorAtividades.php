<?php
  // index.php
  // Programa de abertura do sistema pedindo usuário e senha

  //definição de hora local
  date_default_timezone_set('America/Sao_Paulo');
      
  //Chama conexão com banco de dados que está em outro programa
  include_once './ConnectDB.php';

  //
  $dataOut = date('Y/m/d'); $horaOut = date('H:i:s');
  $query_reg = $connDB->prepare("UPDATE historico_login 
                                 SET DATA_LOGOUT = :dataOut, HORA_LOGOUT = :horaOut, ULTIMA_POSICAO = :posi
                                 WHERE ID_LOGIN = :idLogin");
  $query_reg->bindParam(':dataOut', $dataOut, PDO::PARAM_STR);
  $query_reg->bindParam(':horaOut', $horaOut, PDO::PARAM_STR);
  $query_reg->bindParam(':posi'   , $_SESSION['posicao'], PDO::PARAM_STR);
  $query_reg->bindParam(':idLogin', $_SESSION['idLogin'], PDO::PARAM_INT);
  $query_reg->execute();

  $query_atv = $connDB->prepare("INSERT INTO rastreamento (ID_LOGADO, NOME_FUNCIONARIO, DEPARTAMENTO, DATA_ATV, HORA_ATV, ATV_ACESSADA)
                                 VALUES (:idLog, :nomeFunc, :depto, :dataAtv, :horaAtv, :localAtv)");
  $query_atv->bindParam(':idLog'   , $_SESSION['idLogin']     , PDO::PARAM_INT);
  $query_atv->bindParam(':nomeFunc', $_SESSION['nome_func']   , PDO::PARAM_STR);
  $query_atv->bindParam(':depto'   , $_SESSION['departamento'], PDO::PARAM_STR);
  $query_atv->bindParam(':dataAtv' , $dataOut                 , PDO::PARAM_STR);
  $query_atv->bindParam(':horaAtv' , $horaOut                 , PDO::PARAM_STR);
  $query_atv->bindParam(':localAtv', $_SESSION['posicao']     , PDO::PARAM_STR);
  $query_atv->execute();
