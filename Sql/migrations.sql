-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 18/08/2026 às 12:58
-- Versão do servidor: 8.2.0
-- Versão do PHP: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pindaeco`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `hotel`
--

CREATE TABLE `hotel` (
  `id` int NOT NULL,
  `nome` varchar(150) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `quantidade_quartos` int NOT NULL,
  `possui_wifi` tinyint(1) DEFAULT '1',
  `possui_estacionamento` tinyint(1) DEFAULT '0',
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `restaurante`
--

CREATE TABLE `restaurante` (
  `id` int NOT NULL,
  `nome` varchar(150) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` int DEFAULT NULL,
  `cidade` varchar(100) NOT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `possui_delivery` tinyint(1) DEFAULT '0',
  `possui_wifi` tinyint(1) DEFAULT '1',
  `horario_funcionamento` varchar(100) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `restaurante`
--

INSERT INTO `restaurante` (`id`, `nome`, `logradouro`, `numero`, `cidade`, `cep`, `telefone`, `email`, `categoria`, `possui_delivery`, `possui_wifi`, `horario_funcionamento`, `data_cadastro`) VALUES
(1, 'asda', 'asdas', 2, 'asdasd', 'aaasdas', 'asdas', 'asdasd@email.com', 'asdas', 1, 1, 'asdasda', '2026-08-13 14:48:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('usuario','admin','master') NOT NULL DEFAULT 'usuario'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `cpf`, `email`, `senha`, `telefone`, `cep`, `foto`, `tipo_usuario`) VALUES
(4, 'Kaio Cesar Barbosa', '43755472864', 'kaiobarbosa0694@gmail.com', '$2y$10$Go1W5g1la3aaoRxOh87mQeo/i5xWfrTA140cDRHoF.0bDmJTH1nbi', '12992108610', '12420468', 'user_4_1786450066.png', 'master'),
(2, 'João', '22222222222', 'joao@email.com', '123456', '12992108610', '12420680', NULL, 'usuario'),
(5, 'Sofia Figueiredo', '43755472861', 'sofia@gmail.com', '$2y$10$URbgb.TtRB62nCdIO4DwMeXFiByf0b4Y2TKAa7zk8MiVeM9A7Toga', '12992108611', '12420468', NULL, 'usuario');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `restaurante`
--
ALTER TABLE `restaurante`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `restaurante`
--
ALTER TABLE `restaurante`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;