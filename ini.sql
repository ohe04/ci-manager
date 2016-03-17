/*
Navicat MySQL Data Transfer

Source Server         : 本機mysql
Source Server Version : 50137
Source Host           : localhost:3306
Source Database       : circle_point

Target Server Type    : MYSQL
Target Server Version : 50137
File Encoding         : 65001

Date: 2016-03-17 16:06:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `module`
-- ----------------------------
DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `code` varchar(40) NOT NULL,
  `classId` smallint(6) DEFAULT NULL,
  `index` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of module
-- ----------------------------

-- ----------------------------
-- Table structure for `module_class`
-- ----------------------------
DROP TABLE IF EXISTS `module_class`;
CREATE TABLE `module_class` (
  `classId` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `className` varchar(30) NOT NULL,
  `classIndex` smallint(6) NOT NULL,
  `classCode` varchar(80) NOT NULL,
  PRIMARY KEY (`classId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of module_class
-- ----------------------------

-- ----------------------------
-- Table structure for `rel_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `rel_user_role`;
CREATE TABLE `rel_user_role` (
  `userId` tinyint(4) NOT NULL,
  `roleId` tinyint(4) NOT NULL,
  PRIMARY KEY (`userId`,`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rel_user_role
-- ----------------------------
INSERT INTO `rel_user_role` VALUES ('1', '1');

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `roleId` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`roleId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '管理者');

-- ----------------------------
-- Table structure for `role_permission`
-- ----------------------------
DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE `role_permission` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `roleId` smallint(6) NOT NULL,
  `moduleId` smallint(6) NOT NULL,
  `view` bit(1) NOT NULL,
  `create` bit(1) NOT NULL,
  `edit` bit(1) NOT NULL,
  `delete` bit(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of role_permission
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userId` smallint(4) NOT NULL AUTO_INCREMENT,
  `account` varchar(15) NOT NULL,
  `password` varchar(200) NOT NULL,
  `isDelete` bit(1) NOT NULL,
  `cDate` datetime NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'dc75ffe147f8c827cb35e737dbc48369', '', '2015-09-30 11:25:30');

-- ----------------------------
-- Table structure for `user_permission`
-- ----------------------------
DROP TABLE IF EXISTS `user_permission`;
CREATE TABLE `user_permission` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `userId` smallint(6) NOT NULL,
  `moduleId` smallint(6) NOT NULL,
  `view` bit(1) NOT NULL,
  `create` bit(1) NOT NULL,
  `edit` bit(1) NOT NULL,
  `delete` bit(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_permission
-- ----------------------------
