CREATE DATABASE  IF NOT EXISTS `fridgemate_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fridgemate_db`;
-- MySQL dump 10.13  Distrib 8.0.21, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: fridgemate_db
-- ------------------------------------------------------
-- Server version	8.0.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredient` (
  `recipe_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `category` int DEFAULT NULL,
  `quantity` varchar(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `step` int DEFAULT NULL,
  `prep` tinyint DEFAULT NULL,
  `pk` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient`
--

LOCK TABLES `ingredient` WRITE;
/*!40000 ALTER TABLE `ingredient` DISABLE KEYS */;
INSERT INTO `ingredient` VALUES (1,1,1,'1','Pc',1,1,1),(1,2,1,'1','Pc',1,1,2),(1,3,1,'2','Pc',1,1,3),(1,6,2,'2','Tb',1,0,4),(1,7,2,'2','Tb',1,0,5),(1,9,3,'4','Pc',2,0,6),(1,10,3,'1','Tb',2,0,7),(1,11,3,'1','ts',2,0,8),(1,12,3,'0.5','ts',2,0,9),(1,13,3,'1','Pc',2,0,10),(1,17,4,'*','*',2,0,11),(1,4,1,'450','mL',3,0,12),(1,8,2,'1','C',3,0,13),(1,14,3,'1','Tb',3,0,14),(1,5,1,'450','g',4,1,15),(1,15,4,'*','*',4,0,16),(1,16,4,'*','*',4,0,17),(1,17,4,'*','*',4,0,18),(1,18,4,'2','Tb',4,0,19),(1,19,4,'*','*',4,0,20),(2,1,1,'1','C',1,1,21),(2,2,1,'0.5','C',1,1,22),(2,3,1,'0.25','C',1,1,23),(2,7,2,'0.25','C',1,0,24),(2,21,1,'1.5','C',2,0,25),(2,22,2,'1.75','C',3,0,26),(2,9,3,'1','ts',3,0,27),(2,18,3,'1','Tb',3,0,28),(2,10,3,'0.5','ts',3,0,29),(2,16,3,'1','ts',3,0,30),(2,17,3,'0.25','ts',3,0,31),(3,16,1,'1','ts',1,0,32),(3,23,1,'1','ts',1,0,33),(3,24,1,'1','ts',1,0,34),(3,17,1,'0.5','ts',1,0,35),(3,25,1,'0.5','ts',1,0,36),(3,12,1,'0.5','ts',1,0,37),(3,26,1,'0.5','ts',1,0,38),(3,11,1,'0.5','ts',1,0,39),(3,27,1,'0.5','ts',1,0,40),(4,33,2,'1','Tb',1,0,41),(4,1,2,'1','Pc',1,1,42),(4,9,3,'1','Pc',1,1,43),(4,28,1,'3','Pc',2,1,44),(4,29,1,'2','Pc',2,1,45),(4,34,2,'600','mL',2,0,46),(4,30,1,'0.5','C',3,0,47),(4,36,3,'2','ts',3,0,48),(4,37,3,'1','ts',3,0,49),(4,38,3,'1','ts',3,0,50),(4,13,3,'1','Pc',3,0,51),(4,16,4,'*','*',3,0,52),(4,17,4,'*','*',3,0,53),(4,32,1,'2','Pc',4,0,54),(4,35,2,'2','Tb',4,0,55),(4,7,2,'1','Tb',4,0,56),(4,16,4,'*','*',4,0,57),(4,17,4,'*','*',4,0,58),(4,31,1,'450','mL',5,1,59),(4,39,4,'*','*',6,0,60);
/*!40000 ALTER TABLE `ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pantry`
--

DROP TABLE IF EXISTS `pantry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pantry` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `name1` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name2` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name3` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `recipe_id` int DEFAULT NULL,
  `expires` int DEFAULT NULL,
  `purchase` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cart` tinyint DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pantry`
--

LOCK TABLES `pantry` WRITE;
/*!40000 ALTER TABLE `pantry` DISABLE KEYS */;
INSERT INTO `pantry` VALUES (1,'Onion','','',0,0,NULL,NULL,'Produce',NULL),(2,'Pepper','Bell','',0,0,NULL,NULL,'Produce',NULL),(3,'Celery','','',0,0,NULL,NULL,'Produce',NULL),(4,'Tomato','Canned','',0,0,NULL,NULL,'Canned',NULL),(5,'Shrimp','Raw','',0,0,NULL,NULL,'Seafood',NULL),(6,'Oil','','',0,0,NULL,'2021-06-17','Baking',0),(7,'Butter','','',0,0,NULL,NULL,'Dairy',NULL),(8,'Water','','',0,0,NULL,NULL,'None',NULL),(9,'Garlic','','',0,0,NULL,NULL,'Herbs',NULL),(10,'Cajun seasoning','','',0,0,NULL,NULL,'Spices',NULL),(11,'Thyme','Dry','',0,0,NULL,NULL,'Spices',NULL),(12,'Cayenne','','',0,0,NULL,NULL,'Spices',NULL),(13,'Bay laurel','','',0,0,NULL,'2021-06-17','Spices',0),(14,'Worcestershire sauce','','',0,0,NULL,NULL,'Sauce',NULL),(15,'Tabasco sauce','','',0,0,NULL,NULL,'Sauce',NULL),(16,'Salt','','',0,0,NULL,NULL,'Spices',NULL),(17,'Pepper','','',0,0,NULL,NULL,'Spices',NULL),(18,'Parsley','Fresh','',0,0,NULL,NULL,'Herbs',NULL),(19,'Lemon','','',0,0,NULL,NULL,'Fruit',NULL),(21,'Rice','Basmati ','',0,0,NULL,NULL,'Grains',NULL),(22,'Broth','Chicken','',0,0,NULL,NULL,'Spices',NULL),(23,'Garlic','Powder','',0,0,NULL,NULL,'Spices',NULL),(24,'Paprika','','',0,0,NULL,NULL,'Spices',NULL),(25,'Onion','Powder','',0,0,NULL,NULL,'Spices',NULL),(26,'Oregano','Dry','',0,0,NULL,NULL,'Spices',NULL),(27,'Red pepper flakes','','',0,0,NULL,NULL,'Spices',NULL),(28,'Carrots','','',0,0,NULL,NULL,'Produce',NULL),(29,'Parsnips','','',0,0,NULL,NULL,'Produce',NULL),(30,'Lentils','Red','',0,0,NULL,NULL,'Grains',NULL),(31,'Beans','Red','',0,0,NULL,NULL,'Grains',NULL),(32,'Potatoes','Yellow','',0,0,NULL,NULL,'Produce',NULL),(33,'Oil','Olive','',0,0,NULL,NULL,'Baking',NULL),(34,'Broth','Vegetable','',0,0,NULL,NULL,'Spices',NULL),(35,'Milk','','',0,0,NULL,NULL,'Dairy',NULL),(36,'Cumin','','',0,0,NULL,NULL,'Spices',NULL),(37,'Ginger','','',0,0,NULL,NULL,'Herbs',NULL),(38,'Corriander','Fresh','',0,0,NULL,NULL,'Herbs',NULL),(39,'Parmesan','','',0,0,NULL,NULL,'Dairy',NULL);
/*!40000 ALTER TABLE `pantry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe` (
  `recipe_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `people` int DEFAULT NULL,
  `active` decimal(5,2) DEFAULT NULL,
  `passive` decimal(5,2) DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `percent` decimal(5,2) DEFAULT NULL,
  `image` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top1` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top2` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top3` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top4` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top5` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top6` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `menu` tinyint DEFAULT '0',
  `ingredient` tinyint DEFAULT '0',
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES (1,'The Best Shrimp Creole',4,25.00,20.00,5,NULL,'https://www.fromachefskitchen.com/wp-content/uploads/2019/01/Shrimp-Creole-7.jpg','Onion  ','Pepper Bell ','Celery  ','Tomato Canned ','Shrimp Raw ','Oil  ',0,0),(2,'Cajun Rice Pilaf',4,20.00,10.00,4,NULL,'https://3.bp.blogspot.com/-3B3lagKNTyQ/Thka7AtOaxI/AAAAAAAAPCs/SbSkxVaiErE/w400-h266/Cajun+Rice+Pilaf.jpg','Onion  ','Pepper Bell ','Celery  ','Rice Basmati  ','Butter  ','Broth Chicken ',0,0),(3,'Cajun Spice Mix',0,0.00,0.00,5,NULL,'https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fimages.media-allrecipes.com%2Fuserphotos%2F996280.jpg','Salt  ','Garlic Powder ','Paprika  ','Pepper  ','Onion Powder ','Cayenne  ',0,0),(4,'Carrot, Parsnip and Lentil Casserole',4,40.00,40.00,3,NULL,'https://noshingwiththenolands.com/wp-content/uploads/2020/03/November-2012-929-Small-3-720x405.jpg','Carrots  ','Parsnips  ','Lentils Red ','Potatoes Yellow ','Beans Red ','Oil Olive ',1,0);
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sets`
--

DROP TABLE IF EXISTS `sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sets` (
  `group_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `pk` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sets`
--

LOCK TABLES `sets` WRITE;
/*!40000 ALTER TABLE `sets` DISABLE KEYS */;
INSERT INTO `sets` VALUES (1,1,1),(1,2,2);
/*!40000 ALTER TABLE `sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `recipe_id` int DEFAULT NULL,
  `tag` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pk` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'Dinner',28),(1,'Cajun',29),(1,'Seafood',30),(2,'Cajun',31),(2,'Dinner',32),(2,'Lunch',33);
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-17 17:17:34
