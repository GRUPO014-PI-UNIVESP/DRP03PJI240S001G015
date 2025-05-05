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
    <form action="" id="novoCampo" method="POST">
      <table class="table table-dark table-bordered">
        <caption>
          <p style="font-size: 11px;">Tamanho do tipo alfanumérico VARCHAR deve ser entre 1 até 255.</p>
          <p style="font-size: 11px;">Inteiros, monetários e data não é obrigatório definir tamanho</p>
        </caption>
        <P style="color: aqua;">Tabela DB: <?php echo ' ' . $rowTabela['NOME_TABELA'] ?></P>
        <thead style="font-size: 12px">
          <tr>
            <th scope="col" style="width: 15%; text-align: center;">Etiqueta do Campo</th>
            <th scope="col" style="width: 15%; text-align: center;">Tipo de Dado</th>
            <th scope="col" style="width: 08%; text-align: center;">Tamanho</th>
            <th scope="col" style="width: 10%; text-align: center;">Campo da Tabela</th>
            <th scope="col" style="width: 50%; text-align: center;">Descrição</th>
          </tr>
        </thead>
        <tbody class="table-group-divider" style="height: 75%; font-size: 11px;"><?php
          $novaEtiq = ''; $novoTipo = ''; $novoSize = 0; $novaDesc = ''; $novoCamp = 'Definido Automaticamente';
          $buscaStruc = $connDB->prepare("SELECT * FROM estrutura_campos WHERE ID_ESTRUTURA = :strucColumn");
          $buscaStruc->BINDpARAM(':strucColumn', $_SESSION['ID_TABELA'], PDO::PARAM_INT);
          $buscaStruc->execute();
          while($rowStruc = $buscaStruc->fetch(PDO::FETCH_ASSOC)){
            switch($rowStruc['TIPO']){
              case 'INT'      : $tipo = 'Numérico Inteiro'; break;
              case 'DATETIME' : $tipo = 'Data e Hora'     ; break;
              case 'VARCHAR'  : $tipo = 'Alfanumérico'    ; break;
              case 'FLOAT'    : $tipo = 'Monetário'       ; break;
            }
            ?>
            <tr>
              <td scope="col" style="width: 15%;"><?php echo $rowStruc['ETIQUETA'] ?></td>
              <td scope="col" style="width: 15%;"><?php echo $rowStruc['TIPO'] . ' [' . $tipo . '] ' ?></td>
              <td scope="col" style="width: 08%; text-align: right;"><?php echo $rowStruc['TAMANHO'] ?></td>
              <td scope="col" style="width: 15%;"><?php echo $rowStruc['CAMPO'] ?></td>
              <td scope="col" style="width: 50%;"><?php echo $rowStruc['DESCRICAO'] ?></td>
            </Tr> <?php
          } ?>              
        </tbody>
        <tfoot>
          <tr>
            <td scope="col" style="width: 15%;">
              <div>
                <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" 
                      type="text" class="form-control" id="novaEtiq" name="novaEtiq" value="<?php echo $novaEtiq ?>"
                      size="25" maxlength="25" autofocus required>
              </div>
            </td>
            <td scope="col" style="width: 15%;">
              <div>
                <select style="font-size: 18px;" class="form-select" id="novoTipo" name="novoTipo" style="background: rgba(0,0,0,0.3);">
                  <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="" selected>Selecione</option>
                  <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="INT">Numérico Inteiro</option>
                  <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="DATETIME">Data e Hora</option>
                  <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="VARCHAR()">Alfanumérico</option>
                  <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="FLOAT">V.Monetários</option>
                </select>
              </div>
            </td>
            <td scope="col" style="width: 08%;">
              <div>
                <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3); text-align: right;" 
                      type="number" class="form-control" id="novoSize" name="novoSize" value="<?php echo $novoSize ?>">
              </div>
            </td>
            <td scope="col" style="width: 10%;">
              <div>
                <input style="font-size: 12px; text-align: right;" 
                      type="number" class="form-control" id="novoCamp" name="novoCamp" value="<?php echo $novoCamp ?>" disabled>
              </div>
            </td>
            <td scope="col" style="width: 50%;">
              <div>
                <input style="font-size: 12px; text-transform:uppercase; background: rgba(0,0,0,0.3)" 
                      type="text" class="form-control" id="novoDesc" name="novoDesc" value="<?php echo $novaDesc ?>">
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
      <input class="btn btn-outline-primary" type="submit" name="adicionar" id="adicionar" value="Adicionar Novo Campo">
      <button class="btn btn-outline-danger" onclick="location.href='./MapaGeral.php'">Descartar e Sair</button>
    </form><?php
    $captaNovo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($captaNovo['adicionar'])){
      if($captaNovo['novoTipo'] == 'INT' && $captaNovo['tamanho'] > 0){
        $novoTipo = $captaNovo['novoTipo'] . '(' .  $captaNovo['tamanho'] . ')';
      } else {$novoTipo = $captaNovo['novoTipo'];}
      if($captaNovo['novoTipo'] == 'VARCHAR' && $captaNovo['tamanho'] > 0){
        $novoTipo = $captaNovo['novoTipo'] . '(' .  $captaNovo['tamanho'] . ')';
      } else {$novoTipo = $captaNovo['novoTipo'];}

      $vUltimo = $connDB->prepare("SELECT MAX(ID) AS IDMAX FROM estrutura_campos");
      $vUltimo->execute(); $ultimoID = $vUltimo->fetch(PDO::FETCH_ASSOC);
      $nomeTabela = 'verde';
      $nomeColuna = 'COLUNA' . $ultimoID['IDMAX'];
      $tipoDado   = 'DATETIME';
      try{
        $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $addColuna = "ALTER TABLE $nomeTabela ADD COLUMN {$nomeColuna} {$tipoDado};";
        $connDB->exec($addColuna);
      } catch(PDOException $e) { echo 'Já existe esse nome, tente outro nome'; }
    } ?>
  </div>
</div>