-- Users
INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `password`, `createdAt`, `role`) VALUES
(5, 'Juan', 'Dela Cruz', 'juan@talamban.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(6, 'Ana', 'Santos', 'ana@talamban.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(7, 'Carlos', 'Torres', 'carlos@talamban.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(8, 'Maria', 'Reyes', 'maria@banilad.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(9, 'Leo', 'Gutierrez', 'leo@banilad.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(10, 'Ella', 'Navarro', 'ella@banilad.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(11, 'Jose', 'Lopez', 'jose@lahug.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(12, 'Grace', 'Lim', 'grace@lahug.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(13, 'Mark', 'Rivera', 'mark@lahug.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:26:12', 'official'),
(17, 'Liam', 'Garcia', 'liam@user.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:30:35', 'user'),
(18, 'Chloe', 'Morales', 'chloe@user.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:30:35', 'user'),
(19, 'Noah', 'Fernandez', 'noah@user.com', '$2y$10$VZlZAyGUvvsWweRyC1SV5u87WkbVfN3IQO9dVdp8PBS2BD1dq.2Ve', '2025-05-07 09:30:35', 'user'),
(20, 'admin', 'Admin', 'admin@admin.com', '$2y$10$B0OsAybM9ha6zYgyAhLCwe3EcxTfscYRvrW18ImTxjJEEIQ/QzgEG', '2025-05-08 06:50:32', 'admin');

-- Officials Info
INSERT INTO `officialsInfo` (`userID`, `baranggayID`, `position`, `isActive`, `termStart`, `termEnd`) VALUES
(5, 1, 'Barangay Captain', 1, '2023-01-01', '2026-01-01'),
(6, 1, 'Kagawad', 1, '2023-01-01', '2026-01-01'),
(7, 1, 'Secretary', 1, '2023-01-01', '2026-01-01'),
(8, 2, 'Barangay Captain', 1, '2023-01-01', '2026-01-01'),
(9, 2, 'Kagawad', 1, '2023-01-01', '2026-01-01'),
(10, 2, 'Secretary', 1, '2023-01-01', '2026-01-01'),
(11, 3, 'Barangay Captain', 1, '2023-01-01', '2026-01-01'),
(12, 3, 'Kagawad', 1, '2023-01-01', '2026-01-01'),
(13, 3, 'Secretary', 1, '2023-01-01', '2026-01-01');


-- User Preferences
INSERT INTO `user_preferences` (`user_id`, `theme`, `profile_image`) VALUES
(5, 'system', NULL),
(6, 'system', NULL),
(7, 'system', NULL),
(8, 'system', NULL),
(9, 'system', NULL),
(10, 'system', NULL),
(11, 'system', NULL),
(12, 'system', NULL),
(13, 'system', NULL),
(17, 'system', NULL),
(18, 'system', NULL),
(19, 'system', NULL),
(20, 'system', NULL);
