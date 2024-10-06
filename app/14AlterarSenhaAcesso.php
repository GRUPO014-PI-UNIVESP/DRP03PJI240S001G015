<?php
// Programa: 14AlteraSenhaAcesso.php
// Função  : Alteração de ID de usuário e senha executado pelo próprio usuário durante sua sessão

//definição de hora local
date_default_timezone_set('America/Sao_Paulo');

// inclusão de códigos da estrutura principal do sistema
  include_once './EstruturaPrincipal.php';
  include_once './ConnectDB.php';
  $_SESSION['posicao'] = 'Alterar Senha';
  include_once './RastreadorAtividades.php';

  $altera = filter_input_array(INPUT_POST, FILTER_DEFAULT);

  if(!empty($altera['submit'])){

    $novoUser   = $altera['usuario'];
    $novoPass   = $altera['senha'];
    $criptoUser = password_hash($novoUser, PASSWORD_DEFAULT);
    $criptoPass = password_hash($novoPass, PASSWORD_DEFAULT);
    $idUser     = $_SESSION['idFunc'];

    $registraAlteracao = $connDB->prepare("UPDATE quadro_funcionarios 
                                          SET USUARIO = :usuario, SENHA = :senha, ID_USUARIO = :criptoUser, SENHA_USUARIO = :criptoPass 
                                          WHERE ID_FUNCIONARIO = $idUser");

    $registraAlteracao->bindParam(':usuario',    $novoUser,   PDO::PARAM_STR);
    $registraAlteracao->bindParam(':senha',      $novoPass,   PDO::PARAM_STR);   
    $registraAlteracao->bindParam(':criptoUser', $criptoUser, PDO::PARAM_STR);    
    $registraAlteracao->bindParam(':criptoPass', $criptoPass, PDO::PARAM_STR);
       
    $registraAlteracao->execute();

    header('Location: ./LogOut.php');
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
       time = setTimeout(deslogar, 600000);
     }
  };
  inactivityTime();
</script>
<div class="main">
  <!-- Formulário de entrada de dados para Login -->
  <form method="POST" action="">
    <div class="container-fluid">
      <br><br><br>
      <div class="col-4 mt-3 mb-3 mx-auto">
        <h1 style="font-size: 14px; text-align: center">Alterar ID de Usuário ou Senha de Acesso</h1><br>

        <!-- Label de entrada do usuário -->
        <div class="input-group flex-nowrap">
          <span class="input-group-text" id="addon-wrapping">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
              <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg>
          </span>

          <!-- Input do usuário -->
          <input type="text" id="usuario" name="usuario" class="form-control"
            maxlength="15" aria-label="Username" aria-describedby="addon-wrapping" value="<?php echo $_SESSION['usuario'] ?>" autofocus>
        </div><br>

        <!-- Campo de entrada da senha -->
        <div class="input-group flex-nowrap">
          <span class="input-group-text" id="addon-wrapping">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
              <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/>
            </svg>   
          </span>

          <!-- Input da senha -->
          <input type="text" id="senha" name="senha" class="form-control"
            maxlength="6" aria-label="Password" aria-describedby="addon-wrapping" value="<?php echo $_SESSION['senha'] ?>">
        </div><br>

        <!-- Botão para acessar -->
        <div class="d-grid gap-2">
          <input class="btn btn-primary" type="submit" id="submit" name="submit" value="Confirmar e Salvar">
        </div><br>

        <!-- Botão para recarregar e começar nova entrada caso ocorra algum erro -->
        <div class="d-grid gap-2">
          <input class="btn btn-secondary" type="reset" id="reset" name="reset" value="Descartar e Sair" onclick="location.href='./LogOut.php'">
        </div>
      </div>
    </div>
  </form>
</div>