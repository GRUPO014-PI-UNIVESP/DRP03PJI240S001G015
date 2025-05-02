<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor de Produtos'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
  }; inactivityTime();
</script>
<style> .tabela{ width: 100%; height: 680px; overflow-y: scroll;} </style>
<!-- Área Principal -->
<div class="main">
  <form action="" method="POST">

  </form>
  <?php
  /*
    $nomeTabela = 'verde';
    $nomeColuna = strtoupper('COLUNA1');
    $tipoDado   = 'DATETIME';
    try{
      $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $addColuna = "ALTER TABLE $nomeTabela ADD COLUMN {$nomeColuna} {$tipoDado};";
      $connDB->exec($addColuna);
    } catch(PDOException $e) {
      echo 'Já existe esse nome, tente outro nome'; ?>
      <div>
        <br><br>
        <button class="btn btn-danger" onclick="location.href='./AlterarTabela.php'">Reiniciar</button>
      </div><?php
    } */
  ?>
</div>