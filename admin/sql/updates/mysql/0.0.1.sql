CREATE TABLE #__md_tower ( `id` INT NOT NULL AUTO_INCREMENT , `bells` INT NOT NULL , `city` VARCHAR(50) NOT NULL , `designation` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE #__md_member ( `id` INT NOT NULL AUTO_INCREMENT , `tower_id` INT, `name` VARCHAR(50) NOT NULL , `email` VARCHAR(50) , PRIMARY KEY (`id`)) ENGINE = InnoDB;

INSERT INTO `#__md_tower` (`bells`, `city`, `designation`) VALUES
(8, 'Lindfield', 'All Saints');

