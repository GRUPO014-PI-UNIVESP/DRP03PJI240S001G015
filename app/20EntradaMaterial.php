<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Entrada de Material'; include_once './RastreadorAtividades.php';
//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>window.location.href = 'LogOut.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 6000000); }
  }; inactivityTime();
</script>
<div class="main">
  <div class="container-fluid"><br>
    <form method="POST"><h5>Recebimento de Material</h5><br><h6>Dados do Material</h6> <?php
      if(!empty($_GET['id'])){ $dataEntrada = date('Y-m-d');
        $mpEntra = $connDB->prepare("SELECT * FROM materiais_compra WHERE ID_COMPRA = :id"); $mpEntra->bindParam(':id', $_GET['id'], PDO::PARAM_INT); $mpEntra->execute(); $rowMP = $mpEntra->fetch(PDO::FETCH_ASSOC); ?>
        <div class="row g-2">
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="dataEntrada" name="dataEntrada" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3);" value="<?php echo date('d/m/Y', strtotime($dataEntrada)) ?>" autofocus>
              <label for="dataEntrada" style="color: aqua; font-size: 12px; background: none">Data de Recebimento</label><p style="font-size: 11px; color: grey">Atualize a data caso necessário</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="qtdeLote" name="qtdeLote" style="font-weight: bolder; text-align:right; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo number_format($rowMP['QTDE_PEDIDO'], 0, ',', '.') . ' ' . $rowMP['UNIDADE'] ?>" readonly>
              <label for="qtdeLote" style="color: aqua; font-size: 12px; background: none">Quantidade Recebida</label><p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="descrMat" name="descrMat" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowMP['DESCRICAO'] ?>" readonly>
              <label for="descrMat" style="color: aqua; font-size: 12px; background: none">Descrição do Material</label><p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="date" class="form-control" id="dataFabriMP" name="dataFabriMP" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3);">
              <label for="dataFabriMP" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label><p style="font-size: 11px; color: grey">Insira a data de fabricação do material recebido</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="date" class="form-control" id="dataValidade" name="dataValidade" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3);">
              <label for="dataValidade" style="color: aqua; font-size: 12px; background: none">Prazo de Validade</label><p style="font-size: 11px; color: grey">Insira a prazo de validade do material caso esteja discriminado</p>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-floating">
              <select class="form-select" id="fornecedor" name="fornecedor" aria-label="Floating label select example" style="font-weight: bolder; background: rgba(0,0,0,0.3)">
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o fornecedor</option><?php
                  $query_fornecedor = $connDB->prepare("SELECT DISTINCT FORNECEDOR FROM materiais_estoque");$query_fornecedor->execute();
                  // inclui nome dos produtos como opções de seleção da tag <select>
                  while($supplier = $query_fornecedor->fetch(PDO::FETCH_ASSOC)){ ?>
                    <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $supplier['FORNECEDOR']; ?></option> <?php
                  } ?>
              </select><label style="color: aqua; font-size: 12px; background: none" for="fornecedor">Fornecedor</label><p style="font-size: 11px; color: grey">Caso o fornecedor não conste da lista, será necessário fazer o cadastro antes</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="notaFiscal" name="notaFiscal" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center;">
              <label for="notaFiscal" style="color: aqua; font-size: 12px; background: none">Nota Fiscal</label><p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nLoteForn" name="nLoteForn" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center; text-transform: uppercase">
              <label for="nLoteForn" style="color: aqua; font-size: 12px; background: none">Identificação de Lote Externo</label><p style="font-size: 11px; color: grey">Inserir o número ou código de identificação do material recebido</p>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-floating">
              <select class="form-select" id="encarregado" name="encarregado" aria-label="Floating label select example" style="font-weight: bolder; background: rgba(0,0,0,0.3)">
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o nome do colaborador</option><?php
                  $query_encarregado = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = 'LOGÍSTICA' OR CREDENCIAL >= 4"); $query_encarregado->execute();
                  // inclui nome dos produtos como opções de seleção da tag <select>
                  while($rowFunc = $query_encarregado->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $rowFunc['NOME_FUNCIONARIO']; ?></option> <?php
                  } ?>
              </select><label style="color: aqua; font-size: 12px; background: none" for="encarregado">Encarregado pelo Recebimento</label><p style="font-size: 11px; color: grey">Selecione quem efetuou o recebimento do material</p>
            </div>
          </div>
          <div class="col-md-2"></div><div class="col-md-3"><h6 style="text-align: center">Informações de Estoque do Material</h6></div>
          <div class="col-md-7"></div><div class="col-md-2"></div>
          <div class="col-md-2">
            <div class="form-floating mb-3"><?php
              $query_estoque = $connDB->prepare("SELECT * FROM materiais_estoque WHERE ID_ESTOQUE = :idEstoque"); $query_estoque->bindParam(':idEstoque', $rowMP['ID_ESTOQUE'], pdo::PARAM_INT); $query_estoque->execute(); $rowEstoque = $query_estoque->fetch(PDO::FETCH_ASSOC); ?>
              <input type="text" class="form-control" id="estoque" name="estoque" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $rowEstoque['QTDE_ESTOQUE'] ?>" readonly>
              <label for="estoque" style="color: aqua; font-size: 12px; background: none">Qtde em Estoque</label><p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3"><?php
              // algoritmo para geração de numero de lote interno
              // verifica último lote registrado
              $ultimo = $connDB->prepare("SELECT MAX(ID1) AS U_SEQ, MAX(ID2) AS U_MES, MAX(ID3) AS U_ANO FROM materiais_lotes"); $ultimo->execute(); $resultado = $ultimo->fetch(PDO::FETCH_ASSOC);
              $codMes = intval(date('m')); $codAno = intval(date('y')); $codLetra = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', );
              if(!empty($resultado['U_SEQ'])){
                if($resultado['U_ANO'] < $codAno){ $anoAtual = $resultado['U_ANO'] + 1; } else {$anoAtual = $resultado['U_ANO'];} 
                if($resultado['U_MES'] < $codMes){ $mesAtual = $resultado['U_MES'] + 1; $seqAtual = 1; } else {$mesAtual = $resultado['U_MES']; $seqAtual = $resultado['U_SEQ'] + 1;}  
                if($seqAtual < 10){ $seqLote = '00' . $seqAtual;} if($seqAtual >=10 && $seqAtual < 100){ $seqLote = '0' . $seqAtual;} if($seqAtual >= 100){ $seqLote = $seqAtual;}
              }
              if(empty($resultado['U_SEQ'])){ $seqLote = '001'; $seqAtual = 1; $mesAtual = intval(date('m')); $anoAtual = intval(date('y'));}
              $nLoteIn = $seqLote . ' ' . $codLetra[$mesAtual] . ' ' . $anoAtual; ?>
              <input type="text" class="form-control" id="nLoteInterno" name="nLoteInterno" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center; color: yellow" value="<?php echo $nLoteIn ?>" readonly>
              <label for="nLoteInterno" style="color: aqua; font-size: 12px; background: none">Identificação de Lote Interno</label><p style="font-size: 11px; color: grey">Gerado pelo sistema</p>
            </div>
          </div> 
          <div class="col-md-6"></div><div class="col-md-2"></div>
          <div class="col-md-2">
            <div class="form-floating mb-3"><?php $novoEstoque = $rowEstoque['QTDE_ESTOQUE'] + $rowMP['QTDE_PEDIDO']; ?>
              <input type="text" class="form-control" id="atualizado" name="atualizado" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo number_format($novoEstoque, 1, ',', '.') . ' ' . $rowMP['UNIDADE'] ?>" readonly>
              <label for="atualizado" style="color: aqua; font-size: 12px; background: none">Estoque Atualizado</label><p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3"><?php
              $reserva = $connDB->prepare("SELECT SUM(QTDE_RESERVA) AS RESERVA, UNIDADE FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido AND ID_ESTOQUE = :idEstoque");
              $reserva->bindParam(':numPedido', $rowMP['NUMERO_PEDIDO'], PDO::PARAM_STR); $reserva->bindParam(':idEstoque', $rowMP['ID_ESTOQUE']   , PDO::PARAM_STR);
              $reserva->execute(); $rowReserva = $reserva->fetch(PDO::FETCH_ASSOC); ?>
              <input type="text" class="form-control" id="reservado" name="reservado" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo number_format($rowReserva['RESERVA'], 0, ',', '.') . ' ' . $rowReserva['UNIDADE'] ?>" readonly>
              <label for="reservado" style="color: aqua; font-size: 12px; background: none">Quantidade Reservada</label><p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input class="btn btn-primary" type="submit" id="recebe" name="recebe" value="Confirmar Recebimento" style="height: 55px; font-size:18px">
            </div>
          </div>
        </div><?php
      }?>
    </form><?php
    $confirma = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($confirma['recebe'])){ $etapa = 2;
      $notaFiscal = $confirma['notaFiscal']; $situacao  = 'MATERIAL RECEBIDO, AGUARDANDO LIBERAÇÃO' ; $fornecedor = strtoupper($confirma['fornecedor']); $encarregado = strtoupper($confirma['encarregado']);       
      $atualiza   = $confirma['atualizado']; $descrMat  = strtoupper($confirma['descrMat']) ; $dataFabri  = date('Y-m-d', strtotime($confirma['dataFabriMP']));       
      $reservado  = $confirma['reservado'] ; $nLoteForn = strtoupper($confirma['nLoteForn']); $dataVali   = date('Y-m-d', strtotime($confirma['dataValidade']));
                       
      $salvaMat = $connDB->prepare("UPDATE materiais_lotes SET NUMERO_LOTE=:nLoteF, ID_INTERNO=:nLoteIn, ID1=:id1, ID2=:id2, ID3=:id3, ETAPA_PROCESS=:etapa, SITUACAO=:situacao, FORNECEDOR = :fornecedor, NOTA_FISCAL=:notaFiscal, DATA_FABRI=:dataFabri, DATA_VALI=:dataVali, DATA_COMPRA=:dataCompra, DATA_RECEBIMENTO=:dataReceb, ENCARREGADO=:encarregado, RESPONSAVEL=:responsavel 
      WHERE ID_COMPRA = :idCompra AND ETAPA_PROCESS=1");
      $salvaMat->bindParam(':nLoteF'     , $nLoteForn  , PDO::PARAM_STR); $salvaMat->bindParam(':nLoteIn'    , $nLoteIn              , PDO::PARAM_STR);
      $salvaMat->bindParam(':id1'        , $seqAtual   , PDO::PARAM_STR); $salvaMat->bindParam(':id2'        , $mesAtual             , PDO::PARAM_INT);
      $salvaMat->bindParam(':id3'        , $anoAtual   , PDO::PARAM_INT); $salvaMat->bindParam(':etapa'      , $etapa                , PDO::PARAM_INT); 
      $salvaMat->bindParam(':situacao'   , $situacao   , PDO::PARAM_STR); $salvaMat->bindParam(':notaFiscal' , $notaFiscal           , PDO::PARAM_STR);
      $salvaMat->bindParam(':dataFabri'  , $dataFabri  , PDO::PARAM_STR); $salvaMat->bindParam(':dataVali'   , $dataVali             , PDO::PARAM_STR);
      $salvaMat->bindParam(':dataReceb'  , $dataEntrada, PDO::PARAM_STR); $salvaMat->bindParam(':dataCompra' , $rowMP['DATA_PEDIDO'] , PDO::PARAM_STR);
      $salvaMat->bindParam(':encarregado', $encarregado, PDO::PARAM_STR); $salvaMat->bindParam(':responsavel', $_SESSION['nome_func'], PDO::PARAM_STR);
      $salvaMat->bindParam(':idCompra'   , $_GET['id'] , PDO::PARAM_STR); $salvaMat->bindParam(':fornecedor' , $fornecedor           , PDO::PARAM_STR); $salvaMat->execute();

      $sitProduto = 'AGUARDANDO LIBERAÇÃO DOS MATERIAIS';
      $atualizaPedido = $connDB->prepare("UPDATE pedidos SET SITUACAO = :situacao WHERE NUMERO_PEDIDO = :numPedido");
      $atualizaPedido->bindParam(':situacao', $sitProduto, PDO::PARAM_STR); $atualizaPedido->bindParam(':numPedido', $rowReserva['NUMERO_PEDIDO'], PDO::PARAM_STR); $atualizaPedido->execute();

      $atualizaReserva = $connDB->prepare("UPDATE materiais_reserva SET DISPONIBILIDADE = :disp WHERE ID_COMPRA = :idCompra");
      $atualizaReserva->bindParam(':disp', $etapa, PDO::PARAM_STR); $atualizaReserva->bindParam(':idCompra', $rowMP['ID_COMPRA'] , PDO::PARAM_STR); $atualizaReserva->execute();

      $limpaAgenda = $connDB->prepare("UPDATE materiais_compra SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao WHERE ID_COMPRA = :idCompra");
      $limpaAgenda->bindParam(':etapa'   , $etapa     , PDO::PARAM_INT); $limpaAgenda->bindParam(':situacao', $situacao, PDO::PARAM_STR);
      $limpaAgenda->bindParam(':idCompra', $_GET['id'], PDO::PARAM_INT); $limpaAgenda->execute();

      header('Location: ./02SeletorLogistica.php');
    } ?>
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->