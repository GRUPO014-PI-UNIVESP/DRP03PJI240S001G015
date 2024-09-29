<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php';
include_once './EstruturaPrincipal.php';
$_SESSION['posicao'] = 'Entrada de Material';
include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
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
       time = setTimeout(deslogar, 300000);
     }
  };
  inactivityTime();
</script>
<div class="main">
  <div class="container-fluid"><br>
    <form method="POST">
      <h5>Recebimento de Material</h5><br>
      <h6>Dados do Material</h6> <?php
      if(!empty($_GET['id'])){
        $dataEntrada = date('Y-m-d');
        $mpEntra = $connDB->prepare("SELECT * FROM mp_estoque WHERE ID_ESTOQUE_MP = :id");
        $mpEntra->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $mpEntra->execute();
        $rowMP = $mpEntra->fetch(PDO::FETCH_ASSOC); ?>
        <div class="row g-2">
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="dataEntrada" name="dataEntrada" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3);" value="<?php echo date('d/m/Y', strtotime($dataEntrada)) ?>" autofocus>
              <label for="dataEntrada" style="color: aqua; font-size: 12px; background: none">Data de Recebimento</label>
              <p style="font-size: 11px; color: grey">Atualize a data caso necessário</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="qtdeLote" name="qtdeLote" style="font-weight: bolder; text-align:right; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $rowMP['QTDE_LOTE'] . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>" readonly>
              <label for="qtdeLote" style="color: aqua; font-size: 12px; background: none">Quantidade Recebida</label>
              <p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="descrMat" name="descrMat" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowMP['DESCRICAO_MP'] ?>" readonly>
              <label for="descrMat" style="color: aqua; font-size: 12px; background: none">Descrição do Material</label>
              <p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="date" class="form-control" id="dataFabriMP" name="dataFabriMP" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3);">
              <label for="dataFabriMP" style="color: aqua; font-size: 12px; background: none">Data de Fabricação</label>
              <p style="font-size: 11px; color: grey">Insira a data de fabricação do material recebido</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="date" class="form-control" id="dataValidade" name="dataValidade" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3);">
              <label for="dataValidade" style="color: aqua; font-size: 12px; background: none">Prazo de Validade</label>
              <p style="font-size: 11px; color: grey">Insira a prazo de validade do material caso esteja discriminado</p>
            </div>
          </div>  
          <div class="col-md-8">
            <div class="form-floating">
              <select class="form-select" id="fornecedor" name="fornecedor" aria-label="Floating label select example" style="font-weight: bolder; background: rgba(0,0,0,0.3)">
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o fornecedor</option><?php
                  $query_fornecedor = $connDB->prepare("SELECT DISTINCT FORNECEDOR FROM mp_tabela");
                  $query_fornecedor->execute();

                  // inclui nome dos produtos como opções de seleção da tag <select>
                  while($supplier = $query_fornecedor->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $supplier['FORNECEDOR']; ?></option> <?php
                  } ?>
              </select><label style="color: aqua; font-size: 12px; background: none" for="fornecedor">Fornecedor</label>
              <p style="font-size: 11px; color: grey">Caso o fornecedor não conste da lista, será necessário fazer o cadastro antes</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="notaFiscal" name="notaFiscal" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-transform: uppercase">
              <label for="notaFiscal" style="color: aqua; font-size: 12px; background: none">Nota Fiscal</label>
              <p style="font-size: 11px; color: grey"></p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="nLoteForn" name="nLoteForn" style="font-weight: bolder; background: rgba(0,0,0,0.3)">
              <label for="nLoteForn" style="color: aqua; font-size: 12px; background: none">Identificação de Lote Externo</label>
              <p style="font-size: 11px; color: grey">Inserir o número ou código de identificação do material recebido</p>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-floating">
              <select class="form-select" id="encarregado" name="encarregado" aria-label="Floating label select example" style="font-weight: bolder; background: rgba(0,0,0,0.3)">
                <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)" selected>Selecione o nome do colaborador</option><?php
                  $query_encarregado = $connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = 'LOGÍSTICA' OR CREDENCIAL >= 4");
                  $query_encarregado->execute();

                  // inclui nome dos produtos como opções de seleção da tag <select>
                  while($rowFunc = $query_encarregado->fetch(PDO::FETCH_ASSOC)){?>
                    <option style="font-size: 14px; color: black; background: rgba(0,0,0,0.3)"><?php echo $rowFunc['NOME_FUNCIONARIO']; ?></option> <?php
                  } ?>
              </select><label style="color: aqua; font-size: 12px; background: none" for="encarregado">Encarregado pelo Recebimento</label>
              <p style="font-size: 11px; color: grey">Selecione quem efetuou o recebimento do material</p>
            </div>
          </div>
          <div class="col-md-2"></div>
          <div class="col-md-3"><h6 style="text-align: center">Informações de Estoque do Material</h6></div>
          <div class="col-md-7"></div>
          <div class="col-md-2"></div>
          <div class="col-md-2">
            <div class="form-floating mb-3"><?php
              if($rowMP['QTDE_ESTOQUE'] == null){ $estoque = 0;}
              if($rowMP['QTDE_ESTOQUE'] > 0){ $estoque = $rowMP['QTDE_ESTOQUE']; }
              $atualizado = $estoque + $rowMP['QTDE_LOTE']; ?>
              <input type="text" class="form-control" id="estoque" name="estoque" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $estoque . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>" readonly>
              <label for="estoque" style="color: aqua; font-size: 12px; background: none">Qtde em Estoque</label>
              <p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3"><?php
              // algoritmo para geração de numero de lote interno
              // verifica último lote registrado
              $ultimo = $connDB->prepare("SELECT MAX(N_LOTE_SEQ) AS U_SEQ, MAX(N_LOTE_MES) AS U_MES, MAX(N_LOTE_ANO) AS U_ANO FROM mp_estoque");
              $ultimo->execute(); $resultado = $ultimo->fetch(PDO::FETCH_ASSOC);

              $dMes = intval(date('m')); $codAno = intval(date('y'));
              $codLetra = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', );
              $seq    = $resultado['U_SEQ'] + 1;
              if(!empty($resultado['U_SEQ'] && !empty($resultado['U_MES']) && !empty($resultado['U_ANO']))){
                if($seq > 0   && $seq  < 10  )   { $codSeq    = '00'.$seq;}
                if($seq > 10  && $seq  < 100 )   { $codSeq    = '0' .$seq;}
                if($seq > 100 && $seq  < 1000)   { $codSeq    =      $seq;}
                if($resultado['U_MES'] < $dMes)  { $codSeq   = '001';}
                if($resultado['U_ANO'] < $codAno){ $codAno = intval(date('y', strtotime("+ 1 year" )));}
                $interno = $codSeq . ' ' . $codLetra[$dMes] . ' ' . $codAno;
              }
              if($resultado['U_SEQ'] == '' && $resultado['U_MES'] == '' && $resultado['U_ANO'] == ''){
                $codAno = intval(date('y'));
                $interno = '001' . ' ' . $codLetra[$dMes] . ' ' . $codAno;
              } ?>
              <input type="text" class="form-control" id="nLoteInterno" name="nLoteInterno" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center; color: yellow" value="<?php echo $interno ?>" readonly>
              <label for="nLoteInterno" style="color: aqua; font-size: 12px; background: none">Identificação de Lote Interno</label>
              <p style="font-size: 11px; color: grey">Gerado pelo sistema</p>
            </div>
          </div> 
          <div class="col-md-6"></div>
          <div class="col-md-2"></div>
          <div class="col-md-2">
            <div class="form-floating mb-3"><?php
              $estoque = $rowMP['QTDE_ESTOQUE'] + $rowMP['QTDE_LOTE']; ?>
              <input type="text" class="form-control" id="atualizado" name="atualizado" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $atualizado . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>" readonly>
              <label for="atualizado" style="color: aqua; font-size: 12px; background: none">Estoque Atualizado</label>
              <p style="font-size: 11px; color: grey">Somente consulta</p>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-floating mb-3"><?php
              $reserva = $connDB->prepare("SELECT SUM(QTDE_PEDIDO) AS RESERVA FROM agenda_compra WHERE DESCRICAO_MP = :descrMat");
              $reserva->bindParam(':descrMat', $rowMP['DESCRICAO_MP'], PDO::PARAM_STR);
              $reserva->execute();
              $rowReserva = $reserva->fetch(PDO::FETCH_ASSOC);
              ?>
              <input type="text" class="form-control" id="reservado" name="reservado" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $rowReserva['RESERVA'] . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>" readonly>
              <label for="reservado" style="color: aqua; font-size: 12px; background: none">Quantidade Reservada</label>
              <p style="font-size: 11px; color: grey">Somente consulta</p>
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
    if(!empty($confirma['recebe'])){ $situacao = 'MATERIAL RECEBIDO, AGUARDANDO LIBERAÇÃO';
      $descrMat     = $confirma['descrMat']    ; $etapa = 2; $notaFiscal   = $confirma['notaFiscal']  ; 
      $dataFabriMP  = date('Y-m-d', strtotime($confirma['dataFabriMP']));
      $dataValidade = date('Y-m-d', strtotime($confirma['dataValidade']));
      $fornecedor   = $confirma['fornecedor']  ; $nLoteForn    = $confirma['nLoteForn']   ;
      $nLoteInterno = $confirma['nLoteInterno']; $atualiza     = $confirma['atualizado']  ;
      $reservado    = $confirma['reservado']   ; $encarregado  = $confirma['encarregado'] ;
      $salvaMat     = $connDB->prepare("UPDATE mp_estoque  SET ETAPA_PROD       = :etapa      , SITUACAO_QUALI      = :situacao    , DATA_ENTRADA            = :dataEntrada    ,
                                                                  DATA_FABRICACAO      = :dataFabri  , DATA_VALIDADE       = :dataVali    , QTDE_ESTOQUE            = :atualiza       ,
                                                                  QTDE_RESERVADA       = :reservado  , N_LOTE_SEQ          = :nSeq        , N_LOTE_MES              = :nMes           ,
                                                                  N_LOTE_ANO           = :nAno       , NUMERO_LOTE_INTERNO = :nLoteInterno, NUMERO_LOTE_FORNECEDOR  = :nLoteFornecedor,
                                                                  NOTA_FISCAL_LOTE     = :notaFiscal , FORNECEDOR          = :fornecedor  , ENCARREGADO_RECEBIMENTO = :encarregado    ,
                                                                  RESPONSAVEL_REGISTRO = :responsavel  WHERE DESCRICAO_MP = :descrMat");
      $salvaMat->bindParam(':descrMat'    , $descrMat    , PDO::PARAM_STR); $salvaMat->bindParam(':etapa'          , $etapa                , PDO::PARAM_INT);
      $salvaMat->bindParam(':dataEntrada' , $dataEntrada , PDO::PARAM_STR); $salvaMat->bindParam(':dataFabri'      , $dataFabriMP          , PDO::PARAM_STR);
      $salvaMat->bindParam(':dataVali'    , $dataValidate, PDO::PARAM_STR); $salvaMat->bindParam(':atualiza'       , $atualiza             , PDO::PARAM_INT);
      $salvaMat->bindParam(':reservado'   , $reservado   , PDO::PARAM_INT); $salvaMat->bindParam(':nSeq'           , $codSeq               , PDO::PARAM_STR);
      $salvaMat->bindParam(':nMes'        , $codMes      , PDO::PARAM_STR); $salvaMat->bindParam(':nAno'           , $codAno               , PDO::PARAM_STR);
      $salvaMat->bindParam(':nLoteInterno', $nLoteInterno, PDO::PARAM_STR); $salvaMat->bindParam(':nLoteFornecedor', $nLoteForn            , PDO::PARAM_STR);
      $salvaMat->bindParam(':notaFiscal'  , $notaFiscal  , PDO::PARAM_STR); $salvaMat->bindParam(':fornecedor'     , $fornecedor           , PDO::PARAM_STR);
      $salvaMat->bindParam(':encarregado' , $encarregado , PDO::PARAM_STR); $salvaMat->bindParam(':responsavel'    , $_SESSION['nome_func'], PDO::PARAM_STR);
      $salvaMat->execute();

      $sitProduto = 'AGUARDANDO LIBERAÇÃO DOS MATERIAIS';
      $atualizaPedido = $connDB->prepare("UPDATE pf_pedido SET SITUACAO_QUALI = :situacao WHERE NUMERO_PEDIDO = :numPedido");
      $atualizaPedido->bindParam(':situacao', $sitProduto, PDO::PARAM_STR);
      $atualizaPedido->bindParam(':numPedido', $rowReserva['PEDIDO_NUM'], PDO::PARAM_STR);
      $atualizaPedido->execute();

      $limpaAgenda = $connDB->prepare("DELETE FROM agenda_compra WHERE DESCRICAO_MP = :descrMat");
      $limpaAgenda->bindParam(':descrMat', $descrMat, PDO::PARAM_STR);
      $limpaAgenda->execute();

      header('Location: ./02SeletorLogistica.php');
    }
  ?></div><!-- fim da container fluid -->
</div><!-- fim da main -->