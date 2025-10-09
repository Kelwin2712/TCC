-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/10/2025 às 03:10
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
  `modelo` varchar(75) DEFAULT NULL,
  `estado_local` char(2) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `marca` int(2) DEFAULT NULL,
  `versao` varchar(40) DEFAULT NULL,
  `carroceria` int(2) DEFAULT NULL,
  `preco` decimal(11,2) DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `placa` (`placa`),
  KEY `cor_fk` (`cor`),
  KEY `carroceria_fk` (`carroceria`),
  KEY `vendedor_fk` (`id_vendedor`),
  KEY `estado_fk` (`estado_local`),
  KEY `marca_fk` (`marca`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `anuncios_carros`
--

INSERT INTO `anuncios_carros` (`id`, `modelo`, `estado_local`, `cidade`, `marca`, `versao`, `carroceria`, `preco`, `quilometragem`, `ano_fabricacao`, `ano_modelo`, `propulsao`, `combustivel`, `blindagem`, `id_vendedor`, `imagens`, `leilao`, `portas_qtd`, `assentos_qtd`, `placa`, `data_criacao`, `cor`, `quant_proprietario`, `revisao`, `vistoria`, `sinistro`, `ipva`, `licenciamento`, `estado_conservacao`, `uso_anterior`, `aceita_troca`, `email`, `telefone`, `garantia`) VALUES
(23, 'a', NULL, NULL, 2, 'A', NULL, 1.00, 0, 2024, 2024, NULL, NULL, '0', 6, NULL, NULL, 4, 5, 'FAF8F09', '2025-10-05 18:37:55', 1, '1', '0', 'F', '0', 'D', 'D', '4', '', '1', 'kelwin@gmail.com', '(11) 11111-1111', 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

INSERT INTO `lojas` (`id`, `nome`, `razao_social`, `cnpj`, `inscricao_estadual`, `endereco`, `numero`, `cep`, `bairro`, `cidade`, `estado`, `telefone_fixo`, `whatsapp`, `email_corporativo`, `site`, `instagram`, `facebook`, `logo`, `capa`, `descricao_loja`, `hora_abre`, `hora_fecha`, `dias_funcionamento`, `created_at`) VALUES
(2, 'Kelwin', '', '', '', '', '', '', '', '', '', '', '123', 'a@a.a', '', '', '', '', '', 'A', '21:50:00', '21:50:00', 'Domingo,Segunda', '2025-10-09 00:50:16');

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `telefone` (`telefone`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `sobrenome`, `telefone`, `cpf`, `email`, `senha`, `data_criacao_conta`, `data_nascimento`) VALUES
(6, 'Kelwin', 'Silva', 0, '', 'kelwin@gmail.com', '1111AAAA', '2025-09-20 20:44:02', '0000-00-00'),
(7, 'Vinicius', 'Souza', NULL, NULL, 'vinicius@gmail.com', '1111AAAA', '2025-10-02 22:40:54', NULL);

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
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`anuncio_id`) REFERENCES `anuncios_carros` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
