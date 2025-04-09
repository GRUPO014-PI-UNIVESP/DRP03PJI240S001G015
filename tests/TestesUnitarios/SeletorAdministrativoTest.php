<?php
use PHPUnit\Framework\TestCase;

class PedidoManager {
    private $connDB;

    public function __construct($connDB) {
        $this->connDB = $connDB;
    }

    public function getPedidosEmExecucao() {
        $produtos = $this->connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 4");
        $produtos->execute();
        return $produtos->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComprasAgendadas() {
        $query = $this->connDB->prepare("SELECT * FROM materiais_compra WHERE ETAPA_PROCESS = 0 ORDER BY DATA_PRAZO ASC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

class SeletorAdministrativoTest extends TestCase {
    private $connDB;
    private $pedidoManager;

    protected function setUp(): void {
        $this->connDB = $this->createMock(PDO::class);
        $this->pedidoManager = new PedidoManager($this->connDB);
    }

    public function testGetPedidosEmExecucao() {
        $stmt = $this->createMock(PDOStatement::class);

        $fakeData = [
            [
                'NUMERO_PEDIDO' => '001',
                'PRODUTO' => 'Produto Teste',
                'QTDE_PEDIDO' => 10,
                'UNIDADE' => 'UN',
                'CLIENTE' => 'Cliente Teste',
                'CAPAC_PROCESS' => 10,
                'DATA_AGENDA' => '2023-10-01',
                'DATA_ENTREGA' => '2023-10-05',
                'ETAPA_PROCESS' => 1,
                'SITUACAO' => 'Em Andamento'
            ]
        ];

        // Substituindo expects() por method()
        $this->connDB->method('prepare')
            ->with($this->equalTo("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 4"))
            ->willReturn($stmt);

        $stmt->method('execute')
            ->willReturn(true);

        $stmt->method('fetchAll')
            ->with($this->equalTo(PDO::FETCH_ASSOC))
            ->willReturn($fakeData);

        $result = $this->pedidoManager->getPedidosEmExecucao();
        $this->assertEquals($fakeData, $result);
        $this->assertEquals('001', $result[0]['NUMERO_PEDIDO']);
        $this->assertEquals('Produto Teste', $result[0]['PRODUTO']);
    }

    public function testGetPedidosAgendadas() {
        $stmt = $this->createMock(PDOStatement::class);

        $fakeData = [
            [
                'ID_COMPRA' => 1,
                'NUMERO_PEDIDO' => '001',
                'DESCRICAO' => 'Material Teste',
                'QTDE_PEDIDO' => 10,
                'UNIDADE' => 'kg',
                'DATA-PEDIDO' => '2023-10-01',
                'DATA_PRAZO' => '2023-10-05',
                'SITUACAO' => 'COMPRA AGENDADA'
            ]
        ];

        $this->connDB->method('prepare')
            ->with($this->equalTo("SELECT * FROM materiais_compra WHERE ETAPA_PROCESS = 0 ORDER BY DATA_PRAZO ASC"))
            ->willReturn($stmt);

        $stmt->method('execute')
            ->willReturn(true);

        $stmt->method('fetchAll')
            ->with($this->equalTo(PDO::FETCH_ASSOC))
            ->willReturn($fakeData);

        $result = $this->pedidoManager->getComprasAgendadas();
        $this->assertEquals($fakeData, $result);
        $this->assertEquals(1, $result[0]['ID_COMPRA']);
        $this->assertEquals('001', $result[0]['NUMERO_PEDIDO']);
        $this->assertEquals('Material Teste', $result[0]['DESCRICAO']);
    }
}
?>