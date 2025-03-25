<?php
  // index.php
  // Programa de abertura do sistema pedindo usuário e senha

  // inicia sessão de trabalho.
    session_start();

  // limpa buffer de saída
    ob_start();

  //definição de hora local
    date_default_timezone_set('America/Sao_Paulo');
      
  //Chama conexão com banco de dados que está em outro programa
    include_once './ConnectDB.php'; 
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="dark">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- link da biblioteca do CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Sistema | Login</title>
  </head>
  <body>
    <?php
      // capta os dados inseridos no formulário Login
      $login_dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

      //verifica se foi digitado dados
      if(!empty($login_dados['submit'])){

        //busca dados cadastrados 
        $result_query = $connDB->prepare("SELECT * FROM quadro_funcionarios WHERE USUARIO = :usuario LIMIT 1");

        // atribui valor do campo para o link de dados :usuario
        $result_query->bindParam(':usuario', $login_dados['usuario'], PDO::PARAM_STR);  $result_query->execute();

        if(($result_query) AND ($result_query->rowCount() != 0)){ $row_user = $result_query->fetch(PDO::FETCH_ASSOC);

          //verifica se usuario foi digitado conforme case sensitive(verifica caractere por caractere)
          if(password_verify($login_dados['usuario'], $row_user['ID_USUARIO'])){

            //verifica se senha esta correta
            if(password_verify($login_dados['senha'], $row_user['SENHA_USUARIO'])){

              //atribui valores de usuário para variáveis globais
              $_SESSION['nome_func']    = $row_user['NOME_FUNCIONARIO']; $_SESSION['cargo']      = $row_user['CARGO'];
              $_SESSION['departamento'] = $row_user['DEPARTAMENTO'];     $_SESSION['credencial'] = $row_user['CREDENCIAL'];
              $_SESSION['usuario']      = $row_user['USUARIO'];          $_SESSION['senha']      = $row_user['SENHA'];
              $_SESSION['idFunc']       = $row_user['ID_FUNCIONARIO'];

              //atribui data e hora para registro no histórico de login
              $dataLog  = date('Y-m-d'); $_SESSION['dataLog'] = $dataLog; $horaLog  = date('H:i:s'); $_SESSION['horaLog'] = $horaLog;

              //inserir dados do usuário no histórico de Login
              $register = $connDB->prepare("INSERT INTO historico_login (NOME_FUNCIONARIO, DEPARTAMENTO, DATA_LOGIN, HORA_LOGIN)
                                            VALUES ('" . $row_user['NOME_FUNCIONARIO'] . "','" . $row_user['DEPARTAMENTO'] . "',
                                                    '" . $dataLog . "', '" . $horaLog . "')");
              $register->execute();

              $query_track = $connDB->prepare("SELECT ID_LOGIN FROM historico_login WHERE DATA_LOGIN = :dataLog AND HORA_LOGIN = :horaLog AND NOME_FUNCIONARIO = :nomeFunc");
              $query_track->bindParam(':dataLog' , $dataLog              , PDO::PARAM_STR);
              $query_track->bindParam(':horaLog' , $horaLog              , PDO::PARAM_STR);
              $query_track->bindParam(':nomeFunc', $_SESSION['nome_func'], PDO::PARAM_STR);
              $query_track->execute();
              $tracking = $query_track->fetch(PDO::FETCH_ASSOC);

              $_SESSION['idLogin'] = $tracking['ID_LOGIN'];
              //direciona fluxo
              header("Location: ./Dashboard.php");
            }else{

              //mensagem de erro de entrada
              $_SESSION['msg'] = "<p style='color: red; text-align: center'>Erro: usuário ou senha incorreta!</p>";
            } 
          }else{

            //mensagem de erro de entrada
            $_SESSION['msg'] = "<p style='color: orange; text-align: center'>Erro: usuário ou senha incorreta!</p>";
          }
        }
      }
      //se houver erro de entrada mostra erro na página
      if(isset($_SESSION['msg'])){echo $_SESSION['msg'];unset($_SESSION['msg']);}
    ?>
    <!-- Formulário de entrada de dados para Login -->
    <form method="POST" action="#">
      <div class="container-fluid">
        <div class="col-3 mt-3 mb-3 mx-auto">
          <h1 style="text-align: center">Login</h1><br>

          <!-- Label de entrada do usuário -->
          <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
              </svg>
            </span>

            <!-- Input do usuário -->
            <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuário" 
              maxlength="15" aria-label="Username" aria-describedby="addon-wrapping" require>
          </div><br>

          <!-- Label de entrada da senha -->
          <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/>
              </svg>   
            </span>

            <!-- Input da senha -->
            <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha de 6 dígitos" 
              maxlength="6" aria-label="Password" aria-describedby="addon-wrapping" require>
          </div><br>

          <!-- Botão para acessar -->
          <div class="d-grid gap-2">
            <input class="btn btn-primary" type="submit" id="submit" name="submit" value="Acessar" require>
          </div><br>

          <!-- Botão para recarregar e começar nova entrada caso ocorra algum erro -->
          <div class="d-grid gap-2">
            <input class="btn btn-secondary" type="reset" id="reset" name="reset" value="Recarregar" onclick="location.href='./index.php'">
          </div>
          <br><br>
          <div>
            <p class="text-break" style="font-size: 11px; color:#DEB887; text-align: center;">Developed by DRP03PJI310S002G013 2025</p>
          </div>
        </div>
      </div>
    </form>
    <!-- link para biblioteca Java Script do Bootstrap --> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
  </body>
</html>