-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2022-01-17 09:08:29
-- 服务器版本： 5.6.50-log
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thinkphp`
--

-- --------------------------------------------------------

--
-- 表的结构 `Invitation`
--

CREATE TABLE IF NOT EXISTS `Invitation` (
  `id` int(11) NOT NULL,
  `code` varchar(8) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `Invitation`
--

INSERT INTO `Invitation` (`id`, `code`) VALUES
(1, 'wNdrU7QW');

-- --------------------------------------------------------

--
-- 表的结构 `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) NOT NULL COMMENT 'id唯一 自增长',
  `account` varchar(16) NOT NULL COMMENT '账号',
  `password` varchar(16) NOT NULL COMMENT '密码',
  `token` varchar(128) NOT NULL COMMENT '登录token',
  `name` varchar(16) NOT NULL COMMENT '姓名',
  `login_out_time` int(32) NOT NULL COMMENT '登录过期时间',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `rols` int(11) NOT NULL COMMENT '身份0 管理员 1普通成员\r\n',
  `avatar` varchar(255) NOT NULL COMMENT '头像\r\n',
  `avatar_change` int(1) NOT NULL COMMENT '0可修改头像 1不可以修改头像'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `login`
--

INSERT INTO `login` (`id`, `account`, `password`, `token`, `name`, `login_out_time`, `mobile`, `rols`, `avatar`, `avatar_change`) VALUES
(14, '1249412130', '123456', '94135649012b1c1d3bbcc2e854d42929a409a5c3', 'yellow_Gay', 1642815965, '18873213739', 0, 'http://chat.silence.asia/uploads/20220115/42609021dade8ad2c9d0d75076fd9df6.png', 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_user`
--

CREATE TABLE IF NOT EXISTS `think_user` (
  `id` int(11) NOT NULL COMMENT 'id',
  `user_name` varchar(16) NOT NULL,
  `account` int(11) NOT NULL,
  `password` varchar(16) NOT NULL,
  `create_time` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_user`
--

INSERT INTO `think_user` (`id`, `user_name`, `account`, `password`, `create_time`, `ip`) VALUES
(1, 'yellow_Gay', 1249412130, 'hr20001124', 1629880222, '222.244.76.71');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Invitation`
--
ALTER TABLE `Invitation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `think_user`
--
ALTER TABLE `think_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Invitation`
--
ALTER TABLE `Invitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id唯一 自增长',AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `think_user`
--
ALTER TABLE `think_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
