/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: stepra_db
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goals`
--

DROP TABLE IF EXISTS `goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `goals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `target_value` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goals`
--

LOCK TABLES `goals` WRITE;
/*!40000 ALTER TABLE `goals` DISABLE KEYS */;
/*!40000 ALTER TABLE `goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_members`
--

DROP TABLE IF EXISTS `group_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `role` enum('member','owner','admin','superadmin') NOT NULL DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`,`user_id`),
  KEY `group_members_ibfk_2` (`user_id`),
  CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_members`
--

LOCK TABLES `group_members` WRITE;
/*!40000 ALTER TABLE `group_members` DISABLE KEYS */;
INSERT INTO `group_members` VALUES
(1,1,3,1,'member','2026-07-10 03:35:40'),
(2,2,2,1,'member','2026-07-10 04:34:04'),
(3,3,4,1,'admin','2026-07-10 05:14:42'),
(4,3,2,1,'member','2026-07-10 05:24:44');
/*!40000 ALTER TABLE `group_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_schedules`
--

DROP TABLE IF EXISTS `group_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_task_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `week_days` varchar(20) DEFAULT NULL,
  `start_time` time NOT NULL,
  `required_minutes` int(11) NOT NULL,
  `priority` enum('high','middle','low') DEFAULT NULL,
  `color` char(7) DEFAULT '#FFAA00',
  `period` enum('weekly','monthly','yearly') DEFAULT NULL,
  `notification_enabled` tinyint(1) DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','completed','failed','deleted') DEFAULT 'active',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `group_task_id` (`group_task_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `group_schedules_ibfk_1` FOREIGN KEY (`group_task_id`) REFERENCES `group_tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_schedules_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_schedules_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_schedules`
--

LOCK TABLES `group_schedules` WRITE;
/*!40000 ALTER TABLE `group_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_tasks`
--

DROP TABLE IF EXISTS `group_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `group_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `week_days` varchar(20) DEFAULT NULL,
  `start_time` time NOT NULL,
  `required_minutes` int(11) NOT NULL,
  `priority` enum('high','middle','low') DEFAULT NULL,
  `color` char(7) NOT NULL DEFAULT '#FFAA00',
  `period` enum('weekly','monthly','yearly') DEFAULT NULL,
  `notification_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','completed','failed','deleted') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `group_tasks_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_tasks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_tasks`
--

LOCK TABLES `group_tasks` WRITE;
/*!40000 ALTER TABLE `group_tasks` DISABLE KEYS */;
INSERT INTO `group_tasks` VALUES
(1,2,2,'a','','[1]','10:00:00',60,NULL,'#0d6efd','weekly',1,'2026-07-10',NULL,'active','2026-07-10 05:27:54'),
(2,2,2,'d','','[3]','10:00:00',60,'low','#0d6efd','weekly',1,'2026-07-10',NULL,'active','2026-07-10 05:28:06');
/*!40000 ALTER TABLE `group_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `invite_code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_groups_invite_code` (`invite_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES
(1,'ユーザー',NULL,'CVLIIDFM',NULL,0,'2026-07-10 03:35:40'),
(2,'ともっち',NULL,'ROHBCSPP',NULL,0,'2026-07-10 04:34:04'),
(3,'ともっち２',NULL,'NNGSIHNR',NULL,0,'2026-07-10 05:14:41');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000001_create_cache_table',1),
(2,'0001_01_01_000002_create_jobs_table',1),
(3,'2026_05_21_015106_create_sessions_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `send_time` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `week_days` varchar(20) DEFAULT NULL,
  `start_time` time NOT NULL,
  `required_minutes` int(11) NOT NULL,
  `priority` enum('high','middle','low') DEFAULT NULL,
  `color` char(7) NOT NULL DEFAULT '#FFAA00',
  `period` enum('weekly','monthly','yearly') DEFAULT NULL,
  `notification_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','completed','failed','deleted') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `scheduled_date` (`scheduled_date`),
  CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=532 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
INSERT INTO `schedules` VALUES
(388,12,2,'2026-07-03','勉強',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-03','2026-07-12','completed','2026-07-03 02:45:53'),
(389,12,2,'2026-07-04','勉強',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-03','2026-07-12','active','2026-07-03 02:45:53'),
(390,12,2,'2026-07-05','勉強',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-03','2026-07-12','active','2026-07-03 02:45:53'),
(391,12,2,'2026-07-06','勉強',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-03','2026-07-12','completed','2026-07-03 02:45:53'),
(392,12,2,'2026-07-07','勉強',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-03','2026-07-12','completed','2026-07-03 02:45:53'),
(393,12,2,'2026-07-08','勉強',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-03','2026-07-12','completed','2026-07-03 02:45:53'),
(398,13,2,'2026-07-03','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(399,13,2,'2026-07-04','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(400,13,2,'2026-07-05','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:09:59'),
(401,13,2,'2026-07-06','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:09:59'),
(402,13,2,'2026-07-07','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:09:59'),
(403,13,2,'2026-07-08','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:09:59'),
(404,13,2,'2026-07-09','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:09:59'),
(405,13,2,'2026-07-10','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:09:59'),
(406,13,2,'2026-07-11','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(407,13,2,'2026-07-12','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(408,13,2,'2026-07-13','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(409,13,2,'2026-07-14','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(410,13,2,'2026-07-15','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(411,13,2,'2026-07-16','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(412,13,2,'2026-07-17','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(413,13,2,'2026-07-18','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(414,13,2,'2026-07-19','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(415,13,2,'2026-07-20','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(416,13,2,'2026-07-21','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(417,13,2,'2026-07-22','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(418,13,2,'2026-07-23','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(419,13,2,'2026-07-24','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(420,13,2,'2026-07-25','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(421,13,2,'2026-07-26','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(422,13,2,'2026-07-27','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(423,13,2,'2026-07-28','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(424,13,2,'2026-07-29','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(425,13,2,'2026-07-30','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(426,13,2,'2026-07-31','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(427,13,2,'2026-08-01','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(428,13,2,'2026-08-02','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(429,13,2,'2026-08-03','資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(430,14,2,'2026-07-03','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:27'),
(431,14,2,'2026-07-04','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(432,14,2,'2026-07-05','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:27'),
(433,14,2,'2026-07-06','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:27'),
(434,14,2,'2026-07-07','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:27'),
(435,14,2,'2026-07-08','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:27'),
(436,14,2,'2026-07-09','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:27'),
(437,14,2,'2026-07-10','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:27'),
(438,14,2,'2026-07-11','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(439,14,2,'2026-07-12','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(440,14,2,'2026-07-13','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(441,14,2,'2026-07-14','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(442,14,2,'2026-07-15','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(443,14,2,'2026-07-16','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(444,14,2,'2026-07-17','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(445,14,2,'2026-07-18','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(446,14,2,'2026-07-19','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(447,14,2,'2026-07-20','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(448,14,2,'2026-07-21','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(449,14,2,'2026-07-22','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(450,14,2,'2026-07-23','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(451,14,2,'2026-07-24','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(452,14,2,'2026-07-25','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(453,14,2,'2026-07-26','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(454,14,2,'2026-07-27','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(455,14,2,'2026-07-28','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(456,14,2,'2026-07-29','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(457,14,2,'2026-07-30','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(458,14,2,'2026-07-31','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(459,14,2,'2026-08-01','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(460,14,2,'2026-08-02','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(461,14,2,'2026-08-03','筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(494,16,2,'2026-07-03','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:55'),
(495,16,2,'2026-07-04','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(496,16,2,'2026-07-05','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:55'),
(497,16,2,'2026-07-06','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:55'),
(498,16,2,'2026-07-07','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:55'),
(499,16,2,'2026-07-08','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:55'),
(500,16,2,'2026-07-09','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:55'),
(501,16,2,'2026-07-10','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'completed','2026-07-03 05:10:55'),
(502,16,2,'2026-07-11','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(503,16,2,'2026-07-12','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(504,16,2,'2026-07-13','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(505,16,2,'2026-07-14','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(506,16,2,'2026-07-15','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(507,16,2,'2026-07-16','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(508,16,2,'2026-07-17','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(509,16,2,'2026-07-18','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(510,16,2,'2026-07-19','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(511,16,2,'2026-07-20','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(512,16,2,'2026-07-21','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(513,16,2,'2026-07-22','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(514,16,2,'2026-07-23','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(515,16,2,'2026-07-24','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(516,16,2,'2026-07-25','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(517,16,2,'2026-07-26','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(518,16,2,'2026-07-27','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(519,16,2,'2026-07-28','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(520,16,2,'2026-07-29','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(521,16,2,'2026-07-30','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(522,16,2,'2026-07-31','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(523,16,2,'2026-08-01','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(524,16,2,'2026-08-02','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(525,16,2,'2026-08-03','散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(526,12,2,'2026-07-09','勉強',NULL,'[1,3,4]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-03','2026-07-12','completed','2026-07-09 02:59:41'),
(527,17,2,'2026-07-27','j',NULL,'[1]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-24',NULL,'active','2026-07-09 03:18:10'),
(528,17,2,'2026-08-03','j',NULL,'[1]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-24',NULL,'active','2026-07-09 03:18:10'),
(529,17,2,'2026-08-10','j',NULL,'[1]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-24',NULL,'active','2026-07-09 03:18:10'),
(530,17,2,'2026-08-17','j',NULL,'[1]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-24',NULL,'active','2026-07-09 03:18:10'),
(531,17,2,'2026-08-24','j',NULL,'[1]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-24',NULL,'active','2026-07-09 03:18:10');
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('2fOBNWCsyI61loeZEv9sfKOUWwborhIwjfIGocqZ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiI4OEd3Nkl6aHNaUUtNd08yMnhmV2RiaXhVdzFkcUF2NmNPTlVIdWVPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzQiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783660453),
('9Cd3Uu8t1INF6khUvbMqjopWBsMsVd9Q9yoJgPEK',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJldGZIWWJOaWo0SUpaYTBCVDhXUUVQOWlRZUN5WnFRV05weDJsTUVsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzMiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783654590),
('ArsduQhsp3Oay6qqVzcQvXIVE2vrube2tTsVAid6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJxbThqQ2R3UFRCcUFoNm9wVVVucUpaQWdXcVZxR0s5OFB4bmNrWnFKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzMiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783654248),
('bJJLu8kykfCzEKKDRKwQ9ukQJlPm65nQoLFcZr2J',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJZUjFvRTFnWWt2VEd2Z2FINml0b1hVMjBzb1JzS0RXZVZkMTZpMWh1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ndXJ1cHVzeXVcLzIiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXNlcl9pZCI6Mn0=',1783661390),
('bSzFnbgkVdRdtJZz8bCOR0zJ0DxKGBGNuWF1zxEV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJrQ1JlQndnOG92Q29MWVNMNk9yblNEZ2V3QzRZVGRQOWFQblJHbTJwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzIiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783654148),
('eK5oobotofsUgba5YAOmbbRNiMW2uZN0q4fVjCxY',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJjT3E0ZzVlWnNHSHJOampWTko4OUk1Nk93UVVDdDg4YU5PY0hka2hCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzIiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783654603),
('Gey6gEUhPPeZ73M8Q0l7pwxZzj9g9ubZRboyNsuJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJnQzFyVlFOSTg5NUZKME11M0pXNTZ4aXZTVnRpV3FiY0c3N2lUcEpyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzMiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783654582),
('H2bRd5o3vN0Y4pOwdcxnAC2xUOrzish6tJJm53aq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJuM3JOUkE3RWtyd1djc0tGUUVvbklYZXVjMUZVb1F2bHowWUpqekdqIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1783660019),
('HEFUp67DJM5hsu7a3pL9vn3VK9bwRuwhCek4LyIs',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJWZzJWYzQ1bDhJMU5OM3pxb1ROb3B5TU5xRmV0Q3RNQzVFTDRoc2pvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzIiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783658039),
('hS7H1sYh9SAJzdSHSQIxBxVVLUPmwo4Lh5AGPhTT',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJLcTNIRGVPYU1kZTJrU2c4MG5DUWJCRHduTFJtbEpuSzA2M1B3VllnIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzIiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783654648),
('iPmMzn6zjirE0tEpj9474ix5cjLJre1jqTn5pvRn',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiJzM2xDZlF5T2lNTDJwejcySXJzN1dKMUlpdnpaanBCYVR4WTRpbnlzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzQiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783660490),
('XE5hjo5n4ZpXTlKP6Q4zlfdGj1eKCHNxS8rmXeD3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','eyJfdG9rZW4iOiI5NWFQWkxTY2Z1WTlUaU51RVBEeHNhSmpERlNxN1BtaDJVMEdJQjNDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hcGlcL3VzZXJcLzMiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1783654189);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_logs`
--

DROP TABLE IF EXISTS `task_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `memo` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `task_logs_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_logs`
--

LOCK TABLES `task_logs` WRITE;
/*!40000 ALTER TABLE `task_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `week_days` varchar(20) DEFAULT NULL,
  `start_time` time NOT NULL,
  `required_minutes` int(11) NOT NULL,
  `priority` enum('high','middle','low') DEFAULT NULL,
  `color` char(7) NOT NULL DEFAULT '#FFAA00',
  `period` enum('weekly','monthly','yearly') DEFAULT NULL,
  `notification_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','completed','failed','deleted') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES
(12,2,'勉強',NULL,'[1,3,4]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-03','2026-07-12','active','2026-07-03 02:45:53'),
(13,2,'資格',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#198754',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:09:59'),
(14,2,'筋トレ',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#dc3545',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:27'),
(16,2,'散歩',NULL,'[1,2,3,4,5,6,7]','10:00:00',60,NULL,'#ffc107',NULL,1,'2026-07-03',NULL,'active','2026-07-03 05:10:55'),
(17,2,'j',NULL,'[1]','10:00:00',60,NULL,'#0d6efd',NULL,1,'2026-07-24',NULL,'active','2026-07-09 03:18:10');
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `profile_text` varchar(255) DEFAULT NULL,
  `notification_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `theme_color` char(7) NOT NULL DEFAULT '#FFAA00',
  `level` int(11) NOT NULL DEFAULT 1,
  `xp` int(11) NOT NULL DEFAULT 0,
  `streak` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'user','example@email.com','2005-12-01','$2y$12$pv3o9Af4WwAM9CokvQXwCeP7QXHzPsfRGFhbKYtCfkzfq6ZMwjJki',NULL,NULL,1,'#FFAA00',1,0,0,'2026-06-12 04:50:56'),
(2,'tomotti','tomotti1201@icloud.com','2005-12-01','$2y$12$XrGR/CAkGSMx7womM0EEUuLiEh3Zhdwn3Gc3fyRLZSGHBQxM9fglS',NULL,NULL,1,'#FFAA00',1,0,0,'2026-06-16 05:29:29'),
(3,'ユーザー','nkc20246016@st.denpa.jp','2005-12-01','$2y$12$wtOysxtFxwLwBP11ftLCze8UYrqwgvjI8v38ePlIDKfPzkoqDLlUK',NULL,NULL,1,'#FFAA00',1,0,0,'2026-07-02 03:09:33'),
(4,'ともっち２','tomotti1201@icloud.com2','2005-12-01','$2y$12$emJ7J4Ue4/kbNkGlk/eZaeZYJpbUfNpHTxn56KLSAPhO7pNN3cG26',NULL,NULL,1,'#FFAA00',1,0,0,'2026-07-10 05:06:59');
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

-- Dump completed on 2026-07-10 14:30:31
