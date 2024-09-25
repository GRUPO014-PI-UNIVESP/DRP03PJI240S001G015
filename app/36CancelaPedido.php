<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'Pedido de Produto';
  include_once './RastreadorAtividades.php';

  //verifica identificador do registro para busca no banco de dados
  if(!empty($_GET['id'])){

   $numPedido = $_GET['id'];
   $situacao = 'COMPRA AGENDADA';
   // deleta materiais de agendamento de compras caso ainda não foram efetivados
   $compra = $connDB->prepare("DELETE FROM agenda_compra WHERE PEDIDO_NUM = :numPedido AND SITUACAO_QUALI = :situacao");
   $compra->bindParam(':numPedido', $numPedido, PDO::PARAM_INT);
   $compra->bindParam(':situacao', $situacao, PDO::PARAM_STR);
   $compra->execute();

   // deleta pedido da fila de ocupação da planta
   $fila = $connDB->prepare("DELETE FROM fila_ocupacao WHERE PEDIDO_NUM = :numPedido");
   $fila->bindParam(':numPedido', $numPedido, PDO::PARAM_INT);
   $fila->execute();

   // deleta pedido
   $pedido = $connDB->prepare("DELETE FROM pf_pedido WHERE NUMERO_PEDIDO = :numPedido");
   $pedido->bindParam(':numPedido', $numPedido, PDO::PARAM_INT);
   $pedido->execute();

   header('Location: ./00SeletorAdministrativo.php');
 }