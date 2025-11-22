-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/11/2025 às 03:49
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_cdpio`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_restaurante`
--

CREATE TABLE `tb_restaurante` (
  `idRestaurante` int(11) NOT NULL,
  `nomeRestaurante` varchar(100) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `idTags` int(11) NOT NULL,
  `notaMedia` float NOT NULL,
  `visualizacao` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `tb_restaurante`
--

INSERT INTO `tb_restaurante` (`idRestaurante`, `nomeRestaurante`, `endereco`, `idTags`, `notaMedia`, `visualizacao`, `descricao`) VALUES
(2, 'Restaurante Bella', 'Rua A, 123', 1, 4.5, 100, 'Comida italiana deliciosa'),
(3, 'Sushi Master', 'Rua B, 456', 2, 4.8, 200, 'Sushi fresco e variado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_salvo`
--

CREATE TABLE `tb_salvo` (
  `idSalvo` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idRestaurante` int(11) NOT NULL,
  `notaRestaurante` varchar(100) NOT NULL,
  `avaliado` tinyint(1) NOT NULL,
  `descricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `tb_salvo`
--

INSERT INTO `tb_salvo` (`idSalvo`, `idUsuario`, `idRestaurante`, `notaRestaurante`, `avaliado`, `descricao`) VALUES
(6, 1, 2, '5', 1, 'Melhor massa que já comi!'),
(7, 1, 3, '4', 0, 'Ainda quero experimentar mais pratos');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_tags`
--

CREATE TABLE `tb_tags` (
  `idTags` int(11) NOT NULL,
  `nomeTag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `tb_tags`
--

INSERT INTO `tb_tags` (`idTags`, `nomeTag`) VALUES
(1, 'Italiana'),
(2, 'Japonesa'),
(3, 'Fast Food');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `idUsuario` int(11) NOT NULL,
  `nomeUsuario` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`idUsuario`, `nomeUsuario`, `email`, `senha`) VALUES
(1, 'Jheni', 'jheni@jheni.com', '123');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_restaurante`
--
ALTER TABLE `tb_restaurante`
  ADD PRIMARY KEY (`idRestaurante`),
  ADD KEY `fk_restaurante_tag` (`idTags`);

--
-- Índices de tabela `tb_salvo`
--
ALTER TABLE `tb_salvo`
  ADD PRIMARY KEY (`idSalvo`),
  ADD KEY `fk_salvo_usuario` (`idUsuario`),
  ADD KEY `fk_salvo_restaurante` (`idRestaurante`);

--
-- Índices de tabela `tb_tags`
--
ALTER TABLE `tb_tags`
  ADD PRIMARY KEY (`idTags`);

--
-- Índices de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_restaurante`
--
ALTER TABLE `tb_restaurante`
  MODIFY `idRestaurante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_salvo`
--
ALTER TABLE `tb_salvo`
  MODIFY `idSalvo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tb_tags`
--
ALTER TABLE `tb_tags`
  MODIFY `idTags` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_restaurante`
--
ALTER TABLE `tb_restaurante`
  ADD CONSTRAINT `fk_restaurante_tag` FOREIGN KEY (`idTags`) REFERENCES `tb_tags` (`idTags`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `tb_salvo`
--
ALTER TABLE `tb_salvo`
  ADD CONSTRAINT `fk_salvo_restaurante` FOREIGN KEY (`idRestaurante`) REFERENCES `tb_restaurante` (`idRestaurante`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_salvo_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `tb_usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
