CREATE TABLE IF NOT EXISTS `#__doctorinfo` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`created_by` INT(11)  NOT NULL ,
`date_data_added` DATE NOT NULL ,
`file` TEXT NOT NULL ,
`additional_information` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'doctorinfo','com_doctorinfo.doctorinfo','{"special":{"dbtable":"#__doctorinfo","key":"id","type":"Doctorinfo","prefix":"DoctorinfTable"}}', '{"formFile":"administrator\/components\/com_doctorinfo\/models\/forms\/doctorinfo.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"additional_information"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_doctorinfo.doctorinfo')
) LIMIT 1;
