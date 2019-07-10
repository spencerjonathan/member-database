ALTER TABLE `#__md_district` ADD `email` varchar(100) DEFAULT NULL AFTER `include_in_ar`;

update `#__md_district` set email = 'sec-north@scacr.org' where id = 1;
update `#__md_district` set email = 'sec-south@scacr.org' where id = 2;
update `#__md_district` set email = 'sec-east@scacr.org' where id = 3;
update `#__md_district` set email = 'sec-west@scacr.org' where id = 4;
update `#__md_district` set email = 'secretary@scacr.org' where id = 5;
