-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 94.60.220.12    Database: pap_futebol
-- ------------------------------------------------------
-- Server version	5.5.5-10.11.11-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clube`
--

DROP TABLE IF EXISTS `clube`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clube` (
  `id_clube` int(11) NOT NULL AUTO_INCREMENT,
  `nome_clube` varchar(100) DEFAULT NULL,
  `local_clube` varchar(100) DEFAULT NULL,
  `imagem_clube` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_clube`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clube`
--

LOCK TABLES `clube` WRITE;
/*!40000 ALTER TABLE `clube` DISABLE KEYS */;
INSERT INTO `clube` VALUES (3,'Benfica','Liga Portugal','imagens_clube/benfica.png'),(4,'Porto','Liga Portugal','imagens_clube/porto.png'),(5,'Sporting','Liga Portugal','imagens_clube/sporting.png'),(6,'Real Madrid','La Liga','imagens_clube/realmadrid.png'),(7,'Barcelona','La Liga','imagens_clube/barcelona.png'),(8,'Atletico Madrid','La Liga','imagens_clube/atleticomadrid.png'),(9,'Braga','Liga Portugal','imagens_clube/braga.png');
/*!40000 ALTER TABLE `clube` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jogador`
--

DROP TABLE IF EXISTS `jogador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jogador` (
  `id_jogador` int(11) NOT NULL AUTO_INCREMENT,
  `nome_jogador` varchar(100) DEFAULT NULL,
  `aposentado` tinyint(1) DEFAULT NULL,
  `numero_camisola` int(11) DEFAULT NULL,
  `imagem_jogador` varchar(255) DEFAULT NULL,
  `id_clube` int(11) DEFAULT NULL,
  `id_nacionalidade` int(11) DEFAULT NULL,
  `overall` int(11) DEFAULT NULL,
  `potencial` int(11) DEFAULT NULL,
  `salario` int(11) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_jogador`),
  KEY `id_clube` (`id_clube`),
  KEY `id_nacionalidade` (`id_nacionalidade`),
  CONSTRAINT `jogador_ibfk_1` FOREIGN KEY (`id_clube`) REFERENCES `clube` (`id_clube`),
  CONSTRAINT `jogador_ibfk_2` FOREIGN KEY (`id_nacionalidade`) REFERENCES `nacionalidade` (`id_nacionalidade`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogador`
--

LOCK TABLES `jogador` WRITE;
/*!40000 ALTER TABLE `jogador` DISABLE KEYS */;
INSERT INTO `jogador` VALUES (7,'Trubin',0,1,'imagens_jogador/Trubin.webp',3,3,79,86,8000,30500000),(8,'Samuel Soares ',0,24,'imagens_jogador/SamuelSoares.jpg',3,2,68,80,5000,2600000),(11,'Andre Gomes',0,75,'imagens_jogador/AndreGomes.jpg',3,2,67,83,3000,2300000),(12,'Antonio Silva',0,4,'imagens_jogador/AntonioSilva.jpg',3,2,78,87,11000,29000000),(13,'Tomas Araujo',0,44,'imagens_jogador/tomasaraujo.webp',3,2,78,86,13000,29500000),(14,'Adrian Bajrami',0,81,'imagens_jogador/adrianbajrami.webp',3,4,66,79,7000,2000000),(15,'Otamendi',0,30,'imagens_jogador/otamendi.webp',3,5,81,81,14000,4600000),(17,'Alvaro Carreras ',0,3,'imagens_jogador/carreras.webp',3,7,77,86,10000,22500000),(18,'Alexandar Bah',0,6,'imagens_jogador/alexanderbah.webp',3,8,79,80,16000,19000000),(20,'Florentino',0,61,'imagens_jogador/florentino.webp',3,2,80,84,15000,27500000),(21,'Orkun Kokcu',0,10,'imagens_jogador/orkunkokcu.webp',3,10,81,85,17000,36500000),(22,'Fredrik Ausners',0,8,'imagens_jogador/fredrikausners.webp',3,11,80,80,19000,21000000),(23,'Leandro Barreiro',0,18,'imagens_jogador/leandrobarreiro.webp',3,12,76,81,12000,11500000),(24,'Renato Sanches',0,85,'imagens_jogador/renatosanches.webp',3,2,76,76,62000,8000000),(26,'Kerem Akturkoglu',0,17,'imagens_jogador/keremakturkoglu.webp',3,10,79,80,18000,21000000),(27,'Andreas Schjelderup',0,21,'imagens_jogador/andreasschjelderup.webp',3,11,73,84,9000,6500000),(28,'Gianluca Prestianni',0,25,'imagens_jogador/gianucaprestianni.webp',3,5,70,84,6000,3700000),(30,'Tiago Gouveia',0,47,'imagens_jogador/tiagogouveia.webp',3,2,73,82,10000,7000000),(31,'Angel Di Maria',0,11,'imagens_jogador/angeldimaria.webp',3,5,83,83,18000,12000000),(32,'Vangelis Pavlidis',0,14,'imagens_jogador/vangelispavlidis.webp',3,14,80,83,19000,28500000),(33,'Arthur Cabral',0,9,'imagens_jogador/arthurcabral.webp',3,15,77,80,16000,14000000),(34,'Zeki Amdouni',0,7,'imagens_jogador/zekiamdouni.webp',3,16,74,80,49000,6500000),(35,'Diogo Costa',0,99,'imagens_jogador/diogocosta.webp',4,2,84,90,14000,54000000),(36,'Claudio Ramos',0,14,'imagens_jogador/claudioramos.webp',4,2,73,73,9000,1500000),(37,'Samuel Portugal',0,94,'imagens_jogador/samuelportugal.webp',4,15,71,71,8000,1200000),(38,'Nehuen Perez',0,24,'imagens_jogador/nehuenperez.webp',4,5,76,81,17000,10000000),(39,'Tiago Djalo',0,3,'imagens_jogador/tiagodjalo.webp',4,2,76,81,69000,10000000),(40,'Otavio',0,4,'imagens_jogador/otavio.webp',4,15,76,84,13000,15500000),(41,'Ze Pedro',0,97,'imagens_jogador/zepedro.webp',4,2,74,76,14000,4500000),(42,'Ivan Marcano',0,5,'imagens_jogador/ivanmarcano.webp',4,7,74,74,11000,750000),(43,'Francisco Moura',0,74,'imagens_jogador/franciscomoura.webp',4,2,75,80,13000,8000000),(45,'Zaidu',0,12,'imagens_jogador/zaidu.webp',4,17,73,74,13000,3200000),(46,'Joao Mario',0,23,'imagens_jogador/joaomario.webp',4,2,77,81,14000,14000000),(47,'Martim Fernandes',0,52,'imagens_jogador/martimfernandes.webp',4,2,74,86,9000,9500000),(48,'Alan Varela',0,22,'imagens_jogador/alenvarela.webp',4,5,79,85,17000,26000000),(49,'Marko Grujic',0,8,'imagens_jogador/markogrujic.webp',4,18,74,74,15000,4200000),(51,'Stephen Eustaquio',0,6,'imagens_jogador/stepheneustaquio.webp',4,19,77,78,16000,11000000),(52,'Fabio Vieira',0,10,'imagens_jogador/fabiovieira.webp',4,2,77,80,83000,14500000),(53,'Rodrigo Moura',0,86,'imagens_jogador/rodrigomoura.webp',4,2,70,88,6000,4000000),(55,'Vasco Sousa',0,15,'imagens_jogador/vascosousa.webp',4,2,69,83,8000,3400000),(56,'Andre Franco',0,20,'imagens_jogador/andrefranco.webp',4,2,73,73,13000,3300000),(57,'Pepe',0,11,'imagens_jogador/pepe.webp',4,15,81,81,23000,27500000),(59,'Goncalo Borges',0,70,'imagens_jogador/goncaloborges.webp',4,2,71,77,11000,3000000),(60,'Samu Aghehowa',0,9,'imagens_jogador/samu.webp',4,7,78,87,15000,31000000),(62,'Danny Namaso',0,19,'imagens_jogador/dannynamaso.webp',4,20,73,79,13000,4900000),(63,'Deniz Gul',0,27,'imagens_jogador/denizgul.webp',4,10,66,80,8000,2000000),(64,'Franco Israel',0,1,'imagens_jogador/francoisrael.webp',5,21,76,82,7000,9500000),(65,'Rui Silva',0,24,'imagens_jogador/ruisilva.webp',5,2,81,81,25000,17000000),(66,'Diego Callai',0,41,'imagens_jogador/diegocallai.webp',5,15,69,82,3000,2900000),(67,'Diogo Pinto',0,51,'imagens_jogador/diogopinto.webp',5,2,68,81,3000,2500000),(68,'Gonçalo Inácio',0,25,'imagens_jogador/goncaloinacio.webp',5,2,80,87,15000,40000000),(69,'Ousmane Diomande',0,26,'imagens_jogador/ousmanediomande.webp',5,22,79,86,12000,32000000),(70,'Debast',0,6,'imagens_jogador/zenodebast.webp',5,23,76,86,10000,15500000),(71,'Eduardo Quaresma',0,72,'imagens_jogador/eduardoquaresma.webp',5,2,76,83,11000,14000000),(72,'St. Juste',0,3,'imagens_jogador/jerrystjuste.webp',5,24,77,79,14000,11000000),(73,'Matheus Reis',0,2,'imagens_jogador/matheusreis.webp',5,15,77,77,15000,9000000),(74,'Fresneda',0,22,'imagens_jogador/ivanfresneda.webp',5,7,74,82,8000,8500000),(75,'Ricardo Esgaio',0,47,'imagens_jogador/ricardoesgaio.webp',5,2,73,73,11000,2200000),(76,'Hjulmand',0,42,'imagens_jogador/mortenhjulmand.webp',5,8,82,86,19000,38500000),(77,'Morita',0,5,'imagens_jogador/hidemasamorita.webp',5,25,80,80,19000,20500000),(78,'Daniel Braganca',0,23,'imagens_jogador/danielbraganca.webp',5,2,78,81,15000,18500000),(79,'Joao Simoes',0,52,'imagens_jogador/joaosimoes.webp',5,2,68,83,5000,2700000),(80,'Maxi Araujo',0,20,'imagens_jogador/maxiaraujo.webp',5,21,76,79,12000,10000000),(81,'Nuno Santos',0,11,'imagens_jogador/nunosantos.webp',5,2,79,79,18000,17000000),(82,'Pedro Goncalves',0,8,'imagens_jogador/pedrogoncalves.webp',5,2,83,84,24000,43000000),(83,'Biel',0,30,'imagens_jogador/biel.webp',5,15,72,80,10000,5000000),(84,'Quenda',0,57,'imagens_jogador/geovanyquenda.webp',5,2,75,87,7000,13000000),(85,'Trincao',0,17,'imagens_jogador/trincao.webp',5,2,81,83,17000,33500000),(86,'Geny Catamo',0,21,'imagens_jogador/genycatamo.webp',5,26,77,83,13000,16500000),(87,'Gyokeres',0,9,'imagens_jogador/victorgyokeres.webp',5,27,86,89,32000,90000000),(88,'Harder',0,19,'imagens_jogador/conraharder.webp',5,8,71,86,8000,4500000),(89,'Courtois ',0,1,'imagens_jogador/Courtois.webp',6,23,89,89,220000,51000000),(90,'lunin',0,13,'imagens_jogador/lunin.webp',6,3,81,86,95000,30000000),(91,'Militão',0,3,'imagens_jogador/militao.webp',6,15,85,88,230000,62500000),(93,'Rudiger',0,22,'imagens_jogador/rudiger.webp',6,6,88,88,310000,62500000),(94,'Alaba',0,4,'imagens_jogador/alaba.webp',6,28,84,84,220000,27500000),(95,'Asencio',0,35,'imagens_jogador/asencio.webp',6,7,70,82,59000,3700000),(96,'vallejo',0,18,'imagens_jogador/vallejo.webp',6,7,72,73,89000,24000000),(97,'Mendy',0,23,'imagens_jogador/mendy.webp',6,13,83,83,200000,32000000),(98,'Garcia',0,20,'imagens_jogador/garcia.webp',6,7,79,84,125000,24500000),(99,'carvajal',0,2,'imagens_jogador/carvajal.webp',6,7,86,86,260000,47000000),(100,'vazquez',0,17,'imagens_jogador/vazquez.webp',6,7,80,80,160000,11000000),(101,'Tchouameni',0,14,'imagens_jogador/tchouameni.webp',6,13,85,88,200000,66500000),(102,'Valverde',0,8,'imagens_jogador/valverde.webp',6,21,88,91,300000,120000000),(103,'Camavinga',0,6,'imagens_jogador/camavinga.webp',6,13,83,90,150000,73500000),(104,'Modric',0,10,'imagens_jogador/modric.webp',6,29,85,85,185000,17000000),(105,'Ceballos',0,19,'imagens_jogador/ceballos.webp',6,7,80,80,160000,22500000),(106,'Bellingham',0,5,'imagens_jogador/bellingham.webp',6,20,90,94,280000,174500000),(107,'Vinicius Jr',0,7,'imagens_jogador/viniciusjr.webp',6,15,90,94,340000,171500000),(108,'Rodrygo',0,11,'imagens_jogador/rodrygo.webp',6,15,86,91,240000,102500000),(109,'Guler',0,15,'imagens_jogador/guler.webp',6,10,78,90,95000,33500000),(110,'Brahim Diaz',0,21,'imagens_jogador/brahimdiaz.webp',6,7,82,86,160000,43500000),(111,'Mbappe',0,9,'imagens_jogador/mbappe.webp',6,13,90,93,380000,160000000),(112,'Endrick',0,16,'imagens_jogador/endric.webp',6,15,77,91,77000,24000000),(113,'Pena',0,13,'imagens_jogador/pena.webp',7,7,76,81,48000,9000000),(114,'szczesny',0,25,'imagens_jogador/szczesny.webp',7,30,84,84,100000,7000000),(116,'Cubarsi',0,2,'imagens_jogador/cubarsi.webp',7,7,80,90,54000,40500000),(117,'Araujo',0,4,'imagens_jogador/araujo.webp',7,21,85,90,155000,71500000),(118,'christensen',0,15,'imagens_jogador/christensen.webp',7,8,82,84,130000,30500000),(119,'Garcia',0,24,'imagens_jogador/ericgarcia.webp',7,7,78,83,80000,19000000),(120,'Martinez',0,5,'imagens_jogador/martinez.webp',7,7,83,83,145000,17500000),(121,'Balde',0,3,'imagens_jogador/balde.webp',7,7,82,87,90000,42000000),(122,'Martin',0,35,'imagens_jogador/martin.webp',7,8,70,78,50000,3400000),(123,'Kounde',0,23,'imagens_jogador/kounde.webp',7,13,86,89,170000,83000000),(124,'Fort',0,32,'imagens_jogador/fort.webp',7,7,68,82,27000,2600000),(125,'casado',0,28,'imagens_jogador/casado.webp',7,7,75,86,56000,11500000),(126,'Bernal',0,28,'imagens_jogador/bernal.webp',7,7,66,84,26000,2000000),(127,'Pedri',0,8,'imagens_jogador/pedri.webp',7,7,86,90,140000,98500000),(128,'Gavi',0,6,'imagens_jogador/gavi.webp',7,7,83,89,100000,55000000),(129,'Lopez',0,16,'imagens_jogador/lopez.webp',7,7,77,86,67000,23500000),(130,'De Jong',0,21,'imagens_jogador/Dejong.webp',7,24,86,87,185000,77500000),(131,'Olmo',0,20,'imagens_jogador/olmo.webp',7,7,85,87,175000,66500000),(132,'Torre',0,14,'imagens_jogador/torre.webp',7,7,74,84,57000,9500000),(133,'Raphinha',0,11,'imagens_jogador/raphinha.webp',7,15,87,87,200000,85000000),(134,'Yamal',0,19,'imagens_jogador/yamal.webp',7,7,85,94,90000,111500000),(135,'Torres',0,7,'imagens_jogador/torres.webp',7,7,80,83,105000,29000000),(136,'Lewandowski',0,9,'imagens_jogador/Lewandowski.webp',7,30,89,89,210000,44000000),(137,'victor',0,18,'imagens_jogador/victor.webp',7,7,77,85,71000,23000000),(138,'Oblak',0,13,'imagens_jogador/oblak.webp',8,31,88,88,87000,48500000),(139,'Musso',0,1,'imagens_jogador/musso.webp',8,5,79,80,43000,13000000),(140,'Le Normand',0,24,'imagens_jogador/lenormand.webp',8,7,83,85,82000,38500000),(141,'Gimenez',0,2,'imagens_jogador/gimenez.webp',8,21,83,83,86000,30500000),(142,'Lenglet',0,15,'imagens_jogador/lenglet.webp',8,13,76,76,85000,6500000),(143,'Witsel',0,20,'imagens_jogador/witsel.webp',8,23,80,80,68000,5500000),(144,'Azipilicueta',0,3,'imagens_jogador/azpilicueta.webp',8,7,79,79,64000,5500000),(145,'Mandava',0,23,'imagens_jogador/mandava.webp',8,26,80,80,68000,18000000),(146,'Galan',0,21,'imagens_jogador/galan.webp',8,7,80,80,68000,18500000),(147,'Molina',0,16,'imagens_jogador/molina.webp',8,5,82,85,75000,36500000),(148,'Barrios',0,8,'imagens_jogador/barrios.webp',8,7,78,87,43000,31500000),(149,'Gallagher',0,4,'imagens_jogador/gallagher.webp',8,20,81,86,63000,38500000),(150,'De Paul',0,5,'imagens_jogador/depaul.webp',8,5,83,83,90000,34500000),(152,'Koke',0,6,'imagens_jogador/koke.webp',8,7,83,83,86000,24000000),(153,'Llorente',0,14,'imagens_jogador/llorente.webp',8,7,83,83,90000,35500000),(154,'Lemar',0,11,'imagens_jogador/lemar.webp',8,13,80,80,71000,21000000),(155,'Samuel Lino',0,12,'imagens_jogador/lino.webp',8,15,79,82,56000,24000000),(156,'Riquelme',0,17,'imagens_jogador/riquelme.webp',8,7,78,84,51000,22000000),(157,'Correa',0,10,'imagens_jogador/correa.webp',8,5,82,82,89000,30500000),(158,'Simeone',0,22,'imagens_jogador/simeone.webp',8,5,75,85,36000,12500000),(159,'Julian Alvarez',0,19,'imagens_jogador/alvarez.webp',8,5,85,88,100000,74500000),(160,'Sorloth',0,9,'imagens_jogador/sorloth.webp',8,11,82,82,89000,31000000),(161,'Griezman',0,7,'imagens_jogador/griezman.webp',8,13,88,88,150000,58500000),(162,'Ricardo Horta',0,21,'imagens_jogador/ricardohorta.webp',9,2,81,81,23000,25500000),(163,'Hornicek',0,91,'imagens_jogador/horniseck.webp',9,32,68,74,4000,1600000),(164,'Tiago Sa',0,12,'imagens_jogador/tiagosa.webp',9,2,67,67,7000,700000);
/*!40000 ALTER TABLE `jogador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jogador_posicoes`
--

DROP TABLE IF EXISTS `jogador_posicoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jogador_posicoes` (
  `id_jogador` int(11) NOT NULL,
  `id_posicao` int(11) NOT NULL,
  PRIMARY KEY (`id_jogador`,`id_posicao`),
  KEY `id_posicao` (`id_posicao`),
  CONSTRAINT `jogador_posicoes_ibfk_1` FOREIGN KEY (`id_jogador`) REFERENCES `jogador` (`id_jogador`) ON DELETE CASCADE,
  CONSTRAINT `jogador_posicoes_ibfk_2` FOREIGN KEY (`id_posicao`) REFERENCES `posicoes` (`id_posicao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogador_posicoes`
--

LOCK TABLES `jogador_posicoes` WRITE;
/*!40000 ALTER TABLE `jogador_posicoes` DISABLE KEYS */;
INSERT INTO `jogador_posicoes` VALUES (7,1),(8,1),(11,1),(12,3),(13,3),(14,2),(14,3),(15,3),(17,2),(17,12),(18,4),(18,12),(18,13),(20,5),(21,5),(21,10),(21,11),(22,5),(22,10),(22,12),(23,5),(23,10),(23,11),(24,10),(24,11),(24,13),(26,15),(26,17),(27,11),(27,15),(27,16),(28,11),(28,15),(28,17),(30,15),(30,17),(31,11),(31,15),(31,17),(32,11),(32,15),(32,16),(33,16),(34,11),(34,15),(34,16),(35,1),(36,1),(37,1),(38,3),(38,4),(39,2),(39,3),(39,4),(40,2),(40,3),(41,3),(42,2),(42,3),(43,2),(43,12),(43,15),(45,2),(46,4),(46,13),(46,17),(47,2),(47,4),(48,5),(48,10),(49,5),(49,10),(51,5),(51,10),(52,10),(52,11),(52,13),(53,11),(53,15),(53,17),(55,10),(55,11),(55,15),(56,10),(56,11),(56,17),(57,11),(57,15),(57,17),(59,15),(59,17),(60,16),(62,14),(62,15),(62,16),(63,16),(64,1),(65,1),(66,1),(67,1),(68,2),(68,3),(68,5),(69,3),(70,3),(70,5),(71,3),(71,4),(72,3),(73,2),(73,3),(73,12),(74,3),(74,4),(74,13),(75,4),(75,13),(75,17),(76,5),(76,10),(77,5),(77,10),(78,5),(78,10),(78,11),(79,10),(79,11),(79,13),(80,2),(80,12),(80,15),(81,2),(81,12),(81,15),(82,10),(82,15),(82,17),(83,15),(83,16),(83,17),(84,13),(84,15),(84,17),(85,15),(85,16),(85,17),(86,13),(86,15),(86,17),(87,16),(88,15),(88,16),(88,17),(89,1),(90,1),(91,3),(91,4),(93,3),(93,4),(94,2),(94,3),(94,10),(95,3),(96,3),(96,4),(97,2),(98,2),(99,3),(99,4),(100,4),(100,15),(100,17),(101,3),(101,5),(101,10),(102,10),(102,13),(102,17),(103,2),(103,3),(103,5),(104,10),(104,11),(105,10),(105,11),(105,12),(106,10),(106,11),(106,12),(107,15),(107,16),(108,15),(108,16),(108,17),(109,10),(109,11),(109,17),(110,11),(110,15),(110,17),(111,15),(111,16),(111,17),(112,14),(112,16),(113,1),(114,1),(116,3),(117,3),(117,4),(118,3),(118,5),(119,3),(119,4),(119,5),(120,3),(121,2),(121,15),(122,2),(122,3),(123,3),(123,4),(124,2),(124,4),(125,3),(125,4),(125,5),(126,5),(126,10),(127,10),(127,11),(127,15),(128,10),(128,11),(128,17),(129,10),(129,11),(129,17),(130,5),(130,10),(131,11),(131,15),(131,17),(132,10),(132,11),(132,15),(133,11),(133,15),(133,17),(134,15),(134,17),(135,15),(135,16),(135,17),(136,16),(137,15),(137,16),(137,17),(138,1),(139,1),(140,3),(141,3),(142,3),(143,3),(143,5),(143,10),(144,2),(144,3),(144,4),(145,2),(145,3),(145,12),(146,2),(146,12),(146,15),(147,4),(147,13),(148,5),(148,10),(148,11),(149,5),(149,10),(149,11),(150,10),(150,11),(152,5),(152,10),(153,4),(153,10),(153,13),(154,11),(154,15),(154,17),(155,12),(155,15),(155,17),(156,11),(156,15),(156,17),(157,14),(157,16),(157,17),(158,15),(158,16),(158,17),(159,15),(159,16),(159,17),(160,16),(161,14),(161,15),(161,16),(162,11),(162,15),(162,17),(163,1),(164,1);
/*!40000 ALTER TABLE `jogador_posicoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nacionalidade`
--

DROP TABLE IF EXISTS `nacionalidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nacionalidade` (
  `id_nacionalidade` int(11) NOT NULL AUTO_INCREMENT,
  `nacionalidade` varchar(100) DEFAULT NULL,
  `imagem_nacionalidade` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_nacionalidade`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nacionalidade`
--

LOCK TABLES `nacionalidade` WRITE;
/*!40000 ALTER TABLE `nacionalidade` DISABLE KEYS */;
INSERT INTO `nacionalidade` VALUES (2,'Portugal','imagens_nacionalidade/Portugal.png'),(3,'Ucrânia','imagens_nacionalidade/Ucrânia.png'),(4,'Albania','imagens_nacionalidade/albania.png'),(5,'Argentina','imagens_nacionalidade/argentina.png'),(6,'Alemanha','imagens_nacionalidade/alemanha.png'),(7,'Espanha','imagens_nacionalidade/espanha.png'),(8,'Dinamarca','imagens_nacionalidade/dinamarca.png'),(9,'Burkina Faso','imagens_nacionalidade/burkinafaso.png'),(10,'Turquia','imagens_nacionalidade/turquia.png'),(11,'Noruega','imagens_nacionalidade/noruega.png'),(12,'Luxemburgo','imagens_nacionalidade/luxemburgo.png'),(13,'Franca','imagens_nacionalidade/franca.png'),(14,'grecia','imagens_nacionalidade/grecia.png'),(15,'Brasil','imagens_nacionalidade/brasil.png'),(16,'Suica','imagens_nacionalidade/suica.png'),(17,'Nigeria','imagens_nacionalidade/nigeria.png'),(18,'Servia','imagens_nacionalidade/servia.png'),(19,'Canada','imagens_nacionalidade/canada.png'),(20,'Inglaterra','imagens_nacionalidade/inglaterra.png'),(21,'Uruguai','imagens_nacionalidade/uruguai.png'),(22,'Costa do Marfim','imagens_nacionalidade/costadomarfim.png'),(23,'Belgica','imagens_nacionalidade/belgica.png'),(24,'Paises Baixo','imagens_nacionalidade/paisesbaixos.png'),(25,'Japao','imagens_nacionalidade/japao.png'),(26,'Africa do Sul','imagens_nacionalidade/africadosul.png'),(27,'Suecia','imagens_nacionalidade/suecia.png'),(28,'austria','imagens_nacionalidade/austria.png'),(29,'Croacia','imagens_nacionalidade/croacia.png'),(30,'Polonia','imagens_nacionalidade/polonia.png'),(31,'Eslovenia','imagens_nacionalidade/eslovenia.png'),(32,'Republica Checa','imagens_nacionalidade/chequia.png'),(33,'Mali','imagens_nacionalidade/mali.png');
/*!40000 ALTER TABLE `nacionalidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `noticiais`
--

DROP TABLE IF EXISTS `noticiais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `noticiais` (
  `id_noticia` int(11) NOT NULL AUTO_INCREMENT,
  `noticia` text DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_noticia`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticiais`
--

LOCK TABLES `noticiais` WRITE;
/*!40000 ALTER TABLE `noticiais` DISABLE KEYS */;
INSERT INTO `noticiais` VALUES (2,'O Sporting vai defrontar o Famalicão neste sábado, pelas 20h30, em jogo realizado em Vila Nova de Famalicão. Ruben Amorim fez a antevisão à partida nesta sexta-feira, em Alcochete.\r\n\r\nNela, o treinador do Sporting disse o que esperava do outro lado, prometendo dificuldades. «Não é só o clube. É o estádio, vive-se muito o jogo em Famalicão. É muito difícil bater as equipas do mister Evangelista. Demorámos muito até bater o Famalicão», recordou Amorim. \r\n\r\nA formação nortenha ganhou Benfica, ditando o despedimento de Roger Schmidt. «Eles têm só uma derrota. Têm sempre jogadores talentosos e gostam de jogos grandes. Nestes jogos sentem-se muito confortáveis. Defendem muito bem, têm excelentes transições. Este ano jogam com um falso avançado, diferente do Cádiz, que era mais forte de cabeça e no apoio», destacou.\r\n\r\nAmorim identificou outro traço. «Eles não dão muito espaço, têm uma rotina muito interessante entre o ala e o lateral na forma de marcar os nossos jogadores do corredor. Tivemos atenção ao posicionamento dos nossos médios», explicou.\r\n\r\nRuben Amorim resumiu que, apesar destas dificuldades, o Sporting está num momento «em que tem de ganhar estes jogos». ','Amorim: «É muito difícil bater as equipas de Armando Evangelista»'),(3,'O plantel principal do FC Porto regressou nesta sexta-feira ao trabalho no Olival, onde deu início à preparação para o jogo com o AVS, a contar para a nona jornada do campeonato.\r\n\r\nNo boletim clínico portista figuram os nomes de Iván Marcano (treino condicionado), Zaidu (treino integrado condicionado) e Wendell (treino condicionado).\r\n\r\nNenhum deles é uma novidade, sendo que Wendell foi o mais recente a integrar este lote de lesionados, depois de um choque de cabeças diante do Sintrense. O brasileiro falhou a receção ao Hoffenheim.\r\n\r\nOs dragões voltam a treinar este sábado, às 10h30, no Olival. O próximo jogo decorre na segunda-feira, pelas 20h15, no Estádio do AVS.','FC Porto regressa aos treinos no Olival com três condicionados'),(4,'Victor Lindelof, defesa-central internacional sueco do Manchester United, de 30 anos, falou esta segunda-feira sobre o próximo passo na carreira. Confrontado com a possibilidade de regressar ao Benfica, onde fez formação e alinhou pela equipa principal antes de se mudar para a Premier League, em 2017/18, não fechou essa porta.\r\n«É difícil dizer. Mas o Benfica como clube significa muito para mim. É o clube que me transformou no jogador que sou hoje. Não sei o que vai acontecer e qual será a minha decisão. Mas é um clube que significa muito para mim e um clube que eu gosto. Não tenho apenas em mim mesmo para pensar. É uma decisão que temos que tomar juntos como uma família. Claro, tento pensar no futebol e no que pode ser melhor para mim. Mas muitas vezes anda de mãos dadas. Muitas vezes é bom para a família onde eu acho que será bom», disse o jogador de 30 anos, na conferência de imprensa de antevisão ao jogo particular com a Irlanda do Norte de terça-feira.','Lindelof: «O Benfica signfica muito para mim»');
/*!40000 ALTER TABLE `noticiais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posicoes`
--

DROP TABLE IF EXISTS `posicoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posicoes` (
  `id_posicao` int(11) NOT NULL AUTO_INCREMENT,
  `nome_posicao` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_posicao`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posicoes`
--

LOCK TABLES `posicoes` WRITE;
/*!40000 ALTER TABLE `posicoes` DISABLE KEYS */;
INSERT INTO `posicoes` VALUES (1,'GR'),(2,'DE'),(3,'DC'),(4,'DD'),(5,'MDC'),(10,'MC'),(11,'MCO'),(12,'ME'),(13,'MD'),(14,'SA'),(15,'EE'),(16,'PL'),(17,'ED');
/*!40000 ALTER TABLE `posicoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilizadores`
--

DROP TABLE IF EXISTS `utilizadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilizadores` (
  `id_utilizador` int(11) NOT NULL AUTO_INCREMENT,
  `utilizador` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `tipo` enum('normal','admin') DEFAULT NULL,
  PRIMARY KEY (`id_utilizador`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilizadores`
--

LOCK TABLES `utilizadores` WRITE;
/*!40000 ALTER TABLE `utilizadores` DISABLE KEYS */;
INSERT INTO `utilizadores` VALUES (1,'adimin','$2y$10$wJY5I.dLAdUcMR6Vs02gf.kbuJfMw0.wIIqjbfS7JaDHc9Xzk3QbG','admin'),(2,'leo','$2y$10$w.QCN/OB.Hd5oUeyte0Qh.HgmPTDaK9YCIoc1JQ8qOkFtY0CNc9MK','normal'),(3,'lu','$2y$10$BGFBAwB/44RE5TV68d5u8uJhXoohnNELNo0ahhwrChxmbAJ549bzy','admin'),(4,'leopriv','$2y$10$PKNrUs.twEWmKh5cEloCaeWBa1b4msUyGwuv77eEqDTTFnK9H4OKm','admin'),(5,'leoprivv','$2y$10$BtflRnKROKkrH3IH59qhjOo8XBQfgnwjSD3fDFU5uIQsyHYzL4GQG','admin');
/*!40000 ALTER TABLE `utilizadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'pap_futebol'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-18 14:47:21
