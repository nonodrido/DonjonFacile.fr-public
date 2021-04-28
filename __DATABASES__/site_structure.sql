-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 07 avr. 2021 à 17:28
-- Version du serveur :  10.3.27-MariaDB
-- Version de PHP : 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `nonodrid_site`
--
CREATE DATABASE IF NOT EXISTS `nonodrid_site` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `nonodrid_site`;

-- --------------------------------------------------------

--
-- Structure de la table `ban`
--

DROP TABLE IF EXISTS `ban`;
CREATE TABLE `ban` (
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `time` int(11) NOT NULL,
  `motif` varchar(500) NOT NULL,
  `etat` varchar(255) NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ban_ip`
--

DROP TABLE IF EXISTS `ban_ip`;
CREATE TABLE `ban_ip` (
  `ip` varchar(255) NOT NULL,
  `motif` varchar(500) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `faq`
--

DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` varchar(250) NOT NULL DEFAULT 'default',
  `titre` text NOT NULL,
  `contenu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fav`
--

DROP TABLE IF EXISTS `fav`;
CREATE TABLE `fav` (
  `id` int(11) NOT NULL,
  `etat` varchar(255) NOT NULL,
  `type` varchar(500) NOT NULL DEFAULT 'default',
  `user_id` int(11) NOT NULL,
  `fav_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fiches_stat`
--

DROP TABLE IF EXISTS `fiches_stat`;
CREATE TABLE `fiches_stat` (
  `referer` varchar(250) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nbre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fichiers`
--

DROP TABLE IF EXISTS `fichiers`;
CREATE TABLE `fichiers` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` varchar(250) NOT NULL DEFAULT 'default',
  `url` varchar(250) NOT NULL,
  `date_ftp` datetime NOT NULL,
  `type` varchar(500) NOT NULL,
  `orig` varchar(500) NOT NULL,
  `name` varchar(500) NOT NULL,
  `descr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `group`
--

DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `user_id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `descr` text NOT NULL,
  `recit` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `group_perso`
--

DROP TABLE IF EXISTS `group_perso`;
CREATE TABLE `group_perso` (
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `group_id` int(11) NOT NULL,
  `perso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `group_user`
--

DROP TABLE IF EXISTS `group_user`;
CREATE TABLE `group_user` (
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `auteur_id` int(11) NOT NULL DEFAULT 1,
  `orig_auteur_id` int(11) NOT NULL,
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `type` varchar(255) CHARACTER SET latin1 NOT NULL,
  `subtype` varchar(500) NOT NULL,
  `emplacement` varchar(255) NOT NULL DEFAULT 'default',
  `name` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'objet sans nom',
  `carac` varchar(500) NOT NULL,
  `prix` float NOT NULL DEFAULT 0 COMMENT 'PO',
  `descr` text NOT NULL,
  `effets` text CHARACTER SET latin1 NOT NULL COMMENT '(dans la zone équipement)',
  `rupture` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='liste de tous les items du jdr';

-- --------------------------------------------------------

--
-- Structure de la table `item_rate`
--

DROP TABLE IF EXISTS `item_rate`;
CREATE TABLE `item_rate` (
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rate` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `livreor`
--

DROP TABLE IF EXISTS `livreor`;
CREATE TABLE `livreor` (
  `id` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `type` varchar(500) NOT NULL DEFAULT 'default',
  `txt` text NOT NULL,
  `uri` varchar(500) NOT NULL,
  `user` varchar(500) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `mail` varchar(500) NOT NULL,
  `ip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mdp_oubli`
--

DROP TABLE IF EXISTS `mdp_oubli`;
CREATE TABLE `mdp_oubli` (
  `key` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `etat` varchar(50) NOT NULL DEFAULT 'default',
  `user_id` int(11) NOT NULL,
  `ip` varchar(500) NOT NULL,
  `nav` varchar(500) NOT NULL DEFAULT 'erreur',
  `geoloc` varchar(500) NOT NULL DEFAULT 'erreur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `auteur_id` int(11) NOT NULL,
  `destinataire_id` int(11) NOT NULL,
  `auteur_delete` int(11) NOT NULL DEFAULT 0,
  `destinataire_delete` int(11) NOT NULL DEFAULT 0,
  `prec_id` int(11) NOT NULL DEFAULT 0,
  `lu` int(11) NOT NULL DEFAULT 0,
  `sujet` varchar(500) NOT NULL,
  `contenu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `etat` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'default',
  `type` varchar(500) NOT NULL DEFAULT 'news',
  `titre` varchar(255) CHARACTER SET latin1 NOT NULL,
  `contenu` text CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='table de toutes les news du site';

-- --------------------------------------------------------

--
-- Structure de la table `news_commentaires`
--

DROP TABLE IF EXISTS `news_commentaires`;
CREATE TABLE `news_commentaires` (
  `id` int(11) NOT NULL,
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `user_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `txt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `perso`
--

DROP TABLE IF EXISTS `perso`;
CREATE TABLE `perso` (
  `id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL DEFAULT 1,
  `etat` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'default',
  `pnj` tinyint(1) NOT NULL DEFAULT 0,
  `name` text CHARACTER SET latin1 NOT NULL,
  `xp` int(11) NOT NULL DEFAULT 0,
  `origine` varchar(255) NOT NULL DEFAULT 'humain',
  `metier` varchar(500) NOT NULL DEFAULT 'ranger',
  `specialisation` varchar(255) NOT NULL DEFAULT 'Aucune spécialisation',
  `sexe` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'masculin',
  `evmax` int(11) NOT NULL DEFAULT 0,
  `eamax` int(11) NOT NULL DEFAULT 0,
  `PDest` int(11) NOT NULL DEFAULT 0,
  `COU` int(11) NOT NULL,
  `INTL` int(11) NOT NULL,
  `CHA` int(11) NOT NULL,
  `AD` int(11) NOT NULL,
  `FO` int(11) NOT NULL,
  `AT` int(11) NOT NULL DEFAULT 8,
  `PRD` int(11) NOT NULL DEFAULT 10,
  `PO` int(11) NOT NULL DEFAULT 0,
  `PA` int(11) NOT NULL DEFAULT 0,
  `PC` int(11) NOT NULL DEFAULT 0,
  `LT` int(11) NOT NULL DEFAULT 0,
  `LB` int(11) NOT NULL DEFAULT 0,
  `descr` text CHARACTER SET latin1 NOT NULL,
  `img` text CHARACTER SET latin1 NOT NULL,
  `adv_opt` varchar(500) NOT NULL DEFAULT 'Automatique',
  `old_fiche` varchar(500) NOT NULL,
  `old_fiche_advanced` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='table de tous les personnages enregistrés';

-- --------------------------------------------------------

--
-- Structure de la table `perso_items`
--

DROP TABLE IF EXISTS `perso_items`;
CREATE TABLE `perso_items` (
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `perso_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qte` int(11) NOT NULL DEFAULT 1,
  `equip` int(11) NOT NULL DEFAULT 0,
  `waste` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT 'items'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `search`
--

DROP TABLE IF EXISTS `search`;
CREATE TABLE `search` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `type` varchar(255) NOT NULL,
  `subtype` varchar(500) NOT NULL,
  `auteur_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `descr` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sondage`
--

DROP TABLE IF EXISTS `sondage`;
CREATE TABLE `sondage` (
  `sondage_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `vote` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `etat` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'default',
  `pseudo` varchar(255) CHARACTER SET latin1 NOT NULL,
  `mdp` varchar(255) CHARACTER SET latin1 NOT NULL,
  `orig_mail` varchar(255) NOT NULL,
  `mail` varchar(255) CHARACTER SET latin1 NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_connect` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_ip` varchar(255) CHARACTER SET latin1 NOT NULL,
  `last_nav` varchar(255) CHARACTER SET latin1 NOT NULL,
  `last_geoloc` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'Erreur',
  `current_perso` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'user',
  `option_mail` int(1) NOT NULL DEFAULT 1,
  `option_newsletter` int(1) NOT NULL DEFAULT 1,
  `option_online` int(1) NOT NULL DEFAULT 1,
  `option_gravatar` int(11) NOT NULL DEFAULT 0,
  `sexe` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'non renseigné',
  `age` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'non renseigné',
  `localisation` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'non renseignée',
  `status` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'membre standard',
  `descr` text CHARACTER SET latin1 NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '/ressources/img/avatar/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='table de tous les utilisateurs';

-- --------------------------------------------------------

--
-- Structure de la table `users_persos`
--

DROP TABLE IF EXISTS `users_persos`;
CREATE TABLE `users_persos` (
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` varchar(255) NOT NULL DEFAULT 'default',
  `user_id` int(11) NOT NULL,
  `perso_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'full'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `validation`
--

DROP TABLE IF EXISTS `validation`;
CREATE TABLE `validation` (
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `pseudo` varchar(500) NOT NULL,
  `pass` varchar(500) NOT NULL,
  `key` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ban`
--
ALTER TABLE `ban`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Index pour la table `ban_ip`
--
ALTER TABLE `ban_ip`
  ADD PRIMARY KEY (`ip`);

--
-- Index pour la table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fav`
--
ALTER TABLE `fav`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `fiches_stat`
--
ALTER TABLE `fiches_stat`
  ADD UNIQUE KEY `referer` (`referer`);

--
-- Index pour la table `fichiers`
--
ALTER TABLE `fichiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`);

--
-- Index pour la table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `group_perso`
--
ALTER TABLE `group_perso`
  ADD UNIQUE KEY `group_id` (`group_id`,`perso_id`),
  ADD KEY `perso_id` (`perso_id`);

--
-- Index pour la table `group_user`
--
ALTER TABLE `group_user`
  ADD UNIQUE KEY `group_id` (`group_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id_2` (`group_id`);

--
-- Index pour la table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catégorie` (`type`),
  ADD KEY `auteur_id` (`auteur_id`),
  ADD KEY `name` (`name`),
  ADD KEY `type` (`type`,`subtype`(255)),
  ADD KEY `subtype` (`subtype`(255));

--
-- Index pour la table `item_rate`
--
ALTER TABLE `item_rate`
  ADD PRIMARY KEY (`item_id`,`user_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `livreor`
--
ALTER TABLE `livreor`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mdp_oubli`
--
ALTER TABLE `mdp_oubli`
  ADD PRIMARY KEY (`key`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auteur_id` (`auteur_id`),
  ADD KEY `destinataire_id` (`destinataire_id`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `news_commentaires`
--
ALTER TABLE `news_commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `news_id` (`news_id`);

--
-- Index pour la table `perso`
--
ALTER TABLE `perso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `perso_items`
--
ALTER TABLE `perso_items`
  ADD UNIQUE KEY `perso_id` (`perso_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `search`
--
ALTER TABLE `search`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `search` ADD FULLTEXT KEY `fulltext` (`name`,`descr`);
ALTER TABLE `search` ADD FULLTEXT KEY `name` (`name`);
ALTER TABLE `search` ADD FULLTEXT KEY `descr` (`descr`);

--
-- Index pour la table `sondage`
--
ALTER TABLE `sondage`
  ADD UNIQUE KEY `sondage_id` (`sondage_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_persos`
--
ALTER TABLE `users_persos`
  ADD UNIQUE KEY `user_id` (`user_id`,`perso_id`),
  ADD KEY `perso_id` (`perso_id`),
  ADD KEY `user_id_2` (`user_id`);

--
-- Index pour la table `validation`
--
ALTER TABLE `validation`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fav`
--
ALTER TABLE `fav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fichiers`
--
ALTER TABLE `fichiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `group`
--
ALTER TABLE `group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livreor`
--
ALTER TABLE `livreor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `news_commentaires`
--
ALTER TABLE `news_commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `perso`
--
ALTER TABLE `perso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `search`
--
ALTER TABLE `search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ban`
--
ALTER TABLE `ban`
  ADD CONSTRAINT `ban_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fav`
--
ALTER TABLE `fav`
  ADD CONSTRAINT `fav_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `group`
--
ALTER TABLE `group`
  ADD CONSTRAINT `group_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `group_perso`
--
ALTER TABLE `group_perso`
  ADD CONSTRAINT `group_perso_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_perso_ibfk_2` FOREIGN KEY (`perso_id`) REFERENCES `perso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `group_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `item_rate`
--
ALTER TABLE `item_rate`
  ADD CONSTRAINT `item_rate_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_rate_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `mdp_oubli`
--
ALTER TABLE `mdp_oubli`
  ADD CONSTRAINT `mdp_oubli_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`auteur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`destinataire_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `news_commentaires`
--
ALTER TABLE `news_commentaires`
  ADD CONSTRAINT `news_commentaires_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `news_commentaires_ibfk_2` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `perso`
--
ALTER TABLE `perso`
  ADD CONSTRAINT `perso_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `perso_items`
--
ALTER TABLE `perso_items`
  ADD CONSTRAINT `perso_items_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `perso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `perso_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sondage`
--
ALTER TABLE `sondage`
  ADD CONSTRAINT `sondage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users_persos`
--
ALTER TABLE `users_persos`
  ADD CONSTRAINT `users_persos_ibfk_1` FOREIGN KEY (`perso_id`) REFERENCES `perso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_persos_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `validation`
--
ALTER TABLE `validation`
  ADD CONSTRAINT `validation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
