-- phpMyAdmin SQL Dump
-- version 4.6.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql.rsantunes.notapipe.org
-- Tempo de geração: 26/06/2016 às 12:26
-- Versão do servidor: 5.6.25-log
-- Versão do PHP: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `rsantunes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_room` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `hour` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `reservations`
--

INSERT INTO `reservations` (`id`, `id_user`, `id_room`, `date`, `hour`) VALUES
(1, 3, 5, '2016-06-15', 13),
(2, 6, 4, '2016-06-15', 15),
(3, 6, 5, '2016-06-15', 10),
(4, 7, 1, '2016-06-15', 9),
(5, 7, 5, '2016-06-15', 16),
(23, 3, 1, '2016-06-14', 11),
(24, 3, 1, '2016-06-14', 13),
(25, 3, 4, '2016-06-14', 12),
(27, 3, 7, '2016-06-15', 18),
(30, 12, 4, '2016-06-25', 14),
(36, 3, 7, '2016-06-25', 13),
(39, 3, 4, '2016-06-25', 12),
(42, 3, 7, '2016-06-25', 15),
(50, 3, 4, '2016-06-25', 11),
(51, 3, 4, '2016-06-25', 16),
(52, 3, 7, '2016-06-25', 17),
(55, 6, 1, '2016-06-26', 13),
(56, 6, 4, '2016-06-26', 14),
(58, 3, 9, '2016-06-26', 12),
(59, 3, 4, '2016-06-15', 11),
(60, 3, 9, '2016-06-15', 13),
(61, 3, 4, '2016-06-15', 15),
(62, 7, 4, '2016-06-26', 15),
(63, 7, 1, '2016-06-26', 10),
(64, 7, 7, '2016-06-26', 14),
(65, 11, 4, '2016-06-26', 13),
(66, 11, 7, '2016-06-26', 15),
(67, 11, 9, '2016-06-26', 16),
(68, 3, 4, '2016-06-26', 14),
(69, 3, 9, '2016-06-26', 8),
(70, 3, 7, '2016-06-26', 18);

-- --------------------------------------------------------

--
-- Estrutura para tabela `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(10) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `rooms`
--

INSERT INTO `rooms` (`id`, `number`, `description`) VALUES
(1, '208', 'Sala de conferÃªncias com 45 cadeiras.'),
(4, '1001', 'Sala de ConferÃªncias com 200 lugares.'),
(7, '1003', 'Sala de reuniÃµes com 8 lugares.'),
(9, '1002', 'Mais um teste.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `password`, `admin`) VALUES
(1, 'admin', 'Administrador do Sistema', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1),
(3, 'rsantunes', 'Rodolfo Stoffel Antunes', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1),
(7, 'joanacorrea', 'Joana Correia', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 0),
(11, 'marianasilva', 'Mariana da Silva', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 0);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices de tabela `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT de tabela `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
