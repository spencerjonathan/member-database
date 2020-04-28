CREATE TABLE `#__md_election` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `member_id` int(3) NOT NULL,
  `master_response` int(3) NOT NULL,
  `secretary_response` int(3) NOT NULL,
  `brf_secretary_response` int(3) NOT NULL,
  `treasurer_response` int(3) NOT NULL,
  `safeguarding_response` int(3) NOT NULL,
  `trustee_response` int(3) NOT NULL,
  `eastern_cccbr_response` int(3) NOT NULL,
  `hon_life_response` int(3) NOT NULL,
  `mod_date` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
