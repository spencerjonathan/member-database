CREATE TABLE `#__md_mail` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `tower_id` int(3) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `reply_to_email` varchar(100) DEFAULT NULL,
  `reply_to_name` varchar(40) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `mod_date` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
