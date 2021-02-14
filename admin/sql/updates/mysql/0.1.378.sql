ALTER TABLE `#__md_member` CHANGE `insurance_group` `insurance_group` VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
update #__md_member 
inner join #__users u on u.username = "Jon"
set insurance_group = '80 and over', mod_date = current_timestamp(), mod_user_id = u.id
where insurance_group = '80 and ove';
