CREATE TABLE `#__md_election_token` ( 
`token` VARCHAR(100) NOT NULL , 
`member_id` INT NOT NULL ,
PRIMARY KEY (`token`), UNIQUE INDEX `election_token_i1` (`token`) ) ENGINE = InnoDB;
