ALTER TABLE `#__md_member_type` ADD `include_in_reports` BOOLEAN NOT NULL DEFAULT TRUE AFTER `fee`;
update `#__md_member_type` set include_in_reports=false where id in (4, 7);
