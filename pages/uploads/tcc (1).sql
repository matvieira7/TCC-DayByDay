-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/11/2024 às 00:06
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
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_nota` int(11) DEFAULT NULL,
  `cor` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `id_usuario`, `id_nota`, `cor`) VALUES
(55, 'dwadwadwa', 34, NULL, '#36DF32'),
(56, 'dwadwa', 34, NULL, '#FFFF00'),
(63, 'dwadwa', 34, NULL, '#00FFFF'),
(65, 'dwadaw', 34, NULL, '#FFCF52'),
(66, 'dwadwa', 34, NULL, '#F95B99'),
(67, 'dwadaw', 34, NULL, '#AA8DE4'),
(68, 'dwadwadwadwadwadawdwa', 34, NULL, '#FF5757'),
(69, '123123123', 34, NULL, '#3091FF'),
(70, '3123123', 34, NULL, '#FF914D'),
(71, '312312312321', 34, NULL, '#FF5757'),
(72, '312321312', 34, NULL, '#8C52FF'),
(73, 'dwadwa', 34, NULL, '#397D1D'),
(74, 'dwadwa', 34, NULL, '#3091FF'),
(75, 'sdwaddwa', 34, NULL, '#F95B99'),
(76, 'dwadwa', 36, NULL, '#FF5757'),
(77, 'Yuri Lixo', 36, NULL, '#36DF32'),
(78, 'dwadwa', 37, NULL, '#397D1D'),
(79, 'dwa', 37, NULL, '#FF5757'),
(80, 'dwa', 37, NULL, '#AA8DE4');

-- --------------------------------------------------------

--
-- Estrutura para tabela `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(220) NOT NULL,
  `color` varchar(45) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `obs` text DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `events`
--

INSERT INTO `events` (`id`, `title`, `color`, `start`, `end`, `obs`, `id_usuario`) VALUES
(117, 'vtmnc do yuri!', '#FFCF52', '2024-09-03 00:00:00', '2024-09-03 00:00:00', 'Evento sem descrição', 33),
(122, 'teste', '', '2024-09-18 00:00:00', '2024-09-18 00:00:00', 'Evento sem descrição.', 32),
(127, 'dwadadwa', '#FF5757', '2024-09-25 00:00:00', '2024-09-25 00:00:00', 'Evento sem descrição.', 34),
(128, 'dwadwadwa', '#CB6CE6', '2024-09-26 00:00:00', '2024-09-26 00:00:00', 'Evento sem descrição.', 34),
(129, 'dwadwadwa', '#AA8DE4', '2024-09-28 12:00:00', '2024-09-28 12:00:00', 'Evento sem descrição.', 34),
(130, 'dwadaaaaaaa', '#00FFFF', '2024-09-12 00:00:00', '2024-09-12 00:00:00', 'Evento sem descrição.', 34),
(131, 'dwadwaaaaaa', '#F95B99', '2024-09-19 00:00:00', '2024-09-19 00:00:00', 'Evento sem descrição.', 34),
(132, 'dwad', '#F95B99', '2024-10-25 00:00:00', '2024-10-25 00:00:00', 'awd', 36),
(133, 'dwadwa', '#8C52FF', '2024-10-29 00:00:00', '2024-10-29 00:00:00', 'Evento sem descrição.', 40),
(134, 'morte da magali', '#00FFFF', '2024-10-30 00:00:00', '2024-10-30 00:00:00', 'Evento sem descrição.', 37);

-- --------------------------------------------------------

--
-- Estrutura para tabela `nota`
--

CREATE TABLE `nota` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `conteudo` text DEFAULT NULL,
  `arquivo` varchar(255) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `nota`
--

