# Role-Purview-System 小生蚝角色权限管理系统

---

## 简介

类似于一个框架，角色可以捆绑相应拥有的权限。

可以增删改角色&权限&页面&用户。

可以给角色绑定权限。

可以给用户绑定角色，修改状态、初始密码。

---

## 用途

适合在一个要多角色的项目里使用，可以自行二次开发。

---

## 特点 | 特色

▲ 使用单一入口

▲ 可以随意新增页面

▲ 使用Bootstrap，界面清新简洁

---

# 初次使用

① 导入role.sql数据库文件到你的数据库

② 进入Functions/PDOConn.php按照提示修改数据库配置

③ 进入浏览器输入：你的服务器/role/index.php即可。

④ 管理员用户名：super，初始密码：123456

★如果你修改了当前角色所拥有的权限，请点击导航栏的“退出登录”以显示最新权限。

---

## 鸣谢

* [`zTree`](https://github.com/zTree/zTree_v3)

* [`jQuery`](https://jquery.org/)，遵循`MIT`协议

* [`Bootstrap 3.3`](https://getbootstrap.com/)，遵循`MIT`协议

* [`dm_notify.js`](http://www.jqueryscript.net/other/Lightweight-Growl-style-Notification-Plugin-dm_notify-js.html)，遵循`GNU GPL`协议

* Github提供的免费代码仓库