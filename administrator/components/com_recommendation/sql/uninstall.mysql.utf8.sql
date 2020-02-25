DROP TABLE IF EXISTS `#__recommendation`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_recommendation.%');