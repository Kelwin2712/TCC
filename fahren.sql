-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/10/2025 às 02:38
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
-- Banco de dados: `fahren`
--
CREATE DATABASE IF NOT EXISTS `fahren` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fahren`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `anuncios_carros`
--

DROP TABLE IF EXISTS `anuncios_carros`;
CREATE TABLE IF NOT EXISTS `anuncios_carros` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `ativo` char(1) NOT NULL DEFAULT 'A',
  `modelo` varchar(75) DEFAULT NULL,
  `estado_local` char(2) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `marca` int(2) DEFAULT NULL,
  `versao` varchar(40) DEFAULT NULL,
  `carroceria` int(2) DEFAULT NULL,
  `preco` decimal(11,2) DEFAULT NULL,
  `condicao` char(1) NOT NULL DEFAULT 'U',
  `quilometragem` int(10) DEFAULT NULL,
  `ano_fabricacao` int(4) DEFAULT NULL,
  `ano_modelo` int(4) DEFAULT NULL,
  `propulsao` varchar(10) DEFAULT NULL,
  `combustivel` varchar(20) DEFAULT NULL,
  `blindagem` char(1) DEFAULT '0',
  `id_vendedor` int(10) DEFAULT NULL,
  `imagens` varchar(255) DEFAULT NULL,
  `leilao` char(1) DEFAULT NULL,
  `portas_qtd` smallint(1) DEFAULT 4,
  `assentos_qtd` smallint(1) DEFAULT 5,
  `placa` char(7) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `cor` int(2) NOT NULL,
  `quant_proprietario` char(1) NOT NULL,
  `revisao` char(1) NOT NULL,
  `vistoria` char(1) NOT NULL,
  `sinistro` char(1) NOT NULL,
  `ipva` char(1) NOT NULL,
  `licenciamento` char(1) NOT NULL,
  `estado_conservacao` char(1) NOT NULL,
  `uso_anterior` char(1) NOT NULL,
  `aceita_troca` char(1) NOT NULL,
  `email` varchar(256) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `garantia` int(2) NOT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `placa` (`placa`),
  KEY `cor_fk` (`cor`),
  KEY `carroceria_fk` (`carroceria`),
  KEY `vendedor_fk` (`id_vendedor`),
  KEY `estado_fk` (`estado_local`),
  KEY `marca_fk` (`marca`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `anuncios_carros`
--

INSERT INTO `anuncios_carros` (`id`, `ativo`, `modelo`, `estado_local`, `cidade`, `marca`, `versao`, `carroceria`, `preco`, `condicao`, `quilometragem`, `ano_fabricacao`, `ano_modelo`, `propulsao`, `combustivel`, `blindagem`, `id_vendedor`, `imagens`, `leilao`, `portas_qtd`, `assentos_qtd`, `placa`, `data_criacao`, `cor`, `quant_proprietario`, `revisao`, `vistoria`, `sinistro`, `ipva`, `licenciamento`, `estado_conservacao`, `uso_anterior`, `aceita_troca`, `email`, `telefone`, `garantia`, `descricao`) VALUES
(28, 'A', 'seu', 'SP', '', 3, '3.9 V8 TURBO GASOLINA F1-DCT', 5, 51087.51, 'S', 42141, 2025, 2025, 'abarth', 'abarth', '0', 6, NULL, NULL, 4, 5, 'AAA1A17', '2025-10-10 20:41:00', 8, '1', '2', 'F', 'L', 'I', 'V', '1', 'A', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, ''),
(29, 'A', 'vini', NULL, NULL, 4, '3.9 V8 TURBO GASOLINA F1-DCTa', NULL, 100000.00, 'N', 0, 2020, 2021, NULL, NULL, '0', 7, NULL, NULL, 4, 5, 'ZZZ3Z33', '2025-10-10 20:41:57', 4, '3', '2', 'V', 'L', 'A', 'V', '4', '', '0', 'vinicius@gmail.com', '(11) 11111-1111', 0, ''),
(30, 'A', '911', NULL, NULL, 3, 'v12', NULL, 4821.00, 'N', 0, 2022, 2022, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'FDA1R23', '2025-10-17 08:23:51', 5, '3', '3', 'F', 'L', 'D', 'V', '4', '', '0', 'kelwin@gmail.com', '(12) 98827-3730', 0, ''),
(31, 'A', 'da', NULL, NULL, 6, 'v12', NULL, 95.87, 'N', 0, 2023, 2023, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'GAG2T23', '2025-10-17 08:27:04', 4, '2', '3', 'V', 'L', 'A', 'D', '3', '', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, ''),
(33, 'A', 'b12', 'SP', '', 3, '3.0 24V GASOLINA TURBO S PDK', 1, 698168.62, 'N', 0, 2024, 2025, 'abarth', 'abarth', '0', 6, NULL, NULL, 4, 5, 'DAD8D81', '2025-10-17 08:29:44', 3, '4', '1', 'V', 'L', 'A', 'V', '3', '', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, ''),
(34, 'A', 'seu', NULL, NULL, 4, 'punto', NULL, 100000.00, 'S', 41, 2023, 2023, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'AAA1A11', '2025-10-26 20:30:29', 4, '3', '2', 'F', 'L', 'A', 'V', '3', 'A', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, ''),
(35, 'A', 'b12', NULL, NULL, 4, 'punto', NULL, 5151511.53, 'N', 0, 2022, 2022, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'DAD2C41', '2025-10-26 21:02:09', 4, '2', '3', 'F', '0', 'D', 'T', '4', '', '1', 'vinicius@gmail.com', '(12) 98827-3730', 0, ''),
(36, 'A', 'b12', NULL, NULL, 2, 'punto', NULL, 52341.42, 'N', 0, 2024, 2024, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'ART4G32', '2025-10-26 21:09:47', 1, '2', '2', 'V', 'L', 'A', 'T', '3', '', '1', 'vinicius@gmail.com', '(11) 11111-1111', 0, ''),
(37, 'A', 'afda', NULL, NULL, 2, '3.9 V8 TURBO GASOLINA F1-DCT', NULL, 5843515.16, 'N', 0, 2024, 2024, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'TQF2A44', '2025-10-26 21:15:11', 2, '1', '1', 'V', '0', 'D', 'V', '4', '', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, ''),
(42, 'A', 'b12', NULL, NULL, 10, '3.9 V8 TURBO GASOLINA F1-DCT', NULL, 42141.42, 'N', 0, 2023, 2023, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'AAA1A12', '2025-10-28 07:02:34', 2, '1', '1', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, ''),
(43, 'A', 'amg gt', 'SP', '', 40, '4.0 V8 TURBO GASOLINA R 7G-DCT', 1, 1600000.00, 'S', 15000, 2017, 2018, 'abarth', 'abarth', '0', 6, NULL, NULL, 4, 5, 'AAA1111', '2025-10-28 19:29:33', 5, '1', '2', 'F', '0', 'D', 'D', '4', 'O', '0', 'kelwin@gmail.com', '(11) 11111-1111', 0, ''),
(44, 'A', '488 spider', NULL, NULL, 2, 'punto', NULL, 4151.51, 'N', 0, 2024, 2024, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'DRT1T11', '2025-10-28 19:42:26', 2, '1', '0', 'F', '0', 'D', 'V', '4', '', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, 'rqr1aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrocerias`
--

DROP TABLE IF EXISTS `carrocerias`;
CREATE TABLE IF NOT EXISTS `carrocerias` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrocerias`
--

INSERT INTO `carrocerias` (`id`, `nome`) VALUES
(1, 'Hatchback'),
(2, 'Sedan'),
(3, 'SUV'),
(4, 'Coupé'),
(5, 'Perua'),
(6, 'Van'),
(7, 'Minivan'),
(8, 'Picape'),
(9, 'Conversível');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conversas`
--

DROP TABLE IF EXISTS `conversas`;
CREATE TABLE IF NOT EXISTS `conversas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comprador_id` int(11) NOT NULL,
  `vendedor_id` int(11) NOT NULL,
  `anuncio_id` int(11) NOT NULL,
  `ultima_mensagem` text DEFAULT NULL,
  `data_ultima_mensagem` datetime DEFAULT current_timestamp(),
  `nao_lidas_comprador` int(11) DEFAULT 0,
  `nao_lidas_vendedor` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversa_unica` (`comprador_id`,`vendedor_id`,`anuncio_id`),
  KEY `para_id` (`vendedor_id`),
  KEY `anuncio_id` (`anuncio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conversas`
--

INSERT INTO `conversas` (`id`, `comprador_id`, `vendedor_id`, `anuncio_id`, `ultima_mensagem`, `data_ultima_mensagem`, `nao_lidas_comprador`, `nao_lidas_vendedor`) VALUES
(1, 6, 7, 29, 'd boas', '2025-10-21 18:25:26', 0, 0),
(3, 7, 6, 28, 'blz', '2025-10-21 18:25:35', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cores`
--

