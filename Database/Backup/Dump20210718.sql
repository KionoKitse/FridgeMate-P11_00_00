CREATE DATABASE  IF NOT EXISTS `fridgemate_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fridgemate_db`;
-- MySQL dump 10.13  Distrib 8.0.18, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: fridgemate_db
-- ------------------------------------------------------
-- Server version	8.0.18

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
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `group` (
  `group_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (1,1,1),(1,35,2),(2,19,3),(2,22,4),(3,16,5),(3,9,6),(4,24,7),(4,5,8),(5,29,9),(6,19,11);
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredient` (
  `recipe_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `quantity` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `step` int(11) DEFAULT NULL,
  `prep` tinyint(4) DEFAULT NULL,
  `percent` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=560 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient`
--

LOCK TABLES `ingredient` WRITE;
/*!40000 ALTER TABLE `ingredient` DISABLE KEYS */;
INSERT INTO `ingredient` VALUES (2,1,1,'1','C',1,1,'19.132653061224',232),(2,2,1,'0.5','C',1,1,'19.132653061224',233),(2,3,1,'0.25','C',1,1,'19.132653061224',234),(2,7,2,'0.25','C',1,0,'10.204081632653',235),(2,21,1,'1.5','C',2,0,'19.132653061224',236),(2,22,2,'1.75','C',3,0,'10.204081632653',237),(2,9,3,'1','ts',3,0,'0.61224489795918',238),(2,18,3,'1','Tb',3,0,'0.61224489795918',239),(2,10,3,'0.5','ts',3,0,'0.61224489795918',240),(2,16,3,'1','ts',3,0,'0.61224489795918',241),(2,17,3,'0.25','ts',3,0,'0.61224489795918',242),(3,16,1,'1','ts',1,0,'11.111111111111',263),(3,23,1,'1','ts',1,0,'11.111111111111',264),(3,24,1,'1','ts',1,0,'11.111111111111',265),(3,17,1,'0.5','ts',1,0,'11.111111111111',266),(3,25,1,'0.5','ts',1,0,'11.111111111111',267),(3,12,1,'0.5','ts',1,0,'11.111111111111',268),(3,26,1,'0.5','ts',1,0,'11.111111111111',269),(3,11,1,'0.5','ts',1,0,'11.111111111111',270),(3,27,1,'0.5','ts',1,0,'11.111111111111',271),(1,1,1,'1','Pc',1,1,'15',452),(1,2,1,'1','Pc',1,1,'15',453),(1,3,1,'2','Pc',1,1,'15',454),(1,6,2,'2','Tb',1,0,'6.6666666666667',455),(1,7,2,'2','Tb',1,0,'6.6666666666667',456),(1,9,3,'4','Pc',2,0,'0.5',457),(1,10,3,'1','Tb',2,0,'0.5',458),(1,11,3,'1','ts',2,0,'0.5',459),(1,12,3,'0.5','ts',2,0,'0.5',460),(1,13,3,'1','Pc',2,0,'0.5',461),(1,17,4,'*','*',2,0,'0.33333333333333',462),(1,4,1,'450','mL',3,0,'15',463),(1,8,2,'1','C',3,0,'6.6666666666667',464),(1,14,3,'1','Tb',3,0,'0.5',465),(1,5,1,'450','g',4,1,'15',466),(1,15,4,'*','*',4,0,'0.33333333333333',467),(1,16,4,'*','*',4,0,'0.33333333333333',468),(1,17,4,'*','*',4,0,'0.33333333333333',469),(1,18,4,'2','Tb',4,0,'0.33333333333333',470),(1,19,4,'*','*',4,0,'0.33333333333333',471),(4,33,2,'1','Tb',1,0,'4',472),(4,1,2,'1','Pc',1,1,'4',473),(4,9,3,'1','Pc',1,1,'0.6',474),(4,28,1,'3','Pc',2,1,'15',475),(4,29,1,'2','Pc',2,1,'15',476),(4,34,2,'600','mL',2,0,'4',477),(4,30,1,'0.5','C',3,0,'15',478),(4,36,3,'2','ts',3,0,'0.6',479),(4,37,3,'1','ts',3,0,'0.6',480),(4,38,3,'1','ts',3,0,'0.6',481),(4,13,3,'1','Pc',3,0,'0.6',482),(4,16,4,'*','*',3,0,'0.4',483),(4,17,4,'*','*',3,0,'0.4',484),(4,32,1,'2','Pc',4,0,'15',485),(4,35,2,'2','Tb',4,0,'4',486),(4,7,2,'1','Tb',4,0,'4',487),(4,16,4,'*','*',4,0,'0.4',488),(4,17,4,'*','*',4,0,'0.4',489),(4,31,1,'450','mL',5,1,'15',490),(4,39,4,'*','*',6,0,'0.4',491),(5,40,1,'4','Pc',1,1,'46.875',492),(5,41,1,'1','C',1,0,'46.875',493),(5,37,3,'2','Tb',1,1,'0.46875',494),(5,9,3,'1','Tb',1,1,'0.46875',495),(5,42,3,'4','Pc',1,0,'0.46875',496),(5,43,3,'1','Tb',1,0,'0.46875',497),(5,44,3,'2','Pc',1,0,'0.46875',498),(5,17,3,'1','ts',1,0,'0.46875',499),(5,45,3,'1','Pc',1,0,'0.46875',500),(5,16,3,'*','*',1,0,'0.46875',501),(5,38,4,'*','*',1,0,'2.5',502),(6,28,1,'3','Pc',1,1,'25',533),(6,7,2,'3','Tb',1,0,'4',534),(6,48,2,'1','Pc',1,1,'4',535),(6,16,3,'*','*',1,0,'0.6',536),(6,17,3,'*','*',1,0,'0.6',537),(6,47,1,'225','g',2,1,'25',538),(6,49,2,'0.5','C',3,0,'4',539),(6,50,2,'2','ts',3,0,'4',540),(6,52,3,'5','Pc',3,0,'0.6',541),(6,9,3,'3','Pc',3,0,'0.6',542),(6,46,1,'900','ml',4,0,'25',543),(6,34,2,'2','C',4,0,'4',544),(6,53,3,'1','ts',5,0,'0.6',545),(6,18,4,'*','*',5,0,'2',546),(7,33,2,'3','Tb',1,0,'4',547),(7,1,1,'1','Pc',1,1,'25',548),(7,9,2,'2','Pc',1,1,'4',549),(7,50,2,'1','Tb',2,0,'4',550),(7,36,3,'1','ts',2,0,'0.75',551),(7,16,3,'0.25','ts',2,0,'0.75',552),(7,17,3,'0.25','ts',2,0,'0.75',553),(7,54,3,'1','pch',2,0,'0.75',554),(7,8,2,'2','C',3,0,'4',555),(7,30,1,'1','C',3,0,'25',556),(7,28,1,'1','Pc',3,0,'25',557),(7,19,2,'0.5','Pc',5,0,'4',558),(7,38,4,'3','Tb',5,0,'2',559);
/*!40000 ALTER TABLE `ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pantry`
--

DROP TABLE IF EXISTS `pantry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pantry` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name2` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name3` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `expires` int(11) DEFAULT NULL,
  `purchase` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cart` tinyint(4) DEFAULT NULL,
  `weight` double DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pantry`
--

LOCK TABLES `pantry` WRITE;
/*!40000 ALTER TABLE `pantry` DISABLE KEYS */;
INSERT INTO `pantry` VALUES (1,'Onion','','',1,0,0,'2021-07-17','Produce',0,63.132653061224005),(2,'Pepper','Bell','',0,0,0,'2021-07-13','Produce',0,34.132653061224005),(3,'Celery','','',0,0,0,'2021-07-13','Produce',1,34.132653061224005),(4,'Tomato','Canned','',0,0,NULL,'2021-07-13','Canned',0,15),(5,'Shrimp','Raw','',0,0,NULL,'2021-07-13','Seafood',NULL,15),(6,'Oil','','',0,0,0,'2021-07-13','Baking',0,6.6666666666667),(7,'Butter','','',1,0,0,'2021-07-15','Dairy',0,24.8707482993197),(8,'Water','','',1,0,NULL,'2021-07-17','None',NULL,10.6666666666667),(9,'Garlic','','',1,0,0,'2021-07-13','Herbs',0,6.7809948979591805),(10,'Cajun seasoning','','',0,3,0,'2021-07-13','Spices',0,1.11224489795918),(11,'Thyme','Dry','',0,0,NULL,'2021-07-13','Spices',NULL,11.611111111111),(12,'Cayenne','','',0,0,NULL,'2021-07-13','Spices',0,11.611111111111),(13,'Bay laurel','','',0,0,NULL,'2021-07-15','Spices',0,1.1),(14,'Worcestershire sauce','','',0,0,NULL,'2021-07-13','Sauce',NULL,0.5),(15,'Tabasco sauce','','',0,0,NULL,'2021-07-13','Sauce',0,0.33333333333333),(16,'Salt','','',1,0,0,'2021-07-13','Spices',0,14.67543934240351),(17,'Pepper','','',1,0,0,'2021-07-13','Spices',0,15.008772675736841),(18,'Parsley','Fresh','',0,0,NULL,'2021-07-13','Herbs',NULL,2.94557823129251),(19,'Lemon','','',1,0,NULL,'2021-07-17','Fruit',NULL,4.33333333333333),(21,'Rice','Basmati ','',0,0,NULL,'2021-07-13','Grains',NULL,19.132653061224),(22,'Broth','Chicken','',1,0,NULL,'2021-07-15','Spices',0,10.204081632653),(23,'Garlic','Powder','',0,0,0,'2021-07-13','Spices',0,11.111111111111),(24,'Paprika','','',0,0,NULL,'2021-07-13','Spices',NULL,11.111111111111),(25,'Onion','Powder','',0,0,0,'2021-07-15','Spices',0,11.111111111111),(26,'Oregano','Dry','',0,0,NULL,'2021-07-13','Spices',NULL,11.111111111111),(27,'Red pepper flakes','','',0,0,NULL,'2021-07-13','Spices',NULL,11.111111111111),(28,'Carrots','','',1,0,NULL,'2021-07-17','Produce',0,65),(29,'Parsnips','','',0,0,NULL,'2021-07-13','Produce',NULL,15),(30,'Lentils','Red','',1,0,NULL,'2021-07-17','Grains',NULL,40),(31,'Beans','Red','',0,0,NULL,'2021-07-13','Grains',0,15),(32,'Potatoes','Yellow','',0,0,NULL,'2021-07-13','Produce',NULL,15),(33,'Oil','Olive','',1,0,0,'2021-07-17','Baking',0,8),(34,'Broth','Vegetable','',1,0,0,'2021-07-15','Spices',0,8),(35,'Milk','','',1,0,NULL,'2021-07-17','Dairy',0,4),(36,'Cumin','','',1,0,NULL,'2021-07-17','Spices',0,1.35),(37,'Ginger','','',1,0,0,'2021-07-10','Herbs',0,1.06875),(38,'Cilantro','Fresh','',1,0,0,'2021-07-17','Herbs',0,5.1),(39,'Parmesan','','',0,0,NULL,'2021-07-13','Dairy',NULL,0.4),(40,'Rhubarb','Stalks','',0,0,0,'','Produce',0,46.875),(41,'Lentils','Orange','',1,0,0,'','Grains',0,46.875),(42,'Cardamom','','',1,0,0,'','Spices',0,0.46875),(43,'Mustard','Seeds','',1,0,0,'','Spices',0,0.46875),(44,'Cloves','','',1,0,0,'','Spices',0,0.46875),(45,'Chili','Ancho','Dried',0,0,0,'','Specialty',0,0.46875),(46,'Beans','Cannellini','Canned',0,0,0,'','Canned',0,25),(47,'Mushroom','Cremini','',0,0,0,'','Herbs',0,25),(48,'Onion','Red','',0,0,0,'','Produce',0,4),(49,'Wine','Red','Dry',0,0,0,'','Alcohol',0,4),(50,'Tomato','Paste','',1,0,0,'','Canned',0,8),(51,'Brandy','','',0,0,0,'','Alcohol',0,NULL),(52,'Thyme','','',1,0,0,'','Herbs',0,0.6),(53,'Vinegar','Balsamic','',1,0,0,'2021-07-17','Baking',0,0.6),(54,'Chili','Powder','',1,0,NULL,'2021-07-17',NULL,NULL,0.75);
/*!40000 ALTER TABLE `pantry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `people` int(11) DEFAULT NULL,
  `active` decimal(5,2) DEFAULT NULL,
  `passive` decimal(5,2) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `percent` decimal(5,2) DEFAULT NULL,
  `image` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top1` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top2` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top3` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top4` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top5` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `top6` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `menu` tinyint(4) DEFAULT '0',
  `ingredient` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES (1,'The Best Shrimp Creole',4,25.00,20.00,5,48.50,'https://www.fromachefskitchen.com/wp-content/uploads/2019/01/Shrimp-Creole-7.jpg','Onion  ','Pepper Bell ','Celery  ','Tomato Canned ','Shrimp Raw ','Oil  ',0,0),(2,'Cajun Rice Pilaf',4,20.00,10.00,5,60.51,'https://3.bp.blogspot.com/-3B3lagKNTyQ/Thka7AtOaxI/AAAAAAAAPCs/SbSkxVaiErE/w400-h266/Cajun+Rice+Pilaf.jpg','Onion  ','Pepper Bell ','Celery  ','Rice Basmati  ','Butter  ','Broth Chicken ',0,0),(3,'Cajun Spice Mix',0,0.00,0.00,5,33.33,'https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fimages.media-allrecipes.com%2Fuserphotos%2F996280.jpg','Salt  ','Garlic Powder ','Paprika  ','Pepper  ','Onion Powder ','Cayenne  ',0,0),(4,'Carrot, Parsnip and Lentil Casserole',4,40.00,40.00,3,54.00,'https://noshingwiththenolands.com/wp-content/uploads/2020/03/November-2012-929-Small-3-720x405.jpg','Carrots  ','Parsnips  ','Lentils Red ','Potatoes Yellow ','Beans Red ','Oil Olive ',0,0),(5,'Lentil and Rhubarb Stew With Indian Spices',4,40.00,0.00,0,52.66,'https://pbs.twimg.com/profile_images/1229840659797819392/ZOdybJZH.jpg','Rhubarb Stalks ','Lentils Orange ','Ginger  ','Garlic  ','Cardamom  ','Mustard Seeds ',0,0),(6,'Recipe Name',4,45.00,0.00,0,40.00,'https://static01.nyt.com/images/2020/11/29/dining/lh-white-beans-au-vin/merlin_168064458_f7a77d72-7bdd-4c1e-b368-8a2473577830-articleLarge.jpg','Carrots  ','Mushroom Cremini ','Beans Cannellini Canned','Butter  ','Onion Red ','Wine Red Dry',0,0),(7,'Red lentil soup with lemon',4,45.00,0.00,0,100.00,'https://static01.nyt.com/images/2019/01/17/dining/mc-red-lentil-soup/merlin_146234352_d7bc8486-b067-4cff-a4c0-7741f166fb60-articleLarge.jpg','Lentils Red ','Carrots  ','Onion  ','Broth Vegetable ','Garlic  ','Tomato Paste ',0,0);
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sets`
--

DROP TABLE IF EXISTS `sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sets` (
  `group_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sets`
--

LOCK TABLES `sets` WRITE;
/*!40000 ALTER TABLE `sets` DISABLE KEYS */;
INSERT INTO `sets` VALUES (1,1),(1,25),(2,6),(2,33),(2,7),(3,9),(3,23);
/*!40000 ALTER TABLE `sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping`
--

DROP TABLE IF EXISTS `shopping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shopping` (
  `item_id` int(11) DEFAULT NULL,
  `cart` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping`
--

LOCK TABLES `shopping` WRITE;
/*!40000 ALTER TABLE `shopping` DISABLE KEYS */;
INSERT INTO `shopping` VALUES (3,0);
/*!40000 ALTER TABLE `shopping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `recipe_id` int(11) DEFAULT NULL,
  `tag` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (2,NULL,62),(2,NULL,63),(2,NULL,64),(2,NULL,65),(1,'Dinner',102),(1,'Cajun',103),(1,'Seafood',104),(5,'Dinner',105),(5,'Indian',106),(6,'Dinner',109);
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

-- Dump completed on 2021-07-18  7:01:52
