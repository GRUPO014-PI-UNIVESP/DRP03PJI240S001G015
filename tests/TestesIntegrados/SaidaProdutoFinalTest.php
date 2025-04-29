<?php

use PHPUnit\Framework\TestCase;
use Mockery as m;

class TestSaidaProdutoFinal
{
    private $connDB;

    public function __construct(PDO $connDB)
    {
        $this->connDB = $connDB;
    }

    public function processDelivery(array $data, array $get): array
    {
        if (empty($get['id'])) {
            return ['success' => false, 'message' => 'ID do pedido não fornecido.'];
        }

        $entrega = $this->connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :idPed");
        $entrega->bindParam(':idPed', $get['id'], PDO::PARAM_INT);
        $entrega->execute();
        $rowEntrega = $entrega->fetch(PDO::FETCH_ASSOC);

        if (!$rowEntrega) {
            return ['success' => false, 'message' => 'Pedido não encontrado.'];
        }

        try {
            $this->connDB->beginTransaction();

            if ($rowEntrega['CLIENTE'] === 'INTERNO - ESTOQUE' && !empty($data['armazem'])) {
                if (empty($data['dataS']) || empty($data['colaborador']) || $data['colaborador'] === 'Selecione o nome do encarregado') {
                    $this->connDB->rollBack();
                    return ['success' => false, 'message' => 'Data de saída ou encarregado inválidos.'];
                }

                $etapa = 6;
                $situacao = 'PRODUTO ARMAZENADO NO ESTOQUE COM SUCESSO.';
                $saida = date('Y-m-d', strtotime($data['dataS']));
                $transport = $data['transport'] ?? null;

                $deliveryP = $this->connDB->prepare(
                    "UPDATE pedidos SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao, DATA_ENTREGA = :dataS, TRANSPORTADORA = :transp, ENCARREGADO_ENTREGA = :responsavel 
                    WHERE NUMERO_PEDIDO = :idPed"
                );
                $deliveryP->bindParam(':etapa', $etapa);
                $deliveryP->bindParam(':responsavel', $data['colaborador']);
                $deliveryP->bindParam(':idPed', $get['id']);
                $deliveryP->bindParam(':transp', $transport);
                $deliveryP->bindParam(':dataS', $saida);
                $deliveryP->bindParam(':situacao', $situacao);
                $deliveryP->execute();

                $estoque = $this->connDB->prepare(
                    "INSERT INTO produto_estoque (NOME_PRODUTO, NUMERO_LOTE, QTDE_ESTOQUE, UNIDADE_MEDIDA) 
                    VALUES (:nomeProduto, :numLote, :qtdeLote, :uniMed)"
                );
                $estoque->bindParam(':nomeProduto', $rowEntrega['PRODUTO']);
                $estoque->bindParam(':numLote', $rowEntrega['NUMERO_LOTE']);
                $estoque->bindParam(':qtdeLote', $rowEntrega['QTDE_PEDIDO']);
                $estoque->bindParam(':uniMed', $rowEntrega['UNIDADE']);
                $estoque->execute();

                $dataEntrega = date('Y-m-d H:i:s');

                $buscaTanaPro = $this->connDB->prepare("SELECT T_ANAPRO FROM historico_tempo WHERE NUMERO_PEDIDO = :numPedido");
                $buscaTanaPro->bindParam(':numPedido', $get['id']);
                $buscaTanaPro->execute();
                $rowLinha = $buscaTanaPro->fetch(PDO::FETCH_ASSOC);

                if (!$rowLinha) {
                    $this->connDB->rollBack();
                    return ['success' => false, 'message' => 'Registro de tempo não encontrado.'];
                }

                $dataC = new DateTime($dataEntrega);
                $dataI = new DateTime($rowLinha['T_ANAPRO']);
                $entregaTempo = ($dataC->getTimestamp() - $dataI->getTimestamp()) / 60;

                $marcaData = $this->connDB->prepare(
                    "UPDATE historico_tempo SET T_ENTREGA = :entrega, ETAPA_PROCESS = :etapa, ENTREGA = :tentrega WHERE NUMERO_PEDIDO = :numPedido"
                );
                $marcaData->bindParam(':numPedido', $get['id']);
                $marcaData->bindParam(':entrega', $dataEntrega);
                $marcaData->bindParam(':etapa', $etapa);
                $marcaData->bindParam(':tentrega', $entregaTempo);
                $marcaData->execute();

                $this->connDB->commit();
                return ['success' => true, 'message' => 'Transbordo para armazém registrado com sucesso.'];
            }

            $this->connDB->rollBack();
            return ['success' => false, 'message' => 'Nenhuma ação válida foi realizada.'];
        } catch (\Exception $e) {
            $this->connDB->rollBack();
            return ['success' => false, 'message' => 'Erro: ' . $e->getMessage()];
        }
    }
}

class SaidaProdutoFinalTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testWarehouseTransferSuccess()
    {
        $mockPDO = m::mock(PDO::class);
        $mockStmt = m::mock(PDOStatement::class);

        $mockPDO->shouldReceive('prepare')->with("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :idPed")->andReturn($mockStmt);
        $mockStmt->shouldReceive('bindParam')->andReturnTrue();
        $mockStmt->shouldReceive('execute')->andReturnTrue();
        $mockStmt->shouldReceive('fetch')->andReturn([
            'PRODUTO' => 'Produto X',
            'NUMERO_LOTE' => 'LOTE123',
            'QTDE_PEDIDO' => 100,
            'UNIDADE' => 'UN',
            'CLIENTE' => 'INTERNO - ESTOQUE'
        ]);

        // Outros prepares e execuções:
        $mockPDO->shouldReceive('beginTransaction')->andReturnTrue();
        $mockPDO->shouldReceive('commit')->andReturnTrue();
        $mockPDO->shouldReceive('rollBack')->andReturnTrue();

        // Mock UPDATE pedidos
        $mockUpdatePedido = m::mock(PDOStatement::class);
        $mockPDO->shouldReceive('prepare')->with(m::pattern('/^UPDATE pedidos/'))->andReturn($mockUpdatePedido);
        $mockUpdatePedido->shouldReceive('bindParam')->andReturnTrue();
        $mockUpdatePedido->shouldReceive('execute')->andReturnTrue();

        // Mock INSERT produto_estoque
        $mockInsertEstoque = m::mock(PDOStatement::class);
        $mockPDO->shouldReceive('prepare')->with(m::pattern('/^INSERT INTO produto_estoque/'))->andReturn($mockInsertEstoque);
        $mockInsertEstoque->shouldReceive('bindParam')->andReturnTrue();
        $mockInsertEstoque->shouldReceive('execute')->andReturnTrue();

        // Mock SELECT historico_tempo
        $mockSelectTempo = m::mock(PDOStatement::class);
        $mockPDO->shouldReceive('prepare')->with(m::pattern('/^SELECT T_ANAPRO/'))->andReturn($mockSelectTempo);
        $mockSelectTempo->shouldReceive('bindParam')->andReturnTrue();
        $mockSelectTempo->shouldReceive('execute')->andReturnTrue();
        $mockSelectTempo->shouldReceive('fetch')->andReturn([
            'T_ANAPRO' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
        ]);

        // Mock UPDATE historico_tempo
        $mockUpdateTempo = m::mock(PDOStatement::class);
        $mockPDO->shouldReceive('prepare')->with(m::pattern('/^UPDATE historico_tempo/'))->andReturn($mockUpdateTempo);
        $mockUpdateTempo->shouldReceive('bindParam')->andReturnTrue();
        $mockUpdateTempo->shouldReceive('execute')->andReturnTrue();

        $service = new TestSaidaProdutoFinal($mockPDO);

        $data = [
            'armazem' => 'Confirmar',
            'dataS' => '2025-04-29',
            'colaborador' => 'João Silva'
        ];
        $get = ['id' => 123];

        $result = $service->processDelivery($data, $get);

        $this->assertTrue($result['success']);
        $this->assertEquals('Transbordo para armazém registrado com sucesso.', $result['message']);
    }
}
