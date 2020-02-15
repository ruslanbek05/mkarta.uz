CREATE TABLE IF NOT EXISTS `#__analysis` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`created_by` INT(11)  NOT NULL ,
`explanation` TEXT NOT NULL ,
`type_of_analysis` VARCHAR(255)  NOT NULL ,
`image` TEXT NOT NULL ,
`date` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'analysis','com_analysis.analysis','{"special":{"dbtable":"#__analysis","key":"id","type":"Analysis","prefix":"AnalysisTable"}}', '{"formFile":"administrator\/components\/com_analysis\/models\/forms\/analysis.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"image"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"type_of_analysis","targetTable":"#__type_of_analysis","targetColumn":"65723","displayColumn":"type_of_analysis_ru"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_analysis.analysis')
) LIMIT 1;
