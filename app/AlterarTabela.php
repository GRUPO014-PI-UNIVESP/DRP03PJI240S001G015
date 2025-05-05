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
  <br><p style="font-size: 20px; color: whitesmoke;">Atualização da Estrutura da Tabela de Coleta de Dados</p>
  <?php
  $buscaTabela = $connDB->prepare("SELECT * FROM estrutura WHERE ID_ESTRUTURA = :idStruc");
  $buscaTabela->bindParam(':idStruc', $_SESSION['ID_TABELA'], pdo::PARAM_INT);
  $buscaTabela->execute(); $rowTabela = $buscaTabela->fetch(PDO::FETCH_ASSOC); ?>
  <div class="row g-2">
    <div class="col-md-5">
      <br><p>Departamento: <?php echo ' ' .  $rowTabela['DEPARTAMENTO'] ?></p>
    </div>
  </div>
  <div class="tabela">
    <table class="table table-dark">
      <P style="color: aqua;">Tabela DB: <?php echo ' ' . $rowTabela['NOME_TABELA'] ?></P>
      <thead style="font-size: 12px">
        <tr>
          <th scope="col" style="width: 15%;">Coluna da Tabela</th>
          <th scope="col" style="width: 15%;">Tipo de Dado</th>
          <th scope="col" style="width: 10%;">Tamanho</th>
          <th scope="col" style="width: 50%;">Descrição</th>
        </tr>
      </thead>
      <tbody class="table-group-divider" style="height: 75%; font-size: 11px;"><?php
        $buscaStruc = $connDB->prepare("SELECT * FROM estrutura_campos WHERE ID_ESTRUTURA = :strucColumn");
        $buscaStruc->BINDpARAM(':strucColumn', $_SESSION['ID_TABELA'], PDO::PARAM_INT);
        $buscaStruc->execute();
        while($rowStruc = $buscaStruc->fetch(PDO::FETCH_ASSOC)){
          switch($rowStruc['TIPO']){
            case 'INT'      : $tipo = 'Numérico Inteiro'; break;
            case 'DATETIME' : $tipo = 'Data e Hora'     ; break;
            case 'VARCHAR'  : $tipo = 'Alfanumérico'    ; break;
          }
          ?>
          <tr>
            <td scope="col" style="width: 15%;"><?php echo $rowStruc['CAMPO'] ?></td>
            <td scope="col" style="width: 15%;"><?php echo $rowStruc['TIPO'] . ' [' . $tipo . '] ' ?></td>
            <td scope="col" style="width: 10%;"><?php echo $rowStruc['TAMANHO'] ?></td>
            <td scope="col" style="width: 50%;"><?php echo $rowStruc['DESCRICAO'] ?></td>
          </Tr> <?php
        } ?>              
      </tbody>
      <tfoot>
        <tr>
          <td scope="col" style="width: 15%;">
            
          </td>
          <td scope="col" style="width: 15%;">segunda</td>
          <td scope="col" style="width: 10%;">segunda</td>
          <td scope="col" style="width: 50%;">terceira</td>
        </tr>
      </tfoot>
    </table>
  </div>

  <?php /*
    $nomeTabela = 'verde';
    $nomeColuna = strtoupper('COLUNA1');
    $tipoDado   = 'DATETIME';
    try{
      $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $addColuna = "ALTER TABLE $nomeTabela ADD COLUMN {$nomeColuna} {$tipoDado};";
      $connDB->exec($addColuna);
    } catch(PDOException $e) {
      echo 'Já existe esse nome, tente outro nome'; */?>
      <div>
        <br><br>
        <button class="btn btn-danger" onclick="location.href='./AlterarTabela.php'">Reiniciar</button>
      </div><?php /*
    } */
  ?>
</div>