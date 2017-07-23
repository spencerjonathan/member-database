
ALTER TABLE `#__md_tower` ADD `mod_user_id` INT(11) NULL AFTER `incl_corresp`, ADD `mod_date` TIMESTAMP NULL AFTER `mod_user_id`;

--
-- Table structure for table `#__md_tower_history`
--

CREATE TABLE IF NOT EXISTS `#__md_tower_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(3) NOT NULL,
  `district_id` int(1) DEFAULT NULL,
  `place` varchar(40) DEFAULT NULL,
  `designation` varchar(40) DEFAULT NULL,
  `bells` int(2) DEFAULT NULL,
  `tenor` varchar(20) DEFAULT NULL,
  `grid_ref` varchar(8) DEFAULT NULL,
  `ground_floor` tinyint(1) DEFAULT NULL,
  `anti_clockwise` tinyint(1) DEFAULT NULL,
  `unringable` tinyint(1) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `town` varchar(50) DEFAULT NULL,
  `county` varchar(50) DEFAULT NULL,
  `post_code` varchar(10) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `longitude` decimal(35,14) DEFAULT NULL,
  `latitude` decimal(35,14) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `church_website` varchar(200) DEFAULT NULL,
  `doves_guide` varchar(200) DEFAULT NULL,
  `contact_person` varchar(25) DEFAULT NULL,
  `email2` int(4) DEFAULT NULL,
  `tower_description` varchar(200) DEFAULT NULL,
  `wc` tinyint(1) DEFAULT NULL,
  `sunday_ringing` varchar(100) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `correspondent_id` int(4) DEFAULT NULL,
  `captain_id` int(5) DEFAULT NULL,
  `web_tower_id` int(3) DEFAULT NULL,
  `multi_towers` tinyint(1) DEFAULT NULL,
  `practice_night` varchar(17) DEFAULT NULL,
  `practice_details` varchar(200) DEFAULT NULL,
  `field1` varchar(4) DEFAULT NULL,
  `incl_capt` tinyint(1) DEFAULT NULL,
  `incl_corresp` tinyint(1) DEFAULT NULL,
  `mod_user_id` int(11) DEFAULT NULL,
  `mod_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`history_id`),
  KEY `tower_history_id_i1` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=268 ;


CREATE TRIGGER `md_tower_delete_trigger` BEFORE DELETE ON `#__md_tower` FOR EACH ROW insert into #__md_tower_history SELECT null, #__md_tower.* FROM #__md_tower WHERE #__md_tower.id = OLD.id;

CREATE TRIGGER `md_tower_update_trigger` BEFORE UPDATE ON `#__md_tower` FOR EACH ROW insert into #__md_tower_history SELECT null, #__md_tower.* FROM #__md_tower WHERE #__md_tower.id = NEW.id

