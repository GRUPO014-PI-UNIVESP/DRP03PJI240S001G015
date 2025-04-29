<?php
use PHPUnit\Framework\TestCase;

class ProcessaPedidoTest extends TestCase
{
    private $mockDb;
    private $connDB;

    protected function setUp(): void
    {
        $this->connDB = $this->createMock(PDO::class);
        $this->mockDb = $this->createMock(PDOStatement::class);

        // Importante: configure todos os métodos usados
        $this->mockDb->method('bindParam')->willReturn(true);
        $this->mockDb->method('execute')->willReturn(true);
        $this->mockDb->method('errorInfo')->willReturn([null, null, null]);

        // Sempre retorne o mock do statement quando prepare() for chamado
        $this->connDB->method('prepare')->willReturn($this->mockDb);
    }

    public function testConsultaPedido()
    {
        // Definindo o comportamento do método execute() e fetch()
        $this->mockDb->method('execute')->willReturn(true);
        $this->mockDb->method('fetch')->willReturn([
            'NUMERO_PEDIDO' => 123,
            'DATA_PEDIDO' => '2025-04-01',
            'PRODUTO' => 'Produto X',
            'QTDE_PEDIDO' => 100,
            'UNIDADE' => 'UN',
            'CLIENTE' => 'Cliente Y'
        ]);
        
        // Chama o código que utiliza o PDO para buscar o pedido
        $pedido = $this->consultarPedido(123);  // Simulando um pedido com ID 123
        
        // Verificando se o pedido retornado tem os dados corretos
        $this->assertEquals(123, $pedido['NUMERO_PEDIDO']);
        $this->assertEquals('Produto X', $pedido['PRODUTO']);
        $this->assertEquals(100, $pedido['QTDE_PEDIDO']);
        $this->assertEquals('Cliente Y', $pedido['CLIENTE']);
    }

    private function consultarPedido($idPedido)
    {
        $query = $this->connDB->prepare("SELECT * FROM pedidos WHERE NUMERO_PEDIDO = :idPed");
        $query->bindParam(':idPed', $idPedido, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function testAtualizaPedido()
    {
        // Mock do PDOStatement
        $mockStmt = $this->createMock(PDOStatement::class);

        // Garantir que bindParam pode ser chamado
        $mockStmt->method('bindParam')->willReturn(true);

        // Simulando que execute() retorna true
        $mockStmt->method('execute')->willReturn(true);

        // Simulando que errorInfo() retorna um array vazio (sem erro)
        $mockStmt->method('errorInfo')->willReturn([null, null, null]);

        // Garantir que prepare() retorna o mock do statement
        $this->connDB->method('prepare')->willReturn($mockStmt);

        // Executa a atualização e verifica o resultado
        try {
            $result = $this->atualizaPedido(123, 'FABRICAÇÃO CONCLUÍDA');
            $this->assertTrue($result, 'A atualização do pedido deveria retornar true, mas retornou false.');
        } catch (\Exception $e) {
            $this->fail('Erro inesperado ao atualizar pedido: ' . $e->getMessage());
        }
    }

    private function atualizaPedido($idPedido, $status)
    {
        $query = $this->connDB->prepare("UPDATE pedidos SET SITUACAO = :status WHERE NUMERO_PEDIDO = :idPed");

        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':idPed', $idPedido, PDO::PARAM_INT);

        $result = $query->execute();

        if (!$result) {
            throw new \Exception("Erro ao executar consulta: " . implode(', ', $query->errorInfo()));
        }

        return true;
    }

}
?>