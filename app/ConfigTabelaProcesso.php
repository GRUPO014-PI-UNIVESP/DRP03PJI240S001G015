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
    </div>
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
                <p style="color:bisque; float:inline-end">Os valores representam o tempo máximo de cada atividade em minutos</p>
                <tbody style="height: 75%; font-size: 11px;"><?php 
                    while($rowTabela = $listaTabela->fetch(PDO::FETCH_ASSOC)){ $id = $rowTabela['ID_PRODUTO'];
                        $buscaProd = $connDB->prepare("SELECT PRODUTO, CAPAC_PROCESS, UNIDADE FROM produtos WHERE N_PRODUTO = :idProd");
                        $buscaProd->bindParam(':idProd', $rowTabela['ID_PRODUTO'], pdo::PARAM_INT);
                        $buscaProd->execute();
                        $rowProd = $buscaProd->fetch(PDO::FETCH_ASSOC);
                        $compra = number_format($rowTabela['COMPRA'] / 60, 2, ',', '.');
                        $recebe = number_format($rowTabela['RECEBIMENTO'] / 60, 2, ',', '.');
                        $anaMat = number_format($rowTabela['ANALISE_MATERIAL'] / 60, 2, ',', '.');
                        $anaPro = number_format($rowTabela['ANALISE_PRODUTO'] / 60, 2, ',', '.');
                        $entreg = number_format($rowTabela['ENTREGA'] / 60, 2, ',', '.');
                        ?>
                        <tr>
                            <td scope="col" style="width: 5%; text-align:center;">
                                <input type="submit" class="btn btn-primary" id="ajustar" name="ajustar" value="<?php echo $id ?>">
                            </td>
                            <td scope="col" style="width: 20%;                   "><?php echo $rowProd['PRODUTO']?></td>
                            <td scope="col" style="width: 10%; text-align:center;">
                                <?php echo number_format($rowTabela['COMPRA'], 0, ',', '.') . '<br>' . ' [ ' . $compra . 'hrs ]' ?></td>
                            <td scope="col" style="width: 10%; text-align:center;">
                                <?php echo number_format($rowTabela['RECEBIMENTO'], 0, ',', '.') . '<br>'  . ' [ ' . $recebe . 'hrs ]' ?></td>
                            <td scope="col" style="width: 10%; text-align:center;">
                                <?php echo number_format($rowTabela['ANALISE_MATERIAL'], 0, ',', '.') . '<br>'  . ' [ ' . $anaMat . 'hrs ]' ?></td>
                            <td scope="col" style="width: 10%; text-align:center;">
                                <?php echo $rowProd['CAPAC_PROCESS'] . '<br>' . $rowProd['UNIDADE'] . ' /hora'?></td>
                            <td scope="col" style="width: 10%; text-align:center;">
                                <?php echo number_format($rowTabela['ANALISE_PRODUTO'], 0, ',', '.') . '<br>'  . ' [ ' . $anaPro . 'hrs ]' ?></td>
                            <td scope="col" style="width: 10%; text-align:center;">
                                <?php echo number_format($rowTabela['ENTREGA'], 0, ',', '.') . '<br>'  . ' [ ' . $entreg . 'hrs ]' ?></td>
                        </tr><?php
                    } ?>                   
                </tbody>
            </table>
            <p style="color:darkorange; font-size: 11px;">Clique no número identificador do produto para selecionar o item a alterar </p> 
        </div>
    </form><br>
    <div class="row g-2">
        <?php
            $detalhes = filter_input_array(INPUT_GET, FILTER_DEFAULT);
            if(!empty($detalhes['ajustar'])){
                $buscaProduto = $connDB->prepare("SELECT * FROM historico_tempo WHERE ID_PRODUTO = :idProd AND NUMERO_PEDIDO = 0");
                $buscaProduto->bindParam(':idProd', $detalhes['ajustar'], PDO::PARAM_INT);
                $buscaProduto->execute();
                $rowProduto = $buscaProduto->fetch(PDO::FETCH_ASSOC);
                $buscaNome = $connDB->prepare("SELECT PRODUTO, CAPAC_PROCESS FROM produtos WHERE N_PRODUTO = :idProd");
                $buscaNome->bindParam(':idProd', $detalhes['ajustar'], PDO::PARAM_INT);
                $buscaNome->execute();
                $nome = $buscaNome->fetch(PDO::FETCH_ASSOC);?>
                <p style="color:yellow; font-size: 15px;">Produto: <?php echo $nome['PRODUTO'] ?></p>
                <form action="" method="POST">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label for="compra" class="form-label" style="font-size: 10px; color:aqua; float:inline-end">
                                Tempo estimado para compra de materiais</label>
                            <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="number" 
                                class="form-control" id="compra" name="compra" value="<?php echo $rowProduto['COMPRA'] ?>" autofocus>
                        </div>
                        <div class="col-md-2">
                            <label for="recebe" class="form-label" style="font-size: 10px; color:aqua; float:inline-end">
                                para recebimento de materiais</label>
                            <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="number" 
                                class="form-control" id="recebe" name="recebe" value="<?php echo $rowProduto['RECEBIMENTO'] ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="anaMat" class="form-label" style="font-size: 10px; color:aqua; float:inline-end">
                                para análise de materiais</label>
                            <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="number" 
                                class="form-control" id="anaMat" name="anaMat" value="<?php echo $rowProduto['ANALISE_MATERIAL'] ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="anaPro" class="form-label" style="font-size: 10px; color:aqua; float:inline-end">
                                para análise de produtos</label>
                            <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="number" 
                                class="form-control" id="anaPro" name="anaPro" value="<?php echo $rowProduto['ANALISE_PRODUTO'] ?>" autofocus>
                        </div>
                        <div class="col-md-2">
                            <label for="entrega" class="form-label" style="font-size: 10px; color:aqua; float:inline-end">
                                para entrega de produtos</label>
                            <input style="font-size: 14px; text-align:right; background: rgba(0,0,0,0.3)" type="number" 
                                class="form-control" id="entrega" name="entrega" value="<?php echo $rowProduto['ENTREGA'] ?>" autofocus>
                        </div>
                        <div class="col-md-3">
                            <input type="submit" class="btn btn-primary" id="confirma" name="confirma" value="Confirmar">
                        </div>
                    </div>
                </form><?php
                $atualiza = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($atualiza['confirma'])){
                    $altera = $connDB->prepare("UPDATE historico_tempo SET COMPRA = :compra, RECEBIMENTO = :recebe, ANALISE_MATERIAL = :anaMat,
                                                       ANALISE_PRODUTO = :anaPro, ENTREGA = :entrega WHERE ID_PRODUTO = :idProd AND NUMERO_PEDIDO = 0");
                    $altera->bindParam(':idProd' , $detalhes['ajustar'], PDO::PARAM_INT);
                    $altera->bindParam(':compra' , $atualiza['compra'] , PDO::PARAM_INT);
                    $altera->bindParam(':recebe' , $atualiza['recebe'] , PDO::PARAM_INT);
                    $altera->bindParam(':anaMat' , $atualiza['anaMat'] , PDO::PARAM_INT);
                    $altera->bindParam(':anaPro' , $atualiza['anaPro'] , PDO::PARAM_INT);
                    $altera->bindParam(':entrega', $atualiza['entrega'], PDO::PARAM_INT);
                    $altera->execute();

                    header('Location: ./ConfigTabelaProcesso.php');
                }
            } 
        ?>
    </div>     
</div>