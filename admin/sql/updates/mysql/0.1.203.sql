ALTER TABLE `#__md_tower` DROP `contact_person`, DROP `email2`;
ALTER TABLE `#__md_tower_history` DROP `contact_person`, DROP `email2`;

ALTER TABLE `#__md_tower` ADD `corresp_email` VARCHAR(100) NULL DEFAULT NULL AFTER `correspondent_id`;
ALTER TABLE `#__md_tower_history` ADD `corresp_email` VARCHAR(100) NULL DEFAULT NULL AFTER `correspondent_id`;


