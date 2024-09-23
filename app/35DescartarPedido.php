<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php';
  include_once './EstruturaPrincipal.php';
  $_SESSION['posicao'] = 'CancelaPedido';
  include_once './RastreadorAtividades.php';

   $numPedido = $_SESSION['numPedido'];

   $cancelaPedido = $connDB->prepare("DELETE FROM agenda_compra WHERE PEDIDO_NUM = :numPedido");
   $cancelaPedido->bindParam(':numPedido', $numPedido, PDO::PARAM_INT);
   $cancelaPedido->execute();

   header('Location: ./33PedidoProduto.php');