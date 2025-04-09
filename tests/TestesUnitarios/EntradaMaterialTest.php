<?php
use PHPUnit\Framework\TestCase;

class MaterialEntradaManager {
    private $connDB;

    public function __construct($connDB) {
        $this->connDB = $connDB;
    }

    public function getMaterialCompra($idCompra) {
        $query = $this->connDB->prepare("SELECT * FROM materiais_compra WHERE ID_COMPRA = :id");
        $query->bindParam(':id', $idCompra, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getFornecedores() {
        $query = $this->connDB->prepare("SELECT DISTINCT FORNECEDOR FROM materiais_estoque");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEncarregados() {
        $query = $this->connDB->prepare("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = 'LOGÍSTICA' OR CREDENCIAL >= 4");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstoque($idEstoque) {
        $query = $this->connDB->prepare("SELECT * FROM materiais_estoque WHERE ID_ESTOQUE = :idEstoque");
        $query->bindParam(':idEstoque', $idEstoque, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getUltimoLote() {
        $query = $this->connDB->prepare("SELECT MAX(ID1) AS U_SEQ, MAX(ID2) AS U_MES, MAX(ID3) AS U_ANO FROM materiais_lotes");
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getReserva($numeroPedido, $idEstoque) {
        $query = $this->connDB->prepare("SELECT SUM(QTDE_RESERVA) AS RESERVA, UNIDADE FROM materiais_reserva WHERE NUMERO_PEDIDO = :numPedido AND ID_ESTOQUE = :idEstoque");
        $query->bindParam(':numPedido', $numeroPedido, PDO::PARAM_STR);
        $query->bindParam(':idEstoque', $idEstoque, PDO::PARAM_STR);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function gerarNumeroLoteInterno($ultimoLote) {
        $codMes = intval(date('m'));
        $codAno = intval(date('y'));
        $codLetra = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];

        if (!empty($ultimoLote['U_SEQ'])) {
            $anoAtual = ($ultimoLote['U_ANO'] < $codAno) ? $ultimoLote['U_ANO'] + 1 : $ultimoLote['U_ANO'];
            if ($ultimoLote['U_MES'] < $codMes) {
                $mesAtual = $ultimoLote['U_MES'] + 1;
                $seqAtual = 1;
            } else {
                $mesAtual = $ultimoLote['U_MES'];
                $seqAtual = $ultimoLote['U_SEQ'] + 1;
            }
            $seqLote = str_pad($seqAtual, 3, '0', STR_PAD_LEFT);
        } else {
            $seqLote = '001';
            $seqAtual = 1;
            $mesAtual = $codMes;
            $anoAtual = $codAno;
        }

        return "$seqLote {$codLetra[$mesAtual]} $anoAtual";
    }

    public function registrarRecebimento($idCompra, $dados, $responsavel) {
        $etapa = 2;
        $situacao = 'MATERIAL RECEBIDO, AGUARDANDO LIBERAÇÃO';
        $sitProduto = 'AGUARDANDO LIBERAÇÃO DOS MATERIAIS';
        $materialCompra = $this->getMaterialCompra($idCompra);

        // Atualiza materiais_lotes
        $salvaMat = $this->connDB->prepare(
            "UPDATE materiais_lotes SET NUMERO_LOTE=:nLoteF, ID_INTERNO=:nLoteIn, ID1=:id1, ID2=:id2, ID3=:id3, ETAPA_PROCESS=:etapa, SITUACAO=:situacao, FORNECEDOR=:fornecedor, NOTA_FISCAL=:notaFiscal, DATA_FABRI=:dataFabri, DATA_VALI=:dataVali, DATA_COMPRA=:dataCompra, DATA_RECEBIMENTO=:dataReceb, ENCARREGADO=:encarregado, RESPONSAVEL=:responsavel 
            WHERE ID_COMPRA = :idCompra AND ETAPA_PROCESS=1"
        );
        $ultimoLote = $this->getUltimoLote();
        $nLoteIn = $this->gerarNumeroLoteInterno($ultimoLote);
        $salvaMat->bindParam(':nLoteF', $dados['nLoteForn'], PDO::PARAM_STR);
        $salvaMat->bindParam(':nLoteIn', $nLoteIn, PDO::PARAM_STR);
        $salvaMat->bindParam(':id1', $dados['seqAtual'], PDO::PARAM_STR);
        $salvaMat->bindParam(':id2', $dados['mesAtual'], PDO::PARAM_INT);
        $salvaMat->bindParam(':id3', $dados['anoAtual'], PDO::PARAM_INT);
        $salvaMat->bindParam(':etapa', $etapa, PDO::PARAM_INT);
        $salvaMat->bindParam(':situacao', $situacao, PDO::PARAM_STR);
        $salvaMat->bindParam(':notaFiscal', $dados['notaFiscal'], PDO::PARAM_STR);
        $salvaMat->bindParam(':dataFabri', $dados['dataFabri'], PDO::PARAM_STR);
        $salvaMat->bindParam(':dataVali', $dados['dataVali'], PDO::PARAM_STR);
        $salvaMat->bindParam(':dataReceb', $dados['dataEntrada'], PDO::PARAM_STR);
        $salvaMat->bindParam(':dataCompra', $materialCompra['DATA_PEDIDO'], PDO::PARAM_STR);
        $salvaMat->bindParam(':encarregado', $dados['encarregado'], PDO::PARAM_STR);
        $salvaMat->bindParam(':responsavel', $responsavel, PDO::PARAM_STR);
        $salvaMat->bindParam(':idCompra', $idCompra, PDO::PARAM_STR);
        $salvaMat->bindParam(':fornecedor', $dados['fornecedor'], PDO::PARAM_STR);
        $salvaMat->execute();

        // Atualiza pedidos
        $atualizaPedido = $this->connDB->prepare("UPDATE pedidos SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao WHERE NUMERO_PEDIDO = :numPedido");
        $atualizaPedido->bindParam(':etapa', $etapa, PDO::PARAM_INT);
        $atualizaPedido->bindParam(':situacao', $sitProduto, PDO::PARAM_STR);
        $atualizaPedido->bindParam(':numPedido', $materialCompra['NUMERO_PEDIDO'], PDO::PARAM_STR);
        $atualizaPedido->execute();

        // Atualiza materiais_reserva
        $atualizaReserva = $this->connDB->prepare("UPDATE materiais_reserva SET DISPONIBILIDADE = :disp WHERE ID_COMPRA = :idCompra");
        $atualizaReserva->bindParam(':disp', $etapa, PDO::PARAM_STR);
        $atualizaReserva->bindParam(':idCompra', $materialCompra['ID_COMPRA'], PDO::PARAM_STR);
        $atualizaReserva->execute();

        // Atualiza materiais_compra
        $limpaAgenda = $this->connDB->prepare("UPDATE materiais_compra SET ETAPA_PROCESS = :etapa, SITUACAO = :situacao WHERE ID_COMPRA = :idCompra");
        $limpaAgenda->bindParam(':etapa', $etapa, PDO::PARAM_INT);
        $limpaAgenda->bindParam(':situacao', $situacao, PDO::PARAM_STR);
        $limpaAgenda->bindParam(':idCompra', $idCompra, PDO::PARAM_INT);
        $limpaAgenda->execute();
    }

}

class EntradaMaterialTest extends TestCase {
    private $connDB;
    private $manager;

    protected function setUp(): void {
        $this->connDB = $this->createMock(PDO::class);
        $this->manager = new MaterialEntradaManager($this->connDB);
    }

    public function testGetMaterialCompra() {
        $stmt = $this->createMock(PDOStatement::class);
        $fakeData = [
            'ID_COMPRA' => 1,
            'NUMERO_PEDIDO' => '001',
            'DESCRICAO' => 'Material Teste',
            'QTDE_PEDIDO' => 100,
            'UNIDADE' => 'kg',
            'DATA_PEDIDO' => '2025-04-01',
            'ID_ESTOQUE' => 10
        ];

        $this->connDB->method('prepare')
            ->with($this->equalTo("SELECT * FROM materiais_compra WHERE ID_COMPRA = :id"))
            ->willReturn($stmt);
        $stmt->method('bindParam')->willReturn(true);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->with(PDO::FETCH_ASSOC)->willReturn($fakeData);

        $result = $this->manager->getMaterialCompra(1);
        $this->assertEquals($fakeData, $result);
    }

    public function testGetFornecedores() {
        $stmt = $this->createMock(PDOStatement::class);
        $fakeData = [
            ['FORNECEDOR' => 'Fornecedor A'],
            ['FORNECEDOR' => 'Fornecedor B']
        ];

        $this->connDB->method('prepare')
            ->with($this->equalTo("SELECT DISTINCT FORNECEDOR FROM materiais_estoque"))
            ->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->with(PDO::FETCH_ASSOC)->willReturn($fakeData);

        $result = $this->manager->getFornecedores();
        $this->assertEquals($fakeData, $result);
    }

    public function testGetEncarregados() {
        $stmt = $this->createMock(PDOStatement::class);
        $fakeData = [
            ['NOME_FUNCIONARIO' => 'João Silva'],
            ['NOME_FUNCIONARIO' => 'Maria Souza']
        ];

        $this->connDB->method('prepare')
            ->with($this->equalTo("SELECT NOME_FUNCIONARIO FROM quadro_funcionarios WHERE DEPARTAMENTO = 'LOGÍSTICA' OR CREDENCIAL >= 4"))
            ->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->with(PDO::FETCH_ASSOC)->willReturn($fakeData);

        $result = $this->manager->getEncarregados();
        $this->assertEquals($fakeData, $result);
    }

    public function testGetEstoque() {
        $stmt = $this->createMock(PDOStatement::class);
        $fakeData = [
            'ID_ESTOQUE' => 10,
            'QTDE_ESTOQUE' => 500,
            'UNIDADE' => 'kg'
        ];

        $this->connDB->method('prepare')
            ->with($this->equalTo("SELECT * FROM materiais_estoque WHERE ID_ESTOQUE = :idEstoque"))
            ->willReturn($stmt);
        $stmt->method('bindParam')->willReturn(true);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->with(PDO::FETCH_ASSOC)->willReturn($fakeData);

        $result = $this->manager->getEstoque(10);
        $this->assertEquals($fakeData, $result);
    }

    public function testGerarNumeroLoteInternoPrimeiroLote() {
        $ultimoLote = ['U_SEQ' => null, 'U_MES' => null, 'U_ANO' => null];
        $result = $this->manager->gerarNumeroLoteInterno($ultimoLote);
        $this->assertEquals('001 D 25', $result);
    }

    public function testGerarNumeroLoteInternoProximoLote() {
        $ultimoLote = ['U_SEQ' => 5, 'U_MES' => 3, 'U_ANO' => 25];
        $result = $this->manager->gerarNumeroLoteInterno($ultimoLote);
        $this->assertEquals('001 D 25', $result);
    }

    public function testRegistrarRecebimento() {
        $idCompra = 1;
        $dados = [
            'notaFiscal' => '123',
            'fornecedor' => 'FORNECEDOR A',
            'encarregado' => 'JOÃO SILVA',
            'dataFabri' => '2025-03-01',
            'dataVali' => '2026-03-01',
            'dataEntrada' => '2025-04-09',
            'nLoteForn' => '001',
            'seqAtual' => 1,
            'mesAtual' => 4,
            'anoAtual' => 25
        ];
        $responsavel = 'Maria';

        $materialStmt = $this->createMock(PDOStatement::class);
        $materialStmt->method('execute')->willReturn(true);
        $materialStmt->method('fetch')->willReturn(['ID_COMPRA' => 1, 'NUMERO_PEDIDO' => '001', 'DATA_PEDIDO' => '2025-04-01']);
        
        $loteStmt = $this->createMock(PDOStatement::class);
        $loteStmt->method('execute')->willReturn(true);
        $loteStmt->method('fetch')->willReturn(['U_SEQ' => null, 'U_MES' => null, 'U_ANO' => null]);

        $updateStmt1 = $this->createMock(PDOStatement::class);
        $updateStmt1->method('bindParam')->willReturn(true);
        $updateStmt1->method('execute')->willReturn(true);

        $updateStmt2 = $this->createMock(PDOStatement::class);
        $updateStmt2->method('bindParam')->willReturn(true);
        $updateStmt2->method('execute')->willReturn(true);

        $this->connDB->method('prepare')
            ->willReturnCallback(function ($query) use ($materialStmt, $loteStmt, $updateStmt1, $updateStmt2) {
                if (strpos($query, "SELECT * FROM materiais_compra") !== false) return $materialStmt;
                if (strpos($query, "SELECT MAX(ID1)") !== false) return $loteStmt;
                if (strpos($query, "UPDATE materiais_lotes") !== false) return $updateStmt1;
                if (strpos($query, "UPDATE pedidos") !== false || strpos($query, "UPDATE materiais_reserva") !== false || strpos($query, "UPDATE materiais_compra") !== false) return $updateStmt2;
            });

        $this->manager->registrarRecebimento($idCompra, $dados, $responsavel);
        $this->assertTrue(true);
    }
}
?>