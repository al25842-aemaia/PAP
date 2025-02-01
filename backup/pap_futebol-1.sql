-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01-Fev-2025 às 11:46
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pap_futebol`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clube`
--

CREATE TABLE `clube` (
  `id_clube` int(11) NOT NULL,
  `nome_clube` varchar(100) DEFAULT NULL,
  `local_clube` varchar(100) DEFAULT NULL,
  `imagem_clube` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clube`
--

INSERT INTO `clube` (`id_clube`, `nome_clube`, `local_clube`, `imagem_clube`) VALUES
(3, 'Benfica', 'Europa', 'imagens_clube/benfica.png'),
(4, 'Porto', 'Europa', 'imagens_clube/porto.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jogador`
--

CREATE TABLE `jogador` (
  `id_jogador` int(11) NOT NULL,
  `nome_jogador` varchar(100) DEFAULT NULL,
  `aposentado` tinyint(1) DEFAULT NULL,
  `numero_camisola` int(11) DEFAULT NULL,
  `imagem_jogador` varchar(255) DEFAULT NULL,
  `id_clube` int(11) DEFAULT NULL,
  `id_nacionalidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `jogador`
--

INSERT INTO `jogador` (`id_jogador`, `nome_jogador`, `aposentado`, `numero_camisola`, `imagem_jogador`, `id_clube`, `id_nacionalidade`) VALUES
(7, 'Trubin', 0, 1, 'imagens_jogador/Trubin.webp', 3, 2),
(8, 'Samuel Soares ', 0, 24, 'imagens_jogador/SamuelSoares.jpg', 3, 2),
(11, 'Andre Gomes', 0, 75, 'imagens_jogador/AndreGomes.jpg', 3, 2),
(12, 'Antonio Silva', 0, 4, 'imagens_jogador/AntonioSilva.jpg', 3, 2),
(13, 'Tomas Araujo', 0, 44, 'imagens_jogador/tomasaraujo.webp', 3, 2),
(14, 'Adrian Bajrami', 0, 81, 'imagens_jogador/adrianbajrami.webp', 3, 4),
(15, 'Otamendi', 0, 30, 'imagens_jogador/otamendi.webp', 3, 5),
(16, 'Jan Niklas Beste', 0, 37, 'imagens_jogador/janniklasbeste.webp', 3, 6),
(17, 'Alvaro Carreras ', 0, 3, 'imagens_jogador/carreras.webp', 3, 7),
(18, 'Alexandar Bah', 0, 6, 'imagens_jogador/alexanderbah.webp', 3, 8),
(19, 'Issa Kabore', 0, 28, 'imagens_jogador/issakabore.webp', 3, 9),
(20, 'Florentino', 0, 61, 'imagens_jogador/florentino.webp', 3, 2),
(21, 'Orkun Kokcu', 0, 10, 'imagens_jogador/orkunkokcu.webp', 3, 10),
(22, 'Fredrik Ausners', 0, 8, 'imagens_jogador/fredrikausners.webp', 3, 11),
(23, 'Leandro Barreiro', 0, 18, 'imagens_jogador/leandrobarreiro.webp', 3, 12),
(24, 'Renato Sanches', 0, 85, 'imagens_jogador/renatosanches.webp', 3, 2),
(25, 'Soualiho Meite', 0, 23, 'imagens_jogador/soualihomeite.webp', 3, 13),
(26, 'Kerem Akturkoglu', 0, 17, 'imagens_jogador/keremakturkoglu.webp', 3, 10),
(27, 'Andreas Schjelderup', 0, 21, 'imagens_jogador/andreasschjelderup.webp', 3, 11),
(28, 'Gianluca Prestianni', 0, 25, 'imagens_jogador/gianucaprestianni.webp', 3, 5),
(29, 'Benjamin Rollheiser', 0, 32, 'imagens_jogador/benjaminrollheiser.webp', 3, 5),
(30, 'Tiago Gouveia', 0, 47, 'imagens_jogador/tiagogouveia.webp', 3, 2),
(31, 'Angel Di Maria', 0, 11, 'imagens_jogador/angeldimaria.webp', 3, 5),
(32, 'Vangelis Pavlidis', 0, 14, 'imagens_jogador/vangelispavlidis.webp', 3, 14),
(33, 'Arthur Cabral', 0, 9, 'imagens_jogador/arthurcabral.webp', 3, 15),
(34, 'Zeki Amdouni', 0, 7, 'imagens_jogador/zekiamdouni.webp', 3, 16),
(35, 'Diogo Costa', 0, 99, 'imagens_jogador/diogocosta.webp', 4, 2),
(36, 'Claudio Ramos', 0, 14, 'imagens_jogador/claudioramos.webp', 4, 2),
(37, 'Samuel Portugal', 0, 94, 'imagens_jogador/samuelportugal.webp', 4, 15),
(38, 'Nehuen Perez', 0, 24, 'imagens_jogador/nehuenperez.webp', 4, 5),
(39, 'Tiago Djalo', 0, 3, 'imagens_jogador/tiagodjalo.webp', 4, 2),
(40, 'Otavio', 0, 4, 'imagens_jogador/otavio.webp', 4, 15),
(41, 'Ze Pedro', 0, 97, 'imagens_jogador/zepedro.webp', 4, 2),
(42, 'Ivan Marcano', 0, 5, 'imagens_jogador/ivanmarcano.webp', 4, 7),
(43, 'Francisco Moura', 0, 74, 'imagens_jogador/franciscomoura.webp', 4, 2),
(44, 'Wendell', 0, 18, 'imagens_jogador/wendell.webp', 4, 15),
(45, 'Zaidu', 0, 12, 'imagens_jogador/zaidu.webp', 4, 17),
(46, 'Joao Mario', 0, 23, 'imagens_jogador/joaomario.webp', 4, 2),
(47, 'Martim Fernandes', 0, 52, 'imagens_jogador/martimfernandes.webp', 4, 2),
(48, 'Alan Varela', 0, 22, 'imagens_jogador/alenvarela.webp', 4, 5),
(49, 'Marko Grujic', 0, 8, 'imagens_jogador/markogrujic.webp', 4, 18),
(50, 'Nico Gonzales', 0, 16, 'imagens_jogador/nicogonzales.webp', 4, 7),
(51, 'Stephen Eustaquio', 0, 6, 'imagens_jogador/stepheneustaquio.webp', 4, 19),
(52, 'Fabio Vieira', 0, 10, 'imagens_jogador/fabiovieira.webp', 4, 2),
(53, 'Rodrigo Moura', 0, 86, 'imagens_jogador/rodrigomoura.webp', 4, 2),
(54, 'Ivan Jaime', 0, 17, 'imagens_jogador/ivanjaime.webp', 4, 7),
(55, 'Vasco Sousa', 0, 15, 'imagens_jogador/vascosousa.webp', 4, 2),
(56, 'Andre Franco', 0, 20, 'imagens_jogador/andrefranco.webp', 4, 2),
(57, 'Pepe', 0, 11, 'imagens_jogador/pepe.webp', 4, 15),
(58, 'Galeno', 0, 13, 'imagens_jogador/galeno.webp', 4, 15),
(59, 'Goncalo Borges', 0, 70, 'imagens_jogador/goncaloborges.webp', 4, 2),
(60, 'Samu Aghehowa', 0, 9, 'imagens_jogador/samu.webp', 4, 7),
(61, 'Fran navarro', 0, 21, 'imagens_jogador/frannavarro.webp', 4, 7),
(62, 'Danny Namaso', 0, 19, 'imagens_jogador/dannynamaso.webp', 4, 20),
(63, 'Deniz Gul', 0, 27, 'imagens_jogador/denizgul.webp', 4, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jogador_posicoes`
--

CREATE TABLE `jogador_posicoes` (
  `id_jogador` int(11) NOT NULL,
  `id_posicao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `jogador_posicoes`
--

INSERT INTO `jogador_posicoes` (`id_jogador`, `id_posicao`) VALUES
(7, 1),
(8, 1),
(11, 1),
(12, 3),
(13, 3),
(14, 2),
(14, 3),
(15, 3),
(16, 2),
(16, 12),
(16, 15),
(17, 2),
(17, 12),
(18, 4),
(18, 12),
(18, 13),
(19, 4),
(19, 13),
(20, 5),
(21, 5),
(21, 10),
(21, 11),
(22, 5),
(22, 10),
(22, 12),
(23, 5),
(23, 10),
(23, 11),
(24, 10),
(24, 11),
(24, 13),
(25, 5),
(25, 10),
(25, 11),
(26, 15),
(26, 17),
(27, 11),
(27, 15),
(27, 16),
(28, 11),
(28, 15),
(28, 17),
(29, 11),
(29, 16),
(29, 17),
(30, 15),
(30, 17),
(31, 11),
(31, 15),
(31, 17),
(32, 11),
(32, 15),
(32, 16),
(33, 16),
(34, 11),
(34, 15),
(34, 16),
(35, 1),
(36, 1),
(37, 1),
(38, 3),
(38, 4),
(39, 2),
(39, 3),
(39, 4),
(40, 2),
(40, 3),
(41, 3),
(42, 2),
(42, 3),
(43, 2),
(43, 12),
(43, 15),
(44, 2),
(44, 3),
(44, 12),
(45, 2),
(46, 4),
(46, 13),
(46, 17),
(47, 2),
(47, 4),
(48, 5),
(48, 10),
(49, 5),
(49, 10),
(50, 5),
(50, 10),
(50, 11),
(51, 5),
(51, 10),
(52, 10),
(52, 11),
(52, 13),
(53, 11),
(53, 15),
(53, 17),
(54, 10),
(54, 11),
(54, 15),
(55, 10),
(55, 11),
(55, 15),
(56, 10),
(56, 11),
(56, 17),
(57, 11),
(57, 15),
(57, 17),
(58, 2),
(58, 15),
(58, 17),
(59, 15),
(59, 17),
(60, 16),
(61, 14),
(61, 16),
(62, 14),
(62, 15),
(62, 16),
(63, 16);

-- --------------------------------------------------------

--
-- Estrutura da tabela `nacionalidade`
--

CREATE TABLE `nacionalidade` (
  `id_nacionalidade` int(11) NOT NULL,
  `nacionalidade` varchar(100) DEFAULT NULL,
  `imagem_nacionalidade` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `nacionalidade`
--

INSERT INTO `nacionalidade` (`id_nacionalidade`, `nacionalidade`, `imagem_nacionalidade`) VALUES
(2, 'Portugal', 'imagens_nacionalidade/Portugal.png'),
(3, 'Ucrânia', 'imagens_nacionalidade/Ucrânia.png'),
(4, 'Albania', 'imagens_nacionalidade/albania.png'),
(5, 'Argentina', 'imagens_nacionalidade/argentina.png'),
(6, 'Alemanha', 'imagens_nacionalidade/alemanha.png'),
(7, 'Espanha', 'imagens_nacionalidade/espanha.png'),
(8, 'Dinamarca', 'imagens_nacionalidade/dinamarca.png'),
(9, 'Burkina Faso', 'imagens_nacionalidade/burkinafaso.png'),
(10, 'Turquia', 'imagens_nacionalidade/turquia.png'),
(11, 'Noruega', 'imagens_nacionalidade/noruega.png'),
(12, 'Luxemburgo', 'imagens_nacionalidade/luxemburgo.png'),
(13, 'Franca', 'imagens_nacionalidade/franca.png'),
(14, 'grecia', 'imagens_nacionalidade/grecia.png'),
(15, 'Brasil', 'imagens_nacionalidade/brasil.png'),
(16, 'Suica', 'imagens_nacionalidade/suica.png'),
(17, 'Nigeria', 'imagens_nacionalidade/nigeria.png'),
(18, 'Servia', 'imagens_nacionalidade/servia.png'),
(19, 'Canada', 'imagens_nacionalidade/canada.png'),
(20, 'Inglaterra', 'imagens_nacionalidade/inglaterra.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `noticiais`
--

CREATE TABLE `noticiais` (
  `id_noticia` int(11) NOT NULL,
  `noticia` text DEFAULT NULL,
  `titulo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `noticiais`
--

INSERT INTO `noticiais` (`id_noticia`, `noticia`, `titulo`) VALUES
(2, 'O Sporting vai defrontar o Famalicão neste sábado, pelas 20h30, em jogo realizado em Vila Nova de Famalicão. Ruben Amorim fez a antevisão à partida nesta sexta-feira, em Alcochete.\r\n\r\nNela, o treinador do Sporting disse o que esperava do outro lado, prometendo dificuldades. «Não é só o clube. É o estádio, vive-se muito o jogo em Famalicão. É muito difícil bater as equipas do mister Evangelista. Demorámos muito até bater o Famalicão», recordou Amorim. \r\n\r\nA formação nortenha ganhou Benfica, ditando o despedimento de Roger Schmidt. «Eles têm só uma derrota. Têm sempre jogadores talentosos e gostam de jogos grandes. Nestes jogos sentem-se muito confortáveis. Defendem muito bem, têm excelentes transições. Este ano jogam com um falso avançado, diferente do Cádiz, que era mais forte de cabeça e no apoio», destacou.\r\n\r\nAmorim identificou outro traço. «Eles não dão muito espaço, têm uma rotina muito interessante entre o ala e o lateral na forma de marcar os nossos jogadores do corredor. Tivemos atenção ao posicionamento dos nossos médios», explicou.\r\n\r\nRuben Amorim resumiu que, apesar destas dificuldades, o Sporting está num momento «em que tem de ganhar estes jogos». ', 'Amorim: «É muito difícil bater as equipas de Armando Evangelista»'),
(3, 'O plantel principal do FC Porto regressou nesta sexta-feira ao trabalho no Olival, onde deu início à preparação para o jogo com o AVS, a contar para a nona jornada do campeonato.\r\n\r\nNo boletim clínico portista figuram os nomes de Iván Marcano (treino condicionado), Zaidu (treino integrado condicionado) e Wendell (treino condicionado).\r\n\r\nNenhum deles é uma novidade, sendo que Wendell foi o mais recente a integrar este lote de lesionados, depois de um choque de cabeças diante do Sintrense. O brasileiro falhou a receção ao Hoffenheim.\r\n\r\nOs dragões voltam a treinar este sábado, às 10h30, no Olival. O próximo jogo decorre na segunda-feira, pelas 20h15, no Estádio do AVS.', 'FC Porto regressa aos treinos no Olival com três condicionados');

-- --------------------------------------------------------

--
-- Estrutura da tabela `posicoes`
--

CREATE TABLE `posicoes` (
  `id_posicao` int(11) NOT NULL,
  `nome_posicao` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `posicoes`
--

INSERT INTO `posicoes` (`id_posicao`, `nome_posicao`) VALUES
(1, 'GR'),
(2, 'DE'),
(3, 'DC'),
(4, 'DD'),
(5, 'MDC'),
(10, 'MC'),
(11, 'MCO'),
(12, 'ME'),
(13, 'MD'),
(14, 'SA'),
(15, 'EE'),
(16, 'PL'),
(17, 'ED');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizador` int(11) NOT NULL,
  `utilizador` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `utilizador`, `senha`) VALUES
(1, 'adimin', '$2y$10$wJY5I.dLAdUcMR6Vs02gf.kbuJfMw0.wIIqjbfS7JaDHc9Xzk3QbG'),
(2, 'leo', '$2y$10$w.QCN/OB.Hd5oUeyte0Qh.HgmPTDaK9YCIoc1JQ8qOkFtY0CNc9MK');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `clube`
--
ALTER TABLE `clube`
  ADD PRIMARY KEY (`id_clube`);

--
-- Índices para tabela `jogador`
--
ALTER TABLE `jogador`
  ADD PRIMARY KEY (`id_jogador`),
  ADD KEY `id_clube` (`id_clube`),
  ADD KEY `id_nacionalidade` (`id_nacionalidade`);

--
-- Índices para tabela `jogador_posicoes`
--
ALTER TABLE `jogador_posicoes`
  ADD PRIMARY KEY (`id_jogador`,`id_posicao`),
  ADD KEY `id_posicao` (`id_posicao`);

--
-- Índices para tabela `nacionalidade`
--
ALTER TABLE `nacionalidade`
  ADD PRIMARY KEY (`id_nacionalidade`);

--
-- Índices para tabela `noticiais`
--
ALTER TABLE `noticiais`
  ADD PRIMARY KEY (`id_noticia`);

--
-- Índices para tabela `posicoes`
--
ALTER TABLE `posicoes`
  ADD PRIMARY KEY (`id_posicao`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizador`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clube`
--
ALTER TABLE `clube`
  MODIFY `id_clube` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `jogador`
--
ALTER TABLE `jogador`
  MODIFY `id_jogador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `nacionalidade`
--
ALTER TABLE `nacionalidade`
  MODIFY `id_nacionalidade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `noticiais`
--
ALTER TABLE `noticiais`
  MODIFY `id_noticia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `posicoes`
--
ALTER TABLE `posicoes`
  MODIFY `id_posicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `jogador`
--
ALTER TABLE `jogador`
  ADD CONSTRAINT `jogador_ibfk_1` FOREIGN KEY (`id_clube`) REFERENCES `clube` (`id_clube`),
  ADD CONSTRAINT `jogador_ibfk_2` FOREIGN KEY (`id_nacionalidade`) REFERENCES `nacionalidade` (`id_nacionalidade`);

--
-- Limitadores para a tabela `jogador_posicoes`
--
ALTER TABLE `jogador_posicoes`
  ADD CONSTRAINT `jogador_posicoes_ibfk_1` FOREIGN KEY (`id_jogador`) REFERENCES `jogador` (`id_jogador`) ON DELETE CASCADE,
  ADD CONSTRAINT `jogador_posicoes_ibfk_2` FOREIGN KEY (`id_posicao`) REFERENCES `posicoes` (`id_posicao`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
