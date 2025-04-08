<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Configuração dos Processos'; include_once './RastreadorAtividades.php';

//atribui usuário como responsável por registro de entrada do material ou cadastramento
$responsavel = $_SESSION['nome_func'];
?>
<script>
  // verifica inatividade da página e fecha sessão
  let inactivityTime = function () {
    let time; window.onload = resetTimer; document.onmousemove = resetTimer; document.onkeypress  = resetTimer;
    function deslogar() { <?php $_SESSION['posicao'] = 'Encerrado por inatividade'; include_once './RastreadorAtividades.php'; ?>
      window.location.href = 'LogOut.php';
     }
    function resetTimer() { clearTimeout(time); time = setTimeout(deslogar, 69900000);  }
  }; inactivityTime();
</script>
<style> .tabela{ width: 98%; overflow-y: scroll;} </style>
<!-- Área Principal -->
<div class="main">
    <div class="row g-1">
        <div class="col md-6">
            <br>
            <p style="font-size: 25px; color:cyan">Revisão da Tabela de Tempo de Processos</p>
        </div>
    </div><Br></Br>
    <?php
        $listaTabela = $connDB->prepare("SELECT * FROM historico_tempo WHERE NUMERO_PEDIDO = 0 ORDER BY ID_PRODUTO ASC");
        $listaTabela->execute();
    ?>
    <form action="" method="GET">
        <div class="tabela">
            <table class="table table-dark table-hover">
                <thead style="font-size: 12px">
                    <tr>
                    <th scope="col" style="width: 5%; text-align: center">Ajustar</th>
                    <th scope="col" style="width: 20%;                   ">Produto</th>
                    <th scope="col" style="width: 10%; text-align: center">Compra (min)</th>
                    <th scope="col" style="width: 10%; text-align: center">Recebimento (min)</th>
                    <th scope="col" style="width: 10%; text-align: center">Análise Mat (min)</th>
                    <th scope="col" style="width: 10%; text-align: center">Fabricação</th>
                    <th scope="col" style="width: 10%; text-align: center">Análise Prod (min)</th>
                    <th scope="col" style="width: 10%; text-align: center">Entrega (min)</th>

                    </tr>
                </thead>
                <tbody style="height: 75%; font-size: 11px;"><?php 
                    while($rowTabela = $listaTabela->fetch(PDO::FETCH_ASSOC)){ $id = $rowTabela['ID_PRODUTO'];
                        $buscaProd = $connDB->prepare("SELECT PRODUTO, CAPAC_PROCESS, UNIDADE FROM produtos WHERE N_PRODUTO = :idProd");
                        $buscaProd->bindParam(':idProd', $rowTabela['ID_PRODUTO'], pdo::PARAM_INT);
                        $buscaProd->execute();
                        $rowProd = $buscaProd->fetch(PDO::FETCH_ASSOC);
                        $compra = $rowTabela['COMPRA'] / 60;
                        $recebe = $rowTabela['RECEBIMENTO'] / 60;
                        $anaMat = $rowTabela['ANALISE_MATERIAL'] / 60;
                        $anaPro = $rowTabela['ANALISE_PRODUTO'] / 60;
                        $entreg = $rowTabela['ENTREGA'] / 60;
                        ?>
                        <tr>
                            <td scope="col" style="width: 5%; text-align:center;">
                                <input type="submit" class="btn btn-primary" id="ajustar" name="ajustar" value="<?php echo $id ?>">
                            </td>
                            <td scope="col" style="width: 20%;                   "><?php echo $rowProd['PRODUTO']?></td>
                            <td scope="col" style="width: 10%; text-align:center;"><?php echo $rowTabela['COMPRA'] . ' [' . $compra . 'hrs]' ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"><?php echo $rowTabela['RECEBIMENTO'] . ' [' . $recebe . 'hrs]' ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"><?php echo $rowTabela['ANALISE_MATERIAL'] . ' [' . $anaMat . 'hrs]' ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"><?php echo $rowProd['CAPAC_PROCESS'] . ' ' . $rowProd['UNIDADE'] . ' /hora'?></td>
                            <td scope="col" style="width: 10%; text-align:center;"><?php echo $rowTabela['ANALISE_PRODUTO'] . ' [' . $anaPro . 'hrs]' ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"><?php echo $rowTabela['ENTREGA'] . ' [' . $entreg . 'hrs]' ?></td>

                        </tr><?php
                    } ?>                    
                </tbody>
                <p style="margin-left:30%; color:bisque">Os valores representam o tempo máximo de cada atividade em minutos</p>
            </table>
            <p style="color:darkorange">Clique no número identificador do produto para atualizar os valores de referência </p>
        </div>
    </form><br>
    <div class="row g-2">
        <?php
            $detalhes = filter_input_array(INPUT_GET, FILTER_DEFAULT);
            if(!empty($detalhes['ajustar'])){
                $buscaProduto = $connDB->prepare("SELECT * FROM historico_tempo WHERE ID_PRODUTO = :idProd");
                $buscaProduto->bindParam(':idProd', $detalhes['ajustar'], PDO::PARAM_INT);
                $buscaProduto->execute();
                $rowProduto = $buscaProduto->fetch(PDO::FETCH_ASSOC);
                $buscaNome = $connDB->prepare("SELECT PRODUTO, CAPAC_PROCESS FROM produtos WHERE N_PRODUTO = :idProd");
                $buscaNome->bindParam(':idProd', $detalhes['ajustar'], PDO::PARAM_INT);
                $buscaNome->execute();
                $nome = $buscaNome->fetch(PDO::FETCH_ASSOC);?>
                <p>Produto: <?php echo $nome['PRODUTO'] ?></p>

        <?php
        } ?>
    </div>     
</div>