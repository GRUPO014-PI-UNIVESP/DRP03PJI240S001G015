<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor de Produtos'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

if(!empty($_GET['id'])){
  $_SESSION['ID_TABELA'] = $_GET['id'];
}
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
  }; inactivityTime();
</script>
<style> .tabela{ width: 100%; height: 480px; overflow-y: scroll;} </style>
<!-- Área Principal -->
<div class="main">
  <p>Teste de geração de formulário</p><?php $procedimento = 1; ?>
  <form action="" method="POST">
    <div class="form-floating"><?php $depto = 'LOGÍSTICA';
      $queryTab = $connDB->prepare("SELECT NOME_TABELA FROM estrutura WHERE PROCEDIMENTO = :procedimento AND ATIVO = 1");
      $queryTab->bindParam(':procedimento', $procedimento, PDO::PARAM_INT);
      $queryTab->execute(); ?>
      <select class="form-select" id="tabela" name="tabela" aria-label="Floating label select example" style="background: rgba(0,0,0,0.3);">
        <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione uma tabela</option><?php
          while($rowTab = $queryTab->fetch(PDO::FETCH_ASSOC)){ ?>
        <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $rowTab['NOME_TABELA']; ?></option><?php } ?>
      </select>
      <label for="colaborador" style="font-size: 12px; color:aqua">Nome da Tabela</label>
    </div>
    <div>
      <br>
      <input class="btn btn-outline-success" type="submit" name="busca" id="busca" value="Busca">
    </div>
  </form><?php
  $captaTab = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  if(!empty($captaTab['busca'])){
    $strucTab = $connDB->prepare("SELECT ID_ESTRUTURA FROM estrutura WHERE NOME_TABELA = :nomeTabela");
    $strucTab->bindParam(':nomeTabela', $captaTab['tabela'], PDO::PARAM_INT);
    $strucTab->execute(); $nomeTabela = $strucTab->fetch(PDO::FETCH_ASSOC);
    
    $camposTab = $connDB->prepare("SELECT * FROM estrutura_campos WHERE ID_ESTRUTURA = :idStruc");
    $camposTab->bindParam(':idStruc', $nomeTabela['ID_ESTRUTURA'], pdo::PARAM_INT);
    $camposTab->execute();
    ?>
    <form action="" id="formar" method="POST">
      <div class="row g-2"><?php
        while($rowStruc = $camposTab->fetch(PDO::FETCH_ASSOC)){
          if($rowStruc['TAMANHO'] > 50)      { $widCol = 6; } else if($rowStruc['TAMANHO'] < 50 ){ $widCol = 2; } 
          if($rowStruc['TIPO'] == 'INT')     { $tipo = 'number'; $inputMode = '';}
          if($rowStruc['TIPO'] == 'DATETIME'){ $tipo = 'datetime-local'; $inputMode = '';}
          if($rowStruc['TIPO'] == 'FLOAT')   { $tipo = 'number'; $inputMode = 'decimal';}
          ?>
          <div class="col-md-<?php echo $widCol ?>">
            <label for="<?php echo strtoupper($rowStruc['CAMPO']) ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo strtoupper($rowStruc['ETIQUETA']) ?></label>
            <input style="font-size: 12px; background: rgba(0,0,0,0.3)"  type="<?php echo $tipo; ?>" inputmode="<?php echo $inputMode; ?>" class="form-control" id="<?php echo strtoupper($rowStruc['CAMPO']) ?>" 
                   name="<?php echo strtoupper($rowStruc['CAMPO']) ?>" value="" required>
          </div><?php
        } ?>
      </div>
    </form>
    <?php
  }
  ?>
</div>