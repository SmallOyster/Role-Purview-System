-- --------------------------------------------------------
-- 主机:                           localhost
-- 服务器版本:                        10.1.9-MariaDB - mariadb.org binary distribution
-- 服务器操作系统:                      Win32
-- HeidiSQL 版本:                  9.3.0.4984
-- --------------------------------------------------------

DROP DATABASE IF EXISTS `role`;
CREATE DATABASE IF NOT EXISTS `role`;
USE `role`;


-- 导出  表 role.cache_login 结构
DROP TABLE IF EXISTS `cache_login`;
CREATE TABLE IF NOT EXISTS `cache_login` (
  `CacheID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `RealName` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `SessionID` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `ErrorTimes` int(1) DEFAULT NULL,
  `ExpTime` int(10) NOT NULL,
  `CacheTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IP` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`CacheID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 导出  表 role.role_list 结构
DROP TABLE IF EXISTS `role_list`;
CREATE TABLE IF NOT EXISTS `role_list` (
  `RoleID` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `RoleName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `Brief` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '备注',
  `isSuper` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否系统角色(0非1是)(系统角色不可删除)',
  `isDefault` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否注册后的默认角色(0非1是)',
  PRIMARY KEY (`RoleID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色列表';

-- 正在导出表  role.role_list 的数据：2 rows
INSERT INTO `role_list` (`RoleID`, `RoleName`, `Brief`, `isSuper`, `isDefault`) VALUES
	(1, '超级管理员', '最高权限用户，内置角色不可删除', '1', '0'),
	(2, '普通用户', NULL, '0', '1');


-- 导出  表 role.role_purview 结构
DROP TABLE IF EXISTS `role_purview`;
CREATE TABLE IF NOT EXISTS `role_purview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `RoleID` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `PurvID` int(11) NOT NULL COMMENT '权限ID（与sys_menu关联）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色与权限关联';

-- 正在导出表  role.role_purview 的数据：7 rows
INSERT INTO `role_purview` (`id`, `RoleID`, `PurvID`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3),
	(4, 1, 4),
	(5, 1, 5),
	(6, 1, 6),
	(7, 1, 7);


-- 导出  表 role.sys_log 结构
DROP TABLE IF EXISTS `sys_log`;
CREATE TABLE IF NOT EXISTS `sys_log` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `LogType` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `LogContent` text COLLATE utf8_unicode_ci NOT NULL,
  `LogUser` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `LogIP` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `LogTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LogID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 导出  表 role.sys_menu 结构
DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE IF NOT EXISTS `sys_menu` (
  `MenuID` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `FatherID` int(11) NOT NULL DEFAULT '0' COMMENT '父菜单ID',
  `Menuname` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `MenuIcon` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单图标（FontAwesome类）',
  `PageFile` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'View' COMMENT '对应文件路径',
  `PageDOS` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '对应文件名',
  `isPublic` int(1) DEFAULT '0' COMMENT '是否公有页面',
  PRIMARY KEY (`MenuID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统 - 菜单列表';

-- 正在导出表  role.sys_menu 的数据：7 rows
INSERT INTO `sys_menu` (`MenuID`, `FatherID`, `Menuname`, `MenuIcon`, `PageFile`, `PageDOS`, `isPublic`) VALUES
	(1, 0, '系统', 'cogs', '', '', 0),
	(2, 1, '菜单管理', 'bars', 'Sys', 'ManageMenu.php', 0),
	(3, 1, '用户管理', 'user-circle', 'User', 'toList.php', 0),
	(4, 1, '角色管理', 'users', 'Role', 'toList.php', 0),
	(5, 1, '清空缓存', 'trash', 'Sys', 'EmptyCache.php', 0),
	(6, 1, '发布全局公告', 'bullhorn', 'Sys', 'toPubGlobalNotice.php', 0),
	(7, 1, '操作记录', 'list-alt', 'Sys', 'toLogList.php', 0);


-- 导出  表 role.sys_user 结构
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE IF NOT EXISTS `sys_user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录用户名',
  `RealName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户真实姓名',
  `Phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
  `Email` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱地址',
  `Password` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '加密字符串',
  `RoleID` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `Status` int(1) NOT NULL COMMENT '状态',
  `originPassword` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '初始8位密码',
  `RegiDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  `LastDate` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Index 2` (`UserName`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户 - 用户资料库';

-- 正在导出表  role.sys_user 的数据：1 rows
INSERT INTO `sys_user` (`UserID`, `UserName`, `RealName`, `Phone`, `Email`, `Password`, `salt`, `RoleID`, `Status`, `originPassword`, `RegiDate`, `LastDate`) VALUES
	(1, 'super', '超级管理员', '13900000000', 'test@test.com', 'c0b5ececae126a202683747c84bc1c08ab640ebb', 'GwczGAFD', 1, 2, '', '2017-04-08 07:25:02', '2017-08-10 11:09:08');
