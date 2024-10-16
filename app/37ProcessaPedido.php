<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Produção';
include_once './RastreadorAtividades.php';
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
       time = setTimeout(deslogar, 6000000);
     }
  };
  inactivityTime();
</script>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid">
    <form method="POST">
      <div class="row g-2">
        <div class="col-md-12"><br>
          <h5>Registro de Execução de Fabricação</h5><br>
          <h6 style="color:aqua">Informações do Pedido</h6>
        </div><?php
        $dadosPedido = $connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :idPed");
        $dadosPedido->bindParam(':idPed', $_GET['id'], PDO::PARAM_INT);
        $dadosPedido->execute(); $rowPedido = $dadosPedido->fetch(PDO::FETCH_ASSOC); ?>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="dataPedido" name="dataPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo date('d/m/Y', strtotime($rowPedido['DATA_PEDIDO'])) ?>" readonly>
            <label for="dataPedido" style="color: aqua; font-size: 12px; background: none">Data do Pedido</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="numPedido" name="numPedido" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $rowPedido['NUMERO_PEDIDO'] ?>" readonly>
            <label for="numPedido" style="color: aqua; font-size: 12px; background: none">Pedido No.</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $rowPedido['PRODUTO'] ?>" readonly>
            <label for="nomeProduto" style="color: aqua; font-size: 12px; background: none">Nome do Produto</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="qtdeLote" name="qtdePedido" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center;color: yellow" 
              value="<?php echo number_format($rowPedido['QTDE_PEDIDO'], 0, ',', '.') . ' ' . $rowPedido['UNIDADE'] ?>" readonly>
            <label for="qtdePedido" style="color: aqua; font-size: 12px; background: none">Quantidade do Pedido</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div>
        <div class="col-md-7">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="cliente" name="cliente" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" 
              value="<?php echo $rowPedido['CLIENTE'] ?>" readonly>
            <label for="cliente" style="color: aqua; font-size: 12px; background: none">Cliente</label>
            <p style="font-size: 11px; color: grey"></p>
          </div>
        </div><?php
        // algoritmo para geração de numero de lote interno
        // verifica último lote registrado
        $ultimo = $connDB->prepare("SELECT MAX(NLPSEQ) AS U_SEQ, MAX(NLPMES) AS U_MES, MAX(NLPANO) AS U_ANO FROM producao");
        $ultimo->execute(); $resultado = $ultimo->fetch(PDO::FETCH_ASSOC);

        $codMes = intval(date('m')); $codAno = intval(date('y'));
        $codLetra = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', );
        if(!empty($resultado['U_SEQ'])){
          if($resultado['U_ANO'] < $codAno){ $anoAtual = $resultado['U_ANO'] + 1; 
          } else {$anoAtual = $resultado['U_ANO'];}
    
          if($resultado['U_MES'] < $codMes){ $mesAtual = $resultado['U_MES'] + 1; $seqAtual = 1; 
          } else {$mesAtual = $resultado['U_MES']; $seqAtual = $resultado['U_SEQ'] + 1;}
    
          if($seqAtual < 10){ $seqLote = '00' . $seqAtual;}
          if($seqAtual >=10 && $seqAtual < 100){ $seqLote = '0' . $seqAtual;} 
          if($seqAtual >= 100){ $seqLote = $seqAtual;}
        }
        if(empty($resultado['U_SEQ'])){
          $seqLote = '001'; $seqAtual = 1; $mesAtual = intval(date('m')); $anoAtual = intval(date('y'));
        }
        $nLoteInterno = $seqLote . ' ' . $codLetra[$mesAtual] . ' ' . $anoAtual; ?>
        <div class="col-md-12">
          <h6 style="color:aqua">Informações da Fabricação</h6>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="date" class="form-control" id="dataFabri" name="dataFabri" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color: whitesmoke" 
              value="" required autofocus>
            <label for="dataFabri" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label>
            <p style="font-size: 11px; color: grey">Inserir data</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating">
            <select class="form-select" id="planta" name="planta" aria-label="Floating label select example" style="font-weight: bolder; background: rgba(0,0,0,0.3)">
              <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione</option>
              <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)">ALFA 1.0</option>
              <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)">BETA 2.0</option>
              <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)">OMEGA 4.0</option>
            </select><label style="color: aqua; font-size: 12px; background: none" for="planta">Planta</label>
            <p style="font-size: 11px; color: grey">Selecione a planta</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="inicio" name="inicio" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="<?php echo date('H:i') ?>" required>
            <label for="inicio" style="color: aqua; font-size: 12px; background: none">Início do Processamento</label>
            <p style="font-size: 11px; color: grey">Inserir hora</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="fim" name="fim" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="<?php echo date('H:i') ?>" required>
            <label for="fim" style="color: aqua; font-size: 12px; background: none">Encerramento</label>
            <p style="font-size: 11px; color: grey">Inserir hora</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="number" class="form-control" id="qtdeProd" name="qtdeProd" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align:center" 
              value="" required>
            <label for="fim" style="color: aqua; font-size: 12px; background: none">Quantidade Produzida</label>
            <p style="font-size: 11px; color: grey">Inserir a quantidade gerada</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="nLotePF" name="nLotePF" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3); color:whitesmoke" 
              value="<?php echo $nLoteInterno ?>" readonly>
            <label for="nLotePF" style="color: aqua; font-size: 12px; background: none">Identificação do Lote</label>
            <p style="font-size: 11px; color: grey">Gerado automaticamente</p>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-2">
          <div class="form-floating mb-3"><br>
            <input class="btn btn-primary" type="submit" id="confirma" name="confirma" value="Confirmar" style="font-size:18px; width: 160px">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-3"><br>
            <input class="btn btn-danger" type="reset" id="descarta" name="descarta" value="Descartar" style="font-size:18px; width: 160px" onclick="location.href='./03SeletorProducao.php'">
          </div>
        </div>  
      </div><!-- fim da row g2 -->
    </form> <?php
    $confirma = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($confirma['confirma'])){
      $_SESSION['dataPedido']  = date('Y-m-d', strtotime($confirma['dataPedido'])) ;
      $_SESSION['numPedido']   = $confirma['numPedido']  ;
      $_SESSION['nomeProduto'] = $confirma['nomeProduto'];
      $_SESSION['qtdePedido']  = $confirma['qtdePedido'] ;
      $_SESSION['unidade']     = $rowPedido['UNIDADE']   ;
      $_SESSION['cliente']     = $confirma['cliente']    ;
      $_SESSION['dataFabri']   = $confirma['dataFabri']  ;
      $_SESSION['planta']      = $confirma['planta']     ;
      $_SESSION['horaInicio']  = $confirma['inicio']     ;
      $_SESSION['horaFinali']  = $confirma['fim']        ;
      $_SESSION['qtdeReal']    = $confirma['qtdeProd']   ;
      $_SESSION['nLoteProd']   = $confirma['nLotePF']    ;

      header('Location: ./38ProcessaPedido.php');
    } ?>
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->