CREATE DATABASE IF NOT EXISTS `role` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `role`;

CREATE TABLE `role_list` (
  `Roleid` int(11) NOT NULL COMMENT '角色ID',
  `RoleName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `remark` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色列表';

INSERT INTO `role_list` (`Roleid`, `RoleName`, `remark`) VALUES
(1, '超级管理员', '最高权限用户，内置角色不可删除');

CREATE TABLE `role_purview` (
  `id` int(11) NOT NULL,
  `Roleid` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `Purvid` int(11) NOT NULL COMMENT '权限ID（与sys_menu关联）'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色 - 角色拥有的权限';

INSERT INTO `role_purview` (`id`, `Roleid`, `Purvid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6);

CREATE TABLE `sys_menu` (
  `Menuid` int(11) NOT NULL COMMENT '菜单ID',
  `Fatherid` int(11) NOT NULL DEFAULT '0' COMMENT '父菜单ID',
  `Menuname` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `MenuIcon` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `PageFile` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'View' COMMENT '对应文件路径',
  `PageDOS` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '对应文件名'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统 - 菜单列表';

INSERT INTO `sys_menu` (`Menuid`, `Fatherid`, `Menuname`, `MenuIcon`, `PageFile`, `PageDOS`) VALUES
(1, 0, '系统', '', '', ''),
(3, 1, '菜单管理', '', 'Sys', 'ManageMenu'),
(2, 0, '角色', '', '', ''),
(5, 2, '分配权限', 'fa-icon', 'Role', 'SelectPurview'),
(4, 1, '用户管理', '', 'User', 'toList'),
(6, 2, '角色管理', '', 'Role', 'ManageRole');

CREATE TABLE `sys_user` (
  `Userid` int(11) NOT NULL,
  `UserName` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录用户名',
  `RealName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户真实姓名',
  `Password` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '加密字符串',
  `Roleid` int(11) NOT NULL COMMENT '角色ID（与role_list关联）',
  `Status` int(11) NOT NULL COMMENT '状态',
  `originPassword` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '初始8位密码'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `sys_user` (`Userid`, `UserName`, `RealName`, `Password`, `salt`, `Roleid`, `Status`, `originPassword`) VALUES
(1, 'super', '超级管理员', '5bd8cc16943f611a7833f735f74abadbdfbab230', 'CFPBcHFP', 1, 1, '39I53X27'),
(2, 'testadd', '测试', '5e496c11a2f04d531ccea435097ce91f806f11e5', 'GyYmwjwH', 1, 1, '64M45K75');


ALTER TABLE `role_list`
  ADD PRIMARY KEY (`Roleid`);

ALTER TABLE `role_purview`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sys_menu`
  ADD PRIMARY KEY (`Menuid`);

ALTER TABLE `sys_user`
  ADD PRIMARY KEY (`Userid`);


ALTER TABLE `role_purview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `sys_menu`
  MODIFY `Menuid` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单ID', AUTO_INCREMENT=7;
ALTER TABLE `sys_user`
  MODIFY `Userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;