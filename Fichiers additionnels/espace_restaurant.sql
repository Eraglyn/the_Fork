SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `espace_membres`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis_restaurant`
--

CREATE TABLE `avis_restaurant` (
  `id` int(11) NOT NULL,
  `nom_restaurant` text NOT NULL,
  `nom_client` text NOT NULL,
  `avis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Structure de la table `restaurant`
--

CREATE TABLE `restaurant` (
  `id` int(11) NOT NULL,
  `lieu` text NOT NULL,
  `adresse` text NOT NULL,
  `tel` text NOT NULL,
  `prix` text NOT NULL,
  `nom` text NOT NULL,
  `cuisine` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `avis_restaurant`
--

INSERT INTO `avis_restaurant` (`id`, `nom_restaurant`, `nom_client`, `avis`) VALUES
(1, 'Gastroto', 'Bob', "Le plat était froid et rempli d'oignons alors que j'avais spécifié au serveur que j'y étais allergique, c'est inadmissible !"), 
(2, 'LeGourmet', 'Bob', "Tout le monde était serviable et la nourriture était plus que délicieuse. Je recommande l'établissement à n'importe qui !"),
(3, 'LeGourmet','Fargost',"J'ai passé une superbe soirée ! La nourriture était succulente et la vue était probablement l'une des meilleure que l'on puisse imaginer ! J'y retournerais avec grand plaisir une prochaine fois."),
(4, 'Kevry','Fargost',"J'ai adoré le poulet ! Un vrai délice et une qualité de service irréprochable !");

--
-- Déchargement des données de la table `restaurant`
--

INSERT INTO `restaurant` (`id`, `lieu`, `adresse`, `tel`, `prix`, `nom`, `cuisine`) VALUES
(1, 'Paris', '23 Rue des ponts', '01 26 34 45 32','25€','LeGourmet','Français'), 
(2, 'Marseille', '3 Rue de la Marre', '04 12 34 56 32','30€','Olivium','Portugais'),
(3, 'Bordeaux','12 Allée Pompette','05 67 34 12 65','15€','Gastroto','Italien'),
(4, 'Paris','5 Quai Aulagnier','01 24 56 73 45','35€','Kevry','Indien');

--
-- Index pour la table `avis_restaurant`
--
ALTER TABLE `avis_restaurant`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT pour la table `avis_restaurant`
--
ALTER TABLE `avis_restaurant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Index pour la table `restaurants`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT pour la table `restaurants`
--
ALTER TABLE `restaurant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;