CREATE TABLE IF NOT EXISTS `#__user_list` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`created_by` INT(11)  NOT NULL ,
`avatar` TEXT NOT NULL ,
`username` TEXT NOT NULL ,
`year_of_birth` TEXT NOT NULL ,
`gender` TEXT NOT NULL ,
`view_health_info` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'List','com_user_list.list','{"special":{"dbtable":"#__user_list","key":"id","type":"List","prefix":"User_listTable"}}', '{"formFile":"administrator\/components\/com_user_list\/models\/forms\/list.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_user_list.list')
) LIMIT 1;
