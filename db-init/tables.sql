-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Version du serveur: 5.0.83
-- Version de PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `vbiblio`
--

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_amis`
--

CREATE TABLE IF NOT EXISTS `vBiblio_amis` (
  `id_user1` int(11) NOT NULL,
  `id_user2` int(11) NOT NULL,
  PRIMARY KEY  (`id_user1`,`id_user2`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_author`
--

CREATE TABLE IF NOT EXISTS `vBiblio_author` (
  `id_author` int(11) NOT NULL auto_increment,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `description` longtext,
  PRIMARY KEY  (`id_author`),
  FULLTEXT KEY `prenom` (`prenom`),
  FULLTEXT KEY `nom` (`nom`),
  FULLTEXT KEY `prenom_2` (`prenom`),
  FULLTEXT KEY `nom_2` (`nom`,`prenom`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_book`
--

CREATE TABLE IF NOT EXISTS `vBiblio_book` (
  `id_book` int(11) NOT NULL auto_increment,
  `id_author` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `id_cycle` int(11) default NULL,
  `description` longtext NOT NULL,
  `numero_cycle` int(11) NOT NULL,
  `total_votes` int(11) NOT NULL default '0',
  `nb_votes` int(11) NOT NULL default '0',
  `isbn` varchar(13) NOT NULL,
  PRIMARY KEY  (`id_book`),
  FULLTEXT KEY `titre` (`titre`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_cycle`
--

CREATE TABLE IF NOT EXISTS `vBiblio_cycle` (
  `id_cycle` int(11) NOT NULL auto_increment,
  `titre` varchar(50) NOT NULL,
  `nb_tomes` int(11) NOT NULL,
  `id_author` int(11) NOT NULL,
  PRIMARY KEY  (`id_cycle`),
  FULLTEXT KEY `titre` (`titre`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_demande`
--

CREATE TABLE IF NOT EXISTS `vBiblio_demande` (
  `id_demande` int(11) NOT NULL auto_increment,
  `id_user` int(11) NOT NULL,
  `id_user_requested` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `id_requested` int(11) NOT NULL,
  PRIMARY KEY  (`id_demande`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_message`
--

CREATE TABLE IF NOT EXISTS `vBiblio_message` (
  `id_message` int(11) NOT NULL auto_increment,
  `from_user` int(11) NOT NULL,
  `to_user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY  (`id_message`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_poss`
--

CREATE TABLE IF NOT EXISTS `vBiblio_poss` (
  `id_book` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `possede` tinyint(1) NOT NULL,
  `lu` tinyint(1) NOT NULL,
  `pret` tinyint(1) NOT NULL,
  `date_ajout` datetime NOT NULL,
  PRIMARY KEY  (`id_book`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_pret`
--

CREATE TABLE IF NOT EXISTS `vBiblio_pret` (
  `id_preteur` int(11) NOT NULL,
  `id_emprunteur` int(11) NOT NULL,
  `id_book` int(11) NOT NULL,
  `date_pret` datetime NOT NULL,
  `nom_emprunteur` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_preteur`,`id_emprunteur`,`id_book`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_suggest`
--

CREATE TABLE IF NOT EXISTS `vBiblio_suggest` (
  `id_suggest` int(11) NOT NULL auto_increment,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  `id_book` int(11) NOT NULL,
  `date_suggest` datetime NOT NULL,
  PRIMARY KEY  (`id_suggest`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_tag`
--

CREATE TABLE IF NOT EXISTS `vBiblio_tag` (
  `id_tag` int(11) NOT NULL auto_increment,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_tag_book`
--

CREATE TABLE IF NOT EXISTS `vBiblio_tag_book` (
  `id_tag` int(11) NOT NULL,
  `id_book` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY  (`id_tag`,`id_book`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_toReadList`
--

CREATE TABLE IF NOT EXISTS `vBiblio_toReadList` (
  `id_user` int(11) NOT NULL,
  `id_book` int(11) NOT NULL,
  `date_ajout` datetime NOT NULL,
  PRIMARY KEY  (`id_user`,`id_book`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vBiblio_user`
--

CREATE TABLE IF NOT EXISTS `vBiblio_user` (
  `tableuserid` int(11) NOT NULL auto_increment,
  `userid` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_naiss` datetime NOT NULL,
  `sexe` tinyint(1) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `id_pref_book` int(11) NOT NULL,
  `prefBookStyle` text NOT NULL,
  `website` text NOT NULL,
  `notification_active` tinyint(4) NOT NULL,
  `active_public_page` int(11) NOT NULL,
  PRIMARY KEY  (`tableuserid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
