CREATE TABLE `#__md_member_token` ( `id` INT NOT NULL AUTO_INCREMENT , `email` VARCHAR(100) NOT NULL , `hash_token` VARCHAR(100) NOT NULL , `expiry_date` TIMESTAMP NOT NULL , `created_date` TIMESTAMP NOT NULL, PRIMARY KEY (`id`), INDEX `member_token_email_i1` (`email`), UNIQUE `member_token_hash_token_i2` (`hash_token`)) ENGINE = InnoDB;
