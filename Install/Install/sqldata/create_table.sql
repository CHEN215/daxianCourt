-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-07-06 09:08:09
-- 服务器版本： 5.5.24-log
-- PHP Version: 5.5.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tonjiju`
--

-- --------------------------------------------------------

--
-- 表的结构 `jl_cate`
--

CREATE TABLE `jl_cate` (
  `id` int(11) NOT NULL,
  `name` char(25) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `position` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:导航栏',
  `is_nav` tinyint(4) NOT NULL DEFAULT '1',
  `is_show` tinyint(4) NOT NULL DEFAULT '1',
  `s_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属专题id,0表示不属于'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `jl_ini`
--

CREATE TABLE `jl_ini` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '配置名',
  `value` varchar(255) NOT NULL COMMENT '配置值',
  `link` varchar(100) DEFAULT NULL COMMENT '链接（可选）',
  `type` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `jl_link`
--

CREATE TABLE `jl_link` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `type` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `jl_log`
--

CREATE TABLE `jl_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `jl_news`
--

CREATE TABLE `jl_news` (
  `id` int(11) NOT NULL,
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
  `top` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `jl_user`
--

CREATE TABLE `jl_user` (
  `id` int(10) NOT NULL,
  `username` char(20) NOT NULL,
  `password` char(32) NOT NULL,
  `logintime` int(10) UNSIGNED NOT NULL,
  `loginip` varchar(30) NOT NULL,
  `lock` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `role` varchar(255) NOT NULL,
  `part` varchar(255) NOT NULL,
  `power` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jl_cate`
--
ALTER TABLE `jl_cate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl_ini`
--
ALTER TABLE `jl_ini`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl_link`
--
ALTER TABLE `jl_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl_log`
--
ALTER TABLE `jl_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl_news`
--
ALTER TABLE `jl_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl_user`
--
ALTER TABLE `jl_user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `jl_cate`
--
ALTER TABLE `jl_cate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- 使用表AUTO_INCREMENT `jl_ini`
--
ALTER TABLE `jl_ini`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `jl_link`
--
ALTER TABLE `jl_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- 使用表AUTO_INCREMENT `jl_log`
--
ALTER TABLE `jl_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- 使用表AUTO_INCREMENT `jl_news`
--
ALTER TABLE `jl_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;
--
-- 使用表AUTO_INCREMENT `jl_user`
--
ALTER TABLE `jl_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
