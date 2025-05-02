<?php
// inclusão do banco de dados e estrutura base da página web

use PhpParser\Node\Stmt\Echo_;

include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Criar Novas Tabelas'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func']; $novoNome = '';
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
  // atribui função de enviar formulário
  function submeterFormulario() {
    document.getElementById("criar").submit();
  }
</script>
<style> .tabela{ width: 98%; height: 200px; overflow-y: scroll;} </style>
<!-- Área Principal -->
<form class="main"><br>
  <form class="row g-2" action="" id="criar" method="POST">
    <div class="col-md-5">
      <label for="novoNome" class="form-label" style="font-size: 10px; color:aqua">Nome da Nova Tabela</label>
      <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" 
             type="text" class="form-control" id="novoNome" name="novoNome" value="<?php echo $novoNome ?>" size="25" maxlength="25" required autofocus>
      <p style="font-size: 10px; color: grey">Tamanho máximo de 25 caracteres</p>
    </div>
    <div class="col-md-7"></div>
    <div class="col-md-2">
      <input type="submit" id="criar" name="criar" value="Criar">
    </div>
  </form>
  <?php
    $criar = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($criar)){
      $nomeTabela = $criar['novoNome']; $novoID = 'ID_' . strtoupper($nomeTabela);
      try{
        $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "CREATE TABLE $nomeTabela ($novoID INT AUTO_INCREMENT PRIMARY KEY);";
        $connDB->exec($query);
        echo 'Tabela criada com sucesso!!'; ?>
        <button class="btn btn-success" onclick="location.href='./AlterarTabela.php'">Clique para continuar e definir os campos para coleta de dados</button><?php
      } catch(PDOException $e) {
        echo 'Já existe esse nome, tente outro nome'; ?>
        <div>
          <br><br>
          <button class="btn btn-danger" onclick="location.href='./ConfigurarEstrutura.php'">Reiniciar</button>
        </div><?php
      }
    }
  ?>
</div>