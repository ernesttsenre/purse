# ************************************************************
# Sequel Pro SQL dump
# Версия 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Адрес: 127.0.0.1 (MySQL 5.7.9)
# Схема: purse
# Время создания: 2016-09-13 09:20:55 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы operation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `operation`;

CREATE TABLE `operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purse_id` int(11) DEFAULT NULL,
  `amount` decimal(10,4) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1981A66D1A429CB3` (`purse_id`),
  CONSTRAINT `FK_1981A66D1A429CB3` FOREIGN KEY (`purse_id`) REFERENCES `purse` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `operation` WRITE;
/*!40000 ALTER TABLE `operation` DISABLE KEYS */;

INSERT INTO `operation` (`id`, `purse_id`, `amount`, `created`)
VALUES
	(1,2,10.0000,'2016-09-13 15:04:49'),
	(2,2,-5.0000,'2016-09-13 15:16:07'),
	(3,1,10.0000,'2016-09-13 15:18:15'),
	(4,1,10.0000,'2016-09-13 15:18:44'),
	(5,4,10.0000,'2016-09-13 16:18:22');

/*!40000 ALTER TABLE `operation` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы purse
# ------------------------------------------------------------

DROP TABLE IF EXISTS `purse`;

CREATE TABLE `purse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `balance` decimal(10,4) NOT NULL,
  `created` datetime NOT NULL,
  `currency` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_DAE44A02B36786B` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `purse` WRITE;
/*!40000 ALTER TABLE `purse` DISABLE KEYS */;

INSERT INTO `purse` (`id`, `title`, `balance`, `created`, `currency`)
VALUES
	(1,'Рубли (RUB)',20.0000,'2016-09-13 13:30:00','rub'),
	(2,'Доллары (USD)',5.0000,'2016-09-13 13:30:00','usd'),
	(3,'Евро (EUR)',0.0000,'2016-09-13 13:30:00','eur'),
	(4,'Сомы (KGS)',10.0000,'2016-09-13 13:30:00','kgs');

/*!40000 ALTER TABLE `purse` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
