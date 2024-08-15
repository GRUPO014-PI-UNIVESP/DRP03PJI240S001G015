-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/08/2024 às 04:06
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `drp03pji240s001g015`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `ID_CARGO` int(11) NOT NULL,
  `CARGO` varchar(45) NOT NULL,
  `CREDENCIAL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cargos`
--

INSERT INTO `cargos` (`ID_CARGO`, `CARGO`, `CREDENCIAL`) VALUES
(1, 'DIRETOR(A) EXECUTIVO(A)', 7),
(2, 'GERENTE', 6),
(3, 'SUPERVISOR(A)', 5),
(4, 'ANALISTA SR', 4),
(5, 'ANALISTA JR', 3),
(6, 'ASSISTENTE', 2),
(7, 'LÍDER DE PRODUÇÃO', 3),
(8, 'OPERADOR(A) DE MÁQUINA', 2),
(9, 'SERVENTE', 1),
(10, 'MECÂNICO(A) CHEFE', 4),
(11, 'MECÂNIVO(A)', 3),
(12, 'ENGENHEIRO(A)', 5),
(13, 'TECNICO(A)', 3),
(14, 'PROGRAMADOR(A)', 2),
(15, 'MOTORISTA', 1),
(16, 'ZELADOR(A)', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `departamentos`
--

CREATE TABLE `departamentos` (
  `ID_DEPTO` int(11) NOT NULL,
  `DEPARTAMENTO` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `departamentos`
--

INSERT INTO `departamentos` (`ID_DEPTO`, `DEPARTAMENTO`) VALUES
(1, 'ADMINISTRATIVO'),
(2, 'COMERCIAL'),
(3, 'GARANTIA DA QUALIDADE'),
(4, 'INFRAESTRUTURA E MANUTENÇÃO'),
(5, 'LOGÍSTICA'),
(6, 'PRODUÇÃO'),
(7, 'PESQUISA E DESENVOLVIMENTO'),
(8, 'TECNOLOGIA DA INFORMAÇÃO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `entrada_mp`
--

CREATE TABLE `entrada_mp` (
  `ID_MP` int(11) NOT NULL,
  `DATA_ENTRADA` datetime NOT NULL,
  `FORNECEDOR` varchar(120) NOT NULL,
  `NUMERO_LOTE` varchar(20) NOT NULL,
  `DESCRICAO_MP` varchar(120) NOT NULL,
  `QUANTIDADE_LOTE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `entrada_mp`
--

INSERT INTO `entrada_mp` (`ID_MP`, `DATA_ENTRADA`, `FORNECEDOR`, `NUMERO_LOTE`, `DESCRICAO_MP`, `QUANTIDADE_LOTE`) VALUES
(1, '2024-07-01 00:00:00', 'FORNECEDOR A', 'MPA-010724', 'MATERIA PRIMA A', 2000),
(2, '2024-07-02 00:00:00', 'FORNECEDOR B', 'MPB-020724', 'MATERIA PRIMA B', 1500),
(3, '2024-07-03 00:00:00', 'FORNECEDOR C', 'MPC-030724', 'MATERIA PRIMA C', 5000),
(4, '2024-07-04 00:00:00', 'FORNECEDOR A', 'MPD-040724', 'MATERIA PRIMA D', 10000),
(5, '2024-07-05 00:00:00', 'FORNECEDOR B', 'MPE-050724', 'MATERIA PRIMA E', 3003),
(6, '2024-07-06 00:00:00', 'FORNECEDOR C', 'MPF-060724', 'MATERIA PRIMA F', 1000),
(7, '2024-07-07 00:00:00', 'FORNECEDOR A', 'MPG-070724', 'MATERIA PRIMA G', 4000);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_login`
--

CREATE TABLE `historico_login` (
  `ID_LOGIN` int(11) NOT NULL,
  `NOME_FUNCIONARIO` varchar(150) NOT NULL,
  `DATA_LOGIN` date NOT NULL,
  `HORA_LOGIN` varchar(8) NOT NULL,
  `DATA_LOGOUT` date DEFAULT NULL,
  `HORA_LOGOUT` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico_login`
--

INSERT INTO `historico_login` (`ID_LOGIN`, `NOME_FUNCIONARIO`, `DATA_LOGIN`, `HORA_LOGIN`, `DATA_LOGOUT`, `HORA_LOGOUT`) VALUES
(177, 'EDSON MASSAO MATSUUCHI', '2024-08-12', '22:20:18', '2024-08-12', '22:56:18'),
(179, 'EDSON MASSAO MATSUUCHI', '2024-08-12', '23:55:03', '2024-08-12', '23:57:00'),
(180, 'EDSON MASSAO MATSUUCHI', '2024-08-13', '13:10:34', '2024-08-13', '13:34:49'),
(181, 'EDSON MASSAO MATSUUCHI', '2024-08-13', '13:34:55', '2024-08-13', '13:35:28'),
(182, 'EDSON MASSAO MATSUUCHI', '2024-08-13', '13:35:39', '2024-08-13', '13:52:51'),
(183, 'EDSON MASSAO MATSUUCHI', '2024-08-13', '17:54:36', '2024-08-13', '19:31:00'),
(185, 'EDSON MASSAO MATSUUCHI', '2024-08-13', '19:43:59', '2024-08-13', '19:51:52'),
(186, 'EDSON MASSAO MATSUUCHI', '2024-08-13', '19:52:13', '2024-08-13', '21:02:05'),
(187, 'EDSON MASSAO MATSUUCHI', '2024-08-13', '21:27:34', '2024-08-14', '00:25:54'),
(188, 'ALINE MANAMI MATSUUCHI', '2024-08-14', '00:26:02', '2024-08-14', '00:27:22'),
(190, 'EDSON MASSAO MATSUUCHI', '2024-08-14', '13:11:55', '2024-08-14', '13:51:37'),
(191, 'EDSON MASSAO MATSUUCHI', '2024-08-14', '17:55:37', '2024-08-14', '19:01:39'),
(192, 'EDSON MASSAO MATSUUCHI', '2024-08-14', '19:46:50', '2024-08-14', '20:15:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `ID_MENSAGEM` int(11) NOT NULL,
  `EMISSOR_MSG` varchar(150) NOT NULL,
  `DEPTO_EMISSOR` varchar(45) NOT NULL,
  `DATA_MSG` date NOT NULL,
  `HORA_MSG` varchar(8) NOT NULL,
  `RECEPTOR_MSG` varchar(150) NOT NULL,
  `DEPTO_RECEPTOR` varchar(45) NOT NULL,
  `TITULO_MSG` varchar(100) NOT NULL,
  `MENSAGEM` longtext NOT NULL,
  `CONFIRMA` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`ID_MENSAGEM`, `EMISSOR_MSG`, `DEPTO_EMISSOR`, `DATA_MSG`, `HORA_MSG`, `RECEPTOR_MSG`, `DEPTO_RECEPTOR`, `TITULO_MSG`, `MENSAGEM`, `CONFIRMA`) VALUES
(1, 'ALINE MANAMI MATSUUCHI', 'ADMINISTRATIVO', '2024-08-13', '13:50', 'EDSON MASSAO MATSUUCHI', 'ADMINISTRATIVO', 'OLÁ', 'Por que estou voltando nesse assunto? Pois é, neste PI, você terá a oportunidade de aperfeiçoar o projeto que você iniciou lá ou, então, criar uma outra aplicação! Desta vez, iremos incluir algumas funcionalidades importantes que serão vistas ao longo do semestre, como acessibilidade, linguagens de script, nuvem etc. Será uma excelente oportunidade para você aprofundar seus estudos e trabalhar com diferentes tecnologias que são, atualmente, de grande interesse do mercado.', 'UNRE'),
(2, 'CARLOS YASSUO MATSUUCHI', 'PRODUÇÃO', '2024-06-20', '9:55', 'EDSON MASSAO MATSUUCHI', 'ADMINISTRATIVO', 'Urgente', 'é mentiraaaa', 'UNRE'),
(3, 'PAULO KATSUMI MATSUUCHI', 'LOGÍSTICA', '2024-07-30', '17:35', 'EDSON MASSAO MATSUUCHI', 'ADMINISTRATIVO', 'Pedido', 'tem mais entrega?', 'UNRE'),
(4, 'ALINE MANAMI MATSUUCHI', 'ADMINISTRATIVO', '2024-08-02', '14:15', 'EDSON MASSAO MATSUUCHI', 'ADMINISTRATIVO', 'Tchau', 'vou dar lina na pipa tá', 'UNRE'),
(6, 'PAULO KATSUMI MATSUUCHI', 'LOGÍSTICA', '2024-05-15', '11:00', 'EDSON MASSAO MATSUUCHI', 'ADMINISTRATIVO', 'Fala ae', 'Você deve lembrar do tema central do Projeto Integrador I, cursado no semestre anterior: desenvolvemos uma aplicação Web com suporte a banco de dados e controle de versões. Naquela ocasião, iniciamos nossos estudos nesta área tão importante que é o desenvolvimento web. Vimos como construir websites funcionais e disponibilizá-los na nuvem para poderem ser acessados por qualquer pessoa em qualquer lugar do mundo.', 'READ'),
(7, 'EDSON MASSAO MATSUUCHI', 'ADMINISTRATIVO', '2024-08-14', '00:25:15', 'ALINE MANAMI MATSUUCHI', 'ADMINISTRATIVO', 'Resposta', 'confirmado', 'UNRE'),
(8, 'EDSON MASSAO MATSUUCHI', 'ADMINISTRATIVO', '2024-08-14', '13:20:46', 'PAULO KATSUMI MATSUUCHI', 'LOGÍSTICA', 'Resposta', 'Tudo certo!!!', 'UNRE');

-- --------------------------------------------------------

--
-- Estrutura para tabela `quadro_funcionarios`
--

CREATE TABLE `quadro_funcionarios` (
  `ID_FUNCIONARIO` int(11) NOT NULL,
  `NOME_FUNCIONARIO` varchar(120) NOT NULL,
  `DATA_ADMISSAO` date NOT NULL,
  `CARGO` varchar(45) NOT NULL,
  `DEPARTAMENTO` varchar(45) NOT NULL,
  `CREDENCIAL` int(2) NOT NULL,
  `USUARIO` varchar(15) NOT NULL,
  `SENHA` varchar(6) NOT NULL,
  `ID_USUARIO` varchar(220) NOT NULL,
  `SENHA_USUARIO` varchar(220) NOT NULL,
  `DATA_NASCIMENTO` date NOT NULL,
  `CPF` varchar(14) DEFAULT NULL,
  `RG` varchar(12) DEFAULT NULL,
  `TELEFONE` varchar(15) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `RUA_RES` varchar(100) DEFAULT NULL,
  `NUM_RES` varchar(10) DEFAULT NULL,
  `COMPLEMENTO` varchar(45) DEFAULT NULL,
  `BAIRRO` varchar(75) DEFAULT NULL,
  `CIDADE` varchar(75) DEFAULT NULL,
  `UF` varchar(2) DEFAULT NULL,
  `RESPONSAVEL_CADASTRO` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `quadro_funcionarios`
--

INSERT INTO `quadro_funcionarios` (`ID_FUNCIONARIO`, `NOME_FUNCIONARIO`, `DATA_ADMISSAO`, `CARGO`, `DEPARTAMENTO`, `CREDENCIAL`, `USUARIO`, `SENHA`, `ID_USUARIO`, `SENHA_USUARIO`, `DATA_NASCIMENTO`, `CPF`, `RG`, `TELEFONE`, `EMAIL`, `RUA_RES`, `NUM_RES`, `COMPLEMENTO`, `BAIRRO`, `CIDADE`, `UF`, `RESPONSAVEL_CADASTRO`) VALUES
(1, 'EDSON MASSAO MATSUUCHI', '2000-01-01', 'O CARA DO TI', 'ADMINISTRATIVO', 7, 'EdsOn', '153698', '$2y$10$zeFZ7m5zqcbOlJzGqJ3/eecdOOoZPTvy89K5wF/3zSfHPfOSe1g2m', '$2y$10$O5rp3OrUgX23KOXmW4DwHujIKrE/z82RF1gBrbHzeeUTjRT247bOO', '1970-08-17', '579.057.549-87', '22.800.176-6', '(12) 98852-1494', '1829240@aluno.univesp.br', 'Rua Olinda', '1045', 'sobreloja', 'PARQUE INDUSTRIAL', 'SãO JOSé DOS CAMPOS', 'SP', 'EDSON MASSAO MATSUUCHI'),
(12, 'ALINE MANAMI MATSUUCHI', '2024-08-05', 'ASSISTENTE', 'ADMINISTRATIVO', 2, 'shiro', '153698', '$2y$10$oKYCjZ7k4vxdiSf2tWuSou5KloxUbsk4sJq59Cyj6fcGv9yMV/SAS', '$2y$10$VekwPoQjVrDN8L77cRDAdOGfNZneLyKHWagERx8Ne/E7aIOrv7vl6', '2007-10-29', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Const', 'Nada Consta', '', 'Nada Consta', 'NC', 'EDSON MASSAO MATSUUCHI'),
(13, 'CARLOS YASSUO MATSUUCHI', '2005-10-10', 'GERENTE', 'PRODUÇÃO', 6, 'qzXACc', '217598', '$2y$10$EfMEe4rd4XWvJKBe6qVJSOGeN5GaCsDHfs4g3a00H/CeTtGxA7/6e', '$2y$10$pdGsSwKVewkXZOlXsSU.KOJuVFvnWA/Zy6HLatwDp5AIdQWMLhN1K', '1972-01-21', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Const', 'Nada Consta', '', 'Nada Consta', 'NC', 'EDSON MASSAO MATSUUCHI'),
(14, 'PAULO KATSUMI MATSUUCHI', '2006-03-12', 'MOTORISTA', 'LOGÍSTICA', 1, 'BADwpj', '084172', '$2y$10$girPDKG6fGNF5Sa3Gd3vWuvoLPNU8DXOLfZzyKkha2HbrKJuLLjCK', '$2y$10$ucf0El8C8UnHOBHB9M8ib.Uy6UqU15WbW.sQxlk5/5bvQuaueSZwS', '1973-06-08', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Consta', 'Nada Const', 'Nada Consta', '', 'Nada Consta', 'NC', 'EDSON MASSAO MATSUUCHI'),
(15, 'FULANO SICLANO BELTRANO', '2002-02-20', 'ZELADOR(A)', 'PRODUÇÃO', 0, 'TOamxq', '861209', '$2y$10$d0Euvk8uWmpSGZZhl3sJPevWvSEUKgvS49rX1bH47y3fQV4pt31AG', '$2y$10$Az15uTmrmkk2gWyEz5k./eUFnkmPoGKcjDm925RcRkLczXgNllVeC', '2002-12-12', '987.654.321-03', '98.765.432-0', '(98) 76543-2174', 'fulanodetal@email.com', 'Rua sem nome', '123456', 'sem casa', 'LUGANENHUM', 'SãO JUDAS PERDEU A BOTA', 'XI', 'EDSON MASSAO MATSUUCHI'),
(16, 'ESSE TAL DE ROCK AND ROLL', '0020-02-02', 'TECNICO(A)', 'PRODUÇÃO', 3, 'neidyX', '251604', '$2y$10$E53luc9jyLSbI/90wD7WvODlel6gGRzhCrw/it8Q/HLzDPgEnmpAy', '$2y$10$je83WmMV8joGXCjXfbpI9..EpfN4Hf7WzMSCroXQorTL2gQwckr1C', '2000-02-02', '987.654.321-02', '98.765.432-1', '(98) 76543-2198', 'essetalderockandroll@email.com', 'Rua Californication', '123456', 'apto 123', 'QUEENS', 'LIVERPOOL', 'UK', 'EDSON MASSAO MATSUUCHI');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`ID_CARGO`);

--
-- Índices de tabela `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`ID_DEPTO`);

--
-- Índices de tabela `entrada_mp`
--
ALTER TABLE `entrada_mp`
  ADD PRIMARY KEY (`ID_MP`);

--
-- Índices de tabela `historico_login`
--
ALTER TABLE `historico_login`
  ADD PRIMARY KEY (`ID_LOGIN`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`ID_MENSAGEM`);

--
-- Índices de tabela `quadro_funcionarios`
--
ALTER TABLE `quadro_funcionarios`
  ADD PRIMARY KEY (`ID_FUNCIONARIO`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `ID_CARGO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `ID_DEPTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `entrada_mp`
--
ALTER TABLE `entrada_mp`
  MODIFY `ID_MP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `historico_login`
--
ALTER TABLE `historico_login`
  MODIFY `ID_LOGIN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `ID_MENSAGEM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `quadro_funcionarios`
--
ALTER TABLE `quadro_funcionarios`
  MODIFY `ID_FUNCIONARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
