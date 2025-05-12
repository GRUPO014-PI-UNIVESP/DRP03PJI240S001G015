<!-- Gerador de formulário para dados adicionais -->

<p>Teste de geração de formulário</p>
<form action="" id="formulario" method="POST">
  <div class="row g-1">
    <div class="col-md-3">

          // faz a chamada da estrutura com os campos da tabela
          $callStruc2 = $connDB->prepare("SELECT * FROM estrutura_campos WHERE ID_ESTRUTURA = :idStruc AND N_CAMPO > 3");
          $callStruc2->bindParam(':idStruc', $rowTab['ID_ESTRUTURA'], pdo::PARAM_INT);
          $callStruc2->execute(); $i = 0;
          while($rowRegistra = $callStruc2->fetch(PDO::FETCH_ASSOC)){
            $i = $i + 1; $var = 'variavel' . $i;
            echo '<br>' . $rowRegistra['CAMPO'] . ' = ' . $dados[$var];
            $sql3 = 'UPDATE ' . $rowTab['NOME_TABELA'] . ' SET ' . $rowRegistra['CAMPO'] . ' = :valor WHERE NUMERO_PEDIDO = :nPedido';
            $atualizaTabela = $connDB->prepare($sql3);
            $atualizaTabela->bindParam(':valor'  , $dados[$var]);
            $atualizaTabela->bindParam(':nPedido', $nPedido, PDO::PARAM_INT);
            $atualizaTabela->execute();

    </div>
  </div>
</form><?php
  