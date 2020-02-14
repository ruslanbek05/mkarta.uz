CREATE TABLE IF NOT EXISTS `#__type_of_analysis` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`type_of_analysis_en` VARCHAR(255)  NOT NULL ,
`type_of_analysis_ru` VARCHAR(255)  NOT NULL ,
`type_of_analysis_uz` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Of analysis','com_type_of_analysis.ofanalysis','{"special":{"dbtable":"#__type_of_analysis","key":"id","type":"Ofanalysis","prefix":"Type_of_analysisTable"}}', '{"formFile":"administrator\/components\/com_type_of_analysis\/models\/forms\/ofanalysis.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_type_of_analysis.ofanalysis')
) LIMIT 1;
