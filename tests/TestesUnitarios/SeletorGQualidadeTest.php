<?php
use PHPUnit\Framework\TestCase;

class QualidadeManager {
    private $connDB;

    public function __construct($connDB) {
        $this->connDB = $connDB;
    }

    public function getMateriaisParaAnalise() {
        $query = $this->connDB->prepare("SELECT * FROM materiais_lotes WHERE ETAPA_PROCESS = 2");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProdutosParaAnalise() {
        $query = $this->connDB->prepare("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 5");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}



class SeletorGQualidadeTest extends TestCase {
    private $connDB;
    private $qualidadeManager;

    protected function setUp(): void {
        $this->connDB = $this->createMock(PDO::class);
        $this->qualidadeManager = new QualidadeManager($this->connDB);
    }

    public function testGetMateriaisParaAnalise() {
        $stmt = $this->createMock(PDOStatement::class);

        $fakeData = [
            [
                'ID_INTERNO' => '001',
                'DESCRICAO' => 'Material Teste',
                'QTDE_LOTE' => 100,
                'UNIDADE' => 'KG',
                'DATA_RECEBIMENTO' => '2025-01-01',
                'SITUACAO' => 'Aguardando Analise',
                'ETAPA_PROCESS' => 2,
            ]
        ];

        $this->connDB->method('prepare')
        ->with($this->equalTo("SELECT * FROM materiais_lotes WHERE ETAPA_PROCESS = 2"))
        ->willReturn($stmt);

        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')
            ->with($this->equalTo(PDO::FETCH_ASSOC))
            ->willReturn($fakeData);

        $result = $this->qualidadeManager->getMateriaisParaAnalise();
        $this->assertEquals($fakeData, $result);
        $this->assertEquals('001', $result[0]['ID_INTERNO']);
        $this->assertEquals('Material Teste', $result[0]['DESCRICAO']);
        $this->assertEquals(100, $result[0]['QTDE_LOTE']);
        $this->assertEquals('KG', $result[0]['UNIDADE']);
        $this->assertEquals('Aguardando Analise', $result[0]['SITUACAO']);
        $this->assertEquals(2, $result[0]['ETAPA_PROCESS']);
    }

    public function testGetProdutosParaAnalise() {
        $stmt = $this->createMock(PDOStatement::class);

        $fakeData = [
            [
                'ID_PEDIDO' => 1,
                'PRODUTO' => 'Produto Teste',
                'NUMERO_LOTE' => '123',
                'QTDE_PEDIDO' => 50,
                'UNIDADE' => 'UN',
                'DATA_ENTREGA' => '2025-01-01',
                'SITUACAO' => 'Em Produção',
                'ETAPA_PROCESS' => 4
            ]
        ];

        $this->connDB->method('prepare')
        ->with($this->equalTo("SELECT * FROM pedidos WHERE ETAPA_PROCESS < 5"))
        ->willReturn($stmt);

        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')
            ->with($this->equalTo(PDO::FETCH_ASSOC))
            ->willReturn($fakeData);

        $result = $this->qualidadeManager->getProdutosParaAnalise();
        $this->assertEquals($fakeData, $result);
        $this->assertEquals(1, $result[0]['ID_PEDIDO']);
        $this->assertEquals('Produto Teste', $result[0]['PRODUTO']);
        $this->assertEquals('123', $result[0]['NUMERO_LOTE']);
        $this->assertEquals(50, $result[0]['QTDE_PEDIDO']);
        $this->assertEquals('UN', $result[0]['UNIDADE']);
        $this->assertEquals('Em Produção', $result[0]['SITUACAO']);
        $this->assertEquals(4, $result[0]['ETAPA_PROCESS']);
    }

}

?>