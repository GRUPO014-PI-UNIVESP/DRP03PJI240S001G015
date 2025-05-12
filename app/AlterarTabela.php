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
  <form action="" id="novoCampo" method="POST">
    <div class="tabela table-responsive">
      <table class="table table-dark table-bordered">
        <caption>
          <p style="font-size: 11px;">A ETIQUETA deve ser no máximo 45 caracteres. O tamanho do tipo alfanumérico VARCHAR deve ser entre 1 até 255.</p>
          <p style="font-size: 11px;">Inteiros, valores monetários e data não é obrigatório definir tamanho</p>
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
          $tipo = ''; $novaEtiq = ''; $novoTipo = ''; $novoSize = null; $novaDesc = ''; $novoCamp = 'Definido Automaticamente';
          $buscaStruc = $connDB->prepare("SELECT * FROM estrutura_campos WHERE ID_ESTRUTURA = :strucColumn");
          $buscaStruc->BINDpARAM(':strucColumn', $_SESSION['ID_TABELA'], PDO::PARAM_INT);
          $buscaStruc->execute();
          while($rowStruc = $buscaStruc->fetch(PDO::FETCH_ASSOC)){  ?>
            <tr>
              <td scope="col" style="width: 15%;"><?php echo strtoupper($rowStruc['ETIQUETA']) ?></td>
              <td scope="col" style="width: 15%;"><?php echo $rowStruc['TIPO'] ?></td>
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
                  <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="VARCHAR">Alfanumérico</option>
                  <option style="font-size: 14px; background: rgba(0,0,0,0.3)" value="FLOAT">V.Monetários</option>
                </select>
              </div>
            </td>
            <td scope="col" style="width: 08%;">
              <div>
                <input style="font-size: 12px; background: rgba(0,0,0,0.3); text-align: right;" 
                      type="number" class="form-control" id="novoSize" name="novoSize" value="<?php echo $novoSize ?>">
              </div>
            </td>
            <td scope="col" style="width: 10%;" class="align-bottom">
              <p style="font-size: 11px; color:green; text-align:center">Definido Automaticamente</p>
            </td>
            <td scope="col" style="width: 50%;">
              <div>
                <input style="font-size: 12px; background: rgba(0,0,0,0.3)" 
                      type="text" class="form-control" id="novoDesc" name="novoDesc" value="<?php echo $novaDesc ?>">
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <input class="btn btn-outline-primary" type="submit" name="adicionar" id="adicionar" value="Adicionar Novo Campo">
    <button class="btn btn-outline-danger" onclick="location.href='./MapaGeral.php'">Descartar e Sair</button>
  </form><?php
    $captaNovo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($captaNovo['adicionar'])){
      if($captaNovo['novoTipo'] == 'INT'     && $captaNovo['novoSize'] > 0){
        $novoTipo = strtoupper($captaNovo['novoTipo'] . '(' .  $captaNovo['novoSize'] . ')');
      } else {$novoTipo = strtoupper($captaNovo['novoTipo']);}
      if($captaNovo['novoTipo'] == 'VARCHAR' && $captaNovo['novoSize'] > 0){
        $novoTipo = strtoupper($captaNovo['novoTipo'] . '(' .  $captaNovo['novoSize'] . ')');
      }

      $vUltimo = $connDB->prepare("SELECT MAX(ID) AS IDMAX FROM estrutura_campos");
      $vUltimo->execute(); $ultimoID = $vUltimo->fetch(PDO::FETCH_ASSOC);

      $campoDef     = strtoupper($captaNovo['novoTipo'] . $ultimoID['IDMAX'] +1) ;
      $nomeTabela   = strtolower($rowTabela['NOME_TABELA']);
      $tipoDado     = strtoupper($novoTipo);
      $novaEtiqueta = strtoupper($captaNovo['novaEtiq']);
      try{
        $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $addColuna = "ALTER TABLE $nomeTabela ADD COLUMN {$campoDef} {$tipoDado};";
        $connDB->exec($addColuna);

        $nCampo = $connDB->prepare("SELECT MAX(N_CAMPO) AS ULTIMO_CAMPO FROM estrutura_campos WHERE ID_ESTRUTURA = :idStruc");
        $nCampo->bindParam(':idStruc', $_SESSION['ID_TABELA'], PDO::PARAM_INT);
        $nCampo->execute(); $ultimo = $nCampo->fetch(PDO::FETCH_ASSOC); $novoC = $ultimo['ULTIMO_CAMPO'] + 1; $inicial = substr($novoTipo, 0, 1);

        $atualizaEstrutura = $connDB->prepare("INSERT INTO estrutura_campos (ID_ESTRUTURA, ETIQUETA, CODIGO, TIPO, TAMANHO, N_CAMPO, CAMPO, DESCRICAO) VALUES (:idStruc, :etiq, :code :tipo, :tama, :novoC, :camp, :descr)");
        $atualizaEstrutura->bindParam(':idStruc', $_SESSION['ID_TABELA'], PDO::PARAM_INT);
        $atualizaEstrutura->bindParam(':etiq'   , $novaEtiqueta         , PDO::PARAM_STR);
        $atualizaEstrutura->bindParam(':code'   , $inicial              , PDO::PARAM_STR);
        $atualizaEstrutura->bindParam(':tipo'   , $novoTipo             , PDO::PARAM_STR);
        $atualizaEstrutura->bindParam(':tama'   , $captaNovo['novoSize'], PDO::PARAM_INT);
        $atualizaEstrutura->bindParam(':novoC'  , $novoC                , PDO::PARAM_INT);
        $atualizaEstrutura->bindParam(':camp'   , $campoDef             , PDO::PARAM_STR);
        $atualizaEstrutura->bindParam(':descr'  , $captaNovo['novoDesc'], PDO::PARAM_STR);
        $atualizaEstrutura->execute();
        $tipo = ''; $novaEtiq = ''; $novoTipo = ''; $novoSize = null; $novaDesc = ''; $novoCamp = 'Definido Automaticamente';

        header('Location: ./AlterarTabela.php');

      } catch(PDOException $e) { echo 'Ocorreu algum problema na adição do novo campo, verifique e reinicie o procedimento. Caso necessário comunique ao responsável pelo TI' . $e; }
    } ?>
</div>