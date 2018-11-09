ALTER TABLE `#__md_member_type` ADD `new_member_type` BOOLEAN NOT NULL DEFAULT FALSE AFTER `enabled`;

update `#__md_member_type` set `new_member_type` = 1 where id in (1, 5, 7);
