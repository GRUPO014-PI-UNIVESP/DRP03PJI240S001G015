<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';

//verifica identificador do registro para busca no banco de dados
if(!empty($_GET['id'])){

  $id_del   = $_GET['id'];

  $query_nome = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE ID_FUNCIONARIO = :id");
  $query_nome->bindParam(':id', $id_del, PDO::PARAM_INT);
  $query_nome->execute();
  $rowID = $query_nome->fetch(PDO::FETCH_ASSOC);

  $deletar = filter_input_array(INPUT_POST, FILTER_DEFAULT);

  if(!empty($deletar)){
    $queryMsg = $connDB->prepare("DELETE FROM quadro_funcionarios WHERE ID_FUNCIONARIO = :id");
    $queryMsg->bindParam(':id', $id_del, PDO::PARAM_INT);
    $queryMsg->execute();

    header('Location: ./06QuadroFuncionarios.php');
  }
}
?>
<div class="main">
  <div class="container">
    <form method="POST" action="">
      <p style="margin-left: 5%; margin-top: 5%; font-size: 16px">Deseja deletar o registro de <?php echo '[ ' . $rowID['NOME_FUNCIONARIO'] . ' ]' ?> </p>
      <input class="btn btn-primary" style="width: 100px;margin-left: 20%" type="submit" id="submit" name="submit" value="Confirmar">
      <input class="btn btn-danger"  style="width: 100px" type="reset" id="reset" name="reset" value="Cancelar" onclick="location.href='./06QuadroFuncionarios.php'">
    </form>
  </div>
</div>