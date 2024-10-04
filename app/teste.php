<?php
// index.php
// Programa de abertura do sistema pedindo usuário e senha

// inicia sessão de trabalho.
   session_start();

// limpa buffer de saída
   ob_start();

//definição de hora local
   date_default_timezone_set('America/Sao_Paulo');
      
//Chama conexão com banco de dados que está em outro programa
   include_once './ConnectDB.php'; 

$pedido = $connDB->prepare("SELECT * FROM pf_pedido"); $pedido->execute();
while($verifica = $pedido->fetch(PDO::FETCH_ASSOC)){
   $itens = $pedido->rowCount(); echo $itens; $conta = 0;
   $tabela = $connDB->prepare("SELECT * FROM pf_tabela WHERE NOME_PRODUTO = :nomeProduto");
   $tabela->bindParam(':nomeProduto', $verifica['NOME_PRODUTO'], PDO::PARAM_STR);
   $tabela->execute(); 
   while($material = $tabela->fetch(PDO::FETCH_ASSOC)){
      $estoque = $connDB->prepare("SELECT SUM(QTDE_ESTOQUE) AS total FROM mp_estoque WHERE DESCRICAO_MP = :descrMat");
      $estoque->bindParam(':descrMat', $material['DESCRICAO_MP'], PDO::PARAM_STR);
      $estoque->execute(); $qtdeEstoque = $estoque->fetch(PDO::FETCH_ASSOC);
      if($qtdeEstoque['total'] > ($verifica['QTDE_LOTE_PF'] * ($material['PROPORCAO_MATERIAL'] / 100))){
         $conta = $conta + 1;
      }
   }
   if($conta == $itens){
      $atualiza = 'MATERIAIS LIBERADOS, AGUARDANDO FABRICAÇÃO';
      $registra = $connDB->prepare("UPDATE pf_pedido SET ETAPA_PROD = :etapa, SITUACAO_QUALI = :situacao WHERE NOME_PRODUTO = :nomeProduto");
      $registra->bindParam(':etapa'      , $etapa                   , PDO::PARAM_INT);
      $registra->bindParam(':situacao'   , $atualiza                , PDO::PARAM_STR);
      $registra->bindParam(':nomeProduto', $verifica['NOME_PRODUTO'], PDO::PARAM_STR);
      $registra->execute();
   }
}
    