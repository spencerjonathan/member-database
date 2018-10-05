create table #__md_new_member_proposer (
	`id` INT NOT NULL AUTO_INCREMENT,
	`newmember_id` INT NOT NULL,
	`email` varchar(100) NOT NULL,
	`hash_token` VARCHAR(100) NOT NULL,
	`created_date` timestamp NOT NULL,
	`approved_flag` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
