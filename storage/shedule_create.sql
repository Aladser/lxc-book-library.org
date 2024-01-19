-- MySQL dump 10.13  Distrib 8.0.35, for Linux (x86_64)
--
-- Host: localhost    Database: forum
-- ------------------------------------------------------
-- Server version	8.0.35-0ubuntu0.22.04.1

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
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `summary` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `check_article_author_id` (`author_id`),
  CONSTRAINT `check_article_author_id` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'тема 1',1,'Краткое содержание 1','Основное содержание 1','2023-12-02 07:16:47'),(2,'тема 2',1,'Краткое содержание 2','Основное содержание 2','2023-12-02 07:16:47'),(3,'тема 3',1,'Краткое содержание 3','Основное содержание 3','2023-12-02 07:16:47'),(4,'тема 4',1,'Краткое содержание 4','Основное содержание 4','2023-12-02 07:16:47'),(5,'тема 5',2,'Краткое содержание 5','Основное содержание 5','2023-12-02 07:16:47'),(6,'тема 6',2,'Краткое содержание 6','Основное содержание 6','2023-12-02 07:16:47'),(7,'тема 7',2,'Краткое содержание 7','Основное содержание 7','2023-12-02 07:16:47'),(8,'тема 8',3,'Краткое содержание 8','Основное содержание 8','2023-12-02 07:16:47'),(9,'тема 9',3,'Краткое содержание 9','Основное содержание 9','2023-12-02 07:16:47'),(10,'тема 10',3,'Краткое содержание 10','Основное содержание 10','2023-12-02 07:16:47');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `content` text NOT NULL,
  `time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `check_comment_author_id` (`author_id`),
  KEY `check_comment_article_id` (`article_id`),
  CONSTRAINT `check_comment_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `check_comment_author_id` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,1,'Коммент 1','2023-12-02 07:16:47'),(2,1,2,'Коммент 2','2023-12-02 07:16:47'),(3,1,3,'Коммент 3','2023-12-02 07:16:47'),(4,1,4,'Коммент 4','2023-12-02 07:16:47'),(5,2,1,'Коммент 1','2023-12-02 07:16:47'),(6,2,1,'Коммент 2','2023-12-02 07:16:47'),(7,2,3,'Коммент 3','2023-12-02 07:16:47'),(8,2,3,'Коммент 4','2023-12-02 07:16:47');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'user1','$2y$10$0sYGnvEXGZx5wshGxnubUu6oC3/eKUTwXpID2r5aPXVRh8hafbwwG'),(2,'user2','$2y$10$0sYGnvEXGZx5wshGxnubUu6oC3/eKUTwXpID2r5aPXVRh8hafbwwG'),(3,'user3','$2y$10$0sYGnvEXGZx5wshGxnubUu6oC3/eKUTwXpID2r5aPXVRh8hafbwwG'),(4,'user4','$2y$10$0sYGnvEXGZx5wshGxnubUu6oC3/eKUTwXpID2r5aPXVRh8hafbwwG');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-03 19:23:42