INSERT INTO `nota` (`id`, `id_usuario`, `titulo`, `subtitulo`, `conteudo`, `arquivo`, `id_categoria`, `cor`) VALUES
(61, 34, 'dwadwadwa', '', 'dwadwa', NULL, 56, ''),
(62, 36, 'nigga', 'negro', 'dwa', NULL, 77, '#FFCF52'),
(63, 36, 'dwa', '', 'dwa', NULL, NULL, '#3091FF'),
(76, 40, 'dwa', '', 'dwa', NULL, NULL, '#8C52FF'),
(77, 40, 'dwa', '', 'dwa', NULL, NULL, '#8C52FF'),
(78, 40, 'dwa', '', 'dwa', NULL, NULL, '#8C52FF'),
(79, 40, 'dwa', '', 'dwa', NULL, NULL, '#8C52FF'),
(104, 37, 'dwaD', '', 'dwadwa', 'megalofodase (3).jpg', NULL, NULL),
(105, 37, 'dwa', '', 'dwa', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `profile_pic_url` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `profile_pic_url`, `reset_token`, `reset_token_expires_at`) VALUES
(32, 'dudu', 'dudu@gmail.com', '$2y$10$XdJ/YK1EZf8a4sgTjyMp/uuThcyQPZodF6SZZC6jbbMQQtMB95rv6', NULL, NULL, '2024-09-20 00:08:28'),
(33, 'yuri', 'yuri@gmail.com', '$2y$10$j95IKvEDscoRAselVaxrK.czvfyGKUwBahDWWqng5nW0t2UgrSSDm', NULL, NULL, '2024-09-12 02:05:31'),
(34, 'dudu', 'duduu@gmail.com', '$2y$10$yB19ByH3m0A9SZ20Gv1.i.FLikXUdiLfaXWJj649NDZ0Ul4BMA6z2', NULL, NULL, '2024-09-20 01:18:45'),
(35, 'matheus arrombado', 'matheus@gmail.com', '$2y$10$Vssi6kPzzFGakkIcdXUUGu6FLaDQPSDSp0YJ/sj4uBBqVvHCRLtPO', NULL, NULL, '2024-09-20 01:33:20'),
(36, 'y', 'y@gmail.com', '$2y$10$qAE/Bb0K5jndI03MNF7wi.9anML9rTZwBjetB7sG4Gd8W30MQG8K.', '../img/profile/profile_6.png', NULL, '2024-10-23 23:58:32'),
(37, 'Porpet', 'tmnc@gmail.com', '$2y$10$9yWdiH34/VxBcCLH7GkVG.XYpI14NbZIeBjUucHNOEpmZegSXoXmi', '../img/profile/profile_2.png', NULL, '2024-10-27 01:36:44'),
(38, 'Dany Louca', 'dany@gmail.com', '$2y$10$ZWnH0SImAPOUenF4/7VctOSmI5jUXNB6gx9UFHSBwx0NGyIO4O3Bm', '../img/profile/profile_2.png', NULL, '2024-10-25 15:15:57'),
(39, 'sla', 'fuedase@gmail.com', '$2y$10$eL5zkER2RPspu1KIAgsPzed2nXPkPIOeBOx4LN8LCrKFGY9Fb9Mfy', '../img/profile/profile_3.png', NULL, '2024-10-25 15:38:19'),
(40, 'satã', 'sata@gmail.com', '$2y$10$z.jvJHBuTYyPldSRoZIbyemyJsw48Q3k5bS160ufM00r6Xn.aBy.y', '../img/profile/profile_1.png', NULL, '2024-10-25 15:38:58');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_ibfk_1` (`id_usuario`),
  ADD KEY `fk_categoria_nota` (`id_nota`);

--
-- Índices de tabela `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_usuario` (`id_usuario`);

--
-- Índices de tabela `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de tabela `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT de tabela `nota`
--
ALTER TABLE `nota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_categoria_nota` FOREIGN KEY (`id_nota`) REFERENCES `nota` (`id`);

--
-- Restrições para tabelas `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `nota`
--
ALTER TABLE `nota`
  ADD CONSTRAINT `nota_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
