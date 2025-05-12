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
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
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
      <div class="row g-2">
        <p style="color:aqua">Para qual atividade do processo produtivo?</p>
        <div class="col-md-3">
          <div class="form-check" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="novaTabela" id="compra" value="1" onclick="submeterFormulario()">
            <label class="form-check-label" for="compra">Compra de Materiais</label>
          </div>
          <div class="form-check" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="novaTabela" id="recebe" value="2" onclick="submeterFormulario()">
            <label class="form-check-label" for="recebe">Entrada de Materiais</label>
          </div>
          <div class="form-check" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="novaTabela" id="anaMat" value="3" onclick="submeterFormulario()">
            <label class="form-check-label" for="anaMat">Análise de Materiais</label>
          </div>
          <div class="form-check" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="novaTabela" id="produc" value="4" onclick="submeterFormulario()">
            <label class="form-check-label" for="produc">Execução da Produção</label>
          </div>
          <div class="form-check" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="novaTabela" id="anaPro" value="5" onclick="submeterFormulario()">
            <label class="form-check-label" for="anaPro">Análise do Produto</label>
          </div>
          <div class="form-check" style="margin-left: 30px;">
            <input class="form-check-input" type="radio" name="novaTabela" id="entreg" value="6" onclick="submeterFormulario()">
            <label class="form-check-label" for="entreg">Entrega do Produto</label>
          </div>
        </div>
      </div>     
    </form>
  </div>    
  <?php
  if(isset($_POST['novaTabela'])){

    $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $buscaDepto = $connDB->prepare("SELECT * FROM estrutura WHERE PROCEDIMENTO = :proced");
    $buscaDepto->bindParam(':proced', $_POST['novaTabela'], PDO::PARAM_STR);
    $buscaDepto->execute(); $nomeDepto = $buscaDepto->fetch(PDO::FETCH_ASSOC);

    $_SESSION['depto'] = $nomeDepto['DEPARTAMENTO'];
    $_SESSION['procedimento'] = intval($_POST['novaTabela']);

    if(!empty($nomeDepto['PROCEDIMENTO']) && $nomeDepto['ATIVO'] == 0){ ?>
      <p style="color:aquamarine"><?php echo '<br>' . $nomeDepto['DEPARTAMENTO'] . '<br>'; ?></p><?php
    } else{ ?><br><p style="color:red">Não encontramos nenhuma tabela definida para o processo selecionado!!</p><?php }

    //busca tabelas já existentes para o departamento
    $buscaTabelas = $connDB->prepare("SELECT * FROM estrutura WHERE DEPARTAMENTO = :depto");
    $buscaTabelas->bindParam(':depto', $nomeDepto['DEPARTAMENTO'], PDO::PARAM_STR); $buscaTabelas->execute(); ?>
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
          while($rowDepto = $buscaTabelas->fetch(PDO::FETCH_ASSOC)){
            if($rowDepto['NOME_TABELA'] != NULL){ ?>
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
                  <button class="btn btn-outline-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                      <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                    </svg>
                  </button>
                </td>
              </tr><?php
            } 
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