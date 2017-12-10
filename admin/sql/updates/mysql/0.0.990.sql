ALTER TABLE `#__md_member_history` ADD `db_form_received` BOOLEAN NOT NULL DEFAULT TRUE AFTER `mod_date`, ADD `accept_privicy_policy` BOOLEAN NOT NULL DEFAULT FALSE AFTER `db_form_received`;
ALTER TABLE `#__md_member` ADD `db_form_received` BOOLEAN NOT NULL DEFAULT TRUE AFTER `mod_date`, ADD `accept_privicy_policy` BOOLEAN NOT NULL DEFAULT FALSE AFTER `db_form_received`;
