<?php
//
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Leitura de Mensagens';
include_once './RastreadorAtividades.php';

if(!empty($_GET['id'])){

  $id_edit   = $_GET['id'];
  $queryUser = $connDB->prepare("SELECT * FROM mensagens WHERE ID_MENSAGEM = $id_edit LIMIT 1");
  $queryUser->execute();
  $rowID     = $queryUser->fetch(PDO::FETCH_ASSOC);

  $lido = $connDB->prepare("UPDATE mensagens SET CONFIRMA = 'READ' WHERE ID_MENSAGEM = :idMsg");
  $lido->bindParam(':idMsg', $id_edit, PDO::PARAM_INT);
  $lido->execute();

  $send = filter_input_array(INPUT_POST, FILTER_DEFAULT);

  if(!empty($send['submit'])){

    $dResposta = date('Y-m-d'); $hResposta = date('H:i:s'); $confirma = 'UNRE'; $receptor = strtoupper($rowID['EMISSOR_MSG']);
    $emissor = strtoupper($rowID['RECEPTOR_MSG']); $dpEmissor = strtoupper($rowID['DEPTO_RECEPTOR']); $dpReceptor = strtoupper($rowID['DEPTO_EMISSOR']);
    
    $enviar = $connDB->prepare("INSERT INTO mensagens (EMISSOR_MSG, DEPTO_EMISSOR, DATA_MSG, HORA_MSG, RECEPTOR_MSG, DEPTO_RECEPTOR, TITULO_MSG, MENSAGEM, CONFIRMA) 
                                VALUES (:emissor, :deptoEmissor, :dataMsg, :horaMsg, :receptor, :deptoReceptor, :titulo, :mensagem, :confirma)");

    $enviar->bindParam(':emissor'      , $emissor         , PDO::PARAM_STR);
    $enviar->bindParam(':deptoEmissor' , $dpEmissor       , PDO::PARAM_STR);
    $enviar->bindParam(':dataMsg'      , $dResposta       , PDO::PARAM_STR);
    $enviar->bindParam(':horaMsg'      , $hResposta       , PDO::PARAM_STR);
    $enviar->bindParam(':receptor'     , $receptor        , PDO::PARAM_STR);
    $enviar->bindParam(':deptoReceptor', $dpReceptor      , PDO::PARAM_STR);
    $enviar->bindParam(':titulo'       , $send['assunto']  , PDO::PARAM_STR);
    $enviar->bindParam(':mensagem'     , $send['resposta'], PDO::PARAM_STR);
    $enviar->bindParam(':confirma'     , $confirma        , PDO::PARAM_STR);
    $enviar->execute();

    header('Location: ./15Mensagens.php');
  }

}
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time;
    window.onload        = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress  = resetTimer;
    function deslogar() {
      <?php
        $_SESSION['posicao'] = 'Encerrado por inatividade';
        include_once './RastreadorAtividades.php';
      ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() {
      clearTimeout(time);
       time = setTimeout(deslogar, 60000);
     }
  };
  inactivityTime();
</script>
<div class="main">
  <div class="container">
    <br>
    <form class="row g-2" method="post" action="#">
      <div class="col-7">
        <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Remetente</label>
        <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" value="<?php echo $rowID['EMISSOR_MSG']; ?>" readonly>
      </div>
      <div class="col-5">
        <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Departamento</label>
        <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" value="<?php echo $rowID['DEPTO_EMISSOR']; ?>" readonly>
      </div>
      <div class="col-3">
        <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Data</label>
        <input style="font-size: 12px" type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($rowID['DATA_MSG'])); ?>" readonly>
      </div>
      <div class="col-3">
        <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Hora</label>
        <input style="font-size: 12px" type="text" class="form-control" value="<?php echo date('H:i:s', strtotime($rowID['HORA_MSG'])); ?>" readonly>
      </div>      
      <div class="col-12">
        <label for="nomeFunc" class="form-label" style="font-size: 10px; color:aqua">Assunto</label>
        <input style="font-size: 12px" type="text" class="form-control" value="<?php echo $rowID['TITULO_MSG']; ?>" readonly>
      </div> 
      <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label" style="font-size: 10px; color:aqua">Mensagem</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" style="font-size: 14px"><?php echo $rowID['MENSAGEM']; ?></textarea>
      </div>
      <!-- Botão para confirmar -->
      <div class="col-12"><br>
        <!-- Aciona Modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width: 300px">Responder</button>
        <button type="button" class="btn btn-secondary" style="width: 300px" onclick="location.href='15Mensagens.php'">Voltar</button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel" style="font-size: 12px; color: aqua">Preencha sua resposta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label" style="font-size: 10px; color: aqua">Remetente:</label>
                  <input type="text" class="form-control" id="recipient-name" name="remetente" style="font-size: 12px" value="<?php echo $_SESSION['nome_func'] ?>" readonly>
                </div>
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label" style="font-size: 10px; color: aqua">Assunto:</label>
                  <input type="text" class="form-control" id="recipient-name" name="assunto" style="font-size: 12px">
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label" style="font-size: 10px; color: aqua">Mensagem</label>
                  <textarea class="form-control" id="message-text" name="resposta"></textarea>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 200px" onclick="location.href='./15Mensagens.php'">Descartar</button>
              <input class="btn btn-primary" type="submit" id="submit" name="submit" value="Enviar" style="width: 200px">
            </div>
          </div>
        </div>
      </div>            
      </div>
    </form>
  </div>
</div>