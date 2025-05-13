<?php
  // inclusão do banco de dados e estrutura base da página web
  include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor de Produtos'; include_once './RastreadorAtividades.php';

  //atribui usuário como responsável por registro de entrada do material ou cadastramento
  $responsavel = $_SESSION['nome_func'];

  try{
    $connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //faz a busca do pedido solicitado na entrada
        $buscaPedido = $connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :numPed");
        $buscaPedido->bindParam(':numPed', $_SESSION['nPedido'], PDO::PARAM_INT);
        $buscaPedido->execute();
        $rowPedido = $buscaPedido->fetch(PDO::FETCH_ASSOC);
      // faz a busca dos dados da fabricação do produto
        $buscaProd = $connDB->prepare("SELECT * FROM producao WHERE NUMERO_LOTE = :numLote");
        $buscaProd->bindParam(':numLote', $rowPedido['NUMERO_LOTE'], PDO::PARAM_INT);
        $buscaProd->execute();
        $rowProd = $buscaProd->fetch(PDO::FETCH_ASSOC);
      // faz a busca do numero identificador do produto
        $buscaProduto = $connDB->prepare("SELECT N_PRODUTO FROM produtos WHERE PRODUTO = :nomeProd");
        $buscaProduto->bindParam(':nomeProd', $rowPedido['PRODUTO'], pdo::PARAM_STR);
        $buscaProduto->execute(); 
        $rowProduto = $buscaProduto->fetch(PDO::FETCH_ASSOC);
        $_SESSION['nProduto'] = $rowProduto['N_PRODUTO'];

      // faz a seleção da estrutura da tabela
        $selTab = $connDB->prepare("SELECT * FROM estrutura WHERE PROCEDIMENTO = 4 AND ATIVO = 1");
        $selTab->execute();
        $rowTab = $selTab->fetch(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e){ echo 'Ocorreu algum problema durante a criação da tabela, comunique o responsável do TI.'; }   
?>
<!-- Área Principal -->
<div class="main">
  <br><p style="font-size: 20px; color: whitesmoke">Dados Complementares de Produção</p><br>
  <div class="row g-2">
    <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Número do Pedido</label>
        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: center; width: 160px; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowPedido['NUMERO_PEDIDO'] ?>" disabled>
    </div>
    <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Quantidade</label>
        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: right; width: 160px; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido"
               value="<?php echo number_format($rowPedido['QTDE_PEDIDO'],0,',','.') . ' ' . $rowPedido['UNIDADE'] ?>" disabled>
    </div>
    <div class="col-md-7">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Produto</label>
        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: left; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowPedido['PRODUTO'] ?>" disabled>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Numero do Lote</label>
        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: center; width: 160px; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo $rowProd['NUMERO_LOTE'] ?>" disabled>
    </div>
    <div class="col-md-2">
        <label for="nPedido" class="form-label" style="font-size: 10px; color:aqua">Data de Fabricação</label>
        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: center; width: 160px; color: yellow" 
               type="text" class="form-control" id="nPedido" name="nPedido" value="<?php echo date('d.m.Y',strtotime($rowProd['DATA_FABRI'])) ?>" disabled>
    </div>
  </div><br>
  <p style="font-size: 20px; color: whitesmoke">Dados Operacionais Registrados</p><br><?php
  $sql0 = 'SELECT * FROM estrutura_campos WHERE ID_ESTRUTURA = :idStruc AND N_CAMPO > 2';
  $querySql0 = $connDB->prepare($sql0);
  $querySql0->bindParam(':idStruc', $rowTab['ID_ESTRUTURA'], PDO::PARAM_INT);
  $querySql0->execute(); ?>
  <div class="row g-2"><?php
    while($rowSql0 = $querySql0->fetch(PDO::FETCH_ASSOC)){ $i = 3;
      $sql1 = 'SELECT ' . $rowSql0['CAMPO'] . ' FROM ' . $rowTab['NOME_TABELA'] . ' WHERE NUMERO_PEDIDO = :nPedido';
      $querySql1 = $connDB->prepare($sql1);
      $querySql1->bindParam(':nPedido', $rowPedido['NUMERO_PEDIDO'], PDO::PARAM_INT);
      $querySql1->execute(); $rowSql1 = $querySql1->fetch(PDO::FETCH_ASSOC);
      switch($rowSql0['CODIGO']){
        case 'I' : ?> <div class="col-md-2">
                        <label for="<?php echo $rowSql0['CODIGO'] . $i; ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql0['ETIQUETA'] ?></label>
                        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: right; width: 160px;" type="number" class="form-control" 
                        id="<?php echo $rowSql0['CODIGO'] . $i; ?>" name="<?php echo $rowSql0['CODIGO'] . $i; ?>" value="<?php echo $rowSql1[$rowSql0['CAMPO']] ?>" disabled>
                      </div><?php break;
        case 'F' : ?> <div class="col-md-2">
                        <label for="<?php echo $rowSql0['CODIGO'] . $i; ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql0['ETIQUETA'] ?></label>
                        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: right; width: 160px;" type="number" inputmode="decimal" class="form-control" 
                        id="<?php echo $rowSql0['CODIGO'] . $i; ?>" name="<?php echo $rowSql0['CODIGO'] . $i; ?>" value="<?php echo $rowSql1[$rowSql0['CAMPO']] ?>" disabled>
                      </div><?php break;
        case 'D' : ?> <div class="col-md-2">
                        <label for="<?php echo $rowSql0['CODIGO'] . $i; ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql0['ETIQUETA'] ?></label>
                        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: center; width: 160px;" type="datetime" class="form-control" 
                        id="<?php echo $rowSql0['CODIGO'] . $i; ?>" name="<?php echo $rowSql0['CODIGO'] . $i; ?>" value="<?php echo $rowSql1[$rowSql0['CAMPO']] ?>" disabled>
                      </div><?php break;
        case 'V' :  if($rowSql0['TAMANHO'] <= 75){ ?>
                      <div class="col-md-6">
                        <label for="<?php echo $rowSql0['CODIGO'] . $i; ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql0['ETIQUETA'] ?></label>
                        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: left;" type="text" class="form-control" maxlength="75" 
                        id="<?php echo $rowSql0['CODIGO'] . $i; ?>" name="<?php echo $rowSql0['CODIGO'] . $i; ?>" value="<?php echo $rowSql1[$rowSql0['CAMPO']] ?>" disabled>
                      </div><?php break;
                    }
                    if($rowSql0['TAMANHO'] > 75){ ?>
                      <div class="col-md-12">
                        <label for="<?php echo $rowSql0['CODIGO'] . $i; ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql0['ETIQUETA'] ?></label>
                        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: left;" type="text" class="form-control" maxlength="150" 
                        id="<?php echo $rowSql0['CODIGO'] . $i; ?>" name="<?php echo $rowSql0['CODIGO'] . $i; ?>" value="<?php echo $rowSql1[$rowSql0['CAMPO']] ?>" disabled>
                      </div><?php break;
                    } 
      } $i += $i;
    } ?>
  </div><br><br>
  <form method="POST">
    <p style="font-size: 20px; color: whitesmoke">Inserir Novos Dados</p><br>
    <div class="row g-2">
      <div class="col-md-3"><?php
        $sql2 = 'SELECT ETIQUETA FROM estrutura_campos WHERE ID_ESTRUTURA = :idStruc AND N_CAMPO > 2';
        $querySql2 = $connDB->prepare($sql2);
        $querySql2->bindParam(':idStruc', $rowTab['ID_ESTRUTURA'], PDO::PARAM_INT);
        $querySql2->execute(); ?>
        <label for="etiqueta" class="form-label" style="font-size: 10px; color:aqua">Etiqueta</label>
        <select style="font-size: 18px;" class="form-select" id="etiqueta" name="etiqueta" style="background: rgba(0,0,0,0.3);">
          <option style="font-size: 14px; background: rgba(0,0,0,0.3)" selected>Selecione</option><?php 
          while($rowSql2 = $querySql2->fetch(PDO::FETCH_ASSOC)){ ?>
            <option style="font-size: 14px; background: rgba(0,0,0,0.3)"><?php echo $rowSql2['ETIQUETA']; ?></option><?php
          } ?>
        </select><br>
        <input type="submit" id="confirma" name="confirma" class="btn btn-outline-primary" value="Confirma Seleção" style="width: 150px">
      </div>
    </div>
  </form><?php
  $coleta = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  if(isset($_POST['confirma'])){
    $sql3 = 'SELECT * FROM estrutura_campos WHERE ETIQUETA = :etiqueta AND ID_ESTRUTURA = :idStruc';
    $querySql3 = $connDB->prepare($sql3);
    $querySql3->bindParam(':idStruc', $rowTab['ID_ESTRUTURA'], PDO::PARAM_INT);
    $querySql3->bindParam(':etiqueta', $coleta['etiqueta'], PDO::PARAM_STR);
    $querySql3->execute(); $rowSql3 = $querySql3->fetch(PDO::FETCH_ASSOC); ?>
    <form method="POST"><?php
      switch($rowSql3['CODIGO']){
        case 'I' : ?> <div class="col-md-2">
                          <label for="<?php echo $rowSql3['CAMPO'] ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql3['ETIQUETA'] ?></label>
                          <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: right; width: 150px;" type="number" class="form-control" 
                          id="<?php echo $rowSql3['CODIGO'] . $i; ?>" name="<?php echo $rowSql3['CAMPO'] ?>" required autofocus>
                        </div><?php break;
        case 'F' : ?> <div class="col-md-2">
                          <label for="<?php echo $rowSql3['CAMPO'] ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql3['ETIQUETA'] ?></label>
                          <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: right; width: 150px;" type="number" inputmode="decimal" class="form-control" 
                          id="<?php echo $rowSql3['CAMPO'] ?>" name="<?php echo $rowSql3['CAMPO'] ?>"required autofocus>
                        </div><?php break;
        case 'D' : ?> <div class="col-md-2">
                          <label for="<?php echo $rowSql3['CAMPO'] ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql3['ETIQUETA'] ?></label>
                          <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: center; width: 180px;" type="datetime-local" class="form-control" 
                          id="<?php echo $rowSql3['CAMPO'] ?>" name="<?php echo $rowSql3['CAMPO'] ?>"required autofocus>
                        </div><?php break;
        case 'V' :  if(!empty($rowSql0['TAMANHO']) <= 75){ ?>
                      <div class="col-md-6">
                        <label for="<?php echo $rowSql3['CAMPO'] ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql3['ETIQUETA'] ?></label>
                        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: left;" type="text" class="form-control" maxlength="75" 
                        id="<?php echo $rowSql3['CAMPO'] ?>" name="<?php echo $rowSql3['CAMPO'] ?>"required autofocus>
                      </div><?php break;
                    }
                    if(!empty($rowSql0['TAMANHO']) > 75){ ?>
                      <div class="col-md-12">
                        <label for="<?php echo $rowSql3['CAMPO'] ?>" class="form-label" style="font-size: 10px; color:aqua"><?php echo $rowSql3['ETIQUETA'] ?></label>
                        <input style="font-weight: bold; font-size: 13px; background: rgba(0,0,0,0.3); text-align: left;" type="text" class="form-control" maxlength="150" 
                        id="<?php echo $rowSql3['CAMPO'] ?>" name="<?php echo $rowSql3['CAMPO'] ?>"required autofocus>
                      </div><?php break;
                    }
        } ?>
    </form><?php
    $registra = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(isset($_POST[$rowSql3['CODIGO']])){
      echo $registra[$rowSql3['CODIGO']];
    }
  }?>
</div>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
  }; inactivityTime();
  // atribui função de enviar formulário
  function submeterFormulario() { document.getElementById("enviar").submit(); }

  function receberValor(){ var tipo = document.getElementById("campo").value; }
</script>
<style>  .tabela{ width: 100%; height: auto ; overflow-y: scroll;} </style>