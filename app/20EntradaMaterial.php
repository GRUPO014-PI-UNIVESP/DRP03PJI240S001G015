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
       time = setTimeout(deslogar, 3000000);
     }
  };
  inactivityTime();
</script>
<div class="main">
  <div class="container-fluid"><br>
    <h5>Recebimento de Material</h5><br>
    <h6>Dados do Material</h6> <?php
    if(!empty($_GET['id'])){ 
      $mpEntra = $connDB->prepare("SELECT * FROM mp_estoque WHERE ID_ESTOQUE_MP = :id");
      $mpEntra->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
      $mpEntra->execute();
      $rowMP = $mpEntra->fetch(PDO::FETCH_ASSOC); ?>
      <div class="row g-2">
        <div class="col-md-2">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="dataEntrada" name="dataEntrada" style="font-weight: bolder; text-align: center; background: rgba(0,0,0,0.3);" value="<?php echo date('d/m/Y') ?>" autofocus>
            <label for="dataEntrada" style="color: aqua; font-size: 12px; background: none">Data de Recebimento</label>
            <p style="font-size: 11px; color: grey">Atualize a data caso necessário</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="qtdeLote" name="qtdeLote" style="font-weight: bolder; text-align:right; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $rowMP['QTDE_LOTE'] . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>" disabled>
            <label for="qtdeLote" style="color: aqua; font-size: 12px; background: none">Quantidade Recebida</label>
            <p style="font-size: 11px; color: grey">Somente consulta</p>
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="descrMat" name="descrMat" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow" value="<?php echo $rowMP['DESCRICAO_MP'] ?>" disabled>
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
            <input type="nLoteForn" class="form-control" id="nLoteForn" name="nLoteForn" style="font-weight: bolder; background: rgba(0,0,0,0.3)">
            <label for="nLoteForn" style="color: aqua; font-size: 12px; background: none">Identificação de Lote Externo</label>
            <p style="font-size: 11px; color: grey">Inserir o número ou código de identificação do material recebido</p>
          </div>
        </div>
        <div class="col-md-3"><br><br><br><h6 style="text-align: center">Informações de Estoque do Material</h6></div>
        <div class="col-md-7"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2">
          <div class="form-floating mb-3"><?php
            if($rowMP['QTDE_ESTOQUE'] == null){ $estoque = 0;}
            if($rowMP['QTDE_ESTOQUE'] > 0){ $estoque = $rowMP['QTDE_ESTOQUE']; }
            $atualizado = $estoque + $rowMP['QTDE_LOTE']; ?>
            <input type="text" class="form-control" id="estoque" name="estoque" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $estoque . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>" disabled>
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

            $dMes = intval(date('m')); $dAno = intval(date('y'));
            $codLetra = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', );
            $seq    = $resultado['U_SEQ'] + 1;
            if(!empty($resultado['U_SEQ'] && !empty($resultado['U_MES']) && !empty($resultado['U_ANO']))){
              if($seq > 0   && $seq < 10  ){ $codseq = '00'.$seq;}
              if($seq > 10  && $seq < 100 ){ $codseq = '0'.$seq;}
              if($seq > 100 && $seq < 1000){ $codseq = $seq;}
              if($resultado['U_MES'] < $dMes){ $codseq = '001';}
              if($resultado['U_ANO'] < $dAno){ $codAno = intval(date('y', strtotime("+ 1 year" )));}
              $interno = $codseq . ' ' . $codLetra[$dMes] . ' ' . $codAno;
            }
            if($resultado['U_SEQ'] == '' && $resultado['U_MES'] == '' && $resultado['U_ANO'] == ''){
              $interno = '001' . ' ' . $codLetra[$dMes] . ' ' . $dAno;
            } ?>
            <input type="nLoteForn" class="form-control" id="nLoteForn" name="nLoteForn" style="font-weight: bolder; background: rgba(0,0,0,0.3); text-align: center; color: yellow" value="<?php echo $interno ?>" disabled>
            <label for="nLoteForn" style="color: aqua; font-size: 12px; background: none">Identificação de Lote Interno</label>
            <p style="font-size: 11px; color: grey">Gerado pelo sistema</p>
          </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2">
          <div class="form-floating mb-3"><?php
            $estoque = $rowMP['QTDE_ESTOQUE'] + $rowMP['QTDE_LOTE']; ?>
            <input type="text" class="form-control" id="atualizado" name="atualizado" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $atualizado . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>" disabled>
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
            <input type="text" class="form-control" id="reservado" name="reservado" style="font-weight: bolder; background: rgba(0,0,0,0.3); color: yellow; text-align: center" value="<?php echo $rowReserva['RESERVA'] . ' ' . $rowMP['UNIDADE_MEDIDA'] ?>" disabled>
            <label for="reservado" style="color: aqua; font-size: 12px; background: none">Quantidade Reservada</label>
            <p style="font-size: 11px; color: grey">Somente consulta</p>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-floating mb-3">
            <input class="btn btn-primary" type="submit" id="recebe" name="recebe" value="Confirmar Recebimento" style="height: 55px; font-size:18px">
          </div>
        </div>
      </div><?php  var_dump($_GET['id'], $rowReserva['RESERVA']);
    }?>
  </div><!-- fim da container fluid -->
</div><!-- fim da main -->