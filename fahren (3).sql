-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/11/2025 às 02:21
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

CREATE TABLE `anuncios_carros` (
  `id` int(12) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'A',
  `modelo` varchar(75) DEFAULT NULL,
  `estado_local` char(2) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `marca` int(2) DEFAULT NULL,
  `versao` varchar(150) DEFAULT NULL,
  `carroceria` int(2) DEFAULT NULL,
  `preco` int(9) NOT NULL DEFAULT 0,
  `condicao` char(1) NOT NULL DEFAULT 'U',
  `quilometragem` int(10) DEFAULT NULL,
  `ano_fabricacao` int(4) DEFAULT NULL,
  `ano_modelo` int(4) DEFAULT NULL,
  `propulsao` varchar(10) DEFAULT NULL,
  `combustivel` varchar(20) DEFAULT NULL,
  `cambio` char(1) DEFAULT 'M' COMMENT 'A=Automático, M=Manual',
  `blindagem` char(1) DEFAULT '0',
  `id_vendedor` int(10) DEFAULT NULL,
  `tipo_vendedor` char(1) NOT NULL DEFAULT '0',
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
  `email` varchar(256) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `garantia` int(2) NOT NULL,
  `descricao` text DEFAULT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `anuncios_carros`
--

INSERT INTO `anuncios_carros` (`id`, `ativo`, `modelo`, `estado_local`, `cidade`, `marca`, `versao`, `carroceria`, `preco`, `condicao`, `quilometragem`, `ano_fabricacao`, `ano_modelo`, `propulsao`, `combustivel`, `cambio`, `blindagem`, `id_vendedor`, `tipo_vendedor`, `portas_qtd`, `assentos_qtd`, `placa`, `data_criacao`, `cor`, `quant_proprietario`, `revisao`, `vistoria`, `sinistro`, `ipva`, `licenciamento`, `estado_conservacao`, `uso_anterior`, `aceita_troca`, `email`, `telefone`, `garantia`, `descricao`, `clicks`) VALUES
(43, 'A', 'amg gt', 'DF', 'Brasília', 40, '4.0 V8 TURBO GASOLINA R 7G-DCT', 3, 1600000, 'S', 15000, 2017, 2018, 'combustao', 'Gasolina', 'M', '0', 6, '0', 4, 5, 'AAA1111', '2025-10-28 19:29:33', 5, '1', '2', 'F', '0', 'D', 'D', '4', 'O', '0', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 1),
(45, 'A', 'mustang', 'MS', 'Bela Vista', 18, '4.0 COUPÉ V6 12V GASOLINA 2P AUTOMÁTICO', 8, 249900, 'U', 113000, 2009, 2010, 'combustao', 'Gasolina', 'M', '0', 6, '0', 4, 5, 'FGR8A41', '2025-10-29 20:11:49', 2, '3', '1', 'F', 'L', 'D', 'D', '3', 'A', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 4),
(46, 'A', '911', 'MS', 'Anaurilândia', 47, '3.0 24V H6 GASOLINA CARRERA S PDK', 8, 970000, 'S', 4000, 2022, 2023, 'combustao', 'Gasolina', 'A', '0', 7, '0', 2, 2, 'FQA9Q76', '2025-10-29 20:41:35', 10, '1', '2', 'F', '0', 'D', 'D', '4', 'P', '1', 'kelwin@gmail.com', '(11) 11111-1111', 3, '', 2),
(47, 'A', 'rs5', 'PE', 'Água Preta', 4, '2.9 V6 TFSI GASOLINA SPORTBACK COMPETITION PLUS QUATTRO TIPTRONIC', 1, 620000, 'N', 0, 2023, 2024, 'combustao', 'Gasolina', 'A', '0', 7, '0', 4, 5, 'QTT1F11', '2025-10-30 21:16:39', 2, '1', '1', 'F', '0', 'D', 'D', '4', '', '0', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 1),
(48, 'A', 'camry', 'MA', 'Açailândia', 56, '2.5 VVT-IE HYBRID XLE eCVT', 1, 259900, 'U', 108000, 2022, 2023, 'combustao', 'Flex', 'A', '0', 7, '0', 4, 5, 'FAF9A96', '2025-10-30 21:24:24', 2, '2', '2', 'F', 'L', 'D', 'D', '3', 'P', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 3),
(49, 'A', 'c 63 amg', 'TO', 'Palmas', 40, '2.0 TURBO PHEV S E PERFORMANCE F1 EDITION 4MATIC+ SPEEDSHIFT\n', 3, 869000, 'N', 0, 2023, 2024, 'abarth', 'abarth', 'M', '0', 6, '0', 4, 5, 'HWS9G83', '2025-11-11 14:43:56', 1, '1', '2', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, '', 0),
(50, 'A', '320i', 'PB', 'João Pessoa', 6, '2.0 16V TURBO FLEX SPORT GP AUTOMÁTICO', 1, 215900, 'S', 65000, 2021, 2022, 'combustao', 'Flex', 'A', '0', 6, '0', 4, 5, 'HIU7S68', '2025-11-11 16:27:21', 1, '2', '1', 'F', '0', 'D', 'D', '3', 'P', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, '', 1),
(56, 'A', 'm5', 'GO', 'Abadia de Goiás', 6, '4.4 V8 TWINPOWER GASOLINA COMPETITION M XDRIVE STEPTRONIC', 3, 700000, 'N', 0, 2021, 2022, 'abarth', 'abarth', 'M', '0', 6, '1', 4, 5, 'GHA0G75', '2025-11-11 19:58:03', 2, '2', '1', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, '', 1),
(57, 'A', '911', 'MG', 'Belo Horizonte', 47, '3.0 24V H6 GASOLINA CARRERA 4 GTS CABRIOLET PDK', 10, 1160000, 'N', 0, 2024, 2025, 'combustao', 'Gasolina', 'M', '0', 6, '0', 4, 5, 'UYD9F88', '2025-11-12 20:43:47', 1, '1', '4', 'F', '0', 'D', 'D', '4', '', '0', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 1),
(58, 'A', 'v60', 'CE', 'Aiuaba', 58, '2.0 T5 GASOLINA MOMENTUM GEARTRONIC', 5, 149890, 'U', 82000, 2019, 2020, 'combustao', 'Diesel', 'A', '0', 7, '0', 4, 5, 'FAF8A76', '2025-11-16 22:56:25', 5, '2', '1', 'F', '0', 'D', 'D', '4', 'P', '1', 'vinicius@gmail.com', '(11) 11111-1111', 0, '', 1),
(59, 'A', 'rs e-tron gt', 'ES', 'Águia Branca', 4, 'ELÉTRICO QUATTRO', 1, 484900, 'N', 0, 2021, 2022, 'eletrico', 'Elétrico', 'A', '0', 7, '0', 4, 5, 'UBW7G12', '2025-11-16 23:09:59', 6, '1', '1', 'F', '0', 'D', 'D', '4', '', '0', 'vinicius@gmail.com', '(11) 11111-1111', 0, '', 1),
(60, 'A', 'q5', 'BA', 'Abaré', 4, '2.0 55 TFSIE PHEV PERFORMANCE BLACK QUATTRO S TRONIC', 2, 299900, 'S', 30000, 2023, 2024, 'hibrido', 'HEV', 'A', '0', 7, '0', 4, 5, 'RUU9R90', '2025-11-16 23:20:27', 5, '1', '1', 'F', '0', 'D', 'D', '4', 'P', '1', 'vinicius@gmail.com', '(11) 11111-1111', 0, '', 0),
(61, 'A', 'cayenne', 'GO', 'Goiânia', 47, '3.0 V6 E-HYBRID AWD TIPTRONIC S', 2, 759900, 'N', 0, 2022, 2023, 'hibrido', 'HEV', 'A', '1', 7, '0', 4, 5, 'GYG9A99', '2025-11-17 19:55:18', 2, '1', '1', 'F', '0', 'D', 'D', '4', '', '0', 'vinicius@gmail.com', '(11) 11111-1111', 0, '', 0),
(63, 'A', 'pulse', 'SC', 'Agronômica', 1, '1.3 TURBO 270 FLEX ABARTH AT6', 2, 134900, 'N', 0, 2024, 2025, 'combustao', 'Flex', 'A', '0', 6, '0', 4, 5, 'FAF6A69', '2025-11-20 20:25:50', 2, '1', '1', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(11) 11111-1111', 1, '', 1),
(64, 'A', 'giulietta', 'SP', 'Caçapava', 2, '1.3 SPIDER GASOLINA 2P MANUAL', 10, 680000, 'U', 248567, 1968, 1969, 'combustao', 'Gasolina', 'M', '0', 6, '0', 2, 2, 'FYI7F09', '2025-11-20 20:41:44', 1, '5', '5', 'F', 'L', 'I', 'D', '3', 'P', '0', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 2),
(65, 'A', 'vanquish', 'PA', 'Maracanã', 3, '5.2 V12 TURBO GASOLINA COUPÉ AUTOMÁTICO', 8, 5850000, 'N', 0, 2024, 2025, 'combustao', 'Álcool', 'A', '0', 6, '0', 2, 2, 'FIA9U97', '2025-11-20 20:45:50', 3, '1', '2', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 1),
(66, 'A', 'passat', 'RJ', 'Belford Roxo', 57, '2.0 16V TSI BLUEMOTION GASOLINA HIGHLINE 4P DSG', 1, 179900, 'U', 123264, 2018, 2019, 'combustao', 'Gasolina', 'A', '0', 6, '0', 4, 5, 'OIG6G78', '2025-11-20 20:49:58', 4, '2', '2', 'F', '0', 'D', 'D', '3', 'P', '0', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 2),
(67, 'A', 'flying spur', 'PI', 'Caxingó', 5, '2.9 V6 HYBRID AUTOMÁTICO', 1, 2299000, 'N', 0, 2022, 2023, 'hibrido', 'HEV', 'A', '0', 6, '0', 4, 5, 'QRQ7Y80', '2025-11-21 11:35:47', 2, '1', '0', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(11) 11111-1111', 3, '', 1),
(71, 'A', 'shark', 'MS', 'Bodoquena', 8, '1.5 TURBO PHEV GS AWD AUTOMÁTICO', 4, 465000, 'N', 0, 2024, 2025, 'hibrido', 'HEV', 'A', '0', 6, '1', 4, 5, 'GJA9G90', '2025-11-23 19:41:12', 1, '1', '1', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, '', 1),
(72, 'A', 'kardian', 'MS', 'Bodoquena', 49, '1.0 TCE FLEX PREMIÉRE EDITION EDC', 2, 116900, 'N', 0, 2024, 2025, 'combustao', 'Álcool', 'M', '0', 6, '1', 4, 5, 'YTF6799', '2025-11-24 17:28:15', 1, '1', '0', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, '', 1),
(73, 'A', 'civic', 'MS', 'Bodoquena', 22, '2.0 DI VTEC TURBO GASOLINA TYPE R MANUAL', 3, 434900, 'N', 0, 2024, 2024, 'combustao', 'Gasolina', 'M', '0', 6, '1', 4, 5, 'UFH8F89', '2025-11-24 17:35:01', 4, '1', '1', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, '', 1),
(74, 'A', 'camaro', 'SP', 'São Paulo', 10, '6.2 V8 GASOLINA SS COLLECTION EDITION CONVERSÍVEL AUTOMÁTICO', 8, 487500, 'N', 0, 2023, 2024, 'combustao', 'Gasolina', 'A', '0', 6, '0', 2, 2, 'FAF9A89', '2025-11-24 17:39:50', 2, '1', '3', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 1),
(75, 'A', 'niro', 'MS', 'Bodoquena', 29, '1.6 GDI HEV SX PRESTIGE DCT', 2, 239900, 'N', 0, 2024, 2025, 'hibrido', 'HEV', 'A', '0', 6, '1', 4, 5, 'HGI0A99', '2025-11-24 17:59:06', 1, '1', '1', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(12) 98827-3730', 0, '', 1),
(76, 'A', 'mustang', 'SC', 'Brusque', 18, '5.0 FASTBACK V8 GASOLINA 2P AUTOMÁTICO', 7, 600000, 'U', 335600, 1969, 1969, 'combustao', 'Gasolina', 'M', '0', 6, '0', 2, 2, 'GHY7Y78', '2025-11-24 21:01:12', 6, '3', '5', 'F', '0', 'D', 'D', '4', 'P', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0, '', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrocerias`
--

CREATE TABLE `carrocerias` (
  `id` int(2) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrocerias`
--

INSERT INTO `carrocerias` (`id`, `nome`) VALUES
(1, 'Sedan'),
(2, 'SUV'),
(3, 'Hatchback'),
(4, 'Picape'),
(5, 'Perua'),
(6, 'Minivan'),
(7, 'Fastback'),
(8, 'Coupé'),
(9, 'Van'),
(10, 'Conversível');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conversas`
--

CREATE TABLE `conversas` (
  `id` int(11) NOT NULL,
  `comprador_id` int(11) NOT NULL,
  `vendedor_id` int(11) NOT NULL,
  `anuncio_id` int(11) NOT NULL,
  `ultima_mensagem` text DEFAULT NULL,
  `data_ultima_mensagem` datetime DEFAULT current_timestamp(),
  `nao_lidas_comprador` int(11) DEFAULT 0,
  `nao_lidas_vendedor` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `conversas`
--

INSERT INTO `conversas` (`id`, `comprador_id`, `vendedor_id`, `anuncio_id`, `ultima_mensagem`, `data_ultima_mensagem`, `nao_lidas_comprador`, `nao_lidas_vendedor`) VALUES
(92, 7, 6, 45, 'Daora', '2025-11-24 21:19:46', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cores`
--

CREATE TABLE `cores` (
  `id` int(2) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estrutura para tabela `equipe`
--

CREATE TABLE `equipe` (
  `id` int(11) NOT NULL,
  `loja_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `convidado_por` int(11) DEFAULT NULL,
  `status` char(1) DEFAULT 'P',
  `pode_editar_anuncio` tinyint(1) DEFAULT 0,
  `pode_responder_mensagem` tinyint(1) DEFAULT 0,
  `pode_editar_loja` tinyint(1) DEFAULT 0,
  `pode_adicionar_membros` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `equipe`
--

INSERT INTO `equipe` (`id`, `loja_id`, `usuario_id`, `convidado_por`, `status`, `pode_editar_anuncio`, `pode_responder_mensagem`, `pode_editar_loja`, `pode_adicionar_membros`, `created_at`, `updated_at`) VALUES
(3, 6, 6, NULL, 'A', 1, 1, 1, 1, '2025-11-22 17:14:21', NULL),
(6, 6, 7, 6, 'A', 1, 1, 0, 0, '2025-11-23 20:15:33', '2025-11-23 20:43:38');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estados`
--

CREATE TABLE `estados` (
  `uf` char(2) NOT NULL,
  `nome` varchar(30) NOT NULL
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

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `anuncio_id` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotos_carros`
--

CREATE TABLE `fotos_carros` (
  `id` int(11) NOT NULL,
  `carro_id` int(11) DEFAULT NULL,
  `caminho_foto` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fotos_carros`
--

INSERT INTO `fotos_carros` (`id`, `carro_id`, `caminho_foto`, `ordem`) VALUES
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
(32, 45, '1761779509_361d7a399c0f.jpg', 0),
(33, 45, '1761779509_00ad447789ea.jpg', 1),
(34, 45, '1761779509_0cfcfdec6ed1.jpg', 2),
(35, 45, '1761779509_85de4f355ef0.jpg', 3),
(36, 45, '1761779509_f52a0fa7f00e.jpg', 4),
(37, 45, '1761779509_cfab0f143407.jpg', 5),
(38, 45, '1761779509_e0038320d1dd.jpg', 6),
(39, 45, '1761779509_a40c812ae52f.jpg', 7),
(40, 45, '1761779509_80c99336cc89.jpg', 8),
(41, 45, '1761779509_f542a08a6c29.jpg', 9),
(42, 45, '1761779509_962985839943.jpg', 10),
(43, 45, '1761779509_f8e75559d005.jpg', 11),
(44, 45, '1761779509_4b3d419cb6f8.jpg', 12),
(45, 45, '1761779509_8dbdf3d5f2a9.jpg', 13),
(46, 45, '1761779509_20e982858918.jpg', 14),
(47, 45, '1761779509_7aa47c385726.jpg', 15),
(48, 45, '1761779509_cd273012fc43.jpg', 16),
(49, 46, '1761781295_3604cc9f84fe.jpg', 0),
(50, 46, '1761781295_465968be0294.jpg', 1),
(51, 46, '1761781295_569ed29c6957.jpg', 2),
(52, 46, '1761781295_faaed5d1c29c.jpg', 3),
(53, 46, '1761781295_6aa36add56cd.jpg', 4),
(54, 46, '1761781295_9f5022329b8f.jpg', 5),
(55, 46, '1761781295_eea2e17a2978.jpg', 6),
(56, 46, '1761781295_31a97f658ed1.jpg', 7),
(57, 46, '1761781295_fe76404a9df5.jpg', 8),
(58, 46, '1761781295_492fcb45d13b.jpg', 9),
(59, 46, '1761781295_1521e527e400.jpg', 10),
(60, 46, '1761781295_89c424d7d42c.jpg', 11),
(61, 47, '1761869799_f4fcef6b0c59.jpg', 0),
(62, 47, '1761869799_42059f81199a.jpg', 1),
(63, 47, '1761869799_d737c57a6cd0.jpg', 2),
(64, 47, '1761869799_df00d48299e8.jpg', 3),
(65, 47, '1761869799_d9251555efe8.jpg', 4),
(66, 47, '1761869799_f852e4b2ae27.jpg', 5),
(67, 47, '1761869799_c63c75a06c9e.jpg', 6),
(68, 47, '1761869799_7644f0acfe97.jpg', 7),
(69, 47, '1761869799_dd8ba794df73.jpg', 8),
(70, 47, '1761869799_bdbd91b8fb5b.jpg', 9),
(71, 47, '1761869799_7ef3669f60cb.jpg', 10),
(72, 47, '1761869799_27131e25e8c6.jpg', 11),
(73, 47, '1761869799_4b06175e8e67.jpg', 12),
(74, 48, '1761870264_2e44b198a84f.webp', 0),
(75, 48, '1761870264_8e75f1a12439.webp', 1),
(76, 48, '1761870264_87d0540f909d.webp', 2),
(77, 48, '1761870264_3eaa0cbb742c.webp', 3),
(78, 48, '1761870264_7c648f943767.webp', 4),
(79, 48, '1761870264_dc39eec8a902.webp', 5),
(80, 48, '1761870264_0a18037c0701.webp', 6),
(81, 48, '1761870264_39cc98ea3bad.webp', 7),
(82, 48, '1761870264_e4070a6cc0a0.webp', 8),
(83, 49, '1762883036_40477f52486a.webp', 0),
(84, 49, '1762883036_0aab3c817ad3.webp', 1),
(85, 49, '1762883036_9a23bd17fc3b.webp', 2),
(86, 49, '1762883036_5110253fc674.webp', 3),
(87, 49, '1762883036_d5c7726e9770.webp', 4),
(88, 50, '1762889241_b18b4729712c.jpg', 0),
(89, 50, '1762889241_ddda129e5c2d.jpg', 1),
(90, 50, '1762889241_7e894f305ef7.jpg', 2),
(91, 50, '1762889241_73d304b3f9e0.jpg', 3),
(92, 50, '1762889241_dee398594b03.jpg', 4),
(93, 50, '1762889241_15cf1e80675e.jpg', 5),
(94, 50, '1762889241_f9049ee9fa2c.jpg', 6),
(104, 56, '1762901883_1bc9dbaffbe8.jpg', 0),
(105, 56, '1762901883_e21203352f67.jpg', 1),
(106, 56, '1762901883_81a7b67b62e4.jpg', 2),
(107, 56, '1762901883_5f21286195b7.jpg', 3),
(108, 56, '1762901883_23aece23031b.jpg', 4),
(109, 56, '1762901883_13e3a14e3627.jpg', 5),
(110, 56, '1762901883_357c5922db0e.jpg', 6),
(111, 56, '1762901883_4f6373ed2deb.jpg', 7),
(112, 57, '1762991027_ed6870c2cdee.webp', 0),
(113, 57, '1762991027_25f64aeef703.webp', 1),
(114, 57, '1762991027_b3492cf99121.webp', 2),
(115, 57, '1762991027_04e52e4efdee.webp', 3),
(116, 57, '1762991027_ca7b6764d3ce.webp', 4),
(117, 57, '1762991027_ebd92db5908f.webp', 5),
(118, 57, '1762991027_76c6ee505eba.webp', 6),
(119, 57, '1762991027_9ef07ff5e598.webp', 7),
(120, 57, '1762991027_2f3938de971e.webp', 8),
(121, 57, '1762991027_8188d48f4ee9.webp', 9),
(122, 57, '1762991027_3b2d817cff9c.webp', 10),
(123, 57, '1762991027_d22898f556d7.webp', 11),
(124, 57, '1762991027_67d99d8050d1.webp', 12),
(125, 58, '1763344585_ecd95e3a5005.webp', 0),
(126, 58, '1763344585_f1e4ea8d2b3b.webp', 1),
(127, 58, '1763344585_546b5a1417f5.webp', 2),
(128, 58, '1763344585_554da415f2b3.webp', 3),
(129, 58, '1763344585_ec147c29940c.webp', 4),
(130, 58, '1763344585_75400a7f8e54.webp', 5),
(131, 58, '1763344585_2987f7d1737f.webp', 6),
(132, 59, '1763345399_5563ba3ea6e6.webp', 0),
(133, 59, '1763345399_bc4545b01784.webp', 1),
(134, 59, '1763345399_3e1a55bcbe54.webp', 2),
(135, 59, '1763345399_1b46ef96657e.webp', 3),
(136, 59, '1763345399_aa0f92f8d29c.webp', 4),
(137, 59, '1763345399_aeff584c7bed.webp', 5),
(138, 59, '1763345399_4ec6da73a642.webp', 6),
(139, 59, '1763345399_3fff1c1faaf8.webp', 7),
(140, 60, '1763346027_5fa06235dbd4.jpg', 0),
(141, 60, '1763346027_ff383f2c852d.jpg', 1),
(142, 60, '1763346027_4e233f005c2c.jpg', 2),
(143, 60, '1763346027_79400c89f954.jpg', 3),
(144, 60, '1763346027_6ee0f583442d.jpg', 4),
(145, 60, '1763346027_91bc4a7b7869.jpg', 5),
(146, 60, '1763346027_dc777c142508.jpg', 6),
(147, 60, '1763346027_2690c2e97b45.jpg', 7),
(148, 60, '1763346027_ae696eee13ce.jpg', 8),
(149, 60, '1763346027_b8a89702e4bf.jpg', 9),
(150, 61, '1763420118_c3ed428279a7.webp', 0),
(151, 61, '1763420118_c00c2eeac4ef.webp', 1),
(152, 61, '1763420118_b8731bbb0219.webp', 2),
(153, 61, '1763420118_b4c7b3251448.webp', 3),
(154, 61, '1763420118_6f68a95ccb28.webp', 4),
(155, 61, '1763420118_67b4bf278e1e.webp', 5),
(156, 61, '1763420118_18ca52de4574.webp', 6),
(157, 61, '1763420118_a90c97f87e3e.webp', 7),
(158, 63, '1763681150_f47005976da9.jpg', 0),
(159, 63, '1763681150_07cde8e3dacf.jpg', 1),
(160, 63, '1763681150_a86e860ac981.jpg', 2),
(161, 63, '1763681150_131f780193dc.jpg', 3),
(162, 63, '1763681150_b15d3ab4b415.jpg', 4),
(163, 63, '1763681150_2a3dd7892339.jpg', 5),
(164, 63, '1763681150_968fdfee3cac.jpg', 6),
(165, 64, '1763682104_f3702cbe1c7e.webp', 0),
(166, 64, '1763682104_ccf7a23f477d.webp', 1),
(167, 64, '1763682104_e24695c6dea6.webp', 2),
(168, 64, '1763682104_5902d1dcdec8.webp', 3),
(169, 64, '1763682104_ba56aac6d093.webp', 4),
(170, 64, '1763682104_ce4f7942507a.webp', 5),
(171, 64, '1763682104_1674a7850777.webp', 6),
(172, 64, '1763682104_8ea8947a59b4.webp', 7),
(173, 64, '1763682104_d4da5c8527c8.webp', 8),
(174, 64, '1763682104_4c0ea29e764b.webp', 9),
(175, 64, '1763682104_d766e4c546a2.webp', 10),
(176, 64, '1763682104_dae1a60dd2ac.webp', 11),
(177, 64, '1763682104_232a51ed17e0.webp', 12),
(178, 64, '1763682104_16886e1dbca6.webp', 13),
(179, 65, '1763682350_aaf922ca306b.webp', 0),
(180, 65, '1763682350_0570feb13974.webp', 1),
(181, 65, '1763682350_1f1a2a5aaeea.webp', 2),
(182, 65, '1763682350_ef95eea82cc7.webp', 3),
(183, 65, '1763682350_cb560693cedb.webp', 4),
(184, 65, '1763682350_fa6444a2b74b.webp', 5),
(185, 65, '1763682350_c680ff12bba8.webp', 6),
(186, 65, '1763682350_fa3e403f5309.webp', 7),
(187, 65, '1763682350_526aa54209e0.webp', 8),
(188, 65, '1763682350_e03a695d95c3.webp', 9),
(189, 65, '1763682350_ba3bc2b174e7.webp', 10),
(190, 65, '1763682350_4b8d2e413291.webp', 11),
(191, 65, '1763682350_6e574fd90359.webp', 12),
(192, 65, '1763682350_da7544e88a10.webp', 13),
(193, 65, '1763682350_5c441bbd8662.webp', 14),
(194, 66, '1763682598_94a4cd5346c4.jpg', 0),
(195, 66, '1763682598_04ba4fcfb25c.jpg', 1),
(196, 66, '1763682598_15522d304e07.jpg', 2),
(197, 66, '1763682598_83261d81ec72.jpg', 3),
(198, 66, '1763682598_4ca7432e6b9f.jpg', 4),
(199, 66, '1763682598_911764fce6e5.jpg', 5),
(200, 66, '1763682598_f2642211b7fb.jpg', 6),
(201, 67, '1763735747_09373c0351e7.jpg', 0),
(202, 67, '1763735747_cdd52e839f47.jpg', 1),
(203, 67, '1763735747_ca793a15ae40.jpg', 2),
(204, 67, '1763735747_5fa8ab15927b.jpg', 3),
(205, 67, '1763735747_32c1003c6e4e.jpg', 4),
(206, 67, '1763735747_cdb8fa8f8306.jpg', 5),
(207, 67, '1763735747_ecec4e0a263e.jpg', 6),
(208, 67, '1763735747_e2c2da8dd171.jpg', 7),
(209, 67, '1763735747_904f034da060.jpg', 8),
(210, 67, '1763735747_187c98c3292b.jpg', 9),
(211, 67, '1763735747_7c04f229a41d.jpg', 10),
(230, 71, '1763937672_8cab3836709e.jpg', 0),
(231, 71, '1763937672_95eef44de084.jpg', 1),
(232, 71, '1763937672_5aee9a889a05.jpg', 2),
(233, 71, '1763937672_a1699e0af729.jpg', 3),
(234, 71, '1763937672_45588b7bf11c.jpg', 4),
(235, 71, '1763937672_1cf0bad4770f.jpg', 5),
(236, 72, '1764016095_1f794d94af59.jpg', 0),
(237, 72, '1764016095_afcbb64814da.jpg', 1),
(238, 72, '1764016095_8128bbab3352.jpg', 2),
(239, 72, '1764016095_c1e95a7353dc.jpg', 3),
(240, 72, '1764016095_62f19136a540.jpg', 4),
(241, 72, '1764016095_f7a1aa39b4eb.jpg', 5),
(242, 72, '1764016095_80ad0df57513.jpg', 6),
(243, 72, '1764016095_dfe5c1f516cd.jpg', 7),
(244, 72, '1764016095_50421c89326c.jpg', 8),
(245, 73, '1764016501_61b24d8a633e.webp', 0),
(246, 73, '1764016501_897eac2df536.webp', 1),
(247, 73, '1764016501_c3baae2e425e.webp', 2),
(248, 73, '1764016501_06da066bfbd7.webp', 3),
(249, 73, '1764016501_92e0b22314ed.webp', 4),
(250, 73, '1764016501_e65892297093.webp', 5),
(251, 73, '1764016501_157bf562ae27.webp', 6),
(252, 73, '1764016501_3bec119be3e0.webp', 7),
(253, 73, '1764016501_87fc6e7bb5cf.webp', 8),
(254, 73, '1764016501_80811294a951.webp', 9),
(255, 73, '1764016501_9e1d8b52fd90.webp', 10),
(256, 74, '1764016790_e8e501c2eada.jpg', 0),
(257, 74, '1764016790_cc351f8c2f3a.jpg', 1),
(258, 74, '1764016790_4d39d768ed61.jpg', 2),
(259, 74, '1764016790_52ed0f8830f3.jpg', 3),
(260, 74, '1764016790_3d56f9687c90.jpg', 4),
(261, 74, '1764016790_c2065f0d38a8.jpg', 5),
(262, 75, '1764017946_29aadc645d9d.jpg', 0),
(263, 75, '1764017946_36a07d213b55.jpg', 1),
(264, 75, '1764017946_3d10aa7eb18f.jpg', 2),
(265, 75, '1764017946_7de7fab625be.jpg', 3),
(266, 75, '1764017946_cf48aeea4852.jpg', 4),
(267, 75, '1764017946_4178d688259b.jpg', 5),
(268, 75, '1764017946_ceac5b451c4a.jpg', 6),
(269, 75, '1764017946_b3a6952ead5e.jpg', 7),
(270, 75, '1764017946_ae2e4a023451.jpg', 8),
(271, 75, '1764017946_6ebbdc7806fc.jpg', 9),
(272, 75, '1764017946_6b086f0e39ef.jpg', 10),
(273, 75, '1764017946_94bc555b0c97.jpg', 11),
(274, 76, '1764028872_50905f85c457.jpg', 0),
(275, 76, '1764028872_d24f5b517d95.jpg', 1),
(276, 76, '1764028872_b7b71c5b1d68.jpg', 2),
(277, 76, '1764028872_c28d471dc6d4.jpg', 3),
(278, 76, '1764028872_81adfad9a527.jpg', 4),
(279, 76, '1764028872_d5e6e6f06dc7.jpg', 5),
(280, 76, '1764028872_f4635f1239b8.jpg', 6),
(281, 76, '1764028872_b9ae581016d7.jpg', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `lojas`
--

CREATE TABLE `lojas` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
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
  `horarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lojas`
--

INSERT INTO `lojas` (`id`, `owner_id`, `nome`, `razao_social`, `cnpj`, `inscricao_estadual`, `endereco`, `numero`, `seguidores`, `cep`, `bairro`, `cidade`, `estado`, `telefone_fixo`, `whatsapp`, `email_corporativo`, `site`, `instagram`, `facebook`, `logo`, `capa`, `descricao_loja`, `hora_abre`, `hora_fecha`, `dias_funcionamento`, `created_at`, `horarios`) VALUES
(6, 6, 'M8 Imports', '', '', '', '', '', 1, '', '', 'Bodoquena', 'MS', '', '(12) 98827-3730', 'kelwin@gmail.com', '', '', '', 'logo-6921ef7dc5652.jpeg', 'capa-6921ef7dc5ac1.jpeg', 'Teste', '14:20:00', '20:20:00', '1,2,7', '2025-11-22 17:14:21', '[{\"aberto\":1,\"abre\":\"14:20:00\",\"fecha\":\"20:20:00\"},{\"aberto\":1,\"abre\":\"14:20:00\",\"fecha\":\"20:20:00\"},{\"aberto\":0,\"abre\":null,\"fecha\":null},{\"aberto\":0,\"abre\":null,\"fecha\":null},{\"aberto\":0,\"abre\":null,\"fecha\":null},{\"aberto\":0,\"abre\":null,\"fecha\":null},{\"aberto\":1,\"abre\":\"14:20:00\",\"fecha\":\"20:30\"}]');

-- --------------------------------------------------------

--
-- Estrutura para tabela `loja_membros`
--

CREATE TABLE `loja_membros` (
  `id` int(11) NOT NULL,
  `loja_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pode_editar_anuncio` tinyint(1) DEFAULT 0,
  `pode_responder_mensagem` tinyint(1) DEFAULT 0,
  `pode_editar_loja` tinyint(1) DEFAULT 0,
  `pode_adicionar_membros` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `loja_membros`
--

INSERT INTO `loja_membros` (`id`, `loja_id`, `usuario_id`, `pode_editar_anuncio`, `pode_responder_mensagem`, `pode_editar_loja`, `pode_adicionar_membros`, `created_at`) VALUES
(2, 4, 7, 1, 0, 0, 0, '2025-11-22 14:19:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `value` varchar(50) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `mensagens_chat` (
  `id` int(11) NOT NULL,
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
  `resposta_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens_chat`
--

INSERT INTO `mensagens_chat` (`id`, `de_usuario`, `para_usuario`, `anuncio`, `texto`, `data_envio`, `lida`, `apagada_de`, `double_apagada_de`, `apagada_para`, `double_apagada_para`, `resposta_id`) VALUES
(39, 7, 6, 45, 'Oi, isso é um texte', '2025-11-24 18:03:14', 1, 0, 1, 1, 1, 0),
(40, 7, 6, 45, 'Blz?', '2025-11-24 18:09:27', 1, 0, 1, 1, 1, 0),
(41, 6, 7, 45, 'Teste', '2025-11-24 18:28:17', 1, 1, 1, 0, 0, 0),
(42, 6, 7, 45, 'abu', '2025-11-24 18:28:21', 1, 1, 1, 0, 0, 0),
(43, 6, 7, 45, 'teste', '2025-11-24 18:34:29', 1, 1, 1, 0, 0, 0),
(44, 6, 7, 45, 'TEste', '2025-11-24 18:38:52', 1, 1, 1, 0, 0, 0),
(45, 7, 6, 45, 'Opa', '2025-11-24 19:05:33', 1, 1, 0, 0, 1, 0),
(46, 7, 6, 45, 'Teste', '2025-11-24 21:16:07', 1, 0, 0, 0, 0, 0),
(47, 7, 6, 45, 'Teste', '2025-11-24 21:16:10', 1, 0, 0, 0, 0, 0),
(48, 6, 7, 45, 'Oxe', '2025-11-24 21:19:40', 1, 0, 0, 0, 0, 0),
(49, 6, 7, 45, 'Daora', '2025-11-24 21:19:46', 1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
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
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reservas`
--

INSERT INTO `reservas` (`id`, `id_veiculo`, `nome`, `telefone`, `email`, `preferencia_contato`, `data`, `hora`, `acompanhantes_qtd`, `estado`, `cidade`, `bairro`, `rua`, `numero`, `complemento`, `cep`, `observacoes`, `status`, `criado_em`, `atualizado_em`) VALUES
(7, 43, 'Kelwin', '11111111111', 'kelwin@gmail.com', 'whatsapp', '2025-11-11', '12:03:00', 2, 'SP', 'Teste', 'teste', 'etes', '231', 'teste', '24115-151', 'Teste', 'pendente', '2025-11-11 14:56:37', '2025-11-11 14:56:37'),
(8, 45, 'Cleitinho', '12988273730', 'kelwin@gmail.com', 'whatsapp', '2025-11-11', '14:51:00', 12, 'SP', 'Teste', 'teste', 'etes', '421', 'teste', '24115-151', 'Teste', 'pendente', '2025-11-11 14:57:10', '2025-11-11 14:57:51'),
(9, 45, 'Cleitin', '12988273731', 'kelwinrocha5@gmail.com', 'whatsapp', '2025-11-28', '23:01:00', 120, 'SP', 'Teste', 'teste', 'etes', '42', 'teste', '12551-252', 'Teste', 'pendente', '2025-11-11 14:57:40', '2025-11-11 14:58:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `seguidores`
--

CREATE TABLE `seguidores` (
  `id` int(11) NOT NULL,
  `seguidor_id` int(11) NOT NULL,
  `seguido_id` int(11) NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `seguidores`
--

INSERT INTO `seguidores` (`id`, `seguidor_id`, `seguido_id`, `criado_em`) VALUES
(4, 6, 7, '2025-11-24 19:19:30'),
(20, 7, 6, '2025-11-24 20:31:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(6) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `sobrenome` varchar(30) NOT NULL,
  `telefone` bigint(15) DEFAULT NULL,
  `cpf` char(11) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(256) NOT NULL,
  `data_criacao_conta` datetime NOT NULL DEFAULT current_timestamp(),
  `data_nascimento` date DEFAULT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'img/user.png',
  `estado_local` char(2) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `seguidores` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `sobrenome`, `telefone`, `cpf`, `email`, `senha`, `data_criacao_conta`, `data_nascimento`, `avatar`, `estado_local`, `cidade`, `seguidores`) VALUES
(6, 'Kelwin', 'Silva', 12988273730, '', 'kelwin@gmail.com', '1', '2025-09-20 20:44:02', '2025-10-14', 'img/usuarios/avatares/usuario_6_1763928878.webp', 'MS', 'Bodoquena', 1),
(7, 'Vinicius', 'Souza', NULL, NULL, 'vinicius@gmail.com', '1', '2025-10-02 22:40:54', NULL, 'img/usuarios/avatares/usuario_7_1763928864.jpg', NULL, NULL, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `anuncios_carros`
--
ALTER TABLE `anuncios_carros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placa` (`placa`),
  ADD KEY `cor_fk` (`cor`),
  ADD KEY `carroceria_fk` (`carroceria`),
  ADD KEY `vendedor_fk` (`id_vendedor`),
  ADD KEY `estado_fk` (`estado_local`),
  ADD KEY `marca_fk` (`marca`);

--
-- Índices de tabela `carrocerias`
--
ALTER TABLE `carrocerias`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `conversas`
--
ALTER TABLE `conversas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversa_unica` (`comprador_id`,`vendedor_id`,`anuncio_id`),
  ADD KEY `conversas_ibfk_3` (`anuncio_id`),
  ADD KEY `conversas_ibfk_2` (`vendedor_id`);

--
-- Índices de tabela `cores`
--
ALTER TABLE `cores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `equipe`
--
ALTER TABLE `equipe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loja_id` (`loja_id`,`usuario_id`);

--
-- Índices de tabela `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`uf`);

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`anuncio_id`),
  ADD KEY `anuncio_id` (`anuncio_id`);

--
-- Índices de tabela `fotos_carros`
--
ALTER TABLE `fotos_carros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carro_id` (`carro_id`);

--
-- Índices de tabela `lojas`
--
ALTER TABLE `lojas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_owner_id` (`owner_id`);

--
-- Índices de tabela `loja_membros`
--
ALTER TABLE `loja_membros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loja_id` (`loja_id`,`usuario_id`);

--
-- Índices de tabela `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`);

--
-- Índices de tabela `mensagens_chat`
--
ALTER TABLE `mensagens_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mensagens_chat_ibfk_1` (`de_usuario`),
  ADD KEY `mensagens_chat_ibfk_2` (`para_usuario`),
  ADD KEY `mensagens_chat_ibfk_3` (`anuncio`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_veiculo` (`id_veiculo`);

--
-- Índices de tabela `seguidores`
--
ALTER TABLE `seguidores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_seg` (`seguidor_id`,`seguido_id`),
  ADD KEY `idx_seguido` (`seguido_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefone` (`telefone`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `idx_estado_local` (`estado_local`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `anuncios_carros`
--
ALTER TABLE `anuncios_carros`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de tabela `carrocerias`
--
ALTER TABLE `carrocerias`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `conversas`
--
ALTER TABLE `conversas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de tabela `cores`
--
ALTER TABLE `cores`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `equipe`
--
ALTER TABLE `equipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de tabela `fotos_carros`
--
ALTER TABLE `fotos_carros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT de tabela `lojas`
--
ALTER TABLE `lojas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `loja_membros`
--
ALTER TABLE `loja_membros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de tabela `mensagens_chat`
--
ALTER TABLE `mensagens_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `seguidores`
--
ALTER TABLE `seguidores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  ADD CONSTRAINT `conversas_ibfk_1` FOREIGN KEY (`comprador_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `conversas_ibfk_2` FOREIGN KEY (`vendedor_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `conversas_ibfk_3` FOREIGN KEY (`anuncio_id`) REFERENCES `anuncios_carros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `mensagens_chat_ibfk_1` FOREIGN KEY (`de_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mensagens_chat_ibfk_2` FOREIGN KEY (`para_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mensagens_chat_ibfk_3` FOREIGN KEY (`anuncio`) REFERENCES `anuncios_carros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_veiculo`) REFERENCES `anuncios_carros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
