<?php
// inclusão do banco de dados e estrutura base da página web
include_once 'ConnectDB.php';
include_once 'EstruturaPrincipal.php';

//verifica identificador do registro para busca no banco de dados
if(!empty($_GET['id'])){

  $id_del   = $_GET['id'];
  $queryMsg = $connDB->prepare("DELETE FROM quadro_funcionarios WHERE ID_FUNCIONARIO = :id");
  $queryMsg->bindParam(':id', $id_del, PDO::PARAM_INT);
  $queryMsg->execute();
  header('Location: 06QuadroFuncionarios.php');
}