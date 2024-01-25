--
-- Table structure for table `#__accrediblecertificate`
--
CREATE TABLE IF NOT EXISTS `#__accrediblecertificate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'ID Accredible Group',
  `created` datetime NOT NULL,
  `url_image` varchar(255) DEFAULT NULL,
  `url_badge` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `published` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;