DROP TABLE IF EXISTS `cores`;
CREATE TABLE IF NOT EXISTS `cores` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cores`
--

INSERT INTO `cores` (`id`, `nome`) VALUES
(1, 'Branco'),
(2, 'Preto'),
(3, 'Vermelho'),
(4, 'Azul'),
(5, 'Cinza'),
(6, 'Prata'),
(7, 'Vinho'),
(8, 'Marrom'),
(9, 'Laranja'),
(10, 'Amarelo'),
(11, 'Dourado'),
(12, 'Verde'),
(13, 'Bege');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estados`
--

DROP TABLE IF EXISTS `estados`;
CREATE TABLE IF NOT EXISTS `estados` (
  `uf` char(2) NOT NULL,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`uf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estados`
--

INSERT INTO `estados` (`uf`, `nome`) VALUES
('AC', 'Acre'),
('AL', 'Alagoas'),
('AM', 'Amazonas'),
('AP', 'Amapá'),
('BA', 'Bahia'),
('CE', 'Ceará'),
('DF', 'Distrito Federal'),
('ES', 'Espírito Santo'),
('GO', 'Goiás'),
('MA', 'Maranhão'),
('MG', 'Minas Gerais'),
('MS', 'Mato Grosso do Sul'),
('MT', 'Mato Grosso'),
('PA', 'Pará'),
('PB', 'Paraíba'),
('PE', 'Pernambuco'),
('PI', 'Piauí'),
('PR', 'Paraná'),
('RJ', 'Rio de Janeiro'),
('RN', 'Rio Grande do Norte'),
('RO', 'Rondônia'),
('RR', 'Roraima'),
('RS', 'Rio Grande do Sul'),
('SC', 'Santa Catarina'),
('SE', 'Sergipe'),
('SP', 'São Paulo'),
('TO', 'Tocantins');

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `anuncio_id` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`,`anuncio_id`),
  KEY `anuncio_id` (`anuncio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `favoritos`
--

INSERT INTO `favoritos` (`id`, `usuario_id`, `anuncio_id`, `data_criacao`) VALUES
(64, 6, 37, '2025-10-28 22:00:04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotos_carros`
--

DROP TABLE IF EXISTS `fotos_carros`;
CREATE TABLE IF NOT EXISTS `fotos_carros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carro_id` int(11) DEFAULT NULL,
  `caminho_foto` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `carro_id` (`carro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fotos_carros`
--

INSERT INTO `fotos_carros` (`id`, `carro_id`, `caminho_foto`, `ordem`) VALUES
(1, 36, '1761523775_747ca926b1fc.png', 0),
(2, 36, '1761523778_d8973268f6ce.png', 0),
(3, 36, '1761523781_23336514c342.png', 0),
(4, 37, '1761524098_21f4fb805b1f.png', 0),
(5, 37, '1761524098_09bcf72e5408.png', 1),
(6, 37, '1761524098_8600fec95fac.png', 2),
(7, 42, '1761645754_55c028bea0d7.png', 0),
(8, 42, '1761645754_56b6797555bb.png', 1),
(9, 42, '1761645754_44a277fc2847.png', 2),
(10, 42, '1761645754_d9393f7f9b0b.png', 3),
(11, 42, '1761645754_6794bc72a56c.png', 4),
(12, 43, '1761690573_ae252a69526c.webp', 0),
(13, 43, '1761690573_f55203ba4914.webp', 1),
(14, 43, '1761690573_c9552d782fb6.webp', 2),
(15, 43, '1761690573_c821c908180e.webp', 3),
(16, 43, '1761690573_e2ab9af3eac9.webp', 4),
(17, 43, '1761690573_5ae0f7ade9a6.webp', 5),
(18, 43, '1761690573_ae7047cba327.webp', 6),
(19, 43, '1761690573_e3c36406e44a.webp', 7),
(20, 43, '1761690573_fe227dc15d95.webp', 8),
(21, 43, '1761690573_59f6c451e34b.webp', 9),
(22, 43, '1761690573_bc735cd41e0c.webp', 10),
(23, 43, '1761690573_07d0e6d354bc.webp', 11),
(24, 43, '1761690573_192a66948c5e.webp', 12),
(25, 43, '1761690573_58a31b6b08ef.webp', 13),
(26, 44, '1761691346_ac9e2e567328.png', 0),
(27, 44, '1761691346_3b3b72157e9a.png', 1),
(28, 44, '1761691346_ce10147511a8.png', 2),
(29, 44, '1761691346_2b8f2b7b658f.png', 3),
(30, 44, '1761691346_6231e04de7cd.png', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `lojas`
--

DROP TABLE IF EXISTS `lojas`;
CREATE TABLE IF NOT EXISTS `lojas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `razao_social` varchar(100) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `inscricao_estadual` varchar(30) DEFAULT NULL,
  `endereco` varchar(120) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `seguidores` int(9) NOT NULL DEFAULT 0,
  `cep` varchar(9) DEFAULT NULL,
  `bairro` varchar(60) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `telefone_fixo` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) NOT NULL,
  `email_corporativo` varchar(100) NOT NULL,
  `site` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `capa` varchar(255) DEFAULT NULL,
  `descricao_loja` text DEFAULT NULL,
  `hora_abre` time DEFAULT NULL,
  `hora_fecha` time DEFAULT NULL,
  `dias_funcionamento` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lojas`
--

INSERT INTO `lojas` (`id`, `nome`, `razao_social`, `cnpj`, `inscricao_estadual`, `endereco`, `numero`, `seguidores`, `cep`, `bairro`, `cidade`, `estado`, `telefone_fixo`, `whatsapp`, `email_corporativo`, `site`, `instagram`, `facebook`, `logo`, `capa`, `descricao_loja`, `hora_abre`, `hora_fecha`, `dias_funcionamento`, `created_at`) VALUES
(2, 'Kelwin', '', '', '', '', '', 0, '', '', '', '', '', '123', 'a@a.a', '', '', '', '', '', 'A', '21:50:00', '21:50:00', 'Domingo,Segunda', '2025-10-09 00:50:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `marcas`
--

DROP TABLE IF EXISTS `marcas`;
CREATE TABLE IF NOT EXISTS `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(50) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `marcas`
--

INSERT INTO `marcas` (`id`, `value`, `nome`) VALUES
(1, 'abarth', 'Abarth'),
(2, 'alfa', 'Alfa Romeo'),
(3, 'aston', 'Aston Martin'),
(4, 'audi', 'Audi'),
(5, 'bentley', 'Bentley'),
(6, 'bmw', 'BMW'),
(7, 'bugatti', 'Bugatti'),
(8, 'byd', 'BYD'),
(9, 'cadillac', 'Cadillac'),
(10, 'chevrolet', 'Chevrolet'),
(11, 'chrysler', 'Chrysler'),
(12, 'citroen', 'Citroën'),
(13, 'corvette', 'Corvette'),
(14, 'dacia', 'Dacia'),
(15, 'dodge', 'Dodge'),
(16, 'ferrari', 'Ferrari'),
(17, 'fiat', 'Fiat'),
(18, 'ford', 'Ford'),
(19, 'genesis', 'Genesis'),
(20, 'gmc', 'GMC'),
(21, 'gwm', 'GWM'),
(22, 'honda', 'Honda'),
(23, 'hummer', 'Hummer'),
(24, 'hyundai', 'Hyundai'),
(25, 'infiniti', 'Infiniti'),
(26, 'jaecoo', 'JAECOO'),
(27, 'jaguar', 'Jaguar'),
(28, 'jeep', 'Jeep'),
(29, 'kia', 'Kia'),
(30, 'koenigsegg', 'Koenigsegg'),
(31, 'lamborghini', 'Lamborghini'),
(32, 'lancia', 'Lancia'),
(33, 'land', 'Land Rover'),
(34, 'lexus', 'Lexus'),
(35, 'lincoln', 'Lincoln'),
(36, 'lotus', 'Lotus'),
(37, 'maserati', 'Maserati'),
(38, 'mazda', 'Mazda'),
(39, 'mclaren', 'McLaren'),
(40, 'mercedes', 'Mercedes-Benz'),
(41, 'mini', 'MINI'),
(42, 'mitsubishi', 'Mitsubishi'),
(43, 'nissan', 'Nissan'),
(44, 'omoda', 'Omoda'),
(45, 'opel', 'Opel'),
(46, 'peugeot', 'Peugeot'),
(47, 'porsche', 'Porsche'),
(48, 'ram', 'Ram'),
(49, 'renault', 'Renault'),
(50, 'rolls', 'Rolls-Royce'),
(51, 'skoda', 'Skoda'),
(52, 'smart', 'Smart'),
(53, 'subaru', 'Subaru'),
(54, 'suzuki', 'Suzuki'),
(55, 'tesla', 'Tesla'),
(56, 'toyota', 'Toyota'),
(57, 'volkswagen', 'Volkswagen'),
(58, 'volvo', 'Volvo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_chat`
--

DROP TABLE IF EXISTS `mensagens_chat`;
CREATE TABLE IF NOT EXISTS `mensagens_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `de_usuario` int(11) NOT NULL,
  `para_usuario` int(11) NOT NULL,
  `anuncio` int(11) NOT NULL,
  `texto` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0,
  `apagada_de` tinyint(1) NOT NULL DEFAULT 0,
  `double_apagada_de` tinyint(1) NOT NULL DEFAULT 0,
  `apagada_para` tinyint(1) NOT NULL DEFAULT 0,
  `double_apagada_para` tinyint(1) NOT NULL DEFAULT 0,
  `resposta_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `de_usuario_id` (`de_usuario`),
  KEY `para_usuario_id` (`para_usuario`),
  KEY `anuncio_id` (`anuncio`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens_chat`
--

INSERT INTO `mensagens_chat` (`id`, `de_usuario`, `para_usuario`, `anuncio`, `texto`, `data_envio`, `lida`, `apagada_de`, `double_apagada_de`, `apagada_para`, `double_apagada_para`, `resposta_id`) VALUES
(1, 6, 7, 29, 'Bom dia', '2025-10-15 08:52:38', 1, 1, 0, 1, 0, 0),
(2, 7, 6, 29, 'eba', '2025-10-15 09:14:22', 1, 0, 0, 1, 1, 0),
(3, 6, 7, 29, 'teste', '2025-10-15 13:41:07', 1, 0, 0, 0, 0, 0),
(4, 6, 7, 29, 'apu', '2025-10-15 13:41:11', 1, 0, 0, 0, 0, 0),
(5, 6, 7, 29, 'cszin bora?', '2025-10-15 13:41:18', 1, 0, 0, 0, 0, 0),
(6, 7, 6, 29, 'opa meu amigo', '2025-10-15 13:58:35', 1, 0, 0, 0, 0, 0),
(7, 7, 6, 29, 'vc quer marreta do thor ou vc quer o peitinho do galego', '2025-10-18 14:24:26', 1, 0, 0, 0, 0, 0),
(8, 6, 7, 29, 'teste', '2025-10-18 14:25:02', 1, 0, 0, 0, 0, 0),
(9, 6, 7, 29, 'tempo real', '2025-10-18 14:25:11', 1, 0, 0, 0, 0, 0),
(10, 6, 7, 29, 'opa', '2025-10-18 14:25:37', 1, 0, 0, 0, 0, 0),
(11, 6, 7, 29, 'teste', '2025-10-18 14:26:54', 1, 0, 0, 0, 0, 0),
(12, 6, 7, 29, 'opa', '2025-10-18 14:34:18', 1, 0, 0, 0, 0, 0),
(13, 7, 6, 29, 'opa', '2025-10-18 14:35:47', 1, 0, 0, 0, 0, 0),
(14, 6, 7, 29, 'opa', '2025-10-18 14:35:50', 1, 0, 0, 0, 0, 0),
(15, 6, 7, 29, 'e trem bão', '2025-10-18 14:42:31', 1, 0, 0, 0, 0, 0),
(16, 6, 7, 29, 'teste', '2025-10-18 16:17:34', 1, 0, 0, 0, 0, 0),
(17, 6, 7, 29, 'teste 2', '2025-10-18 16:18:01', 1, 0, 0, 0, 0, 0),
(18, 6, 7, 29, 'E ai mano', '2025-10-18 16:25:58', 1, 0, 0, 0, 0, 0),
(19, 6, 7, 29, 'Blz', '2025-10-18 16:26:02', 1, 0, 0, 0, 0, 0),
(20, 6, 7, 29, 'Bom', '2025-10-18 16:26:16', 1, 0, 0, 0, 0, 0),
(21, 6, 7, 29, 'Joia', '2025-10-18 16:26:25', 1, 0, 0, 0, 0, 0),
(22, 6, 7, 29, 'kk', '2025-10-18 19:45:43', 1, 0, 0, 0, 0, 0),
(23, 6, 7, 29, 'eae', '2025-10-18 19:45:49', 1, 1, 0, 1, 0, 0),
(24, 6, 7, 29, 'oi', '2025-10-18 19:48:16', 1, 0, 0, 0, 0, 0),
(25, 7, 6, 29, 'opa', '2025-10-18 19:49:03', 1, 0, 0, 0, 0, 0),
(26, 6, 7, 29, 'teste', '2025-10-18 19:49:06', 1, 0, 0, 0, 0, 0),
(27, 6, 7, 29, 'opa', '2025-10-20 21:34:55', 1, 0, 0, 0, 0, 0),
(28, 7, 6, 29, 'EAE', '2025-10-20 21:35:20', 1, 0, 0, 0, 0, 0),
(29, 6, 7, 29, 'BLZ', '2025-10-20 21:35:27', 1, 0, 0, 0, 0, 0),
(30, 7, 6, 28, 'OPA', '2025-10-21 18:02:51', 1, 0, 0, 0, 0, 0),
(31, 7, 6, 28, 'de boas', '2025-10-21 18:09:13', 1, 0, 0, 0, 0, 0),
(32, 7, 6, 28, 'opa', '2025-10-21 18:24:02', 1, 0, 0, 0, 0, 0),
(33, 6, 7, 28, 'eae', '2025-10-21 18:24:54', 1, 0, 0, 0, 0, 0),
(34, 7, 6, 29, 'blz', '2025-10-21 18:25:01', 1, 0, 0, 0, 0, 0),
(35, 6, 7, 29, 'tranquilo', '2025-10-21 18:25:12', 1, 0, 0, 0, 0, 0),
(36, 7, 6, 29, 'claro', '2025-10-21 18:25:18', 1, 0, 0, 0, 0, 0),
(37, 7, 6, 29, 'd boas', '2025-10-21 18:25:26', 1, 0, 0, 0, 0, 0),
(38, 6, 7, 28, 'blz', '2025-10-21 18:25:35', 1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

DROP TABLE IF EXISTS `reservas`;
CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_veiculo` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `preferencia_contato` enum('telefone','whatsapp','email') DEFAULT 'telefone',
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `acompanhantes_qtd` tinyint(3) UNSIGNED DEFAULT 0,
  `estado` char(2) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `rua` varchar(150) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('pendente','confirmada','cancelada','realizada') DEFAULT 'pendente',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_veiculo` (`id_veiculo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reservas`
--

INSERT INTO `reservas` (`id`, `id_veiculo`, `nome`, `telefone`, `email`, `preferencia_contato`, `data`, `hora`, `acompanhantes_qtd`, `estado`, `cidade`, `bairro`, `rua`, `numero`, `complemento`, `cep`, `observacoes`, `status`, `criado_em`, `atualizado_em`) VALUES
(6, 28, 'Cleitinho', '21421521414', 'kelwin@gmail.com', 'whatsapp', '2025-10-24', '05:26:00', 1, 'SP', 'Teste', 'teste', 'etes', '4512', 'teste', '24115-151', 'dasfaf', 'pendente', '2025-10-23 21:24:33', '2025-10-25 19:51:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `sobrenome` varchar(30) NOT NULL,
  `telefone` bigint(15) DEFAULT NULL,
  `cpf` char(11) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(256) NOT NULL,
  `data_criacao_conta` datetime NOT NULL DEFAULT current_timestamp(),
  `data_nascimento` date DEFAULT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'img/user.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `telefone` (`telefone`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `sobrenome`, `telefone`, `cpf`, `email`, `senha`, `data_criacao_conta`, `data_nascimento`, `avatar`) VALUES
(6, 'Kelwin', 'Silva', 11, '12332131312', 'kelwin@gmail.com', '1', '2025-09-20 20:44:02', '2025-10-14', 'img/usuarios/avatares/usuario_6_1761690604.jpg'),
(7, 'Vinicius', 'Souza', NULL, NULL, 'vinicius@gmail.com', '1', '2025-10-02 22:40:54', NULL, '');

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `anuncios_carros`
--
ALTER TABLE `anuncios_carros`
  ADD CONSTRAINT `carroceria_fk` FOREIGN KEY (`carroceria`) REFERENCES `carrocerias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cor_fk` FOREIGN KEY (`cor`) REFERENCES `cores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estado_fk` FOREIGN KEY (`estado_local`) REFERENCES `estados` (`uf`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `marca_fk` FOREIGN KEY (`marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vendedor_fk` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `conversas`
--
ALTER TABLE `conversas`
  ADD CONSTRAINT `conversas_ibfk_1` FOREIGN KEY (`comprador_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `conversas_ibfk_2` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `conversas_ibfk_3` FOREIGN KEY (`anuncio_id`) REFERENCES `anuncios_carros` (`id`);

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`anuncio_id`) REFERENCES `anuncios_carros` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `fotos_carros`
--
ALTER TABLE `fotos_carros`
  ADD CONSTRAINT `fotos_carros_ibfk_1` FOREIGN KEY (`carro_id`) REFERENCES `anuncios_carros` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `mensagens_chat`
--
ALTER TABLE `mensagens_chat`
  ADD CONSTRAINT `mensagens_chat_ibfk_1` FOREIGN KEY (`de_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `mensagens_chat_ibfk_2` FOREIGN KEY (`para_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `mensagens_chat_ibfk_3` FOREIGN KEY (`anuncio`) REFERENCES `anuncios_carros` (`id`);

--
-- Restrições para tabelas `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_veiculo`) REFERENCES `anuncios_carros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
