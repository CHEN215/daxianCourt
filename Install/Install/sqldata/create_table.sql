-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 07 月 07 日 03:20
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tonjiju`
--

-- --------------------------------------------------------

--
-- 表的结构 `jl_cate`
--

CREATE TABLE IF NOT EXISTS `jl_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(25) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `position` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:导航栏',
  `is_nav` tinyint(4) NOT NULL DEFAULT '1',
  `is_show` tinyint(4) NOT NULL DEFAULT '1',
  `s_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属专题id,0表示不属于',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- 表的结构 `jl_comments`
--

CREATE TABLE IF NOT EXISTS `jl_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `comment` text NOT NULL,
  `type` tinyint(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- 表的结构 `jl_ini`
--

CREATE TABLE IF NOT EXISTS `jl_ini` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '配置名',
  `value` varchar(255) NOT NULL COMMENT '配置值',
  `link` varchar(100) DEFAULT NULL COMMENT '链接（可选）',
  `type` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `jl_link`
--

CREATE TABLE IF NOT EXISTS `jl_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- 表的结构 `jl_log`
--

CREATE TABLE IF NOT EXISTS `jl_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- 表的结构 `jl_news`
--

CREATE TABLE IF NOT EXISTS `jl_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `time` int(11) NOT NULL,
  `click` int(11) NOT NULL DEFAULT '0',
  `outside` tinyint(1) NOT NULL DEFAULT '0',
  `dept_id` int(11) DEFAULT NULL,
  `imgsrc` varchar(255) DEFAULT NULL,
  `desc` varchar(255) NOT NULL,
  `s_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属专题id;',
  `author` varchar(255) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `leader_id` int(11) NOT NULL COMMENT '领导审核',
  `hot` tinyint(4) NOT NULL DEFAULT '0',
  `top` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=169 ;

-- --------------------------------------------------------

--
-- 表的结构 `jl_user`
--

CREATE TABLE IF NOT EXISTS `jl_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` char(20) NOT NULL,
  `password` char(32) NOT NULL,
  `logintime` int(10) unsigned NOT NULL,
  `loginip` varchar(30) NOT NULL,
  `lock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `role` varchar(255) NOT NULL,
  `part` varchar(255) NOT NULL,
  `power` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
