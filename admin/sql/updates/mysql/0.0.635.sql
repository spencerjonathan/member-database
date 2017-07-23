CREATE TABLE IF NOT EXISTS `#__md_invoicemember` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `member_type_id` int(11) NOT NULL,
  `fee` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id_i1` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

