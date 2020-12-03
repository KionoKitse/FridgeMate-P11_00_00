

DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `GROUP_ID` int(11) DEFAULT NULL,
  `ITEM_ID` int(11) DEFAULT NULL
);


LOCK TABLES `group` WRITE;
INSERT INTO `group` VALUES (1,1),(1,2),(2,19),(2,22),(3,16),(3,9),(4,24),(4,5),(5,29),(5,11);
UNLOCK TABLES;

DROP TABLE IF EXISTS `ingredient`;
CREATE TABLE `ingredient` (
  `RECIPE_ID` int(11) DEFAULT NULL,
  `ITEM_ID` int(11) DEFAULT NULL,
  `CATEGORY` int(11) DEFAULT NULL,
  `QUANTITY` decimal(5,3) DEFAULT NULL,
  `UNIT` varchar(45) DEFAULT NULL,
  `STEP` int(11) DEFAULT NULL,
  `PREP` tinyint(4) DEFAULT NULL
);


LOCK TABLES `ingredient` WRITE;
INSERT INTO `ingredient` VALUES (1,3,1,7.000,'OZ',5,1),(1,4,1,2.000,'CUPS',2,1),(1,5,1,8.000,'OZ',4,0),(1,6,2,0.250,'CUPS',1,1),(1,22,2,2.000,'CLOVES',1,1),(1,8,2,0.330,'CUPS',3,0),(1,9,3,2.000,'TSP',1,0),(1,10,3,0.250,'TSP',2,0),(1,11,3,1.000,'TBSP',2,0),(1,23,3,0.500,'TSP',3,0),(1,12,3,0.250,'TSP',3,0),(1,13,3,2.000,'TBSP',3,0),(1,14,3,1.250,'CUPS',3,0),(1,15,3,2.000,'TSP',3,0),(1,16,3,2.000,'TSP',5,0),(1,17,3,1.500,'TBSP',5,0),(1,18,3,1.500,'TSP',5,0),(1,19,3,0.250,'TSP',5,0),(1,7,3,0.250,'TSP',5,0),(1,21,4,0.500,'TSP',3,0),(2,24,1,8.000,'OZ',0,0),(2,25,1,3.000,'OZ',1,0),(2,14,2,0.500,'CUPS',1,0),(2,22,2,4.000,'CLOVES',3,1),(2,26,2,1.500,'CUPS',4,0),(2,27,2,0.250,'CUPS',5,0),(2,28,2,4.000,'OZ',5,0),(2,29,3,0.250,'TSP',1,0),(2,30,3,0.250,'TSP',1,0),(2,19,3,0.250,'TSP',1,0),(2,31,3,0.250,'TSP',1,0),(2,16,3,2.000,'TSP',2,0),(2,12,3,0.250,'TSP',3,1),(2,18,3,0.500,'TSP',3,1),(2,32,3,0.500,'TSP',3,1),(2,23,3,0.500,'TSP',4,0),(2,19,3,0.500,'TSP',4,0),(2,30,3,0.500,'TSP',4,0),(2,33,3,0.500,'TSP',4,0),(2,31,3,0.500,'TSP',4,0),(2,9,3,1.000,'TBSP',2,0),(2,13,3,2.000,'TBSP',5,0),(3,8,1,0.500,'CUPS',0,0),(3,36,1,1.500,'CUPS',0,0),(4,29,1,1.000,'TSP',0,0),(4,31,1,0.750,'TSP',0,0),(4,37,1,0.500,'TSP',0,0),(4,38,1,0.250,'TSP',0,0),(4,39,1,0.250,'TSP',0,0),(4,12,1,0.250,'TSP',0,0);
UNLOCK TABLES;


DROP TABLE IF EXISTS `pantry`;
CREATE TABLE `pantry` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(45) DEFAULT NULL,
  `name2` varchar(45) DEFAULT NULL,
  `name3` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
);

LOCK TABLES `pantry` WRITE;
INSERT INTO `pantry` VALUES (1,'Tomato','Canned','Whole',1,0),(2,'Tomato','Romma','',1,0),(3,'Tofu','Firm','',1,0),(4,'Squash','Butternut','',1,0),(5,'Pasta','Fettuccine','',1,0),(6,'Onion','Yellow','',1,0),(7,'Sugar','White','',1,0),(8,'Cashew','Raw','',1,0),(9,'Oil','Olive','',1,0),(10,'Pepper Flakes','','',1,0),(11,'Sage','Fresh','',1,0),(12,'Black Pepper','','',1,0),(13,'Nutritional yeast','','',1,0),(14,'Veggie broth','','',0,0),(15,'Lemon','Juice','',1,0),(16,'Oil','','',1,0),(17,'Soy Sauce','','',1,0),(18,'Paprika','Smoked','',1,0),(19,'Garlic','Powder','',0,0),(20,'Maple Syrup','','',1,0),(21,'Miso paste','','',0,0),(22,'Garlic','Fresh','',1,0),(23,'Salt','','',1,0),(24,'Pasta','Farfalle','',1,0),(25,'Soy curls','','',0,0),(26,'Milk','Cashew','',1,3),(27,'Sun dried tomato ','','',1,0),(28,'Spinach','','',1,0),(29,'Sage','Dry','',1,0),(30,'Onion','Dry','',1,0),(31,'Thyme','Dry','',0,0),(32,'Poultry seasoning','','',0,4),(33,'Oregano','Dry','',0,0),(34,'Thyme','Fresh','',1,0),(35,'Basil','Fresh','',1,0),(36,'Water','','',1,0),(37,'Marjoram','Dry','',1,0),(38,'Rosemary','Dry','',1,0),(39,'Nutmeg','Ground','',1,0);
UNLOCK TABLES;


DROP TABLE IF EXISTS `recipe`;
CREATE TABLE `recipe` (
  `RECIPE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(45) DEFAULT NULL,
  `PEOPLE` int(11) DEFAULT NULL,
  `ACTIVE` decimal(5,2) DEFAULT NULL,
  `PASIVE` decimal(5,2) DEFAULT NULL,
  `RATING` int(11) DEFAULT NULL,
  `PERCENT` decimal(5,2) DEFAULT NULL,
  `INGREDIANT` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`RECIPE_ID`)
);



LOCK TABLES `recipe` WRITE;
INSERT INTO `recipe` VALUES (1,'Butternut Squash Carbonara',2,1.15,0.00,4,97.54,0),(2,'Creamy Sun Dried Tomato Pasta with Garlic Soy',3,0.30,0.00,3,61.70,0),(3,'Cashew milk',0,0.00,0.00,5,100.00,1),(4,'Poultry seasoning',0,0.00,0.00,5,87.50,1);
UNLOCK TABLES;



DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `RECIPE_ID` int(11) DEFAULT NULL,
  `TAG` varchar(45) DEFAULT NULL
);


LOCK TABLES `tags` WRITE;
INSERT INTO `tags` VALUES (1,'Vegan'),(1,'Main'),(1,'Savory'),(2,'Vegan'),(2,'Main'),(2,'Savory'),(2,'Italian');
UNLOCK TABLES;

