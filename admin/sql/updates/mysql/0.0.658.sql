CREATE TABLE `#__md_member_attachments`(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` INT UNSIGNED NOT NULL,
  `mod_user_id` INT UNSIGNED NOT NULL,
  `mod_date` TIMESTAMP NOT NULL,
  `name` VARCHAR(30) NOT NULL,
  `description` VARCHAR(250) NULL,
  `file` BLOB NOT NULL,
  PRIMARY KEY(`id`),
  INDEX `attachments_member_id`(`member_id`)
) ENGINE = InnoDB;


