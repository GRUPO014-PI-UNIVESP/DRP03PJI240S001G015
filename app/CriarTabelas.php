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
  <br>
  <br>
  <?php 
  $nomeTabela = 'verde'; $novoID = 'ID_' . strtoupper($nomeTabela);
  echo $novoID;
  $query = "CREATE TABLE $nomeTabela ($novoID INT AUTO_INCREMENT PRIMARY KEY);";
  $connDB->exec($query);
  ?>
</div>