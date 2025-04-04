<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Pedido de Produto'; include_once './RastreadorAtividades.php';
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function(){
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar(){ <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?> window.location.href = 'LogOut.php'; }
    function resetTimer(){ clearTimeout(time); time = setTimeout(deslogar, 69900000); }
  }; inactivityTime();
</script>
<style> 
.tabela1{ width: 300px ; height: 300px; overflow-y: scroll;}
.tabela2{ width: 800px; height: 300px; overflow-y: scroll;}
</style>
<!-- Área Principal -->
<div class="main">
  <div class="container-fluid"><br>
    <div><h5>Pedido de Produto - Finalização</h5></div>   
    <div class="row g-5">
      <div class="col-md-1">
        <label for="numPedido" style="font-size: 10px; color:aqua;">Pedido No.</label>
        <p style="color:yellow; font-size: 13px; text-align: center; border-bottom: 2px solid whitesmoke">
          <?php echo $_SESSION['numPedido'] ?>
        </p>
      </div>
      <div class="col-md-7">
        <label for="nomeProduto" style="font-size: 10px; color:aqua">Produto</label>
        <p style="color:yellow; font-size: 13px; border-bottom: 2px solid whitesmoke"><?php echo $_SESSION['nomeProduto'] ?></p>    
      </div>
      <div class="col-md-2">
        <label for="qtdeLote" style="font-size: 10px; color:aqua">Quantidade do Pedido</label>
        <p style="color:yellow; font-size: 16px; border-bottom: 2px solid whitesmoke; text-align: center;">
          <?php echo number_format($_SESSION['qtdeLote'], 0, ',', '.') . ' ' . $_SESSION['unidade'] ?>
        </p> 
      </div>
      <div class="col-md-1"></div>
    </div><!-- Fim da div row -->
    <br>
    <div class="row g-0">
      <div class="col-md-12"><!-- Construção da tabela dos materiais ingredientes e disponibilidades -->
        <p style="color:aqua">Materiais Ingredientes</p>
          <table class="table table-dark">
            <thead style="font-size: 12px">
              <tr>
                <th scope="col" style="width: 30%;">Ingrediente/Proporção</th>
                <th scope="col" style="width: 10%; text-align: right">Qtde Exigida</th>
                <th scope="col" style="width: 10%; text-align: right">Qtde Disponível</th>
                <th scope="col" style="width: 20%; text-align: center">Qtde Agendada</th>
                <th scope="col" style="width: 20%; text-align: center">Situação</th>
              </tr>
            </thead>
            <tbody style="height: 25%;">
              <?php
              $padrao = 3; 
              $xtend = 6; 
              $verificador = 0;
              $query_matDisponivel = $connDB->prepare('SELECT * FROM produtos WHERE PRODUTO = :nomeProd');
              $query_matDisponivel->bindParam(':nomeProd', $_SESSION['nomeProduto'], PDO::PARAM_STR);
              $query_matDisponivel->execute(); 
                while($rowMat = $query_matDisponivel->fetch(PDO::FETCH_ASSOC)){                 
                  $query_matLista = $connDB->prepare('SELECT * FROM materiais_estoque WHERE DESCRICAO = :nomeMat');
                  $query_matLista->bindParam(':nomeMat', $rowMat['MATERIAL_COMPONENTE'], PDO::PARAM_STR);
                  $query_matLista->execute();
                  $proporcao = $_SESSION['qtdeLote'] * ($rowMat['PROPORCAO_MATERIAL'] / 100);
                  while($dataMat = $query_matLista->fetch(PDO::FETCH_ASSOC)){ ?>
                    <tr>
                      <td scope="col" style="width: 30%; font-size: 13px;"> 
                        <?php echo $dataMat['DESCRICAO'] . ' [ ' . $rowMat['PROPORCAO_MATERIAL'] . '% ]' ?> 
                      </td>
                      <th scope="col" style="width: 10%; text-align: right; font-size: 13px;"> 
                        <?php echo number_format($proporcao, 0, ',', '.') . ' ' . $rowMat['UNIDADE'] ?> 
                      </th>        
                      <th scope="col" style="width: 10%; text-align: right; font-size: 13px;"> 
                        <?php echo number_format($dataMat['QTDE_ESTOQUE'], 0, ',', '.') . ' ' . $dataMat['UNIDADE'] ?> 
                      </th>        
                      <th scope="col" style="width: 10%; text-align: center; font-size: 13px;">
                        <?php $compra = ($proporcao + ($proporcao * 0.2)) - $dataMat['QTDE_ESTOQUE'];
                          echo number_format($compra, 0, ',', '.') . ' ' . $dataMat['UNIDADE'] ?>
                      </th>        
                      <?php 
                        $condicao = $dataMat['QTDE_ESTOQUE'] - $proporcao;
                        if($condicao > 0){ ?>
                          <td scope="col" style="width: 10%; text-align: center; font-size: 13px;">
                            <p><?php echo 'SUFICIENTE' ?></p>
                          </td><?php
                        }
                        if($condicao < 0){ ?>
                          <td scope="col" style="width: 10%; text-align: center; font-size: 13px;">
                              <p><?php echo 'COMPRA AGENDADA!' ?></p>
                          </td><?php
                        } ?>
                    </tr><?php $etapa = 0; $situacao = 'COMPRA DO MATERIAL AGENDADO';/*
                    $agenda = $connDB->prepare("INSERT INTO materiais_compra (ID_ESTOQUE, DESCRICAO, NUMERO_PEDIDO, PRODUTO, ETAPA_PROCESS,
                                                                                     DATA_PEDIDO, HORA_PEDIDO, DATA_AGENDA, DATA_PRAZO, QTDE_PEDIDO, 
                                                                                     UNIDADE, SITUACAO, CAPAC_PROCESS)
                                                        VALUES (:idEstoque, :descrMat, :numPedido, :nomeProd, :etapaPro, :dataPedido, :horaPedido, 
                                                        :dataAgenda, :dataPrazo, :qtdePedido, :uniMed, :situacao, :capaProcess)");
                    $agenda->bindParam(':idEstoque'  , $dataMat['ID_ESTOQUE']  , PDO::PARAM_INT);
                    $agenda->bindParam(':descrMat'   , $dataMat['DESCRICAO']   , PDO::PARAM_STR);
                    $agenda->bindParam(':numPedido'  , $_SESSION['numPedido']  , PDO::PARAM_INT);
                    $agenda->bindParam(':nomeProd'   , $_SESSION['nomeProduto'], PDO::PARAM_STR);
                    $agenda->bindParam(':etapaPro'   , $etapa                  , PDO::PARAM_INT);
                    $agenda->bindParam(':dataPedido' , $_SESSION['dataPedido'] , PDO::PARAM_STR);
                    $agenda->bindParam(':horaPedido' , $_SESSION['horaPedido'] , PDO::PARAM_STR);
                    $agenda->bindParam(':dataAgenda' , $dataMat['dataPedido']  , PDO::PARAM_STR);
                    $agenda->bindParam(':dataPrazo'  , $dataMat['dataEstimada'], PDO::PARAM_STR);
                    $agenda->bindParam(':qtdePedido' , $compra                 , PDO::PARAM_INT);
                    $agenda->bindParam(':uniMed'     , $dataMat['unidade']     , PDO::PARAM_STR);
                    $agenda->bindParam(':situacao'   , $situacao               , PDO::PARAM_STR);
                    $agenda->bindParam(':capaProcess', $rowMat['CAPAC_PROCESS'], PDO::PARAM_STR);
                    $agenda->execute();*/
                  }
                }
              ?> 
            </tbody>               
          </table>
        </div><br>
      </div>
    </div>
    <form action="" method="POST">
      <div class="row g-2" >
        <div class="col-md-2">
          <label for="dataEntrega" class="form-label" style="font-size: 10px; color:aqua;">Produto Disponível em:</label>
          <input style="font-size: 14px; text-align: center; color:whitesmoke; background: rgba(0,0,0,0.3); width:150px" type="date" class="form-control" id="dataEntrega" name="dataEntrega"
            value="" required autofocus>
          <p style="font-size: 10px; color: grey">Selecione uma data estimada para entrega do produto</p>
        </div>
        <div class="col-md-8">
          <label for="cliente" class="form-label" style="font-size: 10px; color:aqua">Nome do Cliente</label>
          <select style="font-size: 16px;color:whitesmoke; background: rgba(0,0,0,0.3)" class="form-select" id="cliente" name="cliente" required>
            <option style="font-size: 16px; background: rgba(0,0,0,0.3), color: black" selected>Selecione o Cliente, caso não esteja relacionado, primeiro será necessário fazer o cadastramento</option><?php
              //Pesquisa de descrição do PRODUTO para seleção
              $query_cliente = $connDB->prepare("SELECT DISTINCT NOME_FANTASIA FROM cliente");
              $query_cliente->execute();
              // inclui nome dos produtos como opções de seleção da tag <select>
              while($rowCliente = $query_cliente->fetch(PDO::FETCH_ASSOC)){?>
                <option style="font-size: 16px; color:black; background: rgba(0,0,0,0.3)"><?php echo $rowCliente['NOME_FANTASIA']; ?></option> <?php
              } ?>
          </select>
        </div>
        <div class="col-md-1"></div><br>
        <div class="col-md-2">
          <label for="dataPrazo" class="form-label" style="font-size: 10px; color:aqua">Data Estimada de Entrega</label>
          <input style="font-size: 16px; text-align: center; color: yellow; background: rgba(0,0,0,0.3)" type="text" class="form-control" id="dataPrazo" name="dataPrazo" value="<?php echo date('d/m/Y', strtotime($_SESSION['dataEntrega'])) ?>" readonly>
        </div>
        <div class="col-md-3"><br>
          <input style="width: 140px; float:inline-end" class="btn btn-secondary" type="reset" id="reset2" name="reset2" value="Descartar" onclick="location.href='./35DescartarPedido.php'">
        </div>
        <div class="col-md-3"><br>
          <input style="width: 180px;" class="btn btn-primary" type="submit" id="salvar3" name="salvar3" value="Confirmar e Salvar">
        </div>
        <div class="col-md-6"><br></div><div class="col-md-12"><br></div><div class="col-md-12"><br></div><div class="col-md-12"><br></div>
      </div>
    </form>

  </div><!-- Fim da div container-fluid -->
</div><!-- Fim da div main -->