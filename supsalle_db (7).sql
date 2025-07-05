-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 05 juil. 2025 à 03:18
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `utilisateurs_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(2, 2, 'Votre réservation #3 a été acceptée', 1, '2025-07-03 20:53:49'),
(4, 2, 'Votre réservation #5 a été acceptée', 1, '2025-07-04 23:27:02'),
(5, 2, 'Votre réservation #6 a été acceptée', 1, '2025-07-05 00:18:31'),
(6, 5, 'Votre compte a été désactivé par l\'administrateur ,vous pouvez nous contactez sur ****** ', 0, '2025-07-05 00:36:33'),
(7, 5, 'Votre compte a été désactivé par l\'administrateur ,vous pouvez nous contactez sur ****** ', 0, '2025-07-05 00:36:45'),
(8, 5, 'Votre compte a été désactivé par l\'administrateur ,vous pouvez nous contactez sur ****** ', 0, '2025-07-05 00:36:51'),
(9, 2, 'Votre compte a été désactivé par l\'administrateur ,vous pouvez nous contactez sur ****** ', 1, '2025-07-05 00:37:05'),
(10, 2, 'Votre compte a été désactivé par l\'administrateur ,vous pouvez nous contactez sur ****** ', 1, '2025-07-05 00:37:21');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `id_salle` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_fin` time DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `id_salle`, `id_user`, `date`, `heure_debut`, `heure_fin`, `statut`) VALUES
(1, 2, 2, '2025-07-03', '09:00:00', '10:30:00', 'accepté'),
(2, 3, 2, '2025-07-03', '11:45:00', '13:00:00', 'refusé'),
(3, 3, 2, '2025-07-03', '09:53:00', '10:52:00', 'accepté'),
(4, 4, 2, '2025-07-03', '10:55:00', '22:58:00', 'refusé'),
(5, 4, 2, '2025-07-03', '11:03:00', '12:03:00', 'accepté'),
(6, 4, 2, '2025-07-04', '08:00:00', '08:30:00', 'accepté');

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `capacité` int(11) DEFAULT NULL,
  `équipements` text DEFAULT NULL,
  `disponibilite` enum('disponible','indisponible') DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id`, `nom`, `type`, `capacité`, `équipements`, `disponibilite`) VALUES
(2, 'Hamidoun', 'salle de cours', 90, '', 'disponible'),
(3, 'test', 'salle de cours', 77, '', 'disponible'),
(4, '103', 'salle de cours', 40, '', 'indisponible'),
(5, 'Réseaux', 'salle informatique', 30, '', 'disponible');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expiration` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `role` enum('user','admin') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `otp_code`, `otp_expiration`, `is_verified`, `role`, `is_active`) VALUES
(1, 'ramoule', '24128@supnum.mr', '$2y$10$OvrUgUJJrr634n6t3pS8v.zQZ0km54/DRcEQ8FGPq1zOSNzW0gn4K', '701792', '2025-06-03 01:48:21', 1, 'admin', 1),
(2, 'ramoule', 'ramlebeirouk08@gmail.com', '$2y$10$JYeyJHc1hZS7KRT5qpoA..jZTnZL0GC4h9dF46XyROnC1/HO/2X0S', '644231', '2025-06-03 02:01:12', 1, 'user', 1),
(4, 'tt', 'rrpix05@gmail.com', '$2y$10$GU7WGlwVCC7yWQ16Q9hbKenFbTwPlq3OF4TVbwWUtdkSuO1wFzqX.', '337302', '2025-07-03 22:31:37', 1, 'user', 1),
(5, 'jj', 'rxs34rxs@gmail.com', '$2y$10$KFKU70vbr3Fk6UHOGUu6reWe7Gq8J/l1hS.U/SOAKVGfcF2oRneLa', '680685', '2025-07-04 03:30:10', 1, 'user', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_salle` (`id_salle`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salles` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
