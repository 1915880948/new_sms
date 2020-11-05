/*
 Navicat MySQL Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : fastadmin

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 10/09/2020 17:46:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for fa_admin
-- ----------------------------
DROP TABLE IF EXISTS `fa_admin`;
CREATE TABLE `fa_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '失败次数',
  `logintime` int(10) NULL DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '登录IP',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_admin
-- ----------------------------
INSERT INTO `fa_admin` VALUES (1, 'admin', 'Admin', '29f3de737ee85e80c0e34479e8c51075', '8c54be', '/assets/img/avatar.png', 'admin@admin.com', 0, 1599705287, '127.0.0.1', 1492186163, 1599705287, '3bbb541e-bbfe-4ddb-bc9a-5d716b5ebd9f', 'normal');

-- ----------------------------
-- Table structure for fa_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_admin_log`;
CREATE TABLE `fa_admin_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '日志标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 181 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员日志表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_admin_log
-- ----------------------------
INSERT INTO `fa_admin_log` VALUES (1, 1, 'admin', '/admin.php/index/login?url=%2Fadmin.php', '登录', '{\"url\":\"\\/admin.php\",\"__token__\":\"afdfeb68ac0fe820c06ad8e6838521df\",\"username\":\"admin\",\"captcha\":\"skq4\",\"keeplogin\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598859196);
INSERT INTO `fa_admin_log` VALUES (2, 1, 'admin', '/admin.php/auth/group/roletree', '权限管理 角色组', '{\"id\":\"2\",\"pid\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598859335);
INSERT INTO `fa_admin_log` VALUES (3, 1, 'admin', '/admin.php/auth/rule/multi/ids/1', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"1\",\"params\":\"ismenu=0\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941343);
INSERT INTO `fa_admin_log` VALUES (4, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941344);
INSERT INTO `fa_admin_log` VALUES (5, 1, 'admin', '/admin.php/auth/rule/multi/ids/1', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"1\",\"params\":\"ismenu=1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941348);
INSERT INTO `fa_admin_log` VALUES (6, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941349);
INSERT INTO `fa_admin_log` VALUES (7, 1, 'admin', '/admin.php/auth/rule/multi/ids/1', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"1\",\"params\":\"ismenu=0\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941354);
INSERT INTO `fa_admin_log` VALUES (8, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941355);
INSERT INTO `fa_admin_log` VALUES (9, 1, 'admin', '/admin.php/auth/rule/multi/ids/1', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"1\",\"params\":\"ismenu=1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941358);
INSERT INTO `fa_admin_log` VALUES (10, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941359);
INSERT INTO `fa_admin_log` VALUES (11, 1, 'admin', '/admin.php/auth/rule/multi/ids/1', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"1\",\"params\":\"ismenu=0\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941423);
INSERT INTO `fa_admin_log` VALUES (12, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941423);
INSERT INTO `fa_admin_log` VALUES (13, 1, 'admin', '/admin.php/auth/rule/multi/ids/1', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"1\",\"params\":\"ismenu=1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941458);
INSERT INTO `fa_admin_log` VALUES (14, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941459);
INSERT INTO `fa_admin_log` VALUES (15, 1, 'admin', '/admin.php/auth/rule/multi/ids/1', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"1\",\"params\":\"ismenu=0\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941471);
INSERT INTO `fa_admin_log` VALUES (16, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941471);
INSERT INTO `fa_admin_log` VALUES (17, 1, 'admin', '/admin.php/auth/rule/multi/ids/1', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"1\",\"params\":\"ismenu=1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941474);
INSERT INTO `fa_admin_log` VALUES (18, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1598941474);
INSERT INTO `fa_admin_log` VALUES (19, 1, 'admin', '/admin.php/index/login', '登录', '{\"__token__\":\"edc0b3d16628f10cc0d22ea9cab6300d\",\"username\":\"admin\",\"captcha\":\"5zzb\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599201912);
INSERT INTO `fa_admin_log` VALUES (20, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"test.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599202400);
INSERT INTO `fa_admin_log` VALUES (21, 1, 'admin', '/admin.php/auth/rule/multi/ids/11', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"11\",\"params\":\"ismenu=0\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599203998);
INSERT INTO `fa_admin_log` VALUES (22, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599203999);
INSERT INTO `fa_admin_log` VALUES (23, 1, 'admin', '/admin.php/auth/rule/multi/ids/11', '权限管理 菜单规则', '{\"action\":\"\",\"ids\":\"11\",\"params\":\"ismenu=1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599204003);
INSERT INTO `fa_admin_log` VALUES (24, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599204004);
INSERT INTO `fa_admin_log` VALUES (25, 1, 'admin', '/admin.php/general/attachment/del/ids/2', '常规管理 附件管理 删除', '{\"action\":\"del\",\"ids\":\"2\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599204476);
INSERT INTO `fa_admin_log` VALUES (26, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"test.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599205994);
INSERT INTO `fa_admin_log` VALUES (27, 1, 'admin', '/admin.php/auth/rule/edit/ids/4?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"7f976691efa4b9c2aaea1a28a0928ade\",\"row\":{\"ismenu\":\"1\",\"pid\":\"0\",\"name\":\"addon\",\"title\":\"\\u63d2\\u4ef6\\u7ba1\\u7406\",\"icon\":\"fa fa-rocket\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"Addon tips\",\"status\":\"hidden\"},\"ids\":\"4\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599462923);
INSERT INTO `fa_admin_log` VALUES (28, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599462923);
INSERT INTO `fa_admin_log` VALUES (29, 1, 'admin', '/admin.php/auth/rule/add?dialog=1', '权限管理 菜单规则 添加', '{\"dialog\":\"1\",\"__token__\":\"c185f06dd64c46b30586964f3a6ce35c\",\"row\":{\"ismenu\":\"1\",\"pid\":\"0\",\"name\":\"blacklist\",\"title\":\"\\u9ed1\\u540d\\u5355\\u7ba1\\u7406\",\"icon\":\"fa fa-address-book-o\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\",\"status\":\"normal\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599463132);
INSERT INTO `fa_admin_log` VALUES (30, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599463133);
INSERT INTO `fa_admin_log` VALUES (31, 1, 'admin', '/admin.php/auth/rule/add?dialog=1', '权限管理 菜单规则 添加', '{\"dialog\":\"1\",\"__token__\":\"14d1e4efd86cc943235496df19db4e8b\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"blacklist\\/index\",\"title\":\"\\u9ed1\\u540d\\u5355\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\",\"status\":\"normal\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599463317);
INSERT INTO `fa_admin_log` VALUES (32, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599463318);
INSERT INTO `fa_admin_log` VALUES (33, 1, 'admin', '/admin.php/auth/rule/edit/ids/86?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"efd2ffd7db01f65b8e4d8598b263f1b7\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"blacklist\\/index\",\"title\":\"\\u9ed1\\u540d\\u5355-T\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\\u56deT\\u7684\\u8fc7\\u6ee4\\u5355\",\"status\":\"normal\"},\"ids\":\"86\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599463571);
INSERT INTO `fa_admin_log` VALUES (34, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599463572);
INSERT INTO `fa_admin_log` VALUES (35, 1, 'admin', '/admin.php/auth/rule/edit/ids/86?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"c554e82811bd4d1c661e3a7304058a3e\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"SmsBlacklistReplyT\\/index\",\"title\":\"\\u9ed1\\u540d\\u5355-T\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\\u56deT\\u7684\\u8fc7\\u6ee4\\u5355\",\"status\":\"normal\"},\"ids\":\"86\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599466166);
INSERT INTO `fa_admin_log` VALUES (36, 1, 'admin', '/admin.php/auth/rule/edit/ids/86?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"d8aeb1d93a186ceba2a78ae1be1e1814\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"SmsBlacklistReplyT\\/index\",\"title\":\"\\u9ed1\\u540d\\u5355-T\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\\u56deT\\u7684\\u8fc7\\u6ee4\\u5355\",\"status\":\"normal\"},\"ids\":\"86\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599466174);
INSERT INTO `fa_admin_log` VALUES (37, 1, 'admin', '/admin.php/auth/rule/edit/ids/86?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"157982a810a022195a11331045566052\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"smsblacklistreplyt\\/index\",\"title\":\"\\u9ed1\\u540d\\u5355-T\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\\u56deT\\u7684\\u8fc7\\u6ee4\\u5355\",\"status\":\"normal\"},\"ids\":\"86\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599466200);
INSERT INTO `fa_admin_log` VALUES (38, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599466201);
INSERT INTO `fa_admin_log` VALUES (39, 1, 'admin', '/admin.php/auth/group/roletree', '权限管理 角色组', '{\"id\":\"2\",\"pid\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599466257);
INSERT INTO `fa_admin_log` VALUES (40, 1, 'admin', '/admin.php/auth/rule/edit/ids/86?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"70d1bd8e33a0f68124b5ecac5767927a\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"blacklist\\/smsblacklistreplyt\",\"title\":\"\\u9ed1\\u540d\\u5355-T\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\\u56deT\\u7684\\u8fc7\\u6ee4\\u5355\",\"status\":\"normal\"},\"ids\":\"86\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599466413);
INSERT INTO `fa_admin_log` VALUES (41, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599466414);
INSERT INTO `fa_admin_log` VALUES (42, 1, 'admin', '/admin.php/auth/rule/edit/ids/86?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"6559dd0aac01457ddf4d54236fd5d792\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"blacklist\\/blackreplyt\",\"title\":\"\\u9ed1\\u540d\\u5355-T\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\\u56deT\\u7684\\u8fc7\\u6ee4\\u5355\",\"status\":\"normal\"},\"ids\":\"86\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599468536);
INSERT INTO `fa_admin_log` VALUES (43, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599468538);
INSERT INTO `fa_admin_log` VALUES (44, 1, 'admin', '/admin.php/auth/rule/edit/ids/86?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"828a4e28f88a2251e7ac0d16f3b2ee9e\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"blacklist\\/blackreplyt\",\"title\":\"\\u9ed1\\u540d\\u5355-T\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\\u56deT\\u7684\\u8fc7\\u6ee4\\u5355\",\"status\":\"normal\"},\"ids\":\"86\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599468757);
INSERT INTO `fa_admin_log` VALUES (45, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599468758);
INSERT INTO `fa_admin_log` VALUES (46, 1, 'admin', '/admin.php/index/login', '登录', '{\"__token__\":\"77b18ca66f6b16e5f3f1e973dffacadd\",\"username\":\"admin\",\"captcha\":\"yqvn\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599468794);
INSERT INTO `fa_admin_log` VALUES (47, 1, 'admin', '/admin.php/auth/rule/del/ids/87', '权限管理 菜单规则 删除', '{\"action\":\"del\",\"ids\":\"87\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599469441);
INSERT INTO `fa_admin_log` VALUES (48, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599469441);
INSERT INTO `fa_admin_log` VALUES (49, 1, 'admin', '/admin.php/auth/rule/edit/ids/86?dialog=1', '权限管理 菜单规则 编辑', '{\"dialog\":\"1\",\"__token__\":\"1096de76aa30bb080aeaf9f5e3a0bd85\",\"row\":{\"ismenu\":\"1\",\"pid\":\"85\",\"name\":\"blacklist\\/black_reply_t\",\"title\":\"\\u9ed1\\u540d\\u5355-T\",\"icon\":\"fa fa-th-list\",\"weigh\":\"0\",\"condition\":\"\",\"remark\":\"\\u56deT\\u7684\\u8fc7\\u6ee4\\u5355\",\"status\":\"normal\"},\"ids\":\"86\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599469457);
INSERT INTO `fa_admin_log` VALUES (50, 1, 'admin', '/admin.php/index/index', '', '{\"action\":\"refreshmenu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599469458);
INSERT INTO `fa_admin_log` VALUES (51, 1, 'admin', '/admin.php/user/user/index', '会员管理 会员管理 查看', '{\"q_word\":[\"\"],\"pageNumber\":\"1\",\"pageSize\":\"10\",\"andOr\":\"AND\",\"orderBy\":[[\"nickname\",\"ASC\"]],\"searchTable\":\"tbl\",\"showField\":\"nickname\",\"keyField\":\"id\",\"searchField\":[\"nickname\"],\"nickname\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599469802);
INSERT INTO `fa_admin_log` VALUES (52, 1, 'admin', '/admin.php/user/user/index', '会员管理 会员管理 查看', '{\"q_word\":[\"\"],\"pageNumber\":\"1\",\"pageSize\":\"10\",\"andOr\":\"AND\",\"orderBy\":[[\"nickname\",\"ASC\"]],\"searchTable\":\"tbl\",\"showField\":\"nickname\",\"keyField\":\"id\",\"searchField\":[\"nickname\"],\"nickname\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599469805);
INSERT INTO `fa_admin_log` VALUES (53, 1, 'admin', '/admin.php/user/user/index', '会员管理 会员管理 查看', '{\"q_word\":[\"\"],\"pageNumber\":\"1\",\"pageSize\":\"10\",\"andOr\":\"AND\",\"orderBy\":[[\"nickname\",\"ASC\"]],\"searchTable\":\"tbl\",\"showField\":\"nickname\",\"keyField\":\"id\",\"searchField\":[\"nickname\"],\"nickname\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599469808);
INSERT INTO `fa_admin_log` VALUES (54, 1, 'admin', '/admin.php/user/user/index', '会员管理 会员管理 查看', '{\"q_word\":[\"\"],\"pageNumber\":\"1\",\"pageSize\":\"10\",\"andOr\":\"AND\",\"orderBy\":[[\"nickname\",\"ASC\"]],\"searchTable\":\"tbl\",\"showField\":\"nickname\",\"keyField\":\"id\",\"searchField\":[\"nickname\"],\"nickname\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599469837);
INSERT INTO `fa_admin_log` VALUES (55, 1, 'admin', '/admin.php/blacklist/black_reply_t/add?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416181\",\"remark\":\"\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599470267);
INSERT INTO `fa_admin_log` VALUES (56, 1, 'admin', '/admin.php/index/login?url=%2Fadmin.php', '登录', '{\"url\":\"\\/admin.php\",\"__token__\":\"5e2a6d0e6192cebe624b5b23f5e9e6f3\",\"username\":\"admin\",\"captcha\":\"knd3\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599530684);
INSERT INTO `fa_admin_log` VALUES (57, 1, 'admin', '/admin.php/blacklist/black_reply_t/edit/ids/1?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416182\",\"remark\":\"test\"},\"ids\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599535683);
INSERT INTO `fa_admin_log` VALUES (58, 1, 'admin', '/admin.php/blacklist/black_reply_t/edit/ids/1?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416182\",\"remark\":\"test\"},\"ids\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599535691);
INSERT INTO `fa_admin_log` VALUES (59, 1, 'admin', '/admin.php/blacklist/black_reply_t/edit/ids/1?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416182\",\"remark\":\"test\"},\"ids\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599535847);
INSERT INTO `fa_admin_log` VALUES (60, 1, 'admin', '/admin.php/blacklist/black_reply_t/edit/ids/1?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416182\",\"remark\":\"test\"},\"ids\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599535872);
INSERT INTO `fa_admin_log` VALUES (61, 1, 'admin', '/admin.php/blacklist/black_reply_t/edit/ids/1?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416182\",\"remark\":\"test\"},\"ids\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599535909);
INSERT INTO `fa_admin_log` VALUES (62, 1, 'admin', '/admin.php/blacklist/black_reply_t/edit/ids/2?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416182\",\"remark\":\"test\"},\"ids\":\"2\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599536385);
INSERT INTO `fa_admin_log` VALUES (74, 1, 'admin', '/admin.php/auth/adminlog/del/ids/73,72,70,69,68,67,66,65,64,63', '权限管理 管理员日志 删除', '{\"action\":\"del\",\"ids\":\"73,72,70,69,68,67,66,65,64,63\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599557287);
INSERT INTO `fa_admin_log` VALUES (75, 1, 'admin', '/admin.php/user/rule/multi/ids/12', '会员管理 会员规则 批量更新', '{\"action\":\"\",\"ids\":\"12\",\"params\":\"ismenu=1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599557398);
INSERT INTO `fa_admin_log` VALUES (76, 1, 'admin', '/admin.php/user/rule/multi/ids/12', '会员管理 会员规则 批量更新', '{\"action\":\"\",\"ids\":\"12\",\"params\":\"ismenu=0\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599557411);
INSERT INTO `fa_admin_log` VALUES (77, 1, 'admin', '/admin.php/blacklist/black_reply_t/add?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416182\",\"remark\":\"\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599617194);
INSERT INTO `fa_admin_log` VALUES (78, 1, 'admin', '/admin.php/blacklist/black_reply_t/add?dialog=1', '黑名单管理 黑名单-T', '{\"dialog\":\"1\",\"row\":{\"phone\":\"15665416183\",\"remark\":\"\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599617265);
INSERT INTO `fa_admin_log` VALUES (79, 1, 'admin', '/admin.php/blacklist/black_reply_t/del/ids/8', '黑名单管理 黑名单-T', '{\"action\":\"del\",\"ids\":\"8\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599617739);
INSERT INTO `fa_admin_log` VALUES (80, 1, 'admin', '/admin.php/blacklist/black_reply_t/del/ids/9', '黑名单管理 黑名单-T', '{\"action\":\"del\",\"ids\":\"9\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599617766);
INSERT INTO `fa_admin_log` VALUES (81, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599617904);
INSERT INTO `fa_admin_log` VALUES (82, 1, 'admin', '/admin.php/user/group/add?dialog=1', '会员管理 会员分组 添加', '{\"dialog\":\"1\",\"row\":{\"rules\":\"\",\"name\":\"\\u8fd0\\u8425\",\"status\":\"normal\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599618849);
INSERT INTO `fa_admin_log` VALUES (83, 1, 'admin', '/admin.php/user/group/del/ids/2', '会员管理 会员分组 删除', '{\"action\":\"del\",\"ids\":\"2\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599618873);
INSERT INTO `fa_admin_log` VALUES (84, 1, 'admin', '/admin.php/auth/group/roletree', '权限管理 角色组', '{\"pid\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599618899);
INSERT INTO `fa_admin_log` VALUES (85, 1, 'admin', '/admin.php/auth/group/roletree', '权限管理 角色组', '{\"id\":\"2\",\"pid\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599618910);
INSERT INTO `fa_admin_log` VALUES (86, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599618961);
INSERT INTO `fa_admin_log` VALUES (87, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599619679);
INSERT INTO `fa_admin_log` VALUES (88, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599619865);
INSERT INTO `fa_admin_log` VALUES (89, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599619888);
INSERT INTO `fa_admin_log` VALUES (90, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599619978);
INSERT INTO `fa_admin_log` VALUES (91, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599620069);
INSERT INTO `fa_admin_log` VALUES (92, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599620133);
INSERT INTO `fa_admin_log` VALUES (93, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599620207);
INSERT INTO `fa_admin_log` VALUES (94, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599620705);
INSERT INTO `fa_admin_log` VALUES (95, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599621788);
INSERT INTO `fa_admin_log` VALUES (96, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599621939);
INSERT INTO `fa_admin_log` VALUES (97, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599622724);
INSERT INTO `fa_admin_log` VALUES (98, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"csv\\u6d4b\\u8bd5\\u6587\\u4ef6.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599622841);
INSERT INTO `fa_admin_log` VALUES (99, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/eae83bf092d866b46fd2917893648f7e.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599622842);
INSERT INTO `fa_admin_log` VALUES (100, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631211);
INSERT INTO `fa_admin_log` VALUES (101, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/4b6a87d8ac2ad5b8fef2b68a236b7a43.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631212);
INSERT INTO `fa_admin_log` VALUES (102, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631272);
INSERT INTO `fa_admin_log` VALUES (103, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/4b6a87d8ac2ad5b8fef2b68a236b7a43.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631274);
INSERT INTO `fa_admin_log` VALUES (104, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631323);
INSERT INTO `fa_admin_log` VALUES (105, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/4b6a87d8ac2ad5b8fef2b68a236b7a43.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631325);
INSERT INTO `fa_admin_log` VALUES (106, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u670d\\u52a1\\u53f7\\u7801test.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631357);
INSERT INTO `fa_admin_log` VALUES (107, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/e9070c5063743f4cb6488367e904ec46.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631358);
INSERT INTO `fa_admin_log` VALUES (108, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631362);
INSERT INTO `fa_admin_log` VALUES (109, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/4b6a87d8ac2ad5b8fef2b68a236b7a43.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631363);
INSERT INTO `fa_admin_log` VALUES (110, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631613);
INSERT INTO `fa_admin_log` VALUES (111, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/4b6a87d8ac2ad5b8fef2b68a236b7a43.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631615);
INSERT INTO `fa_admin_log` VALUES (112, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631819);
INSERT INTO `fa_admin_log` VALUES (113, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/4b6a87d8ac2ad5b8fef2b68a236b7a43.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599631821);
INSERT INTO `fa_admin_log` VALUES (114, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599632075);
INSERT INTO `fa_admin_log` VALUES (115, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/4019bb6a94dfe30edd8afbe92045b610.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599632076);
INSERT INTO `fa_admin_log` VALUES (116, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599632744);
INSERT INTO `fa_admin_log` VALUES (117, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/f7ba3c01d6128f01d8015b19b385e9fc.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599632746);
INSERT INTO `fa_admin_log` VALUES (118, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599632855);
INSERT INTO `fa_admin_log` VALUES (119, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/075ec118335a46dcb55ca36fa6c30b4f.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599632857);
INSERT INTO `fa_admin_log` VALUES (120, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634000);
INSERT INTO `fa_admin_log` VALUES (121, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/075ec118335a46dcb55ca36fa6c30b4f.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634002);
INSERT INTO `fa_admin_log` VALUES (122, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634410);
INSERT INTO `fa_admin_log` VALUES (123, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/620fd02a9c950f5827f7a2d6f84f817d.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634412);
INSERT INTO `fa_admin_log` VALUES (124, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634462);
INSERT INTO `fa_admin_log` VALUES (125, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/620fd02a9c950f5827f7a2d6f84f817d.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634464);
INSERT INTO `fa_admin_log` VALUES (126, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634578);
INSERT INTO `fa_admin_log` VALUES (127, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634580);
INSERT INTO `fa_admin_log` VALUES (128, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634653);
INSERT INTO `fa_admin_log` VALUES (129, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634655);
INSERT INTO `fa_admin_log` VALUES (130, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355.csv\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599634892);
INSERT INTO `fa_admin_log` VALUES (131, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599635500);
INSERT INTO `fa_admin_log` VALUES (132, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599635502);
INSERT INTO `fa_admin_log` VALUES (133, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u670d\\u52a1\\u53f7\\u7801test.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599635749);
INSERT INTO `fa_admin_log` VALUES (134, 1, 'admin', '/admin.php/blacklist/black_reply_t/del/ids/159', '黑名单管理 黑名单-T', '{\"action\":\"del\",\"ids\":\"159\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636053);
INSERT INTO `fa_admin_log` VALUES (135, 1, 'admin', '/admin.php/blacklist/black_reply_t/del/ids/158', '黑名单管理 黑名单-T', '{\"action\":\"del\",\"ids\":\"158\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636087);
INSERT INTO `fa_admin_log` VALUES (136, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636099);
INSERT INTO `fa_admin_log` VALUES (137, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636101);
INSERT INTO `fa_admin_log` VALUES (138, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636236);
INSERT INTO `fa_admin_log` VALUES (139, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636238);
INSERT INTO `fa_admin_log` VALUES (140, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636365);
INSERT INTO `fa_admin_log` VALUES (141, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/a668f4b7d4137e364fd43af61b049430.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636366);
INSERT INTO `fa_admin_log` VALUES (142, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636390);
INSERT INTO `fa_admin_log` VALUES (143, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\",\"file\":\"\\/uploads\\/20200909\\/a668f4b7d4137e364fd43af61b049430.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636391);
INSERT INTO `fa_admin_log` VALUES (144, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636591);
INSERT INTO `fa_admin_log` VALUES (145, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599636634);
INSERT INTO `fa_admin_log` VALUES (146, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599638882);
INSERT INTO `fa_admin_log` VALUES (147, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599639132);
INSERT INTO `fa_admin_log` VALUES (148, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599639134);
INSERT INTO `fa_admin_log` VALUES (149, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599639176);
INSERT INTO `fa_admin_log` VALUES (150, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599639237);
INSERT INTO `fa_admin_log` VALUES (151, 1, 'admin', '/admin.php/blacklist/black_reply_t/del/ids/157', '黑名单管理 黑名单-T', '{\"action\":\"del\",\"ids\":\"157\",\"params\":\"\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599640172);
INSERT INTO `fa_admin_log` VALUES (152, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599640295);
INSERT INTO `fa_admin_log` VALUES (153, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599640401);
INSERT INTO `fa_admin_log` VALUES (154, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599640537);
INSERT INTO `fa_admin_log` VALUES (155, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599640560);
INSERT INTO `fa_admin_log` VALUES (156, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u670d\\u52a1\\u53f7\\u7801test.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599640590);
INSERT INTO `fa_admin_log` VALUES (157, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599641246);
INSERT INTO `fa_admin_log` VALUES (158, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"file\":\"\\/uploads\\/20200909\\/a668f4b7d4137e364fd43af61b049430.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599641247);
INSERT INTO `fa_admin_log` VALUES (159, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599641293);
INSERT INTO `fa_admin_log` VALUES (160, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599641460);
INSERT INTO `fa_admin_log` VALUES (161, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599641462);
INSERT INTO `fa_admin_log` VALUES (162, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599641581);
INSERT INTO `fa_admin_log` VALUES (163, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599641583);
INSERT INTO `fa_admin_log` VALUES (164, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642185);
INSERT INTO `fa_admin_log` VALUES (165, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642187);
INSERT INTO `fa_admin_log` VALUES (166, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642219);
INSERT INTO `fa_admin_log` VALUES (167, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642221);
INSERT INTO `fa_admin_log` VALUES (168, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642367);
INSERT INTO `fa_admin_log` VALUES (169, 1, 'admin', '/admin.php/blacklist/black_reply_t?addtabs=1', '黑名单管理 黑名单-T', '{\"addtabs\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642369);
INSERT INTO `fa_admin_log` VALUES (170, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642493);
INSERT INTO `fa_admin_log` VALUES (171, 1, 'admin', '/admin.php/blacklist/black_reply_t/import', '黑名单管理 黑名单-T', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642495);
INSERT INTO `fa_admin_log` VALUES (172, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642530);
INSERT INTO `fa_admin_log` VALUES (173, 1, 'admin', '/admin.php/blacklist/black_reply_t/import', '黑名单管理 黑名单-T', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642533);
INSERT INTO `fa_admin_log` VALUES (174, 1, 'admin', '/admin.php/general/attachment/add?dialog=1', '常规管理 附件管理 添加', '{\"dialog\":\"1\",\"row\":{\"local\":\"\",\"editor\":\"\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642718);
INSERT INTO `fa_admin_log` VALUES (175, 1, 'admin', '/admin.php/general/attachment/add?dialog=1', '常规管理 附件管理 添加', '{\"dialog\":\"1\",\"row\":{\"local\":\"\",\"editor\":\"\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642720);
INSERT INTO `fa_admin_log` VALUES (176, 1, 'admin', '/admin.php/general/attachment/add?dialog=1', '常规管理 附件管理 添加', '{\"dialog\":\"1\",\"row\":{\"local\":\"\",\"editor\":\"\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642721);
INSERT INTO `fa_admin_log` VALUES (177, 1, 'admin', '/admin.php/ajax/upload', '', '{\"name\":\"test.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599642741);
INSERT INTO `fa_admin_log` VALUES (178, 1, 'admin', '/admin.php/blacklist/black_reply_t/upload', '黑名单管理 黑名单-T', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599643380);
INSERT INTO `fa_admin_log` VALUES (179, 1, 'admin', '/admin.php/blacklist/black_reply_t/import', '黑名单管理 黑名单-T', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599643382);
INSERT INTO `fa_admin_log` VALUES (180, 1, 'admin', '/admin.php/index/login?url=%2Fadmin.php', '登录', '{\"url\":\"\\/admin.php\",\"__token__\":\"13abe63bf2cde631d91bbc1d3df91de0\",\"username\":\"admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 Safari/537.36', 1599705287);

-- ----------------------------
-- Table structure for fa_attachment
-- ----------------------------
DROP TABLE IF EXISTS `fa_attachment`;
CREATE TABLE `fa_attachment`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片帧数',
  `filesize` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `mimetype` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) NULL DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '附件表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_attachment
-- ----------------------------
INSERT INTO `fa_attachment` VALUES (1, 1, 0, '/assets/img/qrcode.png', '150', '150', 'png', 0, 21859, 'image/png', '', 1499681848, 1499681848, 1499681848, 'local', '17163603d0263e4838b9387ff2cd4877e8b018f6');
INSERT INTO `fa_attachment` VALUES (3, 1, 0, '/uploads/20200904/06bfe85432eb61690a73465a63e7896f.xlsx', '', '', 'xlsx', 0, 10258, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '{\"name\":\"test.xlsx\"}', 1599205994, 1599205994, 1599205994, 'local', '5d965c3dc7b7104814d186601ee29434bd238297');
INSERT INTO `fa_attachment` VALUES (4, 1, 0, '/uploads/20200909/e9070c5063743f4cb6488367e904ec46.xlsx', '', '', 'xlsx', 0, 8987, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '{\"name\":\"\\u670d\\u52a1\\u53f7\\u7801test.xlsx\"}', 1599635749, 1599635749, 1599635749, 'local', 'b8dc9f5676a4041f87368369b79f0f2f08f7fa22');
INSERT INTO `fa_attachment` VALUES (5, 1, 0, '/uploads/20200909/a668f4b7d4137e364fd43af61b049430.xlsx', '', '', 'xlsx', 0, 10307, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '{\"name\":\"\\u56deT\\u9ed1\\u540d\\u5355utf8.xlsx\"}', 1599636365, 1599636365, 1599636365, 'local', 'f7aec314c5bc175d343e97c1ec67328377c1580a');
INSERT INTO `fa_attachment` VALUES (6, 1, 0, '/uploads/20200909/06bfe85432eb61690a73465a63e7896f.xlsx', '', '', 'xlsx', 0, 10258, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '{\"name\":\"test.xlsx\"}', 1599642741, 1599642741, 1599642741, 'local', '5d965c3dc7b7104814d186601ee29434bd238297');

-- ----------------------------
-- Table structure for fa_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `fa_auth_group`;
CREATE TABLE `fa_auth_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父组别',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则ID',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '分组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_auth_group
-- ----------------------------
INSERT INTO `fa_auth_group` VALUES (1, 0, 'Admin group', '*', 1490883540, 149088354, 'normal');
INSERT INTO `fa_auth_group` VALUES (2, 1, 'Second group', '13,14,16,15,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,40,41,42,43,44,45,46,47,48,49,50,55,56,57,58,59,60,61,62,63,64,65,1,9,10,11,7,6,8,2,4,5', 1490883540, 1505465692, 'normal');
INSERT INTO `fa_auth_group` VALUES (3, 2, 'Third group', '1,4,9,10,11,13,14,15,16,17,40,41,42,43,44,45,46,47,48,49,50,55,56,57,58,59,60,61,62,63,64,65,5', 1490883540, 1502205322, 'normal');
INSERT INTO `fa_auth_group` VALUES (4, 1, 'Second group 2', '1,4,13,14,15,16,17,55,56,57,58,59,60,61,62,63,64,65', 1490883540, 1502205350, 'normal');
INSERT INTO `fa_auth_group` VALUES (5, 2, 'Third group 2', '1,2,6,7,8,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34', 1490883540, 1502205344, 'normal');

-- ----------------------------
-- Table structure for fa_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `fa_auth_group_access`;
CREATE TABLE `fa_auth_group_access`  (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '会员ID',
  `group_id` int(10) UNSIGNED NOT NULL COMMENT '级别ID',
  UNIQUE INDEX `uid_group_id`(`uid`, `group_id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '权限分组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_auth_group_access
-- ----------------------------
INSERT INTO `fa_auth_group_access` VALUES (1, 1);

-- ----------------------------
-- Table structure for fa_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `fa_auth_rule`;
CREATE TABLE `fa_auth_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否为菜单',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT 0 COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `weigh`(`weigh`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 87 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '节点表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_auth_rule
-- ----------------------------
INSERT INTO `fa_auth_rule` VALUES (1, 'file', 0, 'dashboard', 'Dashboard', 'fa fa-dashboard', '', 'Dashboard tips', 1, 1497429920, 1598941474, 143, 'normal');
INSERT INTO `fa_auth_rule` VALUES (2, 'file', 0, 'general', 'General', 'fa fa-cogs', '', '', 1, 1497429920, 1497430169, 137, 'normal');
INSERT INTO `fa_auth_rule` VALUES (3, 'file', 0, 'category', 'Category', 'fa fa-leaf', '', 'Category tips', 1, 1497429920, 1497429920, 119, 'normal');
INSERT INTO `fa_auth_rule` VALUES (4, 'file', 0, 'addon', '插件管理', 'fa fa-rocket', '', 'Addon tips', 1, 1502035509, 1599462923, 0, 'hidden');
INSERT INTO `fa_auth_rule` VALUES (5, 'file', 0, 'auth', 'Auth', 'fa fa-group', '', '', 1, 1497429920, 1497430092, 99, 'normal');
INSERT INTO `fa_auth_rule` VALUES (6, 'file', 2, 'general/config', 'Config', 'fa fa-cog', '', 'Config tips', 1, 1497429920, 1497430683, 60, 'normal');
INSERT INTO `fa_auth_rule` VALUES (7, 'file', 2, 'general/attachment', 'Attachment', 'fa fa-file-image-o', '', 'Attachment tips', 1, 1497429920, 1497430699, 53, 'normal');
INSERT INTO `fa_auth_rule` VALUES (8, 'file', 2, 'general/profile', 'Profile', 'fa fa-user', '', '', 1, 1497429920, 1497429920, 34, 'normal');
INSERT INTO `fa_auth_rule` VALUES (9, 'file', 5, 'auth/admin', 'Admin', 'fa fa-user', '', 'Admin tips', 1, 1497429920, 1497430320, 118, 'normal');
INSERT INTO `fa_auth_rule` VALUES (10, 'file', 5, 'auth/adminlog', 'Admin log', 'fa fa-list-alt', '', 'Admin log tips', 1, 1497429920, 1497430307, 113, 'normal');
INSERT INTO `fa_auth_rule` VALUES (11, 'file', 5, 'auth/group', 'Group', 'fa fa-group', '', 'Group tips', 1, 1497429920, 1599204003, 109, 'normal');
INSERT INTO `fa_auth_rule` VALUES (12, 'file', 5, 'auth/rule', 'Rule', 'fa fa-bars', '', 'Rule tips', 1, 1497429920, 1497430581, 104, 'normal');
INSERT INTO `fa_auth_rule` VALUES (13, 'file', 1, 'dashboard/index', 'View', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 136, 'normal');
INSERT INTO `fa_auth_rule` VALUES (14, 'file', 1, 'dashboard/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 135, 'normal');
INSERT INTO `fa_auth_rule` VALUES (15, 'file', 1, 'dashboard/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 133, 'normal');
INSERT INTO `fa_auth_rule` VALUES (16, 'file', 1, 'dashboard/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 134, 'normal');
INSERT INTO `fa_auth_rule` VALUES (17, 'file', 1, 'dashboard/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 132, 'normal');
INSERT INTO `fa_auth_rule` VALUES (18, 'file', 6, 'general/config/index', 'View', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 52, 'normal');
INSERT INTO `fa_auth_rule` VALUES (19, 'file', 6, 'general/config/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 51, 'normal');
INSERT INTO `fa_auth_rule` VALUES (20, 'file', 6, 'general/config/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 50, 'normal');
INSERT INTO `fa_auth_rule` VALUES (21, 'file', 6, 'general/config/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 49, 'normal');
INSERT INTO `fa_auth_rule` VALUES (22, 'file', 6, 'general/config/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 48, 'normal');
INSERT INTO `fa_auth_rule` VALUES (23, 'file', 7, 'general/attachment/index', 'View', 'fa fa-circle-o', '', 'Attachment tips', 0, 1497429920, 1497429920, 59, 'normal');
INSERT INTO `fa_auth_rule` VALUES (24, 'file', 7, 'general/attachment/select', 'Select attachment', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 58, 'normal');
INSERT INTO `fa_auth_rule` VALUES (25, 'file', 7, 'general/attachment/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 57, 'normal');
INSERT INTO `fa_auth_rule` VALUES (26, 'file', 7, 'general/attachment/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 56, 'normal');
INSERT INTO `fa_auth_rule` VALUES (27, 'file', 7, 'general/attachment/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 55, 'normal');
INSERT INTO `fa_auth_rule` VALUES (28, 'file', 7, 'general/attachment/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 54, 'normal');
INSERT INTO `fa_auth_rule` VALUES (29, 'file', 8, 'general/profile/index', 'View', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 33, 'normal');
INSERT INTO `fa_auth_rule` VALUES (30, 'file', 8, 'general/profile/update', 'Update profile', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 32, 'normal');
INSERT INTO `fa_auth_rule` VALUES (31, 'file', 8, 'general/profile/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 31, 'normal');
INSERT INTO `fa_auth_rule` VALUES (32, 'file', 8, 'general/profile/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 30, 'normal');
INSERT INTO `fa_auth_rule` VALUES (33, 'file', 8, 'general/profile/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 29, 'normal');
INSERT INTO `fa_auth_rule` VALUES (34, 'file', 8, 'general/profile/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 28, 'normal');
INSERT INTO `fa_auth_rule` VALUES (35, 'file', 3, 'category/index', 'View', 'fa fa-circle-o', '', 'Category tips', 0, 1497429920, 1497429920, 142, 'normal');
INSERT INTO `fa_auth_rule` VALUES (36, 'file', 3, 'category/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 141, 'normal');
INSERT INTO `fa_auth_rule` VALUES (37, 'file', 3, 'category/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 140, 'normal');
INSERT INTO `fa_auth_rule` VALUES (38, 'file', 3, 'category/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 139, 'normal');
INSERT INTO `fa_auth_rule` VALUES (39, 'file', 3, 'category/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 138, 'normal');
INSERT INTO `fa_auth_rule` VALUES (40, 'file', 9, 'auth/admin/index', 'View', 'fa fa-circle-o', '', 'Admin tips', 0, 1497429920, 1497429920, 117, 'normal');
INSERT INTO `fa_auth_rule` VALUES (41, 'file', 9, 'auth/admin/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 116, 'normal');
INSERT INTO `fa_auth_rule` VALUES (42, 'file', 9, 'auth/admin/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 115, 'normal');
INSERT INTO `fa_auth_rule` VALUES (43, 'file', 9, 'auth/admin/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 114, 'normal');
INSERT INTO `fa_auth_rule` VALUES (44, 'file', 10, 'auth/adminlog/index', 'View', 'fa fa-circle-o', '', 'Admin log tips', 0, 1497429920, 1497429920, 112, 'normal');
INSERT INTO `fa_auth_rule` VALUES (45, 'file', 10, 'auth/adminlog/detail', 'Detail', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 111, 'normal');
INSERT INTO `fa_auth_rule` VALUES (46, 'file', 10, 'auth/adminlog/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 110, 'normal');
INSERT INTO `fa_auth_rule` VALUES (47, 'file', 11, 'auth/group/index', 'View', 'fa fa-circle-o', '', 'Group tips', 0, 1497429920, 1497429920, 108, 'normal');
INSERT INTO `fa_auth_rule` VALUES (48, 'file', 11, 'auth/group/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 107, 'normal');
INSERT INTO `fa_auth_rule` VALUES (49, 'file', 11, 'auth/group/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 106, 'normal');
INSERT INTO `fa_auth_rule` VALUES (50, 'file', 11, 'auth/group/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 105, 'normal');
INSERT INTO `fa_auth_rule` VALUES (51, 'file', 12, 'auth/rule/index', 'View', 'fa fa-circle-o', '', 'Rule tips', 0, 1497429920, 1497429920, 103, 'normal');
INSERT INTO `fa_auth_rule` VALUES (52, 'file', 12, 'auth/rule/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 102, 'normal');
INSERT INTO `fa_auth_rule` VALUES (53, 'file', 12, 'auth/rule/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 101, 'normal');
INSERT INTO `fa_auth_rule` VALUES (54, 'file', 12, 'auth/rule/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 100, 'normal');
INSERT INTO `fa_auth_rule` VALUES (55, 'file', 4, 'addon/index', 'View', 'fa fa-circle-o', '', 'Addon tips', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (56, 'file', 4, 'addon/add', 'Add', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (57, 'file', 4, 'addon/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (58, 'file', 4, 'addon/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (59, 'file', 4, 'addon/downloaded', 'Local addon', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (60, 'file', 4, 'addon/state', 'Update state', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (63, 'file', 4, 'addon/config', 'Setting', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (64, 'file', 4, 'addon/refresh', 'Refresh', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (65, 'file', 4, 'addon/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (66, 'file', 0, 'user', 'User', 'fa fa-list', '', '', 1, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (67, 'file', 66, 'user/user', 'User', 'fa fa-user', '', '', 1, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (68, 'file', 67, 'user/user/index', 'View', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (69, 'file', 67, 'user/user/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (70, 'file', 67, 'user/user/add', 'Add', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (71, 'file', 67, 'user/user/del', 'Del', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (72, 'file', 67, 'user/user/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (73, 'file', 66, 'user/group', 'User group', 'fa fa-users', '', '', 1, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (74, 'file', 73, 'user/group/add', 'Add', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (75, 'file', 73, 'user/group/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (76, 'file', 73, 'user/group/index', 'View', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (77, 'file', 73, 'user/group/del', 'Del', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (78, 'file', 73, 'user/group/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (79, 'file', 66, 'user/rule', 'User rule', 'fa fa-circle-o', '', '', 1, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (80, 'file', 79, 'user/rule/index', 'View', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (81, 'file', 79, 'user/rule/del', 'Del', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (82, 'file', 79, 'user/rule/add', 'Add', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (83, 'file', 79, 'user/rule/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (84, 'file', 79, 'user/rule/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (85, 'file', 0, 'blacklist', '黑名单管理', 'fa fa-address-book-o', '', '', 1, 1599463132, 1599463132, 0, 'normal');
INSERT INTO `fa_auth_rule` VALUES (86, 'file', 85, 'blacklist/black_reply_t', '黑名单-T', 'fa fa-th-list', '', '回T的过滤单', 1, 1599463317, 1599469457, 0, 'normal');

-- ----------------------------
-- Table structure for fa_category
-- ----------------------------
DROP TABLE IF EXISTS `fa_category`;
CREATE TABLE `fa_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `flag` set('hot','index','recommend') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '自定义名称',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT 0 COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `weigh`(`weigh`, `id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '分类表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_category
-- ----------------------------
INSERT INTO `fa_category` VALUES (1, 0, 'page', '官方新闻', 'news', 'recommend', '/assets/img/qrcode.png', '', '', 'news', 1495262190, 1495262190, 1, 'normal');
INSERT INTO `fa_category` VALUES (2, 0, 'page', '移动应用', 'mobileapp', 'hot', '/assets/img/qrcode.png', '', '', 'mobileapp', 1495262244, 1495262244, 2, 'normal');
INSERT INTO `fa_category` VALUES (3, 2, 'page', '微信公众号', 'wechatpublic', 'index', '/assets/img/qrcode.png', '', '', 'wechatpublic', 1495262288, 1495262288, 3, 'normal');
INSERT INTO `fa_category` VALUES (4, 2, 'page', 'Android开发', 'android', 'recommend', '/assets/img/qrcode.png', '', '', 'android', 1495262317, 1495262317, 4, 'normal');
INSERT INTO `fa_category` VALUES (5, 0, 'page', '软件产品', 'software', 'recommend', '/assets/img/qrcode.png', '', '', 'software', 1495262336, 1499681850, 5, 'normal');
INSERT INTO `fa_category` VALUES (6, 5, 'page', '网站建站', 'website', 'recommend', '/assets/img/qrcode.png', '', '', 'website', 1495262357, 1495262357, 6, 'normal');
INSERT INTO `fa_category` VALUES (7, 5, 'page', '企业管理软件', 'company', 'index', '/assets/img/qrcode.png', '', '', 'company', 1495262391, 1495262391, 7, 'normal');
INSERT INTO `fa_category` VALUES (8, 6, 'page', 'PC端', 'website-pc', 'recommend', '/assets/img/qrcode.png', '', '', 'website-pc', 1495262424, 1495262424, 8, 'normal');
INSERT INTO `fa_category` VALUES (9, 6, 'page', '移动端', 'website-mobile', 'recommend', '/assets/img/qrcode.png', '', '', 'website-mobile', 1495262456, 1495262456, 9, 'normal');
INSERT INTO `fa_category` VALUES (10, 7, 'page', 'CRM系统 ', 'company-crm', 'recommend', '/assets/img/qrcode.png', '', '', 'company-crm', 1495262487, 1495262487, 10, 'normal');
INSERT INTO `fa_category` VALUES (11, 7, 'page', 'SASS平台软件', 'company-sass', 'recommend', '/assets/img/qrcode.png', '', '', 'company-sass', 1495262515, 1495262515, 11, 'normal');
INSERT INTO `fa_category` VALUES (12, 0, 'test', '测试1', 'test1', 'recommend', '/assets/img/qrcode.png', '', '', 'test1', 1497015727, 1497015727, 12, 'normal');
INSERT INTO `fa_category` VALUES (13, 0, 'test', '测试2', 'test2', 'recommend', '/assets/img/qrcode.png', '', '', 'test2', 1497015738, 1497015738, 13, 'normal');

-- ----------------------------
-- Table structure for fa_config
-- ----------------------------
DROP TABLE IF EXISTS `fa_config`;
CREATE TABLE `fa_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '变量值',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '变量字典数据',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '扩展属性',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统配置' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_config
-- ----------------------------
INSERT INTO `fa_config` VALUES (1, 'name', 'basic', 'Site name', '请填写站点名称', 'string', '我的网站32423324', '', 'required', '');
INSERT INTO `fa_config` VALUES (2, 'beian', 'basic', 'Beian', '粤ICP备15000000号-1', 'string', '', '', '', '');
INSERT INTO `fa_config` VALUES (3, 'cdnurl', 'basic', 'Cdn url', '如果静态资源使用第三方云储存请配置该值', 'string', '', '', '', '');
INSERT INTO `fa_config` VALUES (4, 'version', 'basic', 'Version', '如果静态资源有变动请重新配置该值', 'string', '1.0.1', '', 'required', '');
INSERT INTO `fa_config` VALUES (5, 'timezone', 'basic', 'Timezone', '', 'string', 'Asia/Shanghai', '', 'required', '');
INSERT INTO `fa_config` VALUES (6, 'forbiddenip', 'basic', 'Forbidden ip', '一行一条记录', 'text', '', '', '', '');
INSERT INTO `fa_config` VALUES (7, 'languages', 'basic', 'Languages', '', 'array', '{\"backend\":\"zh-cn\",\"frontend\":\"zh-cn\"}', '', 'required', '');
INSERT INTO `fa_config` VALUES (8, 'fixedpage', 'basic', 'Fixed page', '请尽量输入左侧菜单栏存在的链接', 'string', 'dashboard', '', 'required', '');
INSERT INTO `fa_config` VALUES (9, 'categorytype', 'dictionary', 'Category type', '', 'array', '{\"default\":\"Default\",\"page\":\"Page\",\"article\":\"Article\",\"test\":\"Test\"}', '', '', '');
INSERT INTO `fa_config` VALUES (10, 'configgroup', 'dictionary', 'Config group', '', 'array', '{\"basic\":\"Basic\",\"email\":\"Email\",\"dictionary\":\"Dictionary\",\"user\":\"User\",\"example\":\"Example\"}', '', '', '');
INSERT INTO `fa_config` VALUES (11, 'mail_type', 'email', 'Mail type', '选择邮件发送方式', 'select', '1', '[\"Please select\",\"SMTP\",\"Mail\"]', '', '');
INSERT INTO `fa_config` VALUES (12, 'mail_smtp_host', 'email', 'Mail smtp host', '错误的配置发送邮件会导致服务器超时', 'string', 'smtp.qq.com', '', '', '');
INSERT INTO `fa_config` VALUES (13, 'mail_smtp_port', 'email', 'Mail smtp port', '(不加密默认25,SSL默认465,TLS默认587)', 'string', '465', '', '', '');
INSERT INTO `fa_config` VALUES (14, 'mail_smtp_user', 'email', 'Mail smtp user', '（填写完整用户名）', 'string', '10000', '', '', '');
INSERT INTO `fa_config` VALUES (15, 'mail_smtp_pass', 'email', 'Mail smtp password', '（填写您的密码）', 'string', 'password', '', '', '');
INSERT INTO `fa_config` VALUES (16, 'mail_verify_type', 'email', 'Mail vertify type', '（SMTP验证方式[推荐SSL]）', 'select', '2', '[\"None\",\"TLS\",\"SSL\"]', '', '');
INSERT INTO `fa_config` VALUES (17, 'mail_from', 'email', 'Mail from', '', 'string', '10000@qq.com', '', '', '');

-- ----------------------------
-- Table structure for fa_ems
-- ----------------------------
DROP TABLE IF EXISTS `fa_ems`;
CREATE TABLE `fa_ems`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '事件',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '验证次数',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '邮箱验证码表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_ems
-- ----------------------------

-- ----------------------------
-- Table structure for fa_sms
-- ----------------------------
DROP TABLE IF EXISTS `fa_sms`;
CREATE TABLE `fa_sms`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '验证次数',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '短信验证码表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_sms
-- ----------------------------

-- ----------------------------
-- Table structure for fa_test
-- ----------------------------
DROP TABLE IF EXISTS `fa_test`;
CREATE TABLE `fa_test`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类ID(单选)',
  `category_ids` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类ID(多选)',
  `week` enum('monday','tuesday','wednesday') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '星期(单选):monday=星期一,tuesday=星期二,wednesday=星期三',
  `flag` set('hot','index','recommend') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标志(多选):hot=热门,index=首页,recommend=推荐',
  `genderdata` enum('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'male' COMMENT '性别(单选):male=男,female=女',
  `hobbydata` set('music','reading','swimming') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '爱好(多选):music=音乐,reading=读书,swimming=游泳',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `images` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片组',
  `attachfile` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附件',
  `keywords` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '省市',
  `json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '配置:key=名称,value=值',
  `price` float(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '价格',
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '点击',
  `startdate` date NULL DEFAULT NULL COMMENT '开始日期',
  `activitytime` datetime NULL DEFAULT NULL COMMENT '活动时间(datetime)',
  `year` year(4) NULL DEFAULT NULL COMMENT '年',
  `times` time NULL DEFAULT NULL COMMENT '时间',
  `refreshtime` int(10) NULL DEFAULT NULL COMMENT '刷新时间(int)',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) NULL DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT 0 COMMENT '权重',
  `switch` tinyint(1) NOT NULL DEFAULT 0 COMMENT '开关',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  `state` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态值:0=禁用,1=正常,2=推荐',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '测试表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_test
-- ----------------------------
INSERT INTO `fa_test` VALUES (1, 0, 12, '12,13', 'monday', 'hot,index', 'male', 'music,reading', '我是一篇测试文章', '<p>我是测试内容</p>', '/assets/img/avatar.png', '/assets/img/avatar.png,/assets/img/qrcode.png', '/assets/img/avatar.png', '关键字', '描述', '广西壮族自治区/百色市/平果县', '{\"a\":\"1\",\"b\":\"2\"}', 0.00, 0, '2017-07-10', '2017-07-10 18:24:45', 2017, '18:24:45', 1499682285, 1499682526, 1499682526, NULL, 0, 1, 'normal', '1');

-- ----------------------------
-- Table structure for fa_user
-- ----------------------------
DROP TABLE IF EXISTS `fa_user`;
CREATE TABLE `fa_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '组别ID',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `level` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '等级',
  `gender` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别',
  `birthday` date NULL DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '格言',
  `money` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '余额',
  `score` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '积分',
  `successions` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '连续登录天数',
  `maxsuccessions` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '最大连续登录天数',
  `prevtime` int(10) NULL DEFAULT NULL COMMENT '上次登录时间',
  `logintime` int(10) NULL DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '失败次数',
  `joinip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '加入IP',
  `jointime` int(10) NULL DEFAULT NULL COMMENT '加入时间',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Token',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态',
  `verification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE,
  INDEX `email`(`email`) USING BTREE,
  INDEX `mobile`(`mobile`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_user
-- ----------------------------
INSERT INTO `fa_user` VALUES (1, 1, 'admin', 'admin', 'c13f62012fd6a8fdf06b3452a94430e5', 'rpR6Bv', 'admin@163.com', '13888888888', '', 0, 0, '2017-04-15', '', 0.00, 0, 1, 1, 1516170492, 1516171614, '127.0.0.1', 0, '127.0.0.1', 1491461418, 0, 1516171614, '', 'normal', '');

-- ----------------------------
-- Table structure for fa_user_group
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_group`;
CREATE TABLE `fa_user_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '权限节点',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_user_group
-- ----------------------------
INSERT INTO `fa_user_group` VALUES (1, '默认组', '1,2,3,4,5,6,7,8,9,10,11,12', 1515386468, 1516168298, 'normal');

-- ----------------------------
-- Table structure for fa_user_money_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_money_log`;
CREATE TABLE `fa_user_money_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `money` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更余额',
  `before` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更前余额',
  `after` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更后余额',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员余额变动表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_user_money_log
-- ----------------------------

-- ----------------------------
-- Table structure for fa_user_rule
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_rule`;
CREATE TABLE `fa_user_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) NULL DEFAULT NULL COMMENT '父ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '名称',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '标题',
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `ismenu` tinyint(1) NULL DEFAULT NULL COMMENT '是否菜单',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NULL DEFAULT 0 COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员规则表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_user_rule
-- ----------------------------
INSERT INTO `fa_user_rule` VALUES (1, 0, 'index', '前台', '', 1, 1516168079, 1516168079, 1, 'normal');
INSERT INTO `fa_user_rule` VALUES (2, 0, 'api', 'API接口', '', 1, 1516168062, 1516168062, 2, 'normal');
INSERT INTO `fa_user_rule` VALUES (3, 1, 'user', '会员模块', '', 1, 1515386221, 1516168103, 12, 'normal');
INSERT INTO `fa_user_rule` VALUES (4, 2, 'user', '会员模块', '', 1, 1515386221, 1516168092, 11, 'normal');
INSERT INTO `fa_user_rule` VALUES (5, 3, 'index/user/login', '登录', '', 0, 1515386247, 1515386247, 5, 'normal');
INSERT INTO `fa_user_rule` VALUES (6, 3, 'index/user/register', '注册', '', 0, 1515386262, 1516015236, 7, 'normal');
INSERT INTO `fa_user_rule` VALUES (7, 3, 'index/user/index', '会员中心', '', 0, 1516015012, 1516015012, 9, 'normal');
INSERT INTO `fa_user_rule` VALUES (8, 3, 'index/user/profile', '个人资料', '', 0, 1516015012, 1516015012, 4, 'normal');
INSERT INTO `fa_user_rule` VALUES (9, 4, 'api/user/login', '登录', '', 0, 1515386247, 1515386247, 6, 'normal');
INSERT INTO `fa_user_rule` VALUES (10, 4, 'api/user/register', '注册', '', 0, 1515386262, 1516015236, 8, 'normal');
INSERT INTO `fa_user_rule` VALUES (11, 4, 'api/user/index', '会员中心', '', 0, 1516015012, 1516015012, 10, 'normal');
INSERT INTO `fa_user_rule` VALUES (12, 4, 'api/user/profile', '个人资料', '', 0, 1516015012, 1599557410, 3, 'normal');

-- ----------------------------
-- Table structure for fa_user_score_log
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_score_log`;
CREATE TABLE `fa_user_score_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `score` int(10) NOT NULL DEFAULT 0 COMMENT '变更积分',
  `before` int(10) NOT NULL DEFAULT 0 COMMENT '变更前积分',
  `after` int(10) NOT NULL DEFAULT 0 COMMENT '变更后积分',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员积分变动表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_user_score_log
-- ----------------------------

-- ----------------------------
-- Table structure for fa_user_token
-- ----------------------------
DROP TABLE IF EXISTS `fa_user_token`;
CREATE TABLE `fa_user_token`  (
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `createtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `expiretime` int(10) NULL DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员Token表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of fa_user_token
-- ----------------------------

-- ----------------------------
-- Table structure for sms_blacklist_reply_t
-- ----------------------------
DROP TABLE IF EXISTS `sms_blacklist_reply_t`;
CREATE TABLE `sms_blacklist_reply_t`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注',
  `admin_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '操作人',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT '1971-01-01 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '1正常，2移除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 335 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '黑名单表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sms_blacklist_reply_t
-- ----------------------------
INSERT INTO `sms_blacklist_reply_t` VALUES (4, '15665416184', 'test', '1', '1971-01-01 00:00:00', '2020-09-08 17:52:03', 2);
INSERT INTO `sms_blacklist_reply_t` VALUES (5, '15665416184', '这是一个测试手机号', '1', '1971-01-01 00:00:00', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (6, '15665416182', '这也是一个测试手机号', '1', '1971-01-01 00:00:00', '2020-09-08 17:52:06', 2);
INSERT INTO `sms_blacklist_reply_t` VALUES (7, '15665416186', '这也是一个测试手机号', '1', '2020-09-08 16:05:35', '2020-09-08 16:05:35', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (10, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (11, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (12, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (13, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (14, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (15, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (16, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (17, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (18, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (19, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (20, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (21, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (22, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (23, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (24, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (25, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (26, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (27, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (28, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (29, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (30, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (31, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (32, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (33, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (34, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (35, '', '', '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (36, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (37, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (38, '', '', '1', '2020-09-09 14:00:11', '2020-09-09 14:04:04', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (39, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (40, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (41, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (42, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (43, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (44, '', NULL, '1', '2020-09-09 14:00:11', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (45, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (46, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (47, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (48, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (49, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (50, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (51, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (52, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (53, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (54, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (55, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (56, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (57, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (58, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (59, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (60, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (61, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (62, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (63, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (64, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (65, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (66, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (67, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (68, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (69, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (70, '', '', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (71, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (72, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (73, '', ' \0\0_rels/.relsPK\0', '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (74, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (75, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (76, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (77, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (78, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (79, '', NULL, '1', '2020-09-09 14:01:12', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (80, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (81, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (82, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (83, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (84, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (85, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (86, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (87, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (88, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (89, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (90, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (91, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (92, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (93, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (94, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (95, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (96, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (97, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (98, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (99, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (100, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (101, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (102, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (103, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (104, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (105, '', '', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (106, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (107, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (108, '', ' \0\0_rels/.relsPK\0', '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (109, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (110, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (111, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (112, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (113, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (114, '', NULL, '1', '2020-09-09 14:02:03', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (115, '10000', NULL, '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (116, '10001', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (117, '10002', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (118, '10003', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (119, '10004', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (120, '10005', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (121, '10006', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (122, '10007', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (123, '10008', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (124, '10009', '一些简介', '1', '2020-09-09 14:02:37', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (125, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (126, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (127, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (128, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (129, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (130, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (131, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (132, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (133, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (134, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (135, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (136, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (137, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (138, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (139, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (140, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (141, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (142, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (143, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (144, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (145, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (146, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (147, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (148, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (149, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (150, '', '', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (151, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (152, '', NULL, '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (153, '', ' \0\0_rels/.relsPK\0', '1', '2020-09-09 14:02:42', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (154, '', '1', '1', '2020-09-09 14:02:42', '2020-09-09 15:11:02', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (155, '', '1', '1', '2020-09-09 14:02:42', '2020-09-09 15:11:01', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (156, '', '1', '1', '2020-09-09 14:02:42', '2020-09-09 15:10:58', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (160, '15665411000', '备注1000', '1', '2020-09-09 14:53:30', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (161, '15665411001', '备注1001', '1', '2020-09-09 14:53:30', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (162, '15665411002', '备注1002', '1', '2020-09-09 14:53:30', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (163, '15665411003', '备注1003', '1', '2020-09-09 14:53:30', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (164, '15665411004', '备注1004', '1', '2020-09-09 14:53:30', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (165, '15665411005', '备注1005', '1', '2020-09-09 14:53:30', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (166, '15665411006', '备注1006', '1', '2020-09-09 14:53:30', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (167, '15665411000', '备注1000', '1', '2020-09-09 14:54:22', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (168, '15665411001', '备注1001', '1', '2020-09-09 14:54:22', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (169, '15665411002', '备注1002', '1', '2020-09-09 14:54:22', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (170, '15665411003', '备注1003', '1', '2020-09-09 14:54:22', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (171, '15665411004', '备注1004', '1', '2020-09-09 14:54:22', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (172, '15665411005', '备注1005', '1', '2020-09-09 14:54:22', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (173, '15665411006', '备注1006', '1', '2020-09-09 14:54:22', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (174, '15665411000', '备注1000', '1', '2020-09-09 14:56:18', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (175, '15665411001', '备注1001', '1', '2020-09-09 14:56:18', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (176, '15665411002', '备注1002', '1', '2020-09-09 14:56:18', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (177, '15665411003', '备注1003', '1', '2020-09-09 14:56:18', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (178, '15665411004', '备注1004', '1', '2020-09-09 14:56:18', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (179, '15665411005', '备注1005', '1', '2020-09-09 14:56:18', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (180, '15665411006', '备注1006', '1', '2020-09-09 14:56:18', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (181, '15665411000', '备注1000', '1', '2020-09-09 14:57:33', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (182, '15665411001', '备注1001', '1', '2020-09-09 14:57:33', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (183, '15665411002', '备注1002', '1', '2020-09-09 14:57:33', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (184, '15665411003', '备注1003', '1', '2020-09-09 14:57:33', '1971-01-01 00:00:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (185, '15665411004', '备注1004', '1', '2020-09-09 14:57:33', '2020-09-09 15:11:32', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (186, '15665411005', '备注1005', '1', '2020-09-09 14:57:33', '2020-09-09 15:11:30', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (187, '15665411006', '备注1006', '1', '2020-09-09 14:57:33', '2020-09-09 15:11:26', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (188, '15665411000', '备注1000', '1', '2020-09-09 15:11:40', '2020-09-09 15:11:40', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (189, '15665411001', '备注1001', '1', '2020-09-09 15:11:40', '2020-09-09 15:11:40', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (190, '15665411002', '备注1002', '1', '2020-09-09 15:11:40', '2020-09-09 15:11:40', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (191, '15665411003', '备注1003', '1', '2020-09-09 15:11:40', '2020-09-09 15:11:40', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (192, '15665411004', '备注1004', '1', '2020-09-09 15:11:40', '2020-09-09 15:11:40', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (193, '15665411005', '备注1005', '1', '2020-09-09 15:11:40', '2020-09-09 15:11:40', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (194, '15665411006', '备注1006', '1', '2020-09-09 15:11:40', '2020-09-09 15:11:40', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (195, '15665411000', '备注1000', '1', '2020-09-09 15:21:39', '2020-09-09 15:21:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (196, '15665411001', '备注1001', '1', '2020-09-09 15:21:39', '2020-09-09 15:21:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (197, '15665411002', '备注1002', '1', '2020-09-09 15:21:39', '2020-09-09 15:21:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (198, '15665411003', '备注1003', '1', '2020-09-09 15:21:39', '2020-09-09 15:21:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (199, '15665411004', '备注1004', '1', '2020-09-09 15:21:39', '2020-09-09 15:21:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (200, '15665411005', '备注1005', '1', '2020-09-09 15:21:39', '2020-09-09 15:21:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (201, '15665411006', '备注1006', '1', '2020-09-09 15:21:39', '2020-09-09 15:21:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (202, '15665411000', '备注1000', '1', '2020-09-09 15:23:56', '2020-09-09 15:23:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (203, '15665411001', '备注1001', '1', '2020-09-09 15:23:56', '2020-09-09 15:23:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (204, '15665411002', '备注1002', '1', '2020-09-09 15:23:56', '2020-09-09 15:23:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (205, '15665411003', '备注1003', '1', '2020-09-09 15:23:56', '2020-09-09 15:23:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (206, '15665411004', '备注1004', '1', '2020-09-09 15:23:56', '2020-09-09 15:23:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (207, '15665411005', '备注1005', '1', '2020-09-09 15:23:56', '2020-09-09 15:23:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (208, '15665411006', '备注1006', '1', '2020-09-09 15:23:56', '2020-09-09 15:23:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (209, '15665411000', '备注1000', '1', '2020-09-09 15:29:51', '2020-09-09 15:29:51', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (210, '15665411001', '备注1001', '1', '2020-09-09 15:29:51', '2020-09-09 15:29:51', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (211, '15665411002', '备注1002', '1', '2020-09-09 15:29:51', '2020-09-09 15:29:51', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (212, '15665411003', '备注1003', '1', '2020-09-09 15:29:51', '2020-09-09 15:29:51', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (213, '15665411004', '备注1004', '1', '2020-09-09 15:29:51', '2020-09-09 15:29:51', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (214, '15665411005', '备注1005', '1', '2020-09-09 15:29:51', '2020-09-09 15:29:51', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (215, '15665411006', '备注1006', '1', '2020-09-09 15:29:51', '2020-09-09 15:29:51', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (216, '15665411000', '备注1000', '1', '2020-09-09 15:30:34', '2020-09-09 15:30:34', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (217, '15665411001', '备注1001', '1', '2020-09-09 15:30:34', '2020-09-09 15:30:34', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (218, '15665411002', '备注1002', '1', '2020-09-09 15:30:34', '2020-09-09 15:30:34', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (219, '15665411003', '备注1003', '1', '2020-09-09 15:30:34', '2020-09-09 15:30:34', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (220, '15665411004', '备注1004', '1', '2020-09-09 15:30:34', '2020-09-09 15:30:34', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (221, '15665411005', '备注1005', '1', '2020-09-09 15:30:34', '2020-09-09 15:30:34', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (222, '15665411006', '备注1006', '1', '2020-09-09 15:30:34', '2020-09-09 15:30:34', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (223, '15665411000', '备注1000', '1', '2020-09-09 16:08:02', '2020-09-09 16:08:02', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (224, '15665411001', '备注1001', '1', '2020-09-09 16:08:02', '2020-09-09 16:08:02', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (225, '15665411002', '备注1002', '1', '2020-09-09 16:08:02', '2020-09-09 16:08:02', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (226, '15665411003', '备注1003', '1', '2020-09-09 16:08:02', '2020-09-09 16:08:02', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (227, '15665411004', '备注1004', '1', '2020-09-09 16:08:02', '2020-09-09 16:08:02', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (228, '15665411005', '备注1005', '1', '2020-09-09 16:08:02', '2020-09-09 16:08:02', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (229, '15665411006', '备注1006', '1', '2020-09-09 16:08:02', '2020-09-09 16:08:02', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (230, '15665411000', '备注1000', '1', '2020-09-09 16:12:11', '2020-09-09 16:12:11', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (231, '15665411001', '备注1001', '1', '2020-09-09 16:12:11', '2020-09-09 16:12:11', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (232, '15665411002', '备注1002', '1', '2020-09-09 16:12:11', '2020-09-09 16:12:11', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (233, '15665411003', '备注1003', '1', '2020-09-09 16:12:11', '2020-09-09 16:12:11', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (234, '15665411004', '备注1004', '1', '2020-09-09 16:12:11', '2020-09-09 16:12:11', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (235, '15665411005', '备注1005', '1', '2020-09-09 16:12:11', '2020-09-09 16:12:11', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (236, '15665411006', '备注1006', '1', '2020-09-09 16:12:11', '2020-09-09 16:12:11', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (237, '15665411000', '备注1000', '1', '2020-09-09 16:12:56', '2020-09-09 16:12:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (238, '15665411001', '备注1001', '1', '2020-09-09 16:12:56', '2020-09-09 16:12:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (239, '15665411002', '备注1002', '1', '2020-09-09 16:12:56', '2020-09-09 16:12:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (240, '15665411003', '备注1003', '1', '2020-09-09 16:12:56', '2020-09-09 16:12:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (241, '15665411004', '备注1004', '1', '2020-09-09 16:12:56', '2020-09-09 16:12:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (242, '15665411005', '备注1005', '1', '2020-09-09 16:12:56', '2020-09-09 16:12:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (243, '15665411006', '备注1006', '1', '2020-09-09 16:12:56', '2020-09-09 16:12:56', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (244, '15665411000', '备注1000', '1', '2020-09-09 16:13:57', '2020-09-09 16:13:57', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (245, '15665411001', '备注1001', '1', '2020-09-09 16:13:57', '2020-09-09 16:13:57', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (246, '15665411002', '备注1002', '1', '2020-09-09 16:13:57', '2020-09-09 16:13:57', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (247, '15665411003', '备注1003', '1', '2020-09-09 16:13:57', '2020-09-09 16:13:57', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (248, '15665411004', '备注1004', '1', '2020-09-09 16:13:57', '2020-09-09 16:13:57', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (249, '15665411005', '备注1005', '1', '2020-09-09 16:13:57', '2020-09-09 16:13:57', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (250, '15665411006', '备注1006', '1', '2020-09-09 16:13:57', '2020-09-09 16:13:57', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (251, '15665411000', '备注1000', '1', '2020-09-09 16:31:35', '2020-09-09 16:31:35', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (252, '15665411001', '备注1001', '1', '2020-09-09 16:31:35', '2020-09-09 16:31:35', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (253, '15665411002', '备注1002', '1', '2020-09-09 16:31:35', '2020-09-09 16:31:35', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (254, '15665411003', '备注1003', '1', '2020-09-09 16:31:35', '2020-09-09 16:31:35', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (255, '15665411004', '备注1004', '1', '2020-09-09 16:31:35', '2020-09-09 16:31:35', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (256, '15665411005', '备注1005', '1', '2020-09-09 16:31:35', '2020-09-09 16:31:35', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (257, '15665411006', '备注1006', '1', '2020-09-09 16:31:35', '2020-09-09 16:31:35', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (258, '15665411000', '备注1000', '1', '2020-09-09 16:48:13', '2020-09-09 16:48:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (259, '15665411001', '备注1001', '1', '2020-09-09 16:48:13', '2020-09-09 16:48:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (260, '15665411002', '备注1002', '1', '2020-09-09 16:48:13', '2020-09-09 16:48:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (261, '15665411003', '备注1003', '1', '2020-09-09 16:48:13', '2020-09-09 16:48:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (262, '15665411004', '备注1004', '1', '2020-09-09 16:48:13', '2020-09-09 16:48:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (263, '15665411005', '备注1005', '1', '2020-09-09 16:48:13', '2020-09-09 16:48:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (264, '15665411006', '备注1006', '1', '2020-09-09 16:48:13', '2020-09-09 16:48:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (265, '15665411000', '备注1000', '1', '2020-09-09 16:49:32', '2020-09-09 16:49:32', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (266, '15665411001', '备注1001', '1', '2020-09-09 16:49:32', '2020-09-09 16:49:32', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (267, '15665411002', '备注1002', '1', '2020-09-09 16:49:32', '2020-09-09 16:49:32', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (268, '15665411003', '备注1003', '1', '2020-09-09 16:49:32', '2020-09-09 16:49:32', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (269, '15665411004', '备注1004', '1', '2020-09-09 16:49:32', '2020-09-09 16:49:32', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (270, '15665411005', '备注1005', '1', '2020-09-09 16:49:32', '2020-09-09 16:49:32', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (271, '15665411006', '备注1006', '1', '2020-09-09 16:49:32', '2020-09-09 16:49:32', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (272, '15665411000', '备注1000', '1', '2020-09-09 16:50:12', '2020-09-09 16:50:12', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (273, '15665411001', '备注1001', '1', '2020-09-09 16:50:12', '2020-09-09 16:50:12', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (274, '15665411002', '备注1002', '1', '2020-09-09 16:50:12', '2020-09-09 16:50:12', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (275, '15665411003', '备注1003', '1', '2020-09-09 16:50:12', '2020-09-09 16:50:12', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (276, '15665411004', '备注1004', '1', '2020-09-09 16:50:12', '2020-09-09 16:50:12', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (277, '15665411005', '备注1005', '1', '2020-09-09 16:50:12', '2020-09-09 16:50:12', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (278, '15665411006', '备注1006', '1', '2020-09-09 16:50:12', '2020-09-09 16:50:12', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (279, '15665411000', '备注1000', '1', '2020-09-09 16:51:00', '2020-09-09 16:51:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (280, '15665411001', '备注1001', '1', '2020-09-09 16:51:00', '2020-09-09 16:51:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (281, '15665411002', '备注1002', '1', '2020-09-09 16:51:00', '2020-09-09 16:51:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (282, '15665411003', '备注1003', '1', '2020-09-09 16:51:00', '2020-09-09 16:51:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (283, '15665411004', '备注1004', '1', '2020-09-09 16:51:00', '2020-09-09 16:51:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (284, '15665411005', '备注1005', '1', '2020-09-09 16:51:00', '2020-09-09 16:51:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (285, '15665411006', '备注1006', '1', '2020-09-09 16:51:00', '2020-09-09 16:51:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (286, '15665411000', '备注1000', '1', '2020-09-09 16:53:01', '2020-09-09 16:53:01', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (287, '15665411001', '备注1001', '1', '2020-09-09 16:53:01', '2020-09-09 16:53:01', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (288, '15665411002', '备注1002', '1', '2020-09-09 16:53:01', '2020-09-09 16:53:01', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (289, '15665411003', '备注1003', '1', '2020-09-09 16:53:01', '2020-09-09 16:53:01', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (290, '15665411004', '备注1004', '1', '2020-09-09 16:53:01', '2020-09-09 16:53:01', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (291, '15665411005', '备注1005', '1', '2020-09-09 16:53:01', '2020-09-09 16:53:01', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (292, '15665411006', '备注1006', '1', '2020-09-09 16:53:01', '2020-09-09 16:53:01', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (293, '15665411000', '备注1000', '1', '2020-09-09 17:03:05', '2020-09-09 17:03:05', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (294, '15665411001', '备注1001', '1', '2020-09-09 17:03:05', '2020-09-09 17:03:05', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (295, '15665411002', '备注1002', '1', '2020-09-09 17:03:05', '2020-09-09 17:03:05', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (296, '15665411003', '备注1003', '1', '2020-09-09 17:03:05', '2020-09-09 17:03:05', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (297, '15665411004', '备注1004', '1', '2020-09-09 17:03:05', '2020-09-09 17:03:05', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (298, '15665411005', '备注1005', '1', '2020-09-09 17:03:05', '2020-09-09 17:03:05', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (299, '15665411006', '备注1006', '1', '2020-09-09 17:03:05', '2020-09-09 17:03:05', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (300, '15665411000', '备注1000', '1', '2020-09-09 17:03:39', '2020-09-09 17:03:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (301, '15665411001', '备注1001', '1', '2020-09-09 17:03:39', '2020-09-09 17:03:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (302, '15665411002', '备注1002', '1', '2020-09-09 17:03:39', '2020-09-09 17:03:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (303, '15665411003', '备注1003', '1', '2020-09-09 17:03:39', '2020-09-09 17:03:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (304, '15665411004', '备注1004', '1', '2020-09-09 17:03:39', '2020-09-09 17:03:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (305, '15665411005', '备注1005', '1', '2020-09-09 17:03:39', '2020-09-09 17:03:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (306, '15665411006', '备注1006', '1', '2020-09-09 17:03:39', '2020-09-09 17:03:39', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (307, '15665411000', '备注1000', '1', '2020-09-09 17:06:07', '2020-09-09 17:06:07', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (308, '15665411001', '备注1001', '1', '2020-09-09 17:06:07', '2020-09-09 17:06:07', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (309, '15665411002', '备注1002', '1', '2020-09-09 17:06:07', '2020-09-09 17:06:07', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (310, '15665411003', '备注1003', '1', '2020-09-09 17:06:07', '2020-09-09 17:06:07', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (311, '15665411004', '备注1004', '1', '2020-09-09 17:06:07', '2020-09-09 17:06:07', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (312, '15665411005', '备注1005', '1', '2020-09-09 17:06:07', '2020-09-09 17:06:07', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (313, '15665411006', '备注1006', '1', '2020-09-09 17:06:07', '2020-09-09 17:06:07', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (314, '15665411000', '备注1000', '1', '2020-09-09 17:08:13', '2020-09-09 17:08:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (315, '15665411001', '备注1001', '1', '2020-09-09 17:08:13', '2020-09-09 17:08:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (316, '15665411002', '备注1002', '1', '2020-09-09 17:08:13', '2020-09-09 17:08:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (317, '15665411003', '备注1003', '1', '2020-09-09 17:08:13', '2020-09-09 17:08:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (318, '15665411004', '备注1004', '1', '2020-09-09 17:08:13', '2020-09-09 17:08:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (319, '15665411005', '备注1005', '1', '2020-09-09 17:08:13', '2020-09-09 17:08:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (320, '15665411006', '备注1006', '1', '2020-09-09 17:08:13', '2020-09-09 17:08:13', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (321, '15665411000', '备注1000', '1', '2020-09-09 17:08:50', '2020-09-09 17:08:50', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (322, '15665411001', '备注1001', '1', '2020-09-09 17:08:50', '2020-09-09 17:08:50', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (323, '15665411002', '备注1002', '1', '2020-09-09 17:08:50', '2020-09-09 17:08:50', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (324, '15665411003', '备注1003', '1', '2020-09-09 17:08:50', '2020-09-09 17:08:50', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (325, '15665411004', '备注1004', '1', '2020-09-09 17:08:50', '2020-09-09 17:08:50', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (326, '15665411005', '备注1005', '1', '2020-09-09 17:08:50', '2020-09-09 17:08:50', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (327, '15665411006', '备注1006', '1', '2020-09-09 17:08:50', '2020-09-09 17:08:50', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (328, '15665411000', NULL, '1', '2020-09-09 17:23:00', '2020-09-09 17:23:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (329, '15665411001', NULL, '1', '2020-09-09 17:23:00', '2020-09-09 17:23:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (330, '15665411002', NULL, '1', '2020-09-09 17:23:00', '2020-09-09 17:23:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (331, '15665411003', NULL, '1', '2020-09-09 17:23:00', '2020-09-09 17:23:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (332, '15665411004', NULL, '1', '2020-09-09 17:23:00', '2020-09-09 17:23:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (333, '15665411005', NULL, '1', '2020-09-09 17:23:00', '2020-09-09 17:23:00', 1);
INSERT INTO `sms_blacklist_reply_t` VALUES (334, '15665411006', NULL, '1', '2020-09-09 17:23:00', '2020-09-09 17:23:00', 1);

SET FOREIGN_KEY_CHECKS = 1;
