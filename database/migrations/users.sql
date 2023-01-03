CREATE TABLE IF NOT EXISTS `users`
(
    `id`                bigint UNSIGNED NOT NULL,
    `name`              varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email`             varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password`          varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `remember_token`    varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at`        timestamp NULL DEFAULT NULL,
    `updated_at`        timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;