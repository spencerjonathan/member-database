ALTER TABLE `#__md_district` ADD `email` varchar(100) DEFAULT NULL AFTER `include_in_ar`;

update `#__md_district` set email = 'sec-north@scacr.org' where id = 1;
update `#__md_district` set email = 'sec-south@scacr.org' where id = 2;
update `#__md_district` set email = 'sec-east@scacr.org' where id = 3;
update `#__md_district` set email = 'sec-west@scacr.org' where id = 4;
update `#__md_district` set email = 'secretary@scacr.org' where id = 5;

ALTER TABLE `#__md_district` ADD `secretary_id` int(5) DEFAULT NULL AFTER `email`;

update `#__md_district` set secretary_id = 899 where id = 1;
update `#__md_district` set secretary_id = 1257 where id = 2;
update `#__md_district` set secretary_id = 475 where id = 3;
update `#__md_district` set secretary_id = 65 where id = 4;
update `#__md_district` set secretary_id = 22 where id = 5;
