DROP TABLE IF EXISTS `templates`;

CREATE TABLE `templates`
(
    `id`          bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        varchar(255)    NOT NULL UNIQUE,
    `description` tinytext        NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);