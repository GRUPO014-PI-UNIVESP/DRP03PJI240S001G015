<?php
// inclusão do banco de dados e estrutura base da página web

use PhpParser\Node\Stmt\Echo_;

include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Criar Novas Tabelas'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 600000);  }
  }; inactivityTime();
  // atribui função de enviar formulário
  function submeterFormulario() { document.getElementById("enviar").submit(); }
</script>
<style> .tabela{ width: 98%; height: 300px; overflow-y: scroll;} </style>
<!-- Área Principal -->
<div class="main"><br>
  <p style="font-size: 20px; color: whitesmoke;">Configuração da Estrutura para coleta de Dados do Sistema</p>
  <br>
  <div class="row g-2">
    <form id="enviar" action="" method="POST">
      <?php $adm = 'adm'; $log = 'log'; $gql = 'gql'; $prd = 'prd'; ?>
      <div class="row g-2">
        <p style="color:aqua">Para qual departamento?</p>
        <div class="col-md-1"></div>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="novaTabela" id="adm" value="<?php echo $adm ?>" onclick="submeterFormulario()">
            <label class="form-check-label" for="adm">Depto Administrativo</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="novaTabela" id="log" value="<?php echo $log ?>" onclick="submeterFormulario()">
            <label class="form-check-label" for="log">Depto de Logística</label>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="novaTabela" id="gql" value="<?php echo $gql ?>" onclick="submeterFormulario()">
            <label class="form-check-label" for="gql">Depto da Garantia de Qualidade </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="novaTabela" id="prd" value="<?php echo $prd ?>" onclick="submeterFormulario()">
            <label class="form-check-label" for="prd">Depto de Produção</label>
          </div>
        </div>
      </div>     
    </form>
  </div>    
  <?php
  //coleta seleção
  $selDepto = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  if(!empty($selDepto)){
    //atribui valor dependendo do departamento selecionado
    switch($selDepto['novaTabela']){
      case 'adm' : $depto = 'ADMINISTRATIVO'       ; break; case 'log' : $depto = 'LOGÍSTICA'; break;
      case 'gql' : $depto = 'GARANTIA DA QUALIDADE'; break; case 'prd' : $depto = 'PRODUÇÃO' ; break;
    }
    //busca departamento
    $_SESSION['depto'] = $depto;
    $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $buscaDepto = $connDB->prepare("SELECT * FROM estrutura WHERE DEPARTAMENTO = :depto");
    $buscaDepto->bindParam(':depto', $depto, PDO::PARAM_STR); $buscaDepto->execute(); $nomeDepto = $buscaDepto->fetch(PDO::FETCH_ASSOC);
    if(!empty($nomeDepto['DEPARTAMENTO'])){ ?><p style="color:aquamarine"><?php echo '<br>' . $nomeDepto['DEPARTAMENTO'] . '<br>'; ?></p><?php
    } else{ ?><br><p style="color:red">Não encontramos nenhuma tabela definida para <?php echo $depto ?>!!</p><?php }

    //busca tabelas já existentes para o departamento
    $buscaTabelas = $connDB->prepare("SELECT * FROM estrutura WHERE DEPARTAMENTO = :depto");
    $buscaTabelas->bindParam(':depto', $depto, PDO::PARAM_STR); $buscaTabelas->execute(); ?>
    <div class="tabela">
      <table class="table table-dark table-hover">
        <thead style="font-size: 12px">
          <tr>
            <th scope="col" style="width: 20%;">Nome da Tabela</th>
            <th scope="col" style="width: 60%;">Descrição da Tabela</th>
            <th scope="col" style="width: 20%;">Ação</th>
          </tr>
        </thead>
        <tbody style="font-size: 13px;"><?php
          while($rowDepto = $buscaTabelas->fetch(PDO::FETCH_ASSOC)){ ?>
            <tr>
              <th scope="col" style="width: 20%;"><?php echo $rowDepto['NOME_TABELA']; ?> </th>
              <td scope="col" style="width: 60%;"><?php echo $rowDepto['DESCRICAO_TABELA']; ?> </td>
              <td scope="col" style="width: 20%;"><?php $_SESSION['ID_TABELA'] = $rowDepto['ID_ESTRUTURA']; ?> 
                <!-- Botão para atualização -->
                <button class="btn btn-outline-primary" onclick="location.href='./AlterarTabela.php?id=<?php echo $_SESSION['ID_TABELA'] ?>'">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                  </svg>
                </button>
                <!-- Botão de lixeira -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#<?php echo $rowDepto['ID_ESTRUTURA'] ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                  </svg>
                </button>

              </td>
            </tr><?php 
          }?>
        </tbody>
      </table>
    </div><br>
    <button class="btn btn-outline-primary"   onclick="location.href='./CriarNovaTabela.php'">Criar Nova Tabela</button>
    <button class="btn btn-outline-danger"    onclick="location.href='./MapaGeral.php'">Descartar e Voltar</button>
    <button class="btn btn-outline-secondary" onclick="location.href='./TestadorGerador.php'">Testador</button>
    <?php
  }?>
</div>