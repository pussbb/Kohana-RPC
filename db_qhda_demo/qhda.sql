-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 25 2011 г., 13:19
-- Версия сервера: 5.5.8
-- Версия PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `qhda`
--

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_articles` int(11) DEFAULT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `author` varchar(150) NOT NULL,
  `published` datetime NOT NULL,
  `md5` mediumtext NOT NULL,
  `guid` mediumtext NOT NULL,
  `catid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_articles_UNIQUE` (`id_articles`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `articles`
--


-- --------------------------------------------------------

--
-- Структура таблицы `bookcat`
--

CREATE TABLE IF NOT EXISTS `bookcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `parent` int(11) DEFAULT '0',
  `bookcat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `bookcat`
--


-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `description` text,
  `image` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `name`, `description`, `image`) VALUES
(29, 'serdtfyghui', 'zxcftvgyhuiop[\ndxctvyunimo\nrtvyguhnijmop\nrctvyunimo\ndrftyghuijokp', '1328-81891.png');

-- --------------------------------------------------------

--
-- Структура таблицы `book_access`
--

CREATE TABLE IF NOT EXISTS `book_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` varchar(45) DEFAULT NULL,
  `userid` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Дамп данных таблицы `book_access`
--

INSERT INTO `book_access` (`id`, `book_id`, `userid`) VALUES
(28, '26', '3'),
(29, '27', '3'),
(30, '28', '3'),
(31, '29', '3');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

-- --------------------------------------------------------

--
-- Структура таблицы `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `roles_users`
--

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES
(2, 1),
(3, 1),
(2, 2),
(3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  `apikey` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `logins`, `last_login`, `apikey`) VALUES
(2, 'admin@example.com', 'admin', 'f9498dcd4c2de8dfb78dab778056e4a360c4b289488d27cc1962a8d1cf6e6831', 3, 1316886480, NULL),
(3, 'pussbb@example.com', 'pussbb', 'fdb9e09e5338aa264726878b9f682f2466470ee72bb693b7852230c8840c0174', 0, NULL, '4e7e1d7489080');

-- --------------------------------------------------------

--
-- Структура таблицы `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(40) NOT NULL,
  `type` varchar(100) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `user_agent`, `token`, `type`, `created`, `expires`) VALUES
(1, 2, '2574e282c5402a30e971e57b17e93eb27f7fd1bd', 'a2e91fa92c54a6327a78a4a839ac5214026399c4', '', 0, 1318095582),
(2, 2, '2574e282c5402a30e971e57b17e93eb27f7fd1bd', '53803a156c99c93aaea62424b855699d1e20e422', '', 0, 1318095682),
(3, 2, '2574e282c5402a30e971e57b17e93eb27f7fd1bd', '9347153cd20fd10db905e73ff392c8060bbb13db', '', 0, 1318095747),
(4, 2, '2574e282c5402a30e971e57b17e93eb27f7fd1bd', '72bd2d1f0fe94911e6acd78c5be823ca5d9c5089', '', 0, 1318096080);
