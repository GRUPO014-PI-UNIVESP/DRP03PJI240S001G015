<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Verifica Detalhes do Log';
include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];

// busca de registro no banco de dados
$query_log = $connDB->prepare("SELECT * FROM rastreamento WHERE ID_LOGADO = :regLog ORDER BY HORA_ATV DESC");
$query_log->bindParam(':regLog', $_SESSION['id_log'], PDO::PARAM_INT);
$query_log->execute(); $logado = $query_log->fetch(PDO::FETCH_ASSOC);
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
       time = setTimeout(deslogar, 69900000);
     }
  };
  inactivityTime();
</script>
<style>
  .tabela{ width: 500px; height: 500px; overflow-y: scroll; }
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <P style="font-size: 20px; color:aqua;">Atividades da Sessão</P><br><br>
        <p>Nome: <?php echo $logado['NOME_FUNCIONARIO']; ?></p>
        <div class="tabela">
            <table class="table table-dark table-hover">
                <thead style="font-size: 12px">
                  <tr>
                    <th scope="col" style="width: 10%; text-align: center">Hora de Acesso</th>
                    <th scope="col" style="width: 20%;">Atividade</th>
                  </tr>
                </thead>
                <tbody style="height: 75%; font-size: 10px;">
                    <?php while($nomeLista = $query_log->fetch(PDO::FETCH_ASSOC)){ ?>
                    <tr>
                        <th style="width: 10%; text-align: center"> <?php echo $nomeLista['HORA_ATV']; ?> </th>
                        <td style="width: 20%;"> <?php echo $nomeLista['ATV_ACESSADA']; ?> </td>        
                    </tr><?php } ?>
                </tbody>
            </table>
        </div><br>           
        <button type="button" class="btn btn-info" style="width: 220px;" onclick="location.href='./05MonitorLogin.php'">Voltar ao Quadro de Funcionários</button>
  </div>
</div>