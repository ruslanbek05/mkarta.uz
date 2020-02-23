DROP TABLE IF EXISTS `#__user_list`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_user_list.%');