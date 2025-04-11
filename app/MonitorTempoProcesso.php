<?php
// inclusão do banco de dados e estrutura base da página web
include_once './ConnectDB.php'; include_once './EstruturaPrincipal.php'; $_SESSION['posicao'] = 'Monitor de Processos'; include_once './RastreadorAtividades.php';

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
        <div class="col md-6"> <br>
            <p style="font-size: 25px; color:cyan">Monitor de tempo de processos</p>
        </div>
    </div> <?php
        $listaTabela = $connDB->prepare("SELECT * FROM historico_tempo WHERE NUMERO_PEDIDO > 0");
        $listaTabela->execute(); ?>
    <form action="" method="GET">
        <div class="tabela">
            <table class="table table-dark table-hover">
                <thead style="font-size: 12px">
                    <tr>
                        <th scope="col" style="width: 15%;                   "><?php echo 'No.Pedido' . '<br>' . 'Produto' ?></th>
                        <th scope="col" style="width: 10%; text-align: center">Pedido</th>
                        <th scope="col" style="width: 10%; text-align: center">Compra</th>
                        <th scope="col" style="width: 10%; text-align: center">Recebimento</th>
                        <th scope="col" style="width: 10%; text-align: center">Análise Mat</th>
                        <th scope="col" style="width: 10%; text-align: center">Fabricação</th>
                        <th scope="col" style="width: 10%; text-align: center">Análise Prod</th>
                        <th scope="col" style="width: 10%; text-align: center">Entrega</th>
                    </tr>
                </thead>
                <p style="color:bisque; float:inline-end">Tempo indica a finalização da atividade</p>
                <tbody style="height: 75%; font-size: 11px;"><?php 
                    while($rowTabela = $listaTabela->fetch(PDO::FETCH_ASSOC)){ $id = $rowTabela['ID_PRODUTO'];
                        $buscaProd = $connDB->prepare("SELECT PRODUTO, CAPAC_PROCESS, UNIDADE FROM produtos WHERE N_PRODUTO = :idProd");
                        $buscaProd->bindParam(':idProd', $rowTabela['ID_PRODUTO'], pdo::PARAM_INT);
                        $buscaProd->execute();
                        $rowProd = $buscaProd->fetch(PDO::FETCH_ASSOC); ?>
                        <tr>
                            <td scope="col" style="width: 15%;"><?php echo $rowTabela['NUMERO_PEDIDO'] . '<br>' . $rowProd['PRODUTO'] ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"> <?php
                                if($rowTabela['INICIO'] == NULL){ $dInicio = ''; $hInicio = ''; } else {
                                    $dInicio = date('d/m/Y', strtotime($rowTabela['INICIO']));
                                    $hInicio = date('H:i'  , strtotime($rowTabela['INICIO']));
                                } echo $dInicio . '<br>' . $hInicio; ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"> <?php
                                if($rowTabela['T_COMPRA'] == NULL){ $dCompra = 'Aguardando'; $hCompra = ''; } else {
                                    $dCompra = date('d/m/Y', strtotime($rowTabela['T_COMPRA']));
                                    $hCompra = date('H:i'  , strtotime($rowTabela['T_COMPRA']));
                                } echo $dCompra . '<br>' . $hCompra; ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"> <?php
                                if($rowTabela['T_RECEBE'] == NULL){ $dRecebe = 'Aguardando'; $hRecebe = ''; } else {
                                    $dRecebe = date('d/m/Y', strtotime($rowTabela['T_RECEBE']));
                                    $hRecebe = date('H:i'  , strtotime($rowTabela['T_RECEBE']));
                                } echo $dRecebe . '<br>' . $hRecebe; ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"> <?php
                                if($rowTabela['T_ANAMAT'] == NULL){ $dAnaMt = 'Aguardando'; $hAnaMt = ''; } else {
                                    $dAnaMt = date('d/m/Y', strtotime($rowTabela['T_ANAMAT']));
                                    $hAnaMt = date('H:i'  , strtotime($rowTabela['T_ANAMAT']));
                                } echo $dAnaMt . '<br>' . $hAnaMt; ?></td>

                            <td scope="col" style="width: 10%; text-align:center;">
                                <?php echo $rowProd['CAPAC_PROCESS'] . '<br>' . $rowProd['UNIDADE'] . ' /hora'?></td>

                            <td scope="col" style="width: 10%; text-align:center;"> <?php
                                if($rowTabela['T_ANAPRO'] == NULL){ $dAnaPd = 'Aguardando'; $hAnaPd = ''; } else {
                                    $dAnaPd = date('d/m/Y', strtotime($rowTabela['T_ANAPRO']));
                                    $hAnaPd = date('H:i'  , strtotime($rowTabela['T_ANAPRO']));
                                } echo $dAnaPd . '<br>' . $hAnaPd; ?></td>
                            <td scope="col" style="width: 10%; text-align:center;"> <?php
                                if($rowTabela['T_ENTREGA'] == NULL){ $dAnaEn = 'Aguardando'; $hAnaEn = ''; } else {
                                    $dAnaEn = date('d/m/Y', strtotime($rowTabela['T_ENTREGA']));
                                    $hAnaEn = date('H:i'  , strtotime($rowTabela['T_ENTREGA']));
                                } echo $dAnaEn . '<br>' . $hAnaEn; ?></td>
                        </tr><?php
                    } ?>                   
                </tbody>
            </table>
        </div>
    </form><br>
    </div>     
</div>