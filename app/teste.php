<?php
 // ConnectDB.php
  // faz a conexão com o banco de dados MySQL

  // Conexão ao banco de Dados alocado na AWS
  $dbHost = 'projetointegradordb.c5i6gk06k3tm.sa-east-1.rds.amazonaws.com';
  $dbUser = 'EdsOn';
  $dbPass = 'pji240G015';
  $dbBase = 'drp03pji240s001g015';
  $dbPort = 3306;

  // Conexão ao banco de dados local
  //$dbHost = 'localhost';
  //$dbUser = 'root';
  //$dbPass = '';
  //$dbBase = 'drp03pji240s001g015';
  //$dbPort = 3306;

  try{
    //conexão com porta: não está ativo
    //$connDB = new PDO('mysqli:host=$dbHost; port=$dbPort; dbname=' . $dbBase, $dbUser, $dbPass);

    //conexão sem porta
    $connDB = new PDO("mysql:host=$dbHost; port=$dbPort; dbname=" . $dbBase, $dbUser, $dbPass);

    //echo "Conexão realizada com sucesso!";

  } catch(PDOException $err){
    die('Erro de conexão!Verifique!!' . $err->getMessage());
  }
?>

<!-- Área Principal -->
  <div class="main">
    <?php
        $_SESSION['idCompra'] = 5;
        $verifMatPedido = $connDB->prepare("SELECT * FROM materiais_reserva WHERE ID_COMPRA = :idCompra");
        $verifMatPedido->bindParam(':idCompra', $_SESSION['idCompra'], PDO::PARAM_INT);
        $verifMatPedido->execute();
        $verificadorPedido = $verifMatPedido->fetch(PDO::FETCH_ASSOC);
        $numPedido = $verificadorPedido['NUMERO_PEDIDO'];

        $buscaRegMatLiberado = $connDB->prepare("SELECT * FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
        $buscaRegMatLiberado->bindParam(':numPedido', $numPedido, PDO::PARAM_INT);
        $buscaRegMatLiberado->execute();
        $contaMat = $buscaRegMatLiberado->rowCount(); echo $contaMat;
        $nVerificador = $contaMat * 3;
        $verificaDisponibilidade = $connDB->prepare("SELECT SUM(DISPONIBILIDADE) AS VERIFICADOR FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido");
        $verificaDisponibilidade->bindParam('numPedido', $numPedido, PDO::PARAM_INT);
        $verificaDisponibilidade->execute(); $nDisponibilidade = $verificaDisponibilidade->fetch(PDO::FETCH_ASSOC); echo '<br>'; echo $nDisponibilidade['VERIFICADOR'];
        if(!empty($nDisponibilidade['VERIFICADOR'])){
            if($nVerificador == $nDisponibilidade['VERIFICADOR']){ echo 'liberado';}
            if($nVerificador != $nDisponibilidade['VERIFICADOR']){ echo 'bloqueado';}
        }

        ?>

  </div>
