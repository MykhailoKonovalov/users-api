SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240731144630', '2024-07-31 14:51:10', 53);

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `login`, `phone`, `pass`) VALUES
(1, 'login_1', '50878360', 'cfb69827'),
(2, 'login_2', '58486918', '1d0e4e64'),
(3, 'login_3', '88613437', 'a6f81d40'),
(4, 'login_4', '46574973', '00972c33'),
(5, 'login_5', '28228740', '8be06348'),
(6, 'login_6', '80569248', '9a892100'),
(7, 'login_7', '16724865', '721010cb'),
(8, 'login_8', '44100979', '8756bc2d'),
(9, 'login_9', '54892713', '6fc5a016'),
(10, 'login_10', '97888758', '5cf75bf8'),
(11, 'admin', '11010010', 'admin100');

ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_LOGIN_PASS` (`login`,`pass`);

ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;
