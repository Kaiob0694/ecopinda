-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 24/08/2026 às 11:56
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
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `hotel`
--

INSERT INTO `hotel` (`id`, `nome`, `endereco`, `cidade`, `estado`, `cep`, `telefone`, `email`, `quantidade_quartos`, `possui_wifi`, `possui_estacionamento`, `data_cadastro`) VALUES
(1, 'Summit Suítes Hotel', 'Av. Dr. Francisco Lessa Júnior, 2385 101, Pindamonhangaba, CEP 12422-531, Brasil', 'Pindamonhangaba', 'São Paulo', '12422531', '(12) 2101-0621', 'kaiobcesar0694@gmail.com', 25, 1, 1, '2026-08-21 03:00:00'),
(2, 'Colonial Plaza Hotel Pindamonhangaba', 'Av. Nossa Senhora do Bom Sucesso, 4201, Pindamonhangaba, CEP 12420-010, Brasil', 'Pindamonhangaba', 'São Paulo', '12420-010', '(12) 3644-3644', 'pbarbosa@sp.senai.br', 50, 1, 1, '2026-08-21 03:00:00'),
(3, 'Hotel Pousada Liberdade', 'R. Martin Cabral, 42 - Centro, Pindamonhangaba - SP, 12400-020', 'Pindamonhangaba', 'São Paulo', '12400-020', '(12) 99162-2019', 'kaiobcesar0694@gmail.com', 50, 1, 1, '2026-08-21 03:00:00'),
(4, 'Hotel Brasil Pindamonhangaba', 'R. Dez de Julho, 48 - Centro, Pindamonhangaba - SP, 12400-480', 'Pindamonhangaba', 'São Paulo', '12400-480', '(12) 3643-2229', 'kaiobarbosa0694@gmail.com', 50, 1, 1, '2026-08-21 03:00:00'),
(5, 'Pinda Palace Hotel', ' Av. Amélia Prata Balarin, Nº26 - Parque das Palmeiras, Pindamonhangaba -', 'Pindamonhangaba', 'São Paulo', '12404-241', '(12) 2126-2150', 'kaiobarbosa0694@gmail.com', 55, 1, 1, '2026-08-21 03:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `hotel_foto`
--

CREATE TABLE `hotel_foto` (
  `id` int NOT NULL,
  `id_hotel` int NOT NULL,
  `caminho` varchar(255) NOT NULL,
  `data_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `hotel_foto`
--

INSERT INTO `hotel_foto` (`id`, `id_hotel`, `caminho`, `data_upload`) VALUES
(1, 1, 'hotel_6a88a8fa2e6a07.66265332.jpg', '2026-08-21 19:37:30'),
(2, 1, 'hotel_6a88a8fa352c00.18015519.jpg', '2026-08-21 19:37:30'),
(3, 1, 'hotel_6a88a8fa37c965.17471334.jpg', '2026-08-21 19:37:30'),
(4, 1, 'hotel_6a88a8fa39f066.42513910.jpg', '2026-08-21 19:37:30'),
(5, 2, 'hotel_6a88cd4d1a1149.84425005.jpg', '2026-08-21 22:12:29'),
(6, 2, 'hotel_6a88cd4d1f47b3.93922794.jpg', '2026-08-21 22:12:29'),
(7, 2, 'hotel_6a88cd4d21d0d4.12949684.jpg', '2026-08-21 22:12:29'),
(8, 2, 'hotel_6a88cd4d246c20.79875711.jpg', '2026-08-21 22:12:29'),
(9, 2, 'hotel_6a88cd4d268d90.74374431.jpg', '2026-08-21 22:12:29'),
(10, 3, 'hotel_6a88ce1fa90b57.64065718.jpg', '2026-08-21 22:15:59'),
(11, 3, 'hotel_6a88ce1fad5110.12071773.jpg', '2026-08-21 22:15:59'),
(12, 3, 'hotel_6a88ce1fb00cb3.73362274.jpg', '2026-08-21 22:15:59'),
(13, 3, 'hotel_6a88ce1fb24376.53838185.jpg', '2026-08-21 22:15:59'),
(14, 4, 'hotel_6a88ced60a1442.80413990.jpg', '2026-08-21 22:19:02'),
(15, 4, 'hotel_6a88ced60cf581.53225005.jpg', '2026-08-21 22:19:02'),
(16, 4, 'hotel_6a88ced60ffed3.87051015.jpg', '2026-08-21 22:19:02'),
(17, 4, 'hotel_6a88ced612a070.62483046.jpg', '2026-08-21 22:19:02'),
(18, 5, 'hotel_6a88cf678a1db3.36485381.jpg', '2026-08-21 22:21:27'),
(19, 5, 'hotel_6a88cf678df501.52527998.jpg', '2026-08-21 22:21:27'),
(20, 5, 'hotel_6a88cf67910976.91534952.jpg', '2026-08-21 22:21:27'),
(21, 5, 'hotel_6a88cf67946c27.84975107.jpg', '2026-08-21 22:21:27'),
(22, 5, 'hotel_6a88cf6797ad64.40677829.jpg', '2026-08-21 22:21:27');

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
(1, 'Kaio Barbosa', '43755472864', 'kaiobarbosa0694@gmail.com', '1234', '12992108610', '12420480', NULL, 'master');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `hotel_foto`
--
ALTER TABLE `hotel_foto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hotel` (`id_hotel`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `hotel_foto`
--
ALTER TABLE `hotel_foto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `restaurante`
--
ALTER TABLE `restaurante`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
