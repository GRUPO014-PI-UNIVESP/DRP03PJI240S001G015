<?php

use PHPUnit\Framework\TestCase;
use Mockery;

// Classe SeletorLogistica - Implementação
class SeletorLogistica
{
    protected $pdo;

    // Construtor que recebe a dependência PDO
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Método para listar materiais para recebimento
    public function listaMateriaisParaRecebimento()
    {
        $sql = "SELECT id, material FROM materiais WHERE status = 'para_recebimento'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Retorna os resultados como um array
        $materiais = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $materiais[] = $row;
        }

        return $materiais;
    }

    // Método para listar pedidos para saída
    public function listaPedidosParaSaida()
    {
        $sql = "SELECT id, pedido FROM pedidos WHERE status = 'para_saida'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Retorna os resultados como um array
        $pedidos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pedidos[] = $row;
        }

        return $pedidos;
    }
}

// Classe de Testes para SeletorLogistica
class SeletorLogisticaTest extends TestCase
{
    protected $pdoMock;
    protected $seletorLogistica;

    protected function setUp(): void
    {
        // Criar o mock do PDO
        $this->pdoMock = Mockery::mock(PDO::class);

        // Instanciar a classe SeletorLogistica com o mock do PDO
        $this->seletorLogistica = new SeletorLogistica($this->pdoMock);
    }

    public function testListaMateriaisParaRecebimento()
    {
        // Criar o mock da consulta
        $stmtMock = Mockery::mock(PDOStatement::class);
        $this->pdoMock->shouldReceive('prepare')->once()->andReturn($stmtMock);
        $stmtMock->shouldReceive('execute')->once();
        $stmtMock->shouldReceive('fetch')->once()->andReturn(['id' => 1, 'material' => 'Material A']);
        $stmtMock->shouldReceive('fetch')->once()->andReturn(false); // Finalizar a iteração

        // Testar o método
        $materiais = $this->seletorLogistica->listaMateriaisParaRecebimento();
        $this->assertCount(1, $materiais);
        $this->assertEquals('Material A', $materiais[0]['material']);
    }

    public function testListaPedidosParaSaida()
    {
        // Criar o mock da consulta
        $stmtMock = Mockery::mock(PDOStatement::class);
        $this->pdoMock->shouldReceive('prepare')->once()->andReturn($stmtMock);
        $stmtMock->shouldReceive('execute')->once();
        $stmtMock->shouldReceive('fetch')->once()->andReturn(['id' => 1, 'pedido' => 'Pedido A']);
        $stmtMock->shouldReceive('fetch')->once()->andReturn(false); // Finalizar a iteração

        // Testar o método
        $pedidos = $this->seletorLogistica->listaPedidosParaSaida();
        $this->assertCount(1, $pedidos);
        $this->assertEquals('Pedido A', $pedidos[0]['pedido']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
