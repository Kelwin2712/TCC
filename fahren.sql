-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/09/2025 às 03:47
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

-- --------------------------------------------------------

--
-- Estrutura para tabela `carros`
--

CREATE TABLE `carros` (
  `id` int(12) NOT NULL,
  `estado_local` char(2) DEFAULT NULL,
  `marca` varchar(25) DEFAULT NULL,
  `modelo` varchar(75) DEFAULT NULL,
  `versao` varchar(40) DEFAULT NULL,
  `carroceria` varchar(15) DEFAULT NULL,
  `preco` decimal(11,2) DEFAULT NULL,
  `quilometragem` int(10) DEFAULT NULL,
  `ano_fabricacao` int(4) DEFAULT NULL,
  `ano_modelo` int(4) DEFAULT NULL,
  `propulsao` varchar(10) DEFAULT NULL,
  `combustivel` varchar(20) DEFAULT NULL,
  `blindagem` char(1) DEFAULT '0',
  `id_vendedor` int(10) DEFAULT NULL,
  `fotos` varchar(255) DEFAULT NULL,
  `leilao` char(1) DEFAULT NULL,
  `portas_qtd` smallint(1) DEFAULT 4,
  `acentos_qtd` smallint(1) DEFAULT 5,
  `placa` char(7) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `cor` varchar(10) NOT NULL,
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
  `telefone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `data_nascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `sobrenome`, `telefone`, `cpf`, `email`, `senha`, `data_criacao_conta`, `data_nascimento`) VALUES
(6, 'Kelwin', 'Silva', NULL, NULL, 'kelwin@gmail.com', '1111AAAA', '2025-09-20 20:44:02', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carros`
--
ALTER TABLE `carros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placa` (`placa`),
  ADD KEY `carro_link_vendedor_id` (`id_vendedor`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefone` (`telefone`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carros`
--
ALTER TABLE `carros`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carros`
--
ALTER TABLE `carros`
  ADD CONSTRAINT `carro_link_vendedor_id` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
