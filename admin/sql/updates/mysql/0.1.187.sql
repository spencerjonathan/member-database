ALTER TABLE #__md_member ADD `soudbow_subscriber` BOOLEAN NOT NULL DEFAULT FALSE AFTER `accept_privicy_policy`; 
ALTER TABLE #__md_member_history ADD `soudbow_subscriber` BOOLEAN NOT NULL DEFAULT FALSE AFTER `accept_privicy_policy`; 

ALTER TABLE #__md_member ADD `can_publish_name` BOOLEAN NULL AFTER `soudbow_subscriber`;
ALTER TABLE #__md_member_history ADD `can_publish_name` BOOLEAN NULL AFTER `soudbow_subscriber`;
