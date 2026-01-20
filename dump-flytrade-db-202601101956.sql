-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: flytrade-db.cvey2iq88vos.ap-southeast-2.rds.amazonaws.com    Database: flytrade-db
-- ------------------------------------------------------
-- Server version	8.0.42

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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

-- SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message_id` bigint unsigned NOT NULL,
  `url` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned DEFAULT NULL,
  `width` int unsigned DEFAULT NULL,
  `height` int unsigned DEFAULT NULL,
  `duration_ms` int unsigned DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_message_id_index` (`message_id`),
  KEY `attachments_mime_index` (`mime`),
  CONSTRAINT `attachments_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_days`
--

DROP TABLE IF EXISTS `booking_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_days` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `booking_date` date NOT NULL,
  `booking_start_time` time NOT NULL,
  `booking_end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_days_booking_id_foreign` (`booking_id`),
  CONSTRAINT `booking_days_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_days`
--

LOCK TABLES `booking_days` WRITE;
/*!40000 ALTER TABLE `booking_days` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_messages`
--

DROP TABLE IF EXISTS `booking_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_messages`
--

LOCK TABLES `booking_messages` WRITE;
/*!40000 ALTER TABLE `booking_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_reschedules`
--

DROP TABLE IF EXISTS `booking_reschedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_reschedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `requested_by` bigint unsigned NOT NULL,
  `old_slots` json NOT NULL,
  `new_slots` json NOT NULL,
  `status` enum('pending','accepted','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_reschedules_booking_id_foreign` (`booking_id`),
  KEY `booking_reschedules_requested_by_foreign` (`requested_by`),
  CONSTRAINT `booking_reschedules_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_reschedules_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_reschedules`
--

LOCK TABLES `booking_reschedules` WRITE;
/*!40000 ALTER TABLE `booking_reschedules` DISABLE KEYS */;
INSERT INTO `booking_reschedules` VALUES (1,11,16,'[{\"id\": 1, \"end_time\": \"15:00:00\", \"booking_id\": 11, \"created_at\": \"2025-12-18T19:42:47.000000Z\", \"start_time\": \"14:00:00\", \"updated_at\": \"2025-12-18T19:42:47.000000Z\", \"service_date\": \"2025-12-19\", \"duration_minutes\": 60}, {\"id\": 2, \"end_time\": \"11:00:00\", \"booking_id\": 11, \"created_at\": \"2025-12-18T19:42:47.000000Z\", \"start_time\": \"10:00:00\", \"updated_at\": \"2025-12-18T19:42:47.000000Z\", \"service_date\": \"2025-12-20\", \"duration_minutes\": 60}]','[{\"end_time\": \"15:00\", \"start_time\": \"14:00\", \"service_date\": \"2025-12-26\"}]','pending',NULL,'2025-12-21 01:20:08','2025-12-21 01:20:08');
/*!40000 ALTER TABLE `booking_reschedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_slots`
--

DROP TABLE IF EXISTS `booking_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `service_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_slots_booking_id_foreign` (`booking_id`),
  KEY `booking_slots_service_date_start_time_end_time_index` (`service_date`,`start_time`,`end_time`),
  CONSTRAINT `booking_slots_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_slots`
--

LOCK TABLES `booking_slots` WRITE;
/*!40000 ALTER TABLE `booking_slots` DISABLE KEYS */;
INSERT INTO `booking_slots` VALUES (1,11,'2025-12-19','14:00:00','15:00:00',60,'2025-12-18 19:42:47','2025-12-18 19:42:47'),(2,11,'2025-12-20','10:00:00','11:00:00',60,'2025-12-18 19:42:47','2025-12-18 19:42:47'),(3,12,'2025-12-30','14:00:00','15:00:00',60,'2025-12-18 20:13:34','2025-12-18 20:13:34'),(4,12,'2025-12-31','08:00:00','09:00:00',60,'2025-12-18 20:13:34','2025-12-18 20:13:34'),(5,13,'2025-12-26','14:00:00','15:00:00',60,'2025-12-18 20:15:41','2025-12-18 20:15:41'),(6,14,'2025-12-23','14:00:00','15:00:00',60,'2025-12-19 13:19:58','2025-12-19 13:19:58'),(7,15,'2025-12-20','12:30:00','16:30:00',240,'2025-12-20 21:48:10','2025-12-20 21:48:10'),(8,16,'2026-01-08','12:00:00','13:00:00',60,'2026-01-03 23:43:41','2026-01-03 23:43:41'),(9,17,'2026-01-10','12:00:00','13:00:00',60,'2026-01-03 23:51:41','2026-01-03 23:51:41'),(10,18,'2026-01-09','12:00:00','13:00:00',60,'2026-01-03 23:54:01','2026-01-03 23:54:01'),(11,19,'2026-01-19','12:00:00','13:00:00',60,'2026-01-03 23:58:12','2026-01-03 23:58:12'),(12,20,'2026-01-20','12:00:00','13:00:00',60,'2026-01-03 23:59:07','2026-01-03 23:59:07'),(13,21,'2026-01-23','10:00:00','11:00:00',60,'2026-01-04 00:01:02','2026-01-04 00:01:02'),(14,22,'2026-01-24','08:00:00','09:00:00',60,'2026-01-04 00:02:56','2026-01-04 00:02:56'),(15,23,'2026-01-10','08:00:00','09:00:00',60,'2026-01-04 00:08:12','2026-01-04 00:08:12'),(16,24,'2026-01-10','10:00:00','11:00:00',60,'2026-01-04 00:12:50','2026-01-04 00:12:50'),(17,25,'2026-01-10','14:00:00','16:00:00',120,'2026-01-04 00:21:51','2026-01-04 00:21:51'),(18,26,'2026-01-30','12:00:00','13:00:00',60,'2026-01-07 00:20:55','2026-01-07 00:20:55'),(19,27,'2026-01-29','12:00:00','13:00:00',60,'2026-01-07 00:28:42','2026-01-07 00:28:42'),(20,28,'2026-01-09','14:00:00','15:00:00',60,'2026-01-07 00:31:34','2026-01-07 00:31:34');
/*!40000 ALTER TABLE `booking_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_ref` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `provider_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `provider_service_id` bigint unsigned NOT NULL,
  `booking_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('awaiting_provider','confirmed','in_progress','rejected','completed','cancelled','refunded','reschedule_pending_provider','reschedule_pending_customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'awaiting_provider',
  `booking_type` enum('custom','hourly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hourly',
  `booking_working_minutes` int unsigned NOT NULL DEFAULT '0',
  `total_price` decimal(10,2) NOT NULL,
  `service_charges` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_method_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cancelled_reason` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_ref_unique` (`booking_ref`),
  KEY `bookings_customer_id_foreign` (`customer_id`),
  KEY `bookings_service_id_foreign` (`service_id`),
  KEY `bookings_provider_service_id_foreign` (`provider_service_id`),
  KEY `bookings_provider_id_status_index` (`provider_id`,`status`),
  KEY `bookings_stripe_payment_intent_id_index` (`stripe_payment_intent_id`),
  CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_provider_service_id_foreign` FOREIGN KEY (`provider_service_id`) REFERENCES `provider_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,'FT-20251204-SHFJ3V',14,4,5,1,'321 Elm Street, Houston, TX 77001','Sample booking for review seeder','completed','hourly',369,167.00,47.00,NULL,NULL,'2025-11-23 23:40:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37',NULL),(2,'FT-20251204-A6MSHD',12,6,13,2,'456 Oak Avenue, Los Angeles, CA 90001','Sample booking for review seeder','completed','hourly',238,218.00,30.00,NULL,NULL,'2025-11-22 23:40:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37',NULL),(3,'FT-20251204-HTYMBO',12,7,5,3,'654 Maple Drive, Phoenix, AZ 85001','Sample booking for review seeder','completed','hourly',272,267.00,17.00,NULL,NULL,'2025-11-24 23:40:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37',NULL),(4,'FT-20251204-RH0FHW',12,4,3,4,'456 Oak Avenue, Los Angeles, CA 90001','Sample booking for review seeder','completed','hourly',266,417.00,39.00,NULL,NULL,'2025-11-09 23:40:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37',NULL),(5,'FT-20251204-3FMCKW',12,14,3,5,'321 Elm Street, Houston, TX 77001','Sample booking for review seeder','completed','hourly',295,201.00,42.00,NULL,NULL,'2025-11-23 23:40:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37',NULL),(6,'FT-20251204-E6QCAX',14,5,9,6,'654 Maple Drive, Phoenix, AZ 85001','Sample booking for review seeder','completed','hourly',363,149.00,43.00,NULL,NULL,'2025-11-25 23:40:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37',NULL),(7,'FT-20251204-RPHZAE',11,3,14,7,'654 Maple Drive, Phoenix, AZ 85001','Sample booking for review seeder','completed','hourly',186,127.00,37.00,NULL,NULL,'2025-12-02 23:40:37',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37',NULL),(8,'FT-20251204-URZZTB',9,6,9,8,'123 Main Street, New York, NY 10001','Sample booking for review seeder','completed','hourly',391,200.00,42.00,NULL,NULL,'2025-11-17 23:40:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:38','2025-12-04 23:40:38',NULL),(9,'FT-20251204-KALWEY',9,5,5,9,'456 Oak Avenue, Los Angeles, CA 90001','Sample booking for review seeder','completed','hourly',434,469.00,32.00,NULL,NULL,'2025-11-07 23:40:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:38','2025-12-04 23:40:38',NULL),(10,'FT-20251204-WEIG8E',8,7,8,10,'789 Pine Road, Chicago, IL 60601','Sample booking for review seeder','completed','hourly',262,379.00,39.00,NULL,NULL,'2025-11-29 23:40:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-04 23:40:38','2025-12-04 23:40:38',NULL),(11,'FT-20251218-DR5S9W',17,16,6,12,'Dubai rashid Mina’s road','Cooking','reschedule_pending_customer','hourly',120,11.00,5.00,NULL,NULL,'2025-12-20 22:59:26','2025-12-18 21:42:47',NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-18 19:42:47','2025-12-21 01:20:08',NULL),(12,'FT-20251218-NPOVTK',17,16,6,12,'Dubai rashid Mina’s road','Cooking','completed','hourly',120,11.00,5.00,NULL,NULL,'2025-12-22 00:08:48','2025-12-18 22:13:34',NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-18 20:13:34','2026-01-04 01:26:40',NULL),(13,'FT-20251218-SPW2M0',17,16,6,12,'Dubai rashid Mina’s road','Cooking','completed','hourly',60,11.00,5.00,NULL,NULL,'2025-12-20 23:23:25','2025-12-18 22:15:41',NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-18 20:15:41','2025-12-22 00:27:23',NULL),(14,'FT-20251219-S9EGXV',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','completed','hourly',60,27.00,5.00,NULL,NULL,'2025-12-22 01:00:22','2025-12-19 15:19:58',NULL,NULL,NULL,NULL,NULL,NULL,'2025-12-19 13:19:58','2026-01-04 01:36:59',NULL),(15,'FT-20251220-IDCBGF',17,16,2,11,'12 Main Street','Deep cleaning','cancelled','hourly',240,150.00,5.00,NULL,NULL,'2025-12-22 01:01:28','2025-12-20 23:48:10',NULL,NULL,NULL,'2026-01-06 22:00:09',NULL,NULL,'2025-12-20 21:48:10','2026-01-06 22:00:09','Booked wrong service'),(16,'FT-20260103-O56MWR',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','cancelled','hourly',60,12.00,5.00,NULL,NULL,'2026-01-06 22:16:23','2026-01-04 01:43:41',NULL,NULL,NULL,'2026-01-06 22:17:59',NULL,NULL,'2026-01-03 23:43:41','2026-01-06 22:17:59','Issue with pricing'),(17,'FT-20260103-PQFXL5',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','rejected','hourly',60,12.00,5.00,NULL,NULL,NULL,'2026-01-04 01:51:41',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-03 23:51:41','2026-01-04 01:45:10',NULL),(18,'FT-20260103-KHD4GW',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','cancelled','hourly',60,12.00,5.00,NULL,NULL,'2026-01-06 22:16:51','2026-01-04 01:54:01',NULL,NULL,NULL,'2026-01-06 22:49:41',NULL,NULL,'2026-01-03 23:54:01','2026-01-06 22:49:41','unable to work properly'),(19,'FT-20260103-XZWX5A',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','completed','hourly',60,12.00,5.00,NULL,NULL,'2026-01-06 22:17:32','2026-01-04 01:58:12',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-03 23:58:12','2026-01-07 02:18:30',NULL),(20,'FT-20260103-6LTQMW',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','confirmed','hourly',60,12.00,5.00,NULL,NULL,'2026-01-06 22:17:21','2026-01-04 01:59:07',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-03 23:59:07','2026-01-06 22:17:21',NULL),(21,'FT-20260104-G7LRVR',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','confirmed','hourly',60,12.00,5.00,NULL,NULL,NULL,'2026-01-04 02:01:02',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-04 00:01:02','2026-01-04 01:58:22',NULL),(22,'FT-20260104-KH9LY9',17,16,6,12,'Dubai rashid Mina’s road','Cooking','rejected','hourly',60,11.00,5.00,NULL,NULL,NULL,'2026-01-04 02:02:56',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-04 00:02:56','2026-01-04 01:58:28',NULL),(23,'FT-20260104-B82Y3X',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','rejected','hourly',60,12.00,5.00,NULL,NULL,NULL,'2026-01-04 02:08:12',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-04 00:08:12','2026-01-04 01:58:34',NULL),(24,'FT-20260104-XSPPBB',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','rejected','hourly',60,12.00,5.00,NULL,NULL,NULL,'2026-01-04 02:12:50',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-04 00:12:50','2026-01-04 01:58:39',NULL),(25,'FT-20260104-1HDYMS',17,16,2,11,'Dubai rashid Mina’s road','Deep cleaning','rejected','hourly',120,12.00,5.00,NULL,NULL,NULL,'2026-01-04 02:21:51',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-04 00:21:51','2026-01-04 01:58:44',NULL),(26,'FT-20260107-4T2JE8',17,16,6,12,'Dubai rashid Mina’s road','Cooking','confirmed','hourly',60,13.00,5.00,NULL,NULL,NULL,'2026-01-07 02:20:55',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-07 00:20:55','2026-01-07 00:21:22',NULL),(27,'FT-20260107-PV8V7W',17,16,6,12,'Dubai rashid Mina’s road','Cooking','confirmed','hourly',60,11.00,5.00,NULL,NULL,NULL,'2026-01-07 02:28:42',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-07 00:28:42','2026-01-07 00:29:18',NULL),(28,'FT-20260107-BDAO67',17,16,6,12,'Dubai rashid Mina’s road','Cooking','confirmed','hourly',60,11.00,5.00,NULL,NULL,NULL,'2026-01-07 02:31:34',NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-07 00:31:34','2026-01-07 00:32:02',NULL);
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookmarks`
--

DROP TABLE IF EXISTS `bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookmarks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provider_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookmarks_user_id_provider_id_unique` (`user_id`,`provider_id`),
  KEY `bookmarks_provider_id_foreign` (`provider_id`),
  CONSTRAINT `bookmarks_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookmarks`
--

LOCK TABLES `bookmarks` WRITE;
/*!40000 ALTER TABLE `bookmarks` DISABLE KEYS */;
INSERT INTO `bookmarks` VALUES (2,17,3,'2025-12-06 20:08:26','2025-12-06 20:08:26'),(4,17,5,'2025-12-06 20:08:31','2025-12-06 20:08:31'),(5,17,6,'2025-12-06 20:08:34','2025-12-06 20:08:34');
/*!40000 ALTER TABLE `bookmarks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversation_participants`
--

DROP TABLE IF EXISTS `conversation_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversation_participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` enum('customer','provider','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `joined_at` timestamp NULL DEFAULT NULL,
  `muted_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversation_participants_conversation_id_user_id_unique` (`conversation_id`,`user_id`),
  KEY `conversation_participants_conversation_id_index` (`conversation_id`),
  KEY `conversation_participants_user_id_index` (`user_id`),
  KEY `conversation_participants_role_index` (`role`),
  CONSTRAINT `conversation_participants_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversation_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversation_participants`
--

LOCK TABLES `conversation_participants` WRITE;
/*!40000 ALTER TABLE `conversation_participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversation_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_by_id` bigint unsigned NOT NULL,
  `type` enum('direct','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'direct',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conversations_created_by_id_index` (`created_by_id`),
  KEY `conversations_type_index` (`type`),
  KEY `conversations_last_message_at_index` (`last_message_at`),
  CONSTRAINT `conversations_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_tokens`
--

DROP TABLE IF EXISTS `device_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_tokens`
--

LOCK TABLES `device_tokens` WRITE;
/*!40000 ALTER TABLE `device_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disputes`
--

DROP TABLE IF EXISTS `disputes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disputes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disputes`
--

LOCK TABLES `disputes` WRITE;
/*!40000 ALTER TABLE `disputes` DISABLE KEYS */;
/*!40000 ALTER TABLE `disputes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"a593f40d-0681-4bc7-ab37-b32358037717\",\"displayName\":\"App\\\\Mail\\\\OtpCodeMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:20:\\\"App\\\\Mail\\\\OtpCodeMail\\\":3:{s:8:\\\"mailData\\\";a:5:{s:5:\\\"title\\\";s:18:\\\"Reset Password OTP\\\";s:4:\\\"body\\\";s:41:\\\"Use the OTP below to reset your password.\\\";s:5:\\\"email\\\";s:18:\\\"provider@gmail.com\\\";s:3:\\\"otp\\\";i:123456;s:4:\\\"logo\\\";s:48:\\\"http:\\/\\/16.176.207.45\\/assets\\/logos\\/email_logo.png\\\";}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"provider@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1766087123,\"delay\":null}',0,NULL,1766087123,1766087123);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_reads`
--

DROP TABLE IF EXISTS `message_reads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message_reads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_reads_message_id_user_id_unique` (`message_id`,`user_id`),
  KEY `message_reads_message_id_index` (`message_id`),
  KEY `message_reads_user_id_index` (`user_id`),
  CONSTRAINT `message_reads_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `message_reads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_reads`
--

LOCK TABLES `message_reads` WRITE;
/*!40000 ALTER TABLE `message_reads` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_reads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `kind` enum('text','attachment','system') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `body` text COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`),
  KEY `messages_conversation_id_index` (`conversation_id`),
  KEY `messages_sender_id_index` (`sender_id`),
  KEY `messages_kind_index` (`kind`),
  CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=452 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (417,'0001_01_01_000000_create_users_table',1),(418,'0001_01_01_000001_create_cache_table',1),(419,'0001_01_01_000002_create_jobs_table',1),(420,'2025_08_11_205721_create_personal_access_tokens_table',1),(421,'2025_08_11_230409_create_permission_tables',1),(422,'2025_08_11_234249_create_services_table',1),(423,'2025_08_13_235317_create_booking_messages_table',1),(424,'2025_08_13_235330_create_payouts_table',1),(425,'2025_08_13_235335_create_disputes_table',1),(426,'2025_08_13_235339_create_provider_profiles_table',1),(427,'2025_08_13_235343_create_categories_table',1),(428,'2025_08_13_235358_create_transactions_table',1),(429,'2025_08_13_235401_create_device_tokens_table',1),(430,'2025_08_15_180748_create_provider_services_table',1),(431,'2025_08_15_180817_create_provider_certificates_table',1),(432,'2025_08_15_181326_create_provider_service_media_table',1),(433,'2025_08_21_191624_create_provider_working_hours_table',1),(434,'2025_08_29_194915_create_bookmarks_table',1),(435,'2025_09_03_230328_create_bookings_table',1),(436,'2025_09_03_230330_create_booking_days_table',1),(437,'2025_09_04_185150_create_booking_slots_table',1),(438,'2025_09_04_235503_create_booking_reschedules_table',1),(439,'2025_09_23_100001_create_conversations_table',1),(440,'2025_09_23_100002_create_conversation_participants_table',1),(441,'2025_09_23_100003_create_messages_table',1),(442,'2025_09_23_100004_create_attachments_table',1),(443,'2025_09_23_100005_create_message_reads_table',1),(444,'2025_09_23_100006_create_offers_table',1),(445,'2025_09_23_100007_create_offer_revisions_table',1),(446,'2025_11_24_233527_add_rate_mid_to_provider_services_table',1),(447,'2025_11_25_002219_add_cover_photo_to_users_table',1),(448,'2025_12_04_225209_create_reviews_table',1),(449,'2025_12_16_000001_add_stripe_customer_id_to_users',2),(450,'2025_12_16_000002_create_user_payment_methods_table',2),(451,'2026_01_02_123310_add_cancel_reason_to_bookings_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',2),(3,'App\\Models\\User',3),(3,'App\\Models\\User',4),(3,'App\\Models\\User',5),(3,'App\\Models\\User',6),(3,'App\\Models\\User',7),(2,'App\\Models\\User',8),(1,'App\\Models\\User',9),(2,'App\\Models\\User',10),(2,'App\\Models\\User',11),(2,'App\\Models\\User',12),(2,'App\\Models\\User',13),(3,'App\\Models\\User',13),(2,'App\\Models\\User',14),(3,'App\\Models\\User',14),(2,'App\\Models\\User',15),(3,'App\\Models\\User',16),(3,'App\\Models\\User',17),(3,'App\\Models\\User',18),(3,'App\\Models\\User',19),(3,'App\\Models\\User',20);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offer_revisions`
--

DROP TABLE IF EXISTS `offer_revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offer_revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `offer_id` bigint unsigned NOT NULL,
  `by_user_id` bigint unsigned NOT NULL,
  `cost_items` json DEFAULT NULL,
  `materials` json DEFAULT NULL,
  `flat_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `offer_revisions_offer_id_index` (`offer_id`),
  KEY `offer_revisions_by_user_id_index` (`by_user_id`),
  CONSTRAINT `offer_revisions_by_user_id_foreign` FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `offer_revisions_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offer_revisions`
--

LOCK TABLES `offer_revisions` WRITE;
/*!40000 ALTER TABLE `offer_revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `offer_revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offers`
--

DROP TABLE IF EXISTS `offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `provider_id` bigint unsigned NOT NULL,
  `service_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_from` timestamp NULL DEFAULT NULL,
  `time_to` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','countered','bargained','accepted','declined','finalized') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `current_revision_id` bigint unsigned DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `finalized_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `offers_conversation_id_index` (`conversation_id`),
  KEY `offers_customer_id_index` (`customer_id`),
  KEY `offers_provider_id_index` (`provider_id`),
  KEY `offers_service_type_index` (`service_type`),
  KEY `offers_time_from_index` (`time_from`),
  KEY `offers_time_to_index` (`time_to`),
  KEY `offers_status_index` (`status`),
  KEY `offers_current_revision_id_index` (`current_revision_id`),
  CONSTRAINT `offers_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `offers_current_revision_id_foreign` FOREIGN KEY (`current_revision_id`) REFERENCES `offer_revisions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `offers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `offers_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offers`
--

LOCK TABLES `offers` WRITE;
/*!40000 ALTER TABLE `offers` DISABLE KEYS */;
/*!40000 ALTER TABLE `offers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('admin@admin.com','$2y$12$qECCHGLWL7dmcQzGBy1bwOxeS1msh9lLfAbCVtsrdna571SbBud62','2025-12-07 22:43:11');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payouts`
--

DROP TABLE IF EXISTS `payouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payouts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payouts`
--

LOCK TABLES `payouts` WRITE;
/*!40000 ALTER TABLE `payouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `payouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'read user management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(2,'write user management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(3,'create user management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(4,'delete user management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(5,'read content management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(6,'write content management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(7,'create content management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(8,'delete content management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(9,'read financial management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(10,'write financial management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(11,'create financial management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(12,'delete financial management','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(13,'read reporting','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(14,'write reporting','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(15,'create reporting','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(16,'delete reporting','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(17,'read view products','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(18,'write view products','web','2025-12-04 23:40:32','2025-12-04 23:40:32'),(19,'create view products','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(20,'delete view products','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(21,'read place orders','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(22,'write place orders','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(23,'create place orders','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(24,'delete place orders','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(25,'read manage profile','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(26,'write manage profile','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(27,'create manage profile','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(28,'delete manage profile','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(29,'read manage services','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(30,'write manage services','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(31,'create manage services','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(32,'delete manage services','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(33,'read view bookings','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(34,'write view bookings','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(35,'create view bookings','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(36,'delete view bookings','web','2025-12-04 23:40:33','2025-12-04 23:40:33');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',15,'guest_token','c24fc2dfdc98f8e35e6953f335c89b64c977908b762cc13137924b7717777ef8','[\"*\"]','2025-12-15 21:19:36',NULL,'2025-12-04 23:40:57','2025-12-15 21:19:36'),(2,'App\\Models\\User',16,'guest_token','db57081f79596f82b5ebb384bf153e8424f0c8769f79bde0bb54a93259c64cc5','[\"*\"]','2025-12-15 21:09:37',NULL,'2025-12-04 23:41:56','2025-12-15 21:09:37'),(3,'App\\Models\\User',17,'guest_token','ab05c965cc798477f89075c14a1c564a5a03957f2b40bfd0a9265f8c47f789d0','[\"*\"]','2025-12-06 20:08:34',NULL,'2025-12-06 20:08:01','2025-12-06 20:08:34'),(4,'App\\Models\\User',17,'guest_token','df92113f6ab400d3fafddb71891b07fce16642e3620dee46a76d6c175540fd1a','[\"*\"]','2025-12-15 19:46:05',NULL,'2025-12-15 18:58:55','2025-12-15 19:46:05'),(5,'App\\Models\\User',17,'guest_token','d506162f55b40260277bc85bd910c7b5ba9c9bd7cdc20d2c32a7d318e5328358','[\"*\"]','2025-12-19 12:21:52',NULL,'2025-12-15 19:13:40','2025-12-19 12:21:52'),(6,'App\\Models\\User',16,'guest_token','5d0f0fe47d6e9e0c0962c67dd8d38e4ababa26f2be1fd53dc2408e3850f5dbba','[\"*\"]','2025-12-15 19:53:21',NULL,'2025-12-15 19:51:37','2025-12-15 19:53:21'),(7,'App\\Models\\User',17,'guest_token','583f9bbfb8725de146c8f8e4871407add2bcb050907358d52da4a5e4df11d4f0','[\"*\"]','2025-12-15 19:53:50',NULL,'2025-12-15 19:53:50','2025-12-15 19:53:50'),(8,'App\\Models\\User',16,'guest_token','5578907f83c93a35819908e511efb306190db483beea515e91150f9332b824e0','[\"*\"]','2025-12-15 20:10:26',NULL,'2025-12-15 20:09:38','2025-12-15 20:10:26'),(9,'App\\Models\\User',17,'guest_token','f7be9a444e9569c47f21b8c68f0a5fb444f63dd9c8a265d8379733f3c08d3a70','[\"*\"]','2025-12-18 19:42:47',NULL,'2025-12-15 20:10:49','2025-12-18 19:42:47'),(10,'App\\Models\\User',16,'guest_token','a95d6d4f4be89a070daed674298ec3e54c98d10561289698b9040ddc3ede547b','[\"*\"]',NULL,NULL,'2025-12-15 21:19:23','2025-12-15 21:19:23'),(11,'App\\Models\\User',16,'guest_token','e450ec5ba077f5c000d8dcdacd554ff8671362b5593f1a679e356832ed2782a4','[\"*\"]',NULL,NULL,'2025-12-15 21:21:34','2025-12-15 21:21:34'),(12,'App\\Models\\User',15,'guest_token','6518b380db60bf02b8b54fd55a7c54af15004f9ee99fa7ffbed830f0fff410d8','[\"*\"]','2025-12-15 21:22:51',NULL,'2025-12-15 21:22:05','2025-12-15 21:22:51'),(13,'App\\Models\\User',16,'guest_token','0b9db945adf7336bc69e100fafb5669afa8f44f883c75cd5c2ddf6e00c0985d3','[\"*\"]',NULL,NULL,'2025-12-15 21:22:29','2025-12-15 21:22:29'),(14,'App\\Models\\User',15,'guest_token','cc9b1c3b9e34c06b8b7d31c278190ecf3eb8e85bcc974e8a79a4c0acb2b00d05','[\"*\"]','2025-12-18 15:36:18',NULL,'2025-12-15 22:19:09','2025-12-18 15:36:18'),(15,'App\\Models\\User',16,'guest_token','2d3e25675409306001da51ed90faa295f2f157d23a14861842410eaee4174bec','[\"*\"]','2025-12-15 22:31:08',NULL,'2025-12-15 22:29:30','2025-12-15 22:31:08'),(16,'App\\Models\\User',16,'guest_token','35421a9c373db7dd30aa62ba62c981cf10a2150a43827d2d035f0a6d51ba61a7','[\"*\"]','2025-12-18 19:48:56',NULL,'2025-12-18 19:47:48','2025-12-18 19:48:56'),(17,'App\\Models\\User',16,'guest_token','91b20681ddcef1b514d04ce4f3fba33ee1311fe65b91a224606c6a19842d9a61','[\"*\"]','2026-01-04 01:15:42',NULL,'2025-12-18 19:54:03','2026-01-04 01:15:42'),(18,'App\\Models\\User',17,'guest_token','42bc124143b262af671a4a0900991c912b38939fd85642bdfa27f5d9337fe76f','[\"*\"]','2025-12-19 06:50:26',NULL,'2025-12-18 20:12:59','2025-12-19 06:50:26'),(19,'App\\Models\\User',16,'guest_token','d07e433f7d10945422bd182acd2ca0c731506992e27c2668e4b140fd9e5721b3','[\"*\"]','2025-12-19 12:20:10',NULL,'2025-12-19 06:50:42','2025-12-19 12:20:10'),(20,'App\\Models\\User',17,'guest_token','2b5683d22d1d18a5b82f36ea25527f4a71224f57af127f616befb2d5b0c25ec4','[\"*\"]','2025-12-21 08:13:08',NULL,'2025-12-19 12:22:35','2025-12-21 08:13:08'),(21,'App\\Models\\User',17,'guest_token','48ff2422bf5a851a8ed4f8dce84a6d42fdb132a61d2996c2f5413aa76edb902c','[\"*\"]','2026-01-06 23:16:24',NULL,'2025-12-19 12:32:28','2026-01-06 23:16:24'),(22,'App\\Models\\User',16,'guest_token','d72b11e7af59b6e7644fcb70a01224d1bc56880ac18384a5a5eac0be7fccd7a5','[\"*\"]','2025-12-22 00:00:08',NULL,'2025-12-20 10:30:27','2025-12-22 00:00:08'),(23,'App\\Models\\User',17,'guest_token','baae780cc1fabe8527cfcc5d0cbf20ee827bf5f4758c8c8f87e8ff907a53b357','[\"*\"]','2026-01-07 02:06:15',NULL,'2025-12-21 23:41:42','2026-01-07 02:06:15'),(24,'App\\Models\\User',16,'guest_token','f7ee30ddc6911ff3d76aac7efa3c0a8f2490395a13a040cd66cf72d5396012e6','[\"*\"]','2025-12-22 00:56:49',NULL,'2025-12-22 00:02:30','2025-12-22 00:56:49'),(25,'App\\Models\\User',17,'guest_token','a5892ba04ed3aaaf5a91df30fa9868c691c427d0d83d6d0967a7de68551ff12d','[\"*\"]','2025-12-22 01:06:09',NULL,'2025-12-22 00:59:54','2025-12-22 01:06:09'),(26,'App\\Models\\User',16,'guest_token','4d1c599ca31d1df5b3e55fccb2a252060797a5fa6abb96250b016f1337519d53','[\"*\"]','2026-01-04 00:46:43',NULL,'2026-01-03 23:17:46','2026-01-04 00:46:43'),(27,'App\\Models\\User',16,'guest_token','96cd96e13cdfc3df1af151d966e5b3b28291bc49882d013034721463fcf4bd58','[\"*\"]','2026-01-07 02:18:31',NULL,'2026-01-04 00:49:40','2026-01-07 02:18:31'),(28,'App\\Models\\User',17,'guest_token','61e814390dae24380f69e3196014196873c477177abcee3f3f72d24b354e8522','[\"*\"]','2026-01-06 21:38:44',NULL,'2026-01-04 02:00:45','2026-01-06 21:38:44'),(29,'App\\Models\\User',15,'guest_token','79c743d63e5d787329ec938ca1ad09ce85f28707f7f4d8fe3447ca1d13b33c06','[\"*\"]','2026-01-10 14:41:44',NULL,'2026-01-06 20:15:55','2026-01-10 14:41:44'),(30,'App\\Models\\User',16,'guest_token','cb917fce9ba386d660158a99619cc5e9005d4b84d649876ba460553afc7ed951','[\"*\"]','2026-01-07 01:11:42',NULL,'2026-01-07 01:11:19','2026-01-07 01:11:42'),(31,'App\\Models\\User',18,'guest_token','45801d800aa838bfbd8c396ceebcd60146c7921feacceb8b4a5f9aaee75b9385','[\"*\"]','2026-01-09 21:05:13',NULL,'2026-01-07 01:23:41','2026-01-09 21:05:13'),(32,'App\\Models\\User',16,'guest_token','ffe4f62ad3dfc461ae1110db35b8afd196eb72dadf964500826d11688723ce12','[\"*\"]','2026-01-07 11:49:19',NULL,'2026-01-07 11:45:42','2026-01-07 11:49:19'),(33,'App\\Models\\User',16,'guest_token','adae00a708d5086c066565e4e83161f6740eff6d8dcc5ba85f25356b970a35f5','[\"*\"]','2026-01-10 14:07:08',NULL,'2026-01-07 12:50:06','2026-01-10 14:07:08'),(34,'App\\Models\\User',20,'guest_token','3aec228f06514c39ba3340f41ccfadae76974b07459e54cb456a92202c34c75e','[\"*\"]','2026-01-07 22:34:49',NULL,'2026-01-07 22:29:54','2026-01-07 22:34:49'),(35,'App\\Models\\User',17,'guest_token','f81c2c29427951421367113291fb82ef63391923ec489a2529302c9ca8b450dd','[\"*\"]','2026-01-09 21:51:27',NULL,'2026-01-09 21:06:23','2026-01-09 21:51:27');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_certificates`
--

DROP TABLE IF EXISTS `provider_certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provider_profile_id` bigint unsigned NOT NULL,
  `provider_service_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_certificates_user_id_foreign` (`user_id`),
  KEY `provider_certificates_provider_profile_id_foreign` (`provider_profile_id`),
  KEY `provider_certificates_provider_service_id_foreign` (`provider_service_id`),
  CONSTRAINT `provider_certificates_provider_profile_id_foreign` FOREIGN KEY (`provider_profile_id`) REFERENCES `provider_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_certificates_provider_service_id_foreign` FOREIGN KEY (`provider_service_id`) REFERENCES `provider_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_certificates`
--

LOCK TABLES `provider_certificates` WRITE;
/*!40000 ALTER TABLE `provider_certificates` DISABLE KEYS */;
/*!40000 ALTER TABLE `provider_certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_profiles`
--

DROP TABLE IF EXISTS `provider_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `about_me` text COLLATE utf8mb4_unicode_ci,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `id_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_permit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_photo_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `passport_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `work_permit_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `availability_status` enum('fully_booked','available','not_available') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `provider_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_profiles`
--

LOCK TABLES `provider_profiles` WRITE;
/*!40000 ALTER TABLE `provider_profiles` DISABLE KEYS */;
INSERT INTO `provider_profiles` VALUES (1,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending','pending','available',0,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(2,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending','pending','available',0,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(3,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending','pending','available',0,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(4,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending','pending','available',0,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(5,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending','pending','available',0,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(6,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending','pending','available',0,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(7,16,NULL,'storage/provider/profile/5JOtNOTR3SJKQ3JbMMNl7msnIx0NLeSXEhJrAtYU.png',NULL,'Fujairah',NULL,NULL,'123456',NULL,NULL,'storage/provider/profile/0yxwkCaczUBiOBvv64AVvHPjCbTctuwyREIAZWwz.png','storage/provider/profile/ZD4ukyWGyN37NRZkD9njbfuyH6RpJvcQuo4x34tl.png','storage/provider/profile/IWmylVPbBaRuMJIjOKx99s3lQhIOfahwZBzogUTE.jpg','pending','pending','pending','available',0,'2025-12-04 23:41:51','2026-01-07 13:07:49'),(8,18,NULL,'storage/provider/profile/ORnAOh8WrB60HKMCDnUXOyf614NaDUv4Z41tfXQ9.jpg',NULL,'Al Ain',NULL,NULL,'test',NULL,NULL,'storage/provider/profile/Lh6BWSXdzJIaVr3l3LnGdEWw8ythSABwFxSCn2HI.jpg','storage/provider/profile/YI4mEyxCtCvFyrUT2ZLHKWnDHr7uyEsS9j558sQN.jpg','storage/provider/profile/k5o2nfKZ8f5hfXoUO532xloE5YgzHVPRVpvlS0Uo.jpg','pending','pending','pending','available',0,'2026-01-07 01:23:11','2026-01-09 21:05:12'),(9,19,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending','pending','available',0,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(10,20,NULL,'storage/provider/profile/opOkZ0Vqy5Hp3XYIUiURzXR1C7LOlT0YDJ6ZjOUm.jpg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending','pending','available',0,'2026-01-07 22:29:43','2026-01-07 22:31:33');
/*!40000 ALTER TABLE `provider_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_service_media`
--

DROP TABLE IF EXISTS `provider_service_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_service_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provider_profile_id` bigint unsigned NOT NULL,
  `provider_service_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('photo','video') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_service_media_user_id_foreign` (`user_id`),
  KEY `provider_service_media_provider_profile_id_foreign` (`provider_profile_id`),
  KEY `provider_service_media_provider_service_id_foreign` (`provider_service_id`),
  CONSTRAINT `provider_service_media_provider_profile_id_foreign` FOREIGN KEY (`provider_profile_id`) REFERENCES `provider_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_service_media_provider_service_id_foreign` FOREIGN KEY (`provider_service_id`) REFERENCES `provider_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_service_media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_service_media`
--

LOCK TABLES `provider_service_media` WRITE;
/*!40000 ALTER TABLE `provider_service_media` DISABLE KEYS */;
INSERT INTO `provider_service_media` VALUES (1,16,7,11,'storage/provider/services/photos/v6iz48F6eOL8Y2AJqtkrA1kBblKQ67kKnh3E5x9N.jpg','photo','2025-12-15 19:53:20','2025-12-15 19:53:20'),(2,16,7,12,'storage/provider/services/photos/5TLXkNm6PDXTlYgGc78MjCJK5srzKK4mMJqWyQ4q.jpg','photo','2025-12-15 20:10:25','2025-12-15 20:10:25'),(3,18,8,13,'storage/provider/services/photos/8BmfJrIQJTBpd4rdDt9LaWeesPjuz9CdlKTkIvbU.jpg','photo','2026-01-07 01:42:18','2026-01-07 01:42:18'),(4,18,8,14,'storage/provider/services/photos/vnshRkqQQk03ZwEOUUtXEDdZi4720UyCOr7CohD3.jpg','photo','2026-01-07 01:48:53','2026-01-07 01:48:53'),(5,20,10,15,'storage/provider/services/photos/4lTv2MfAYZ4acpKHrM35P4liP8egbPp0I1QJULF3.jpg','photo','2026-01-07 22:34:14','2026-01-07 22:34:14'),(6,20,10,15,'storage/provider/services/videos/JzD8FuJpDdijqZvnu4hBhUIzoUgjVqkOojImQmIT.mov','video','2026-01-07 22:34:14','2026-01-07 22:34:14');
/*!40000 ALTER TABLE `provider_service_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_services`
--

DROP TABLE IF EXISTS `provider_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `provider_profile_id` bigint unsigned NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `show_certificate` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` longtext COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `staff_count` int unsigned DEFAULT '1',
  `rate_min` decimal(10,2) DEFAULT NULL,
  `rate_mid` decimal(10,2) DEFAULT NULL,
  `rate_max` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_services_user_id_service_id_unique` (`user_id`,`service_id`),
  KEY `provider_services_service_id_foreign` (`service_id`),
  KEY `provider_services_provider_profile_id_foreign` (`provider_profile_id`),
  CONSTRAINT `provider_services_provider_profile_id_foreign` FOREIGN KEY (`provider_profile_id`) REFERENCES `provider_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_services`
--

LOCK TABLES `provider_services` WRITE;
/*!40000 ALTER TABLE `provider_services` DISABLE KEYS */;
INSERT INTO `provider_services` VALUES (1,4,5,1,0,1,NULL,NULL,NULL,1,27.00,NULL,67.00,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(2,6,13,2,0,1,NULL,NULL,NULL,1,46.00,NULL,74.00,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(3,7,5,3,0,1,NULL,NULL,NULL,1,38.00,NULL,57.00,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(4,4,3,1,0,1,NULL,NULL,NULL,1,49.00,NULL,67.00,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(5,14,3,4,0,1,NULL,NULL,NULL,1,20.00,NULL,84.00,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(6,5,9,5,0,1,NULL,NULL,NULL,1,30.00,NULL,96.00,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(7,3,14,6,0,1,NULL,NULL,NULL,1,48.00,NULL,90.00,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(8,6,9,2,0,1,NULL,NULL,NULL,1,25.00,NULL,97.00,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(9,5,5,5,0,1,NULL,NULL,NULL,1,38.00,NULL,72.00,'2025-12-04 23:40:38','2025-12-04 23:40:38'),(10,7,8,3,0,1,NULL,NULL,NULL,1,28.00,NULL,52.00,'2025-12-04 23:40:38','2025-12-04 23:40:38'),(11,16,2,7,1,1,'Carpenter',NULL,'abcdefghi',3,12.00,NULL,15.00,'2025-12-15 19:53:20','2025-12-15 19:53:20'),(12,16,6,7,0,1,'I can cook well','Cooking','I can cook well',1,11.00,NULL,19.00,'2025-12-15 20:10:25','2025-12-15 20:10:25'),(13,18,3,8,1,1,'Laundry',NULL,'I can do laundry',12,14.00,NULL,22.00,'2026-01-07 01:42:18','2026-01-07 01:42:18'),(14,18,7,8,1,1,'Electric Work',NULL,'dfs',23,13.00,NULL,13.00,'2026-01-07 01:48:53','2026-01-07 01:48:53'),(15,20,3,10,0,1,'washing title','washing service','description',NULL,10.00,NULL,20.00,'2026-01-07 22:34:14','2026-01-07 22:34:48');
/*!40000 ALTER TABLE `provider_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_working_hours`
--

DROP TABLE IF EXISTS `provider_working_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_working_hours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provider_profile_id` bigint unsigned NOT NULL,
  `day` enum('sunday','monday','tuesday','wednesday','thursday','friday','saturday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_working_hours_user_id_day_unique` (`user_id`,`day`),
  KEY `provider_working_hours_provider_profile_id_foreign` (`provider_profile_id`),
  CONSTRAINT `provider_working_hours_provider_profile_id_foreign` FOREIGN KEY (`provider_profile_id`) REFERENCES `provider_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `provider_working_hours_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_working_hours`
--

LOCK TABLES `provider_working_hours` WRITE;
/*!40000 ALTER TABLE `provider_working_hours` DISABLE KEYS */;
INSERT INTO `provider_working_hours` VALUES (1,16,7,'sunday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(2,16,7,'monday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(3,16,7,'tuesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(4,16,7,'wednesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(5,16,7,'thursday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(6,16,7,'friday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(7,16,7,'saturday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(8,4,1,'sunday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(9,4,1,'monday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(10,4,1,'tuesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(11,4,1,'wednesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(12,4,1,'thursday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(13,4,1,'friday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(14,4,1,'saturday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(15,5,5,'sunday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(16,5,5,'monday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(17,5,5,'tuesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(18,5,5,'wednesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(19,5,5,'thursday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(20,5,5,'friday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(21,5,5,'saturday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(24,6,2,'sunday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(25,6,2,'monday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(26,6,2,'tuesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(27,6,2,'wednesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(28,6,2,'thursday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(29,6,2,'friday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(30,6,2,'saturday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(31,7,3,'sunday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(32,7,3,'monday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(33,7,3,'tuesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(34,7,3,'wednesday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(35,7,3,'thursday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(36,7,3,'friday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(37,7,3,'saturday',NULL,NULL,1,'2025-12-04 23:41:51','2025-12-04 23:41:51'),(38,18,8,'sunday',NULL,NULL,0,'2026-01-07 01:23:11','2026-01-07 01:23:11'),(39,18,8,'monday',NULL,NULL,0,'2026-01-07 01:23:11','2026-01-07 01:23:11'),(40,18,8,'tuesday',NULL,NULL,0,'2026-01-07 01:23:11','2026-01-07 01:23:11'),(41,18,8,'wednesday',NULL,NULL,0,'2026-01-07 01:23:11','2026-01-07 01:23:11'),(42,18,8,'thursday',NULL,NULL,0,'2026-01-07 01:23:11','2026-01-07 01:23:11'),(43,18,8,'friday',NULL,NULL,0,'2026-01-07 01:23:11','2026-01-07 01:23:11'),(44,18,8,'saturday',NULL,NULL,0,'2026-01-07 01:23:11','2026-01-07 01:23:11'),(45,19,9,'sunday',NULL,NULL,0,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(46,19,9,'monday',NULL,NULL,0,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(47,19,9,'tuesday',NULL,NULL,0,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(48,19,9,'wednesday',NULL,NULL,0,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(49,19,9,'thursday',NULL,NULL,0,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(50,19,9,'friday',NULL,NULL,0,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(51,19,9,'saturday',NULL,NULL,0,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(52,20,10,'sunday',NULL,NULL,0,'2026-01-07 22:29:43','2026-01-07 22:29:43'),(53,20,10,'monday',NULL,NULL,0,'2026-01-07 22:29:43','2026-01-07 22:29:43'),(54,20,10,'tuesday',NULL,NULL,0,'2026-01-07 22:29:43','2026-01-07 22:29:43'),(55,20,10,'wednesday',NULL,NULL,0,'2026-01-07 22:29:43','2026-01-07 22:29:43'),(56,20,10,'thursday',NULL,NULL,0,'2026-01-07 22:29:43','2026-01-07 22:29:43'),(57,20,10,'friday',NULL,NULL,0,'2026-01-07 22:29:43','2026-01-07 22:29:43'),(58,20,10,'saturday',NULL,NULL,0,'2026-01-07 22:29:43','2026-01-07 22:29:43');
/*!40000 ALTER TABLE `provider_working_hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'Rating from 1 to 5',
  `review` text COLLATE utf8mb4_unicode_ci COMMENT 'Review text content',
  `status` enum('pending','published','unpublished') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_booking_id_unique` (`booking_id`),
  KEY `reviews_booking_id_index` (`booking_id`),
  KEY `reviews_sender_id_index` (`sender_id`),
  KEY `reviews_receiver_id_index` (`receiver_id`),
  KEY `reviews_service_id_index` (`service_id`),
  KEY `reviews_status_index` (`status`),
  CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,14,4,5,5,'Excellent service! Very professional and completed the work on time. Highly recommended!','published','2025-12-04 23:40:38','2025-12-04 23:40:38'),(2,2,12,6,13,5,'Great experience overall. The provider was punctual and did a fantastic job.','pending','2025-12-04 23:40:38','2025-12-04 23:40:38'),(3,3,12,7,5,5,'Satisfactory work. Met expectations and completed on schedule.','pending','2025-12-04 23:40:38','2025-12-04 23:40:38'),(4,4,12,4,3,5,'Very professional and friendly. The work was done perfectly. Thank you!','published','2025-12-04 23:40:38','2025-12-04 23:40:38'),(5,5,12,14,3,5,'Good service provider. Completed the task efficiently and cleaned up afterwards.','published','2025-12-04 23:40:38','2025-12-04 23:40:38'),(6,6,14,5,9,4,'Excellent service! Very professional and completed the work on time. Highly recommended!','pending','2025-12-04 23:40:38','2025-12-04 23:40:38'),(7,7,11,3,14,5,'Satisfactory service. Met the basic requirements but nothing exceptional.','published','2025-12-04 23:40:38','2025-12-04 23:40:38'),(8,8,9,6,9,5,'Great experience overall. The provider was punctual and did a fantastic job.','pending','2025-12-04 23:40:38','2025-12-04 23:40:38'),(9,9,9,5,5,4,'Excellent service! Very professional and completed the work on time. Highly recommended!','published','2025-12-04 23:40:38','2025-12-04 23:40:38'),(10,10,8,7,8,3,'Great job! The provider was knowledgeable and completed everything perfectly.','published','2025-12-04 23:40:38','2025-12-04 23:40:38'),(11,18,17,16,2,1,'his work was not good','pending','2026-01-06 22:50:25','2026-01-06 22:50:25'),(12,12,17,16,6,4,'this was good','pending','2026-01-06 22:57:17','2026-01-06 22:57:17');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,2),(18,2),(19,2),(20,2),(21,2),(22,2),(23,2),(24,2),(25,2),(26,2),(27,2),(28,2),(25,3),(26,3),(27,3),(28,3),(29,3),(30,3),(31,3),(32,3),(33,3),(34,3),(35,3),(36,3),(2,5),(13,5),(14,5),(34,5),(35,5),(1,6),(2,6),(3,6),(2,7),(3,7);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(2,'customer','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(3,'provider','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(4,'multi','web','2025-12-04 23:40:33','2025-12-04 23:40:33'),(5,'super_admin','web','2025-12-08 00:03:48','2025-12-08 00:03:48'),(6,'sub_admin','web','2025-12-08 00:06:59','2025-12-08 00:06:59'),(7,'Web Manager','web','2025-12-18 21:34:10','2025-12-18 21:34:10');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_name_unique` (`name`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (2,'Carpenter','carpenter','assets/images/services/carpenter.png','Woodwork services including furniture repairs, cabinet making, door and window installations, and custom carpentry projects.',1,NULL,NULL),(3,'Laundry','laundry','assets/images/services/laundry.png','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',1,NULL,NULL),(4,'Painting','painting','assets/images/services/painting.png','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',1,NULL,NULL),(5,'Logistics','logistics','assets/images/services/logistics.png','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',1,NULL,NULL),(6,'Cooking','cooking','assets/images/services/cooking.png','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',1,NULL,NULL),(7,'Electric Work','electric-work','assets/images/services/electric_work.png','Electrical installation, repairs, wiring, lighting fixtures, appliance setup, and troubleshooting electrical faults',1,NULL,NULL),(8,'Plumbing','plumbing','assets/images/services/plumbing.png','Installation, maintenance, and repair of plumbing systems, including leak repairs, pipe fitting, unclogging drains, and bathroom/kitc..',1,NULL,NULL),(9,'Beauty','beauty','assets/images/services/beauty.png','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',1,NULL,NULL),(11,'AC repair','ac-repair','assets/images/services/ac_repair.png','Air conditioning unit installation, repairs, cleaning, gas refilling, and regular servicing to improve cooling efficiency.',1,NULL,NULL),(12,'Baking','baking','assets/images/services/baking.png','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',1,NULL,NULL),(13,'Gardener','gardener','assets/images/services/gardener.png','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim a',1,NULL,NULL),(14,'Man\'s saloon','mans-saloon','assets/images/services/man_saloon.png','Electrical installation, repairs, wiring, lighting fixtures, appliance setup, and troubleshooting electrical faults',1,NULL,NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('0apnb5uadBKyb2EezIVt1TpBAPZAYNOTsHJmu19G',NULL,'193.26.115.44','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV253UDk0emFtcUhFZ2ppcjlqNFBDRTVqbHRzYUtIZjR2TEx5NzhZeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768020000),('0NVJ882lXpgO94jDLVhQrU6cYYd6YuXTUqMPf6jG',NULL,'46.105.40.140','Mozilla/5.0 (compatible; MJ12bot/v2.0.4; http://mj12bot.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2NVNlVFcXdzUHdPV0dtUHM0anBQQ0lJQXYxdnFlTFhFYU1BVHFZTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768018386),('0oL0rX4eEnN1RLiQ1C4e7w8sWZSPmOBoLyxdQpF1',NULL,'129.226.213.145','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMlV1U2hvbjdWTmhXenJocWZ0N0c0c1ZPNGNJVm5ZOUdsU09aVGhMMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3LmZseWVydHJhZGUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768046230),('1SZoXyKvkpkcGFUfD68Attcc6S2LFE1u0enZmFpd',NULL,'43.157.22.109','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2VoV1pKVlEzMEZUWUtZcUExUnRyNTU1WVg1cFMzSGJlS2w4TkUwNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768021803),('3Xkg2mGaM7Imp9yxxeV03V2KJfkYH0bKNv2e4ryb',NULL,'3.143.33.63','cypex.ai/scanning Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibDJzcnI3TnJ5UUNpSDBwaDZsWVFLelNNTThyWFRrYlBuc3NLWjZ5VyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768011622),('5616oOIosUWg446zZxyq6K7iFb1KDTLTN1sLYCD8',NULL,'185.242.226.100','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlAzenNCbHJJd2VDMUNpTXRXOVBEdHIyTEpWT1RnWHBKOXBxTWVWNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768047277),('5loosyWHss90wSb9uixDbFwKhZicAdIJMHFgXBCZ',NULL,'44.249.19.57','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoib1kyRnJBUmhOeHRzTm90OHBPSFpVUkk3N2pmOFIyemFEMzU1M2tQMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768055737),('6uC6aQZ6Gh4dHRheoYETrUEeEmQScAJ3VEwtT0HU',NULL,'193.26.115.44','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV1RSVk1NM1FqWHptQWJTVG5HU2dZR2JXd2RtQTJwbHBKT3BRREhpNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768019926),('6uydmTPPSAx0noNHDKPfy7UqnuErfteE0O2prQQR',NULL,'46.105.39.49','Mozilla/5.0 (compatible; MJ12bot/v2.0.4; http://mj12bot.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoidng2anhwbW1oblVVQ2JSWDNtR2x6bnBaNW9hV1F4QkpLaEJrM2QweCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3LmZseWVydHJhZGUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768052186),('71pYbKC6tOhN6tEPC1Wx6WdgnLzV77ZDzYDnZAKM',NULL,'216.26.230.157','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Vivaldi/5.3.2679.68','YTozOntzOjY6Il90b2tlbiI7czo0MDoielFtV1R4NVdiZXZFVUs4bFA0bTZMYlh5QmNaZ09FOVRzdWoxZzdUZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768035366),('80qkKoNp3tBj2swXpwQw5W4vLtLvshg8zG5u3TY9',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:106.0) Gecko/20100101 Firefox/106.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTZqRU1iaGc3aWFzd1JPTUVBVmpxMkY1Q0FsTVM1NEpmelRKZ2ZvayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768019951),('8d6oMm5zgHN3DDkMeO6mzceCDLVovSEKYuoHtxSn',NULL,'165.154.206.35','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0VkbjQwSHc3QjJEWmtaMHNzSDQxQWlPbEVZdHlDa2RLdHlxSHE5eiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768000497),('8McKxl6WzsP9I96pGnT6ODX7aTcpZKsftuGTQ6gR',NULL,'172.105.177.106','','YTozOntzOjY6Il90b2tlbiI7czo0MDoidjFqdmlhdWtwa1dPT29MNVJUanhBOERZNmV3Sm5KQ3NWR2s1R2VLeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1767997253),('93LGTBkf78HeD73Kvbq5wf42qZmE9Sujfb38jMU4',NULL,'77.90.19.158','fasthttp','YTozOntzOjY6Il90b2tlbiI7czo0MDoicTE2eG5SQkxjU29zOGZMVERReDczdG9uR1lkTHU3ZElPRm1pbXY1YSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768015112),('9VcdrUNlEdXueNZhC2SxAgxPAg59fqlWNyg8x0iS',NULL,'193.26.115.44','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFRnWUZJd3VHem5tTUhFUWtFWFg3ekE3VG5oVkRGd3d3bTRvbTZjMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1L2luZGV4LnBocD9wPWFkbWluJTJGZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768019939),('A4mQE8J2uCFwgP9EWK7GRrGSGonUglJg1B7AMP36',NULL,'43.134.186.61','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoic01JTDRMUjVjbFNwMEZFdm1EdmJIcnJMeEJtT25lVU9kVWg0NGc5TyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3LmZseWVydHJhZGUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768009502),('A7ss3m8uDtddNip0F5fBgNdXhRZT4Ijlitx3uRMl',NULL,'216.218.206.68','Mozilla/5.0 (Windows NT 10.0; rv:109.0) Gecko/20100101 Firefox/109.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidUg2djNBOXc0NkpJcTl3WlR0aTJqcE9vb0QyakNjd0FONWNjVkxYbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768028204),('a7XrYYY3L3HpL4jxwZa0rRabbdrqwQEvIEW0ZHx8',NULL,'45.142.154.66','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVVEcWFUVjlmeHdpeGVrTEJmM1lLa1dNREdyRjRIMWdZZ05MQUFLcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768000509),('abJmBIOOSA2LfgRCycYtUBEmF09Os5p0AL8a1CTx',NULL,'172.105.177.106','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNndzeENmOWk1czFxT1JIYjUydmhsaU9SSGhySUI4NXZNa1RxZktHTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHBzOi8vZWMyLTE2LTE3Ni0yMDctNDUuYXAtc291dGhlYXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767997253),('Absuo9HTOa08c8noMO6ck2pVuRulLyUmJSU5x1LE',NULL,'3.140.182.19','cypex.ai/scanning Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDNVQW5sOFZlM01rc1l3YUFGSnBzcmY3TWFuNHI0cklUM2wwNjRkbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768056221),('ArDXS76RHBqVRAXD9Q3Of1pHDNarnWU2gZGm3imu',NULL,'172.105.177.106','Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYVhDNERWQUJVcFlkWVI4ZXhha1dab3JFTzNycE01YzRjNjRndk80RSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Nzc6Imh0dHBzOi8vZWMyLTE2LTE3Ni0yMDctNDUuYXAtc291dGhlYXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tL2xvZ2luP25leHQ9JTJGIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1767997233),('AzRrd1W9UdTKaPLxQNsVOKIocIDZeWuU3ZhQxPQ4',NULL,'206.168.34.204','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoicHJVaWR4NURLUk5xb2V0SE9LU1VJQ2NodkVONEp0QkxqZDNuaTNSQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768004942),('B5HzNd4hsUzItG9ngcZ6A3jj5bTzkIiIiBBKHGkt',NULL,'185.177.72.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiV0NMaEtJdWtacW1IT2xQbFpxWVE5Yk9rZFpmUVd1V3ZYbTF0ZEZ6dSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768010479),('bDQKh7esqZs1y7sKaNTjIkJ3kXZz3Y3lsyLJDmhM',NULL,'43.157.170.126','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVmJGYUtXOW1iRkViRzFMZEJhZ3RuZTc1ZDNha1NhNWE4V00yY0VhNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768018975),('be7s5t2N7JZ0ZuaWZQ1bW4DWaZ6kYm9EZBSOPZPC',NULL,'34.1.0.164','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko; compatible; BW/1.3; rb.gy/qyzae5) Chrome/124.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWwySk9uVDJoYXhyMnU4RUd1YXBNWU9QZUdycUxKZmQ2NTNEQ0ZiZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768051517),('BmAMP9B9zeMgMih0iR7oPl5YkIluBrbISqDSfyRb',NULL,'172.105.177.106','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUzJManRHNDlXOEpDdThRYk1hcVNlTW9MMUZGc1FCYUhRZGNGNG5VZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1767997224),('BxQs4XRNqmd0IXeXvvqKfpMq3w58xJyUTvuMtAjO',NULL,'4.194.99.179','Mozilla/5.0 (Linux; Android 13; SM-G991U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicGlxNFBpUUxsZzlDYWM5SHRQcEhweE1mcUhhWWN1c3FTa1NqNEFOVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20vaW5kZXgucGhwIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768002480),('c4DJY30j0yPeEDqQu7U2Fx3NCEhRuOn79vMuGGru',NULL,'193.26.115.44','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFAwRDk2b0FNQUZTRGZBaXVXMnlCaHNrcXhYbjVOd2Rwd05LNEpWUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1Lz9waHBpbmZvPTEiO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768019991),('C4GyqHK9PXD95MvNPKgA0RHbwGlPgx5ZNqcZGKD7',NULL,'43.152.72.247','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWG5EVTVtc1JHeDJ2SnNsQmRoVEtLVnpRVm0xT05oblFXOGJmYnVETyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767993679),('caVtSkCHx3pULcFo6O5NFGNHsk6cqxIjmN6OsLg2',NULL,'165.154.206.35','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnNyeW12TTB4V2sxSTQ1WTVpdElqRHYydkx1eVBzSVAweG5IZUJBaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768000505),('cKzDzfuOmiCL8ToOBTgO6lvOttl6VpVshmoEeftE',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibmdNVU1Ga1kxZEtXa2FoaDFLeXdiOENaVFdUZmtISmhLOEtuSXFZcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768019972),('CtlXblcy3Wh7aQyHKFyNdgUYqQwdfYteSPGFCClT',NULL,'172.105.177.106','Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNXFQdU92dGlWelZoSTMxcTJqNHI5cE9rYThWSW9pRk1YeU5wSDNuZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHBzOi8vZWMyLTE2LTE3Ni0yMDctNDUuYXAtc291dGhlYXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767997217),('cZvwB1TWka3orjLjZdubZShGNfLG1W7mOF2LQ7R8',NULL,'3.101.111.133','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibFBQblFiRkY0ZzE4SUhhREZKTmtrSnRYMk15OTJIMzhNUWJTWUFyVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1767996292),('DNyBEjcXn1NCGhJ74EoAWF0WvXezTzlmPS5p1Fct',NULL,'118.193.38.85','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieU1sTTZ2Q0lsQVE2NWNVUFo1TXkyY1Y1NjJ2Z2pyVWh6emxBZnF4eCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768000510),('dtTsoOpEENSi76SYZtrZkhf9fJaC6s4OwqaH71Tw',NULL,'152.32.148.140','curl/7.29.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0NnMWZGSHMyME1UbXJsQWZCWnlMM2pmek9FVUVzZktjVlQ1dUk3dyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1767996926),('dwApFmJWkn0C4AaCWJe7Ddnalow0X51icTGhlThx',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkNsVjBTUzZuelI0Qk5udG5uMEc3YTg3Q0xSTWpVa09nQnM3cjJ6NyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NS9pbmRleC5waHA/cD1hZG1pbiUyRmRhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768019942),('E9p2StQVlTKf2kkfoSCPMcsh2e7tsYfZfv6g7OBU',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidnZVM0NjN3gzVDNXVnFpUHBkT0oxeXZ5SjVCQmp0RWptQm5jWTBCMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768019934),('ekmuKHmnc4u6A267wcPNMN7G98I2tBwYG4Vv0rdn',NULL,'101.36.112.233','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHlIdFQ0cEczSjhxYTBoTWdBUkJmckNYWkhMVjA4UVhmWW5LelpvayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768000510),('EqOSCC0pUoxmUMo8MoRvOAi4pZr7GS42WQEg6FpR',NULL,'185.220.101.16','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Whale/2.7.98.27 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0NTVkdPMU13Wkw0SndFVDFibFI3bjRXVVU4enE5V3NYelo4amJPNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768035934),('fMXRvqvkOg8buIHxO5m0jgDprTSTXRtzFNYz0tVq',NULL,'205.210.31.133','Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZXhtZHlNUkpWU3ZSRFZHQmJSbW9jMERwWkFmRVAyUGNiMGF6R2lxVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768035488),('fue5LerhrfZxqJT3JQXwQP7o6KYAIGWDxsxCS29G',NULL,'134.199.225.183','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlg1ZEllS1QzdWR5MGFMNTJuT3poZVdWRzlCb3pWQWpwRHVCbGJxMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768020566),('G9YDKasPyDTfZqR55U10JnHhU8BR11LSIeHIZs8N',NULL,'198.235.24.8','Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXVYTmpTZ002RlFMb0w3c1paTDkza042cWVNb2VXdnFmMURqVTk1MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1767997115),('gBJdI02GUoX5NtrHt0fF0urUP5pe1ZsuHpusBKxP',NULL,'144.76.19.24','Mozilla/5.0 (compatible; SERankingBacklinksBot/1.0; +https://seranking.com/backlinks-crawler)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRW5BdU5IVE9MeVA2YUYyNXhtNHBmTkh0S0g3ZUdBMENVYjloTzJ0WCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768005175),('GhkNymArrhJp1amBegdYIIGNRd2HpUL9kct5boY0',NULL,'195.184.76.12','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZXE5QmtRbzV0Zk9KNDZaV3ZjU255QWJFMk5lN3hCQ1NkZmFqRldFSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768022551),('gRD9thkYTY85qBj5FDvZaqA6lBINZICM9U6ZfxMn',NULL,'51.68.111.207','Mozilla/5.0 (compatible; MJ12bot/v2.0.4; http://mj12bot.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoieXRFUDVKSFNyR1BnYVdhUDVScjNyVTNVWVZtNEIzdVRhZm5GR1ZlaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3LmZseWVydHJhZGUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767992418),('HamOm6ZYGsPhCnt4BBF5tdLEVYbk7SmfdEjQLOnV',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibkE0NzBLY3NsR25qZGhMS1k5YmlsS3ROWVVsUUV5amxEUG4zdWE2ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768019956),('Hc1c0VlDU1u1zdAtsNpM8LaCWwFpQjr6pfGlL5Vk',NULL,'4.194.99.179','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSHU2UUI0bFJyMFBheVlVOU9JMGJJYldWWGJqaVI3aElpS3Z1WE1IQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20vaW5kZXgucGhwIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768044651),('hLqNObXiL7ZlRpoXscTALvnRbtH2OS5GvIViSoFQ',NULL,'172.105.177.106','Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML','YTozOntzOjY6Il90b2tlbiI7czo0MDoidGtkMUF4cXJRRjRMMEVWN0QxckNmZkV2czg1UVBoSlZlelA0ekRCcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHBzOi8vZWMyLTE2LTE3Ni0yMDctNDUuYXAtc291dGhlYXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767997228),('HpMED0klMZlGaHKxZ17b5zKEDKY2Lvgs9MEjaWI0',NULL,'193.26.115.44','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVhVSWxXWkE0M25jWlJ3dFJWbFJreWdDa1B6ZFZIVHVkYW1vRkpBSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1Lz9waHBpbmZvPS0xIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768019994),('iFyI6OdEeCzdEMToFfQHvFGUMnn3MEBDdXR7bYky',NULL,'185.177.72.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiSUtXazl3bWlRU2lHN082QnZoeGtOM0lyUFdJcXlPcEtlc3hIOXVjayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768021887),('JCzDbHJizRbIhLTuURSzcDvkrwMBLjWXIuiPbPby',NULL,'175.19.74.195','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnVCV1JRRGR2VEFWd3BiWmFTa3RFUHNSNEpoMEZGS1ozdVhWV1J2SSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1767993881),('JNVnqXPy0y3J1WWBKBBekgfHZgkeOhMuLlj4dyyE',NULL,'198.235.24.37','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWUxWbEY1Nm1pb3kwTE1QUE1PMDVqSFQwQVJqdWdSaW9KS2ZCd2ttRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3LmZseWVydHJhZGUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768035012),('JYVhJN9HIxE1f3C8eLbwYsKnEPtnRBJhzkf65yj5',NULL,'104.248.243.8','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYlZaNTlYZHFTTW1kVUhyOEtFUnFaUklpTGpVcVVVY09oeTZoSjBnWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768048559),('k3B0ChmJk4DaUttVGdH275DAYsmio7oR8k3SNL6h',NULL,'195.24.236.108','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGFLRHZNRVBYRlZ5SVl1eUFmd3FQazhQOEx1YzNVSjExU0VleFBmUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768054736),('K7M4GHnaF2CmqLGe64hvk53VAgfhZ1RLpRx5HtMc',NULL,'66.132.153.114','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQjRrZGdtc2xnaDhENXd6Mm5pWHo3TEh1Y0REc2dmaktUdGRJNlo4diI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768046005),('KAAJ4TJhxJMA95EtoP26vvnpbm5Oyu7mMrAr8DfZ',NULL,'36.41.75.167','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnJOdHlRRldGejlNOHBoMGJpMmM0WWpGMVhuWFVwTnp4Z3lYeno3YSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768033411),('kAl3OJ92FcdvUSBC5jKCdWmekkYU4wApdCXmUCeS',NULL,'34.180.40.227','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoickVOVnN3ektOdFd2cXQ5ZmpHNnJjMlgxZmJ2T3d2MjJFanNnNjZhTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768040462),('kLvg7boyNLXBNOs7xt47PYAdPaUKVp9sfK4FA5Rf',NULL,'185.177.72.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiWVJ0dFlPOWdNMnF1c0JDSDM2ZGdKU1g3SGhSM0UxTXVrUG10eEFBVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768035246),('knu5IaKLujEfdyzj6i39rl0piOh1Pp5bdLX1WMOK',NULL,'45.156.129.131','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVBHM25ZVTlwbVpMQzhEWjY4UGI3SWthT2k4blFzQm8xNUJ0Wml3cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768016005),('L7ZWoB7CNbtiruOCm4cCxEWNrOpc5NTbwGFOnx9u',NULL,'34.11.175.179','Mozilla/5.0 (Linux; Android 12; Pixel 6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS1ZSR3U5ZWJqMjdwYmFMcURsUXNCQm53WG5HemUwa3NhV2RFU29VUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768025939),('LVcDJUSfn8A8rp7aoEQLATrdnUHTupmY5cMUPhXh',NULL,'195.178.110.54','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.79 Safari/537.36 Maxthon/5.2.7.2000','YTozOntzOjY6Il90b2tlbiI7czo0MDoiczdUMFFXZnJwd2c3djVCTGZvOTFnaDFnTGZIR2lvSEREN3pvazdHaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1767991634),('MbHqrA8KV4dUV8dzencKddGLReXDWQWhGuNruhum',NULL,'98.93.63.52','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic3Z1TWZUekNVSld5TlpBVFhsdHJQdWx6UTZEcElVVVlUM3JoZUpLYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768050577),('n3c0M1720uDyprh80G6bFcO3FnEqFmar4RxXwftr',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:105.0) Gecko/20100101 Firefox/105.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDFOTkpSZTEwamhQRjFBSE9pMW04emZiSWtxRmQ0M1dvbDZNYU1TUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768019985),('NbMdYq9gvWTR8fYRDRiVSNNMYdxeW8jhjUqjFQhq',NULL,'172.105.177.106','Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWmRVSmZucXJCWVNVZDVlbjY2UnpHS1dRTEJjQzZoU1Z6SXdWZ2JjRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHBzOi8vZWMyLTE2LTE3Ni0yMDctNDUuYXAtc291dGhlYXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767997215),('ndUVudV3uRaYFYo2BNBmJBRPpJat7Cq1xgBgbw5H',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:106.0) Gecko/20100101 Firefox/106.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTEpVcWNvMGM1TEdhbHd4RUw5WjVXQ0ZiTk1tNlNlSGYxektGb0lwMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768019931),('nEw1a7k0bpcQJ3qBecM9cq2UE9T7dkNvuMDWK5jb',NULL,'134.199.225.183','Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 10.0; .NET4.0C; .NET4.0E; .NET CLR 2.0.50727; .NET CLR 3.0.30729; .NET CLR 3.5.30729)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3Zxck92RGV1TFQ3NmlMWkFBZGlGc2FkR29NOXZXa0gycTF3VHFBdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768020567),('nf4aAwo8ucUfC0zGzFpxbbSBjMqVL71GywxTludm',NULL,'193.26.115.44','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRnp0cElYZ2tycWh6VmpEc0M3NW1QcTkzRUJGV0x3VnNPWFNuMHBZYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768019926),('nGBWWzVVcpQOhRHP2U6dPbTKrb3wkmUzz6gX6kyA',NULL,'52.224.242.102','Mozilla/5.0 zgrab/0.x','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXo3OXExV3kxWml5MUdTZ3dwektZYWk4a3ZUazdnVmVxcHlncHhGWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768032347),('Nj6cHxDapeOTJN75EDZdsSw3qKpaj2yvdQuMJC5S',NULL,'144.76.19.24','Mozilla/5.0 (compatible; SERankingBacklinksBot/1.0; +https://seranking.com/backlinks-crawler)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXdxVzRXSThVbmpJZEtQdTJGZ3hlSEZ3NVZkUjN1NHp6MzNMcWJVRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1767997717),('Nj74Fc0VUr2uHbpbcbyEJZqGGutcTMaObdb8MhXZ',NULL,'43.134.141.244','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWHpEc0czZUZKN2tMVHM1M2pRZUVRWGtHZmhFandqNFI2dnlpak1ZayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768036804),('NvJVRvzdjq0zkPy6Sjqiyaa9GiC8pz74GdB2wzuV',NULL,'144.76.19.24','Mozilla/5.0 (compatible; SERankingBacklinksBot/1.0; +https://seranking.com/backlinks-crawler)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFlwaGNSS2NqUE1hdU1mcmMxWU5oREdHWDJnVU5lU1ZjMEZaRzFWOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768005170),('oTeIo016BcD4Zi5OGn5O3PAvzTTb5IuFdJV4vrfh',NULL,'8.208.10.94','Go-http-client/1.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkZXZXp1cGZVSHowY3VYYWdrVFVyWnZBeVR5Yk5kY2RZYXlMY1RxayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODI6Imh0dHBzOi8vMTYuMTc2LjIwNy40NS8/ZG5zPXQ0TUJBQUFCQUFBQUFBQUFBVEVFYjJSdWN3RnRDbVJ1YzIxbFlYTjFjbVVEZEc5d0FBQUJBQUUiO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768035366),('p81jV1PzGpme5VXwb7PRbRdeWnvIFQQXzBN8VSSt',NULL,'172.105.177.106','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHhIcmc1bzVUa2c3NHBOcVQ5aVpBUTRyY2JtRDJXTjIyVXNmM2pOcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1767997213),('PmnnY6UybZIdZPJs2TcJBU9dLBL2LUJCeocN3dZY',NULL,'94.231.206.6','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMG1LZ3p0SHJ2UmlpMXprRGh3czB6U09lWDBJUnlydkpISllOTlBKRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768006011),('pOVFxezB9g9PYJAz3dQnMR9Zeh1X21qN4iHRkUEP',NULL,'172.105.177.106','Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3FCa0x2RnhJcTd0cTVoR0VzdTNUeUJXZ1IwNkRFcU1GT0RLWDZmMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHBzOi8vZWMyLTE2LTE3Ni0yMDctNDUuYXAtc291dGhlYXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767997216),('Pzhqk3SgkIOYjquj8w1m5WAIH4Z5otD8MHCTW0eo',NULL,'43.157.53.115','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2lxZmFNcTBhSDI0T3dDT3AzV3ZIMThRVmx2SFdHVlN0WmhnVFUzMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768027167),('q9NQfKnim5V9aIDiLXEKnhxOSh2V5UPTavJoRCmx',NULL,'172.105.177.106','Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2JtZnN1VFBpWVZpckNPQ0FqUk5YaHFEeTFQVE44Z2FIRVZQREJmZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njg6Imh0dHBzOi8vZWMyLTE2LTE3Ni0yMDctNDUuYXAtc291dGhlYXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1767997237),('QJ5VlMFshuzQUtcaSuUrNcgcrlFu5usWnPpAQw4N',NULL,'123.58.215.102','Mozilla/5.0 (Windows NT 7_1_2; Win64; x64) AppleWebKit/579.55 (KHTML, like Gecko) Chrome/85.0.2567 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicGtKb2diTW5VQ3dkcGVseVpxblBuWXlEbWJRWGpTa1NqRzM0UGkydSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768012481),('rI9FmnAmHXqcnCvWRTI8zcteuwDUGvpAHgYeuJUg',NULL,'3.137.73.221','cypex.ai/scanning Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMnhobXJIUk8zNDRiNHM5MnBZNWFCSnVQSlRYenhlQXhna3hoYWQ0RyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768008581),('RjJ4Ucx3SvIIG7CryPavsJSNC7Ug4NgSqAgoMtwZ',NULL,'34.136.42.120','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoib1FJWWE5UTBqaUVTYkdFZ3ZxTTdwN0dJU1FNb3NYMU41a0c1QWlJayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1L2Jyb2FkY2FzdGluZy9hdXRoIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6Okp4MmJ1eTlUN2EzSDVLamYiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768002723),('SLhPDkRTUDjjYNS9dR4bgxyPFU4UE5Gsa3xLWqQy',NULL,'54.39.136.28','Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUtXUGNsOWtXcmVqM2JjRlRjWDZBNFBvU1hIamdsOEgwRUx1anhEdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768045277),('SunmQRcT6m2GAHjznXOGAyq1VkFoa5l7SqP3xfj6',NULL,'206.168.34.204','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMGtxRDlNY0gwY1dTMTd1SmFJcVV4SXpvWVJUcUdJT09uR0tTaE1mUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768004949),('tcFo2Z3a22Gej6gJUTJUMFPafMRWEzR1FT714GKi',NULL,'54.145.158.145','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYXppVkRadnJEMmh5UGZxcnRJeXNWbDRaTEt4OGZlSnI5Vnh4UGVJTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768050417),('tdzrLwEU0V3iOySfzDkyLSP7LmGlsMq6gL4v1U5n',NULL,'185.242.226.112','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicUpXWUxiRmJTVmhNU0s4c1RqbXB0dkVZVjM2ZWc1aFJjcXN4S3pINSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768035917),('TExnq5ihz9EQzruldJy1BvmVsB8Nn3gzI717bvQ4',NULL,'172.105.177.106','Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML','YTozOntzOjY6Il90b2tlbiI7czo0MDoieXpzeGw3TzF6V1NKSHM3UE9OSHU1eUdHNFV1RWxKR2lMaVRSc3RMUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHBzOi8vZWMyLTE2LTE3Ni0yMDctNDUuYXAtc291dGhlYXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767997218),('ucvF0KDRnaX2yhXEduVjja30rCS85CEfkf6liIHt',NULL,'54.163.2.228','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDlrbWRyUE5NOG93SFFNS2s4QW9lRTVCYWFuMjBxWlU4OENJMnJhSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768029283),('ULhVEIISjhVCyyCHrLMDfdt9z4yxKGdpvd7OxsXR',NULL,'43.157.181.189','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3lNMWpsQ29kZ1NsTEgwSFRYeFRsS2pZMmtsWEVlSzZmNDZ3M0l2ZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768050819),('UMtxRyjejJSuGIuCKX68YkRRMHJ2SyjJhAYUGxex',NULL,'159.89.51.122','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicVdHRmNrY3RKQVA4VWthU3N5aVdRWTZXMVQzUk82ZExoNlRKMmJ4USI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768011454),('UPwCBJfRwyHJ7yefPKFgMjSMRhoy5k5PveLTEHoz',NULL,'185.242.226.112','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWJndWN6UjJLRjFvZHVaOW9IQTNoanZzTEt2Tk1wV2hpSTExa0pDMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768005214),('UUpAjcvz1pEBuWDnqs9tBAvHtsuevvmubFVdYBEU',NULL,'44.249.19.57','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibFRmWDVucEM1ZnloODZJVGJxRzVQbE5IUDNHOExqVjR1MHVjOTMwbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768055737),('V7vlNhWuwwvOZcU05w6yHpfd4fGhgOCB92jVdCD2',NULL,'167.94.146.62','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYWc0VFU0SEdLalRJaXNLT0tRR3REaVVpQUcxUDVNSmVRUkFwRnFnOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1767995914),('VDfvrgL3i7dOY8Q1qJsHzQurzncCTrcfI1o2M1ZW',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic0NpNUNaSG9Gd29oOGM0U3JBUUhlT1BYYW1aOXdFNU50SFZJVERsVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768019933),('VDFXNP6J1xZcuINF1n5EFreWxmbXQbCLBccKGsmG',NULL,'45.142.154.62','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWHRJclNmbEQ2aEpUd1JsaVNKQk9VQ1kyQThyQkpxZjNIcXgyUHR2cCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3LmZseWVydHJhZGUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768000509),('VsAXubQo5pPWUAwCwiixNkY64tEAOrcE518t6AM0',NULL,'8.208.10.94','Go-http-client/1.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoidUVZY28zZ0NRdGFUSWpmc2N2RXB0a2J3VjdPdlFPR1JObFlBV3BvOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODI6Imh0dHBzOi8vMTYuMTc2LjIwNy40NS8/ZG5zPTBqSUJBQUFCQUFBQUFBQUFBVEVFYjJSdWN3RnRDbVJ1YzIxbFlYTjFjbVVEZEc5d0FBQUJBQUUiO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768035356),('w0RD4gAVAdtrHsyGJ4hCUrwhNj05Wy026mt38f8W',NULL,'144.76.19.24','Mozilla/5.0 (compatible; SERankingBacklinksBot/1.0; +https://seranking.com/backlinks-crawler)','YTozOntzOjY6Il90b2tlbiI7czo0MDoibk9OUEV6WXhROTlRdlBIOERpNnlXaTFhUG1zZTFMdE5QbHZhNWhwdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1767997722),('W7aZ6Qsy98hlkThcbgfzem8LQ7GXOY386gZMKaNl',NULL,'193.26.115.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidjZiUmlKT05acjdWNm5veHJOTTZNTWpxT2xmWjFhaExCYW5PVGhVWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768019938),('WbT5aKWRTFoVj0SFkABc39Q7SnkJgXWcba3ycmaZ',NULL,'8.208.10.94','Go-http-client/1.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoid2NuRk5GNVpLWEd0SGl5d1l1Z2JpQnFPQ29IVE5iRmZ4WEhDTlY3TiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vMTYuMTc2LjIwNy40NS8/bmFtZT0xLm9kbnMubS5kbnNtZWFzdXJlLnRvcCZ0eXBlPUEiO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768035366),('WwWWbrvHIe5wehxKAW9pDTw8Bu0aor0B9o5572p2',NULL,'45.142.154.63','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWVSMmNsYUZMT0JUMzJMY28zSWtGRHVSSUhKa2NIclBlbzR6VDZZbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3LmZseWVydHJhZGUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768000509),('WzuzLWoYdPFVsC1IWVRUyPyswfl3jEo9TfGbeMl2',NULL,'203.55.131.5','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTh5dDkwOE9tcndjaXNRV3RGWXgwb3IzU1pxVGQ0aEtRV2U5OWN0RiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6Vkl3YWlDRFlYZVcxanRSUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768044851),('xIDOvPvRE3RUUyOVaEAcB9FwwBdig33Z3MBLjOrB',NULL,'5.189.130.33','Mozilla/5.0 (CentOS; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWNFSTRjT3JMMDRBRG9DVElFSEZ6aVQ0UXp1c1pmd2J4NEUzUjNjRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vMTYuMTc2LjIwNy40NSI7czo1OiJyb3V0ZSI7czoyNzoiZ2VuZXJhdGVkOjpWSXdhaUNEWVhlVzFqdFJTIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768048949),('xqse78PlC8j9wgfT5MHk8GEd5LxOLRZxFChgCodf',NULL,'193.26.115.44','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNlRncm9sUlJXdldjd0pCdXh2cWFkdHBSNXFzbnl1SzdJc1VBRVZ0QiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vMTYuMTc2LjIwNy40NS8/cGhwaW5mbz0xIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768020018),('xqT7xjaer78UfXHJvb3LfFuzBu0wV7iuBFoV4QtK',NULL,'3.238.114.97','python-requests/2.32.5','YToyOntzOjY6Il90b2tlbiI7czo0MDoiUVV2RWhQZDFUZTFSZHVFRnJYS1hZelpEbFYyZXRZa0pUUVZoWjBQdSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1768047868),('XRYZG0a1E9yNL2XbY4aUN1sLYxgWEnu5QGkRsV7q',NULL,'167.94.138.117','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2h3UXhvc1I0RFVMYWhnOUQ0eTdxbFlwYjBMZUIyOFQ0VXkyMjVoRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vMTYuMTc2LjIwNy40NS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768046130),('Y3ITSC0A2X93RcjaSIsRGcAdAh2bg8r6KA2WXilt',NULL,'172.236.228.193','Mozilla/5.0 (Macintosh; Intel Mac OS X 13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidTlhQWowMnZMd0syY24zdTgwNDIwWjNGNEd5QnpuaVZCVDlKOVBSdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768004358),('yOs8ieG4bZ4dXritkbg3mHRqHvh2t2BMbvg6hsdM',NULL,'49.7.227.204','Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ3ZFMUVQeWJEbHJEOG5wNmlBZWxwTE9LNkg3d2J0c3RNazF2bzc2SCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vd3d3LmZseWVydHJhZGUuY29tIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768056865),('yPTH4FgRrs9MuLXW9vFxIS1HtNhx7X06MjCloLrG',NULL,'87.236.176.209','Mozilla/5.0 (compatible; InternetMeasurement/1.0; +https://internet-measurement.com/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoia09mMGlGQ0RwYW9TZm9hTmZuT3RxakNsMHdxREVZbDhpaHpBSmhXcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768046166),('YzfrVwWIIm4cp3q4GyVrjJ7RM5CHQHChmxZXEFkT',NULL,'87.0.123.203','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVGVxcGQ2YzNhUHV0R2tXUnBySGdEbmNMeUxrTGxiYTl2OWdqbVdiSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZmx5ZXJ0cmFkZS5jb20vP2F1dGhvcj0xIjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768048071),('ZAYbfFanT9vzEPAzHDT3XCBcQZ3GStwrCvJL0spb',NULL,'3.143.33.63','cypex.ai/scanning Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/126.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM21vR0RvSkRIdEcxUXM2VUJ3M3l4MWpoQTB6WFp6R3JCMXVuWkZweSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xNi4xNzYuMjA3LjQ1IjtzOjU6InJvdXRlIjtzOjI3OiJnZW5lcmF0ZWQ6OlZJd2FpQ0RZWGVXMWp0UlMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1768011622);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_payment_methods`
--

DROP TABLE IF EXISTS `user_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `stripe_payment_method_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last4` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exp_month` tinyint unsigned DEFAULT NULL,
  `exp_year` smallint unsigned DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_payment_methods_stripe_payment_method_id_unique` (`stripe_payment_method_id`),
  KEY `user_payment_methods_user_id_is_default_index` (`user_id`,`is_default`),
  CONSTRAINT `user_payment_methods_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_payment_methods`
--

LOCK TABLES `user_payment_methods` WRITE;
/*!40000 ALTER TABLE `user_payment_methods` DISABLE KEYS */;
INSERT INTO `user_payment_methods` VALUES (1,15,'pm_1Sek99SGAyF5mkUGvWrgnfFn','visa','4242',12,2026,0,'2025-12-15 22:22:02','2025-12-15 22:29:58'),(2,15,'pm_1SekEeSGAyF5mkUG0BHAqZ5T','visa','4242',12,2026,0,'2025-12-15 22:27:35','2025-12-15 22:30:35'),(3,16,'pm_1SekI4SGAyF5mkUG6GDzuZ3G','visa','4242',12,2026,0,'2025-12-15 22:31:10','2025-12-15 22:31:10');
/*!40000 ALTER TABLE `user_payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'assets/images/avatar/default.png',
  `cover_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'assets/images/avatar/default.png',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` enum('customer','provider','admin','multi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `user_type` enum('customer','provider','admin','multi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_verified` enum('pending','verified','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apple_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` int DEFAULT NULL,
  `is_guest` tinyint(1) NOT NULL DEFAULT '0',
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_booking_notification` tinyint(1) NOT NULL DEFAULT '1',
  `is_promo_option_notification` tinyint(1) NOT NULL DEFAULT '0',
  `referral_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  KEY `users_stripe_customer_id_index` (`stripe_customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@admin.com','2025-12-04 23:40:33','$2y$12$NtZuEV3/jMUI.DoQIPtd.eqojC.p.9/y1BRsMjBWyiOeu3.SZsqOK','assets/images/avatar/default.png','assets/images/avatar/default.png','+14499802240','admin','admin','pending','active','USA','San Diego','CA','23923','5688 Main Street',71.6900000,-103.3900000,NULL,NULL,NULL,NULL,0,NULL,1,0,'PISPSG','c9gpAxYmUtCMJp5nidjhFnmomixrPYXSgdLwAtWq1omLhgsllYTLjCfBOywr',NULL,'2025-12-04 23:40:34','2025-12-04 23:40:34'),(2,'Super Admin','demo@demo.com','2025-12-04 23:40:34','$2y$12$YewSOs6tc4TSXXM0xQa9gufVQ2PRWl4Gtph4Nbmt3D4f0ZsoKHtSS','assets/images/avatar/default.png','assets/images/avatar/default.png','+14212692103','admin','admin','pending','active','USA','San Jose','CA','98917','1490 Main Street',67.5000000,-80.7800000,NULL,NULL,NULL,NULL,0,NULL,1,0,'0BHO7O','jGQNRNSzMe',NULL,'2025-12-04 23:40:34','2025-12-04 23:40:34'),(3,'Lisa Davis','lisa.davis2272@example.com','2025-12-04 23:40:34','$2y$12$nqZ9bf/O6FQW36V32TdRcuj53TVYR0WLtJtGBCe8x3uwW63awUe1.','assets/images/avatar/default.png','assets/images/avatar/default.png','+18394723058','provider','provider','pending','active','USA','Houston','TX','15234','5696 Main Street',68.3200000,-69.7000000,NULL,NULL,NULL,NULL,0,NULL,1,0,'0U9HZW','cEeKfMGU6E',NULL,'2025-12-04 23:40:35','2025-12-04 23:40:35'),(4,'Jane Garcia','jane.garcia3938@example.com','2025-12-04 23:40:34','$2y$12$7zu0R6T72WMNKkka0k0cHuiKY7oYvqmRlQlE97NZpiCp25OkLk.XK','assets/images/avatar/default.png','assets/images/avatar/default.png','+19772001751','provider','provider','pending','active','USA','Phoenix','AZ','24869','4709 Main Street',27.0900000,-105.4900000,NULL,NULL,NULL,NULL,0,NULL,1,0,'NESYS7','HnkAQ1oxfN',NULL,'2025-12-04 23:40:35','2025-12-04 23:40:35'),(5,'Amy Jones','amy.jones2309@example.com','2025-12-04 23:40:35','$2y$12$aLh3gNmDkCMV0KI4ET6qA.7IM/wf/VxLKuantnRCix3qThupooJBW','assets/images/avatar/default.png','assets/images/avatar/default.png','+14594900431','provider','provider','pending','active','USA','Chicago','IL','66045','5640 Main Street',45.6900000,-119.3400000,NULL,NULL,NULL,NULL,0,NULL,1,0,'GZPP1M','jLaQlC43do',NULL,'2025-12-04 23:40:35','2025-12-04 23:40:35'),(6,'Mike Williams','mike.williams9448@example.com','2025-12-04 23:40:35','$2y$12$kWs67sgdseNBtwvjJ2yT0.fl4fPOyN1ReZ2NlBR7YjZeDmyPhZhR.','assets/images/avatar/default.png','assets/images/avatar/default.png','+17525297738','provider','provider','pending','active','USA','Houston','TX','92736','475 Main Street',61.6000000,-108.3400000,NULL,NULL,NULL,NULL,0,NULL,1,0,'LTSNT0','gNvYrGy7Vw',NULL,'2025-12-04 23:40:35','2025-12-04 23:40:35'),(7,'Sarah Miller','sarah.miller5396@example.com','2025-12-04 23:40:35','$2y$12$wipBf/2YxmzGO3m8Ix.rROHXb4mXCFdulRiru4ErMzMGyHF9ohVr.','assets/images/avatar/default.png','assets/images/avatar/default.png','+13263645555','provider','provider','pending','active','USA','Phoenix','AZ','31659','9058 Main Street',68.1200000,-97.5300000,NULL,NULL,NULL,NULL,0,NULL,1,0,'BGRT7A','vY99s2Ju9A',NULL,'2025-12-04 23:40:35','2025-12-04 23:40:35'),(8,'Chris Garcia','chris.garcia2116@example.com','2025-12-04 23:40:35','$2y$12$3Cu5dKJKXuEHVEyzv5vA9uCUdWJF.sM43p4N426uGqt0LM4wPaKXa','assets/images/avatar/default.png','assets/images/avatar/default.png','+19317628374','customer','customer','pending','active','USA','Phoenix','AZ','13663','2630 Main Street',65.8000000,-66.2200000,NULL,NULL,NULL,NULL,0,NULL,1,0,'IPFSIF','pmggS7sbjP',NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(9,'Amy Davis','amy.davis3476@example.com','2025-12-04 23:40:36','$2y$12$Ls3bCuQxW0VZsJc02hl/nOQSA4pThQbvvZwIGpyDFx4tnBdHftOZC','assets/images/avatar/default.png','assets/images/avatar/default.png','+19823239936','customer','customer','pending','active','USA','Phoenix','AZ','30298','5088 Main Street',35.1500000,-84.2600000,NULL,NULL,NULL,NULL,0,NULL,1,0,'JJHUYU','8bt2yh66K6',NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(10,'Chris Davis','chris.davis6900@example.com','2025-12-04 23:40:36','$2y$12$.dljk.xeQSRopdsIfCHozu0lk7NclQ4kw0zXdJwAXazm0UPZHvUam','assets/images/avatar/default.png','assets/images/avatar/default.png','+12239499812','customer','customer','pending','active','USA','San Diego','CA','33015','8541 Main Street',49.0900000,-120.0600000,NULL,NULL,NULL,NULL,0,NULL,1,0,'OAOHXQ','5Kyfp3zOdg',NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(11,'Amy Jones','amy.jones6500@example.com','2025-12-04 23:40:36','$2y$12$Z9VL6ajvFucjtN7pFZpcmO4ZArAZ/fkTuWwGSK7mTw9Yhz1l/DM26','assets/images/avatar/default.png','assets/images/avatar/default.png','+17104672738','customer','customer','pending','active','USA','Chicago','IL','98426','6815 Main Street',33.8200000,-81.0300000,NULL,NULL,NULL,NULL,0,NULL,1,0,'IYHOH6','2d4sCN2s76',NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(12,'Chris Smith','chris.smith2653@example.com','2025-12-04 23:40:36','$2y$12$yviHfYvw/1xJZpdc7RrwKujTNlNlQU2V30goKLVo5ePWuvnWPtTJG','assets/images/avatar/default.png','assets/images/avatar/default.png','+17382447834','customer','customer','pending','active','USA','Philadelphia','PA','55803','5601 Main Street',65.9700000,-100.3600000,NULL,NULL,NULL,NULL,0,NULL,1,0,'6YLGZY','iBCUk4ZivU',NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(13,'David Brown','david.brown1127@example.com','2025-12-04 23:40:37','$2y$12$o.lkRrPweCQ0ESFsq4ymqOh57m68KyyX6aoCTFjGd00vHqQfOpbO6','assets/images/avatar/default.png','assets/images/avatar/default.png','+12161157099','multi','multi','pending','active','USA','San Diego','CA','88913','3878 Main Street',42.8900000,-79.4900000,NULL,NULL,NULL,NULL,0,NULL,1,0,'DE1GJR','pRAU8K1DUH',NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(14,'John Johnson','john.johnson5311@example.com','2025-12-04 23:40:37','$2y$12$vDbhEq6zPE62M5ceVFwvEeLd6kmCrR1xfHMtioPcCay2onTpTNTSu','assets/images/avatar/default.png','assets/images/avatar/default.png','+19984148782','multi','multi','pending','active','USA','Los Angeles','CA','93661','2641 Main Street',45.8000000,-104.7200000,NULL,NULL,NULL,NULL,0,NULL,1,0,'MLNQNA','z5TKdOCmwk',NULL,'2025-12-04 23:40:37','2025-12-04 23:40:37'),(15,'customer','customer@gmail.com',NULL,'$2y$12$h9Sdg78BX5r/eNJpdwkDg.Y1T4Ex4l06xOz8jPvHk4Wi9iXqn5bvK','assets/images/avatar/default.png','assets/images/avatar/default.png',NULL,'customer','customer','pending','active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,1,0,'3U4EXA',NULL,'cus_Tby5J41OArqfwW','2025-12-04 23:40:49','2025-12-15 22:22:00'),(16,'provider','provider@gmail.com',NULL,'$2y$12$2uXdPqbRYh2ujEyJ8tjlNeLVAf.OQvk32MnbaWdZtyw6cv3Y/CPeK','storage/provider/profile/5JOtNOTR3SJKQ3JbMMNl7msnIx0NLeSXEhJrAtYU.png','storage/provider/profile/F4V80KjsjONK7yTX6TCCk4hYY9mqTFMNjaQIfAMu.jpg',NULL,'provider','provider','pending','active',NULL,'Fujairah',NULL,NULL,'123456',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,1,0,'JEAZGU',NULL,'cus_TbyEXVELMkNR9i','2025-12-04 23:41:51','2026-01-07 13:07:49'),(17,'bisharat','bisharat@gmail.com',NULL,'$2y$12$5Pw/12ww5TyiyiZ6r495Aen5zwqlV.FmAgNT.851Mfk99nga9QPMS','assets/images/avatar/default.png','assets/images/avatar/default.png','12345678','customer','customer','pending','active',NULL,NULL,NULL,NULL,'Dubai rashid Mina’s road',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,1,0,'NJNCSW',NULL,NULL,'2025-12-06 20:07:51','2025-12-15 20:42:11'),(18,'provider 2','provider2@gmail.com',NULL,'$2y$12$XVHbB1Sb.Ips1paLDejF/ODeKpAxCJ68TM8MPkiommnTUKUuIPf1G','storage/provider/profile/ORnAOh8WrB60HKMCDnUXOyf614NaDUv4Z41tfXQ9.jpg','assets/images/avatar/default.png',NULL,'provider','provider','pending','active',NULL,'Al Ain',NULL,NULL,'test',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,1,0,'VYNEVY',NULL,NULL,'2026-01-07 01:23:11','2026-01-07 01:50:28'),(19,'provider','provider1@gmail.com',NULL,'$2y$12$XSGM3Ez70k396/Z6wD7/5eSwKLInmhN16KhYhsqGigoS2/bHRYaGG','assets/images/avatar/default.png','assets/images/avatar/default.png',NULL,'provider','provider','pending','active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,1,0,'I97VV9',NULL,NULL,'2026-01-07 12:25:53','2026-01-07 12:25:53'),(20,'Hamza','hamza@test.com',NULL,'$2y$12$UtIyICIkrKLj7Bq6D00RMumZnHYWh4/SJ8WSgM6462UB7pplpAxCu','storage/provider/profile/opOkZ0Vqy5Hp3XYIUiURzXR1C7LOlT0YDJ6ZjOUm.jpg','assets/images/avatar/default.png',NULL,'provider','provider','pending','active',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,1,0,'TMACBN',NULL,NULL,'2026-01-07 22:29:43','2026-01-07 22:31:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'flytrade-db'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-10 19:58:16
