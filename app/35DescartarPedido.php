<?php
// inclusão do banco de dados e estrutura base da página webinclude_once './ConnectDB.php';
include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'CancelaPedido'; include_once './RastreadorAtividades.php';

$cancelaPedido = $connDB->prepare("DELETE FROM materiais_compra WHERE NUMERO_PEDIDO = :numPedido");
$cancelaPedido->bindParam(':numPedido', $_SESSION['numPedido'], PDO::PARAM_INT); $cancelaPedido->execute();

$cancelaReserva = $connDB->prepare("DELETE FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
$cancelaReserva->bindParam('numPedido', $_SESSION['numPedido'], PDO::PARAM_INT); $cancelaReserva->execute();

header('Location: ./33PedidoProduto1.php');