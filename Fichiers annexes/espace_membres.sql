SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `espace_membres`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `pseudo` text NOT NULL,
  `mdp` text NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `pseudo`, `mdp`, `admin`) VALUES
(1, 'admin@test.fr', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997','1'), 
(2, 'boby@hotmail.fr', 'Bob', '48181acd22b3edaebc8a447868a7df7ce629920a','0'),
(3, 'farg@hotmail.fr','Fargost','492524b7cce25605e9788752760df0b89597a0de','0'),
(4, 'Leelou@hotmail.fr','Leelou','0cd57c85dad728e47cd073cc6909cf04cc111423','0');

-- Index pour les tables déchargées
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
--