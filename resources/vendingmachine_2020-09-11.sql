# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.7.26)
# Database: vendingmachine
# Generation Time: 2020-09-11 09:36:36 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table coins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `coins`;

CREATE TABLE `coins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `coin` varchar(5) DEFAULT '0',
  `value` decimal(10,2) DEFAULT '0.00',
  `count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `coins` WRITE;
/*!40000 ALTER TABLE `coins` DISABLE KEYS */;

INSERT INTO `coins` (`id`, `coin`, `value`, `count`)
VALUES
	(1,'005',0.05,10),
	(2,'010',0.10,10),
	(3,'025',0.25,10),
	(4,'100',1.00,0);

/*!40000 ALTER TABLE `coins` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table current
# ------------------------------------------------------------

DROP TABLE IF EXISTS `current`;

CREATE TABLE `current` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `coin` varchar(5) DEFAULT '0',
  `value` decimal(10,2) DEFAULT NULL,
  `count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `current` WRITE;
/*!40000 ALTER TABLE `current` DISABLE KEYS */;

INSERT INTO `current` (`id`, `coin`, `value`, `count`)
VALUES
	(1,'005',0.05,0),
	(2,'010',0.10,0),
	(3,'025',0.25,0),
	(4,'100',1.00,0);

/*!40000 ALTER TABLE `current` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`id`, `product`, `price`, `stock`)
VALUES
	(1,'water',0.65,10),
	(2,'juice',1.00,10),
	(3,'soda',1.50,10);

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
