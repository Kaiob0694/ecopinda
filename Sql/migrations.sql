-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 01/09/2026 às 13:20
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
(5, 'Pinda Palace Hotel', ' Av. Amélia Prata Balarin, Nº26 - Parque das Palmeiras, Pindamonhangaba -', 'Pindamonhangaba', 'São Paulo', '12404-241', '(12) 2126-2150', 'kaiobarbosa0694@gmail.com', 55, 1, 1, '2026-08-21 03:00:00'),
(6, 'Sleep Inn Pindamonhangaba', 'Av. Dr. Francisco Lessa Júnior, 2385 - Q. Coberta, Pindamonhangaba', 'Pindamonhangaba', 'São Paulo', '12421-010', '(12) 3550-0663', 'teste@gmail.com', 50, 1, 1, '2026-08-24 03:00:00');

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
(22, 5, 'hotel_6a88cf6797ad64.40677829.jpg', '2026-08-21 22:21:27'),
(23, 6, 'hotel_6a8c597751b888.51073484.jpg', '2026-08-24 14:47:19'),
(24, 6, 'hotel_6a8c5977538bd6.66322132.jpg', '2026-08-24 14:47:19'),
(25, 6, 'hotel_6a8c597754e536.32696924.jpg', '2026-08-24 14:47:19'),
(26, 6, 'hotel_6a8c59775658f3.82373464.jpg', '2026-08-24 14:47:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ponto_turistico`
--

CREATE TABLE `ponto_turistico` (
  `id` int NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text,
  `endereco` varchar(150) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `cep` varchar(15) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `horario_funcionamento` varchar(100) DEFAULT NULL,
  `entrada_gratuita` tinyint(1) DEFAULT '0',
  `possui_estacionamento` tinyint(1) DEFAULT '0',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ponto_turistico_foto`
--

CREATE TABLE `ponto_turistico_foto` (
  `id` int NOT NULL,
  `id_ponto_turistico` int NOT NULL,
  `caminho` varchar(255) NOT NULL,
  `data_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP
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
(2, 'Gramado Churrascaria', 'R. Bicudo Leme, 55 - Centro, Pindamonhangaba - SP, 12400-180', 101, 'Pindamonhangaba', '12400-180', '012981802222', 'teste@gmail.com', 'Brasileira', 1, 1, '08:00 - 15:00', '2026-08-27 03:00:00'),
(3, 'Caseiro Restaurante', 'R. Cônego João Antônio da Costa Bueno - Santana, ', 123, 'Pindamonhangaba', '12403-260', '1235500j663', 'teste4@gmail.com', 'Brasileira', 1, 1, '08:00 - 15:00', '2026-08-27 03:00:00'),
(4, 'Restaurante Casaboa', 'R. Euclídes de Figueiredo, 104 - Alto do Cardoso, ', 125, 'Pindamonhangaba', '12420-060', '1235500j663', 'teste5@gmail.com', 'Brasileira', 1, 1, '08:00 - 15:00', '2026-08-27 03:00:00'),
(5, 'Restaurante Trento Grill', 'R. Guilherme de Souza e Silva, 451 - Res. Comercial Vila Verde', 156, 'Pindamonhangaba', '12412-575', '1235500j663', 'kaso@email.com', 'Brasileira', 1, 1, '08:00 - 15:00', '2026-08-27 03:00:00'),
(6, 'Armazém da Fazenda - Restaurante e Pizzaria', 'Av. Nossa Sra. do Bom Sucesso, 4275 - Nossa Sra. do Perpetuo Socorro, Pindamonhangaba', 188, 'Pindamonhangaba', '12420-010', '1235500j663', '3kaso@email.com', 'Brasileira', 1, 1, '08:00 - 15:00', '2026-08-27 03:00:00'),
(7, 'Restaurante Vitoria', 'R. Prudente de Moraes, 325 - Centro', 188, 'Pindamonhangaba', '12400-230', '01236434345', '4kaso@email.com', 'Brasileira', 1, 1, '08:00 - 15:00', '2026-08-31 03:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `restaurante_foto`
--

CREATE TABLE `restaurante_foto` (
  `id` int NOT NULL,
  `id_restaurante` int NOT NULL,
  `caminho` varchar(255) NOT NULL,
  `data_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `restaurante_foto`
--

INSERT INTO `restaurante_foto` (`id`, `id_restaurante`, `caminho`, `data_upload`) VALUES
(1, 1, 'restaurante_6a8d7efd6a4431.60697676.jpg', '2026-08-25 11:39:41'),
(2, 1, 'restaurante_6a8d7efd6d7279.33343856.jpg', '2026-08-25 11:39:41'),
(3, 1, 'restaurante_6a8d7efd6ef9a7.09019597.jpg', '2026-08-25 11:39:41'),
(4, 1, 'restaurante_6a8d7efd7038f9.07420746.jpg', '2026-08-25 11:39:41'),
(5, 2, 'restaurante_6a9044a2809ca1.44493001.jpg', '2026-08-27 14:07:30'),
(6, 2, 'restaurante_6a9044a2844ad4.13614175.jpg', '2026-08-27 14:07:30'),
(7, 2, 'restaurante_6a9044a2862262.49952427.jpg', '2026-08-27 14:07:30'),
(8, 2, 'restaurante_6a9044a2877ac4.89271599.jpg', '2026-08-27 14:07:30'),
(9, 3, 'restaurante_6a9045e49e3865.64610788.jpg', '2026-08-27 14:12:52'),
(10, 3, 'restaurante_6a9045e4a04688.30275544.jpg', '2026-08-27 14:12:52'),
(11, 3, 'restaurante_6a9045e4a1b485.96429114.jpg', '2026-08-27 14:12:52'),
(12, 3, 'restaurante_6a9045e4a382d4.46885200.jpg', '2026-08-27 14:12:52'),
(17, 5, 'restaurante_6a9046f433c311.52544091.jpg', '2026-08-27 14:17:24'),
(14, 4, 'restaurante_6a9046619e2189.38135537.jpg', '2026-08-27 14:14:57'),
(15, 4, 'restaurante_6a9046619fa353.96331901.jpg', '2026-08-27 14:14:57'),
(16, 4, 'restaurante_6a904661a125a4.60277344.jpg', '2026-08-27 14:14:57'),
(18, 5, 'restaurante_6a9046f437b987.34624129.jpg', '2026-08-27 14:17:24'),
(19, 5, 'restaurante_6a9046f43a22f7.77196254.jpg', '2026-08-27 14:17:24'),
(20, 5, 'restaurante_6a9046f43b8c99.98307712.jpg', '2026-08-27 14:17:24'),
(21, 6, 'restaurante_6a904765aa9756.72423685.jpg', '2026-08-27 14:19:17'),
(22, 6, 'restaurante_6a904765acc3f7.79260129.jpg', '2026-08-27 14:19:17'),
(23, 6, 'restaurante_6a904765af4a03.25228546.jpg', '2026-08-27 14:19:17'),
(24, 6, 'restaurante_6a904765b0a922.06897934.jpg', '2026-08-27 14:19:17'),
(25, 7, 'restaurante_6a959180c809a6.02537385.jpg', '2026-08-31 14:36:48'),
(26, 7, 'restaurante_6a959180c9f406.34680865.jpg', '2026-08-31 14:36:48'),
(27, 7, 'restaurante_6a959180cb5869.41690375.jpg', '2026-08-31 14:36:48'),
(28, 7, 'restaurante_6a959180ccb070.87890988.jpg', '2026-08-31 14:36:48');

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
(1, 'Kaio Barbosa', '43755472864', 'kaiobarbosa0694@gmail.com', '1234', '12992108610', '12420480', 'user_1_1787661777.jpg', 'master'),
(2, 'asdasdas', '12345678910', 'teste@gmail.com', '$2y$10$/rj6n4OIgLdbq7GMigVRZO3i8v4nApIXbveC6/UMD18kTe52nNqEm', '12992108611', '12420468', NULL, 'usuario');

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
-- Índices de tabela `ponto_turistico`
--
ALTER TABLE `ponto_turistico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ponto_turistico_foto`
--
ALTER TABLE `ponto_turistico_foto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ponto_turistico` (`id_ponto_turistico`);

--
-- Índices de tabela `restaurante`
--
ALTER TABLE `restaurante`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `restaurante_foto`
--
ALTER TABLE `restaurante_foto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_restaurante` (`id_restaurante`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `hotel_foto`
--
ALTER TABLE `hotel_foto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `ponto_turistico`
--
ALTER TABLE `ponto_turistico`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ponto_turistico_foto`
--
ALTER TABLE `ponto_turistico_foto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `restaurante`
--
ALTER TABLE `restaurante`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `restaurante_foto`
--
ALTER TABLE `restaurante_foto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
