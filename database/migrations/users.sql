# Delete any existing user data
DROP TABLE IF EXISTS `users`;

# Create a fresh table for storing all users
CREATE TABLE `users`
(
    `id`             bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`           varchar(255)    NOT NULL,
    `email`          varchar(255)    NOT NULL UNIQUE,
    `password`       varchar(255)    NOT NULL,
    `remember_token` varchar(100)    NULL DEFAULT NULL,
    `created_at`     timestamp       NULL DEFAULT NULL,
    `updated_at`     timestamp       NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);