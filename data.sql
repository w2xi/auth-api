/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2021-03-16 21:50:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for api_token
-- ----------------------------
DROP TABLE IF EXISTS `api_token`;
CREATE TABLE `api_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `expire_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of api_token
-- ----------------------------
INSERT INTO `api_token` VALUES ('1', '1', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhcGkudzJ4aS5jb20iLCJhdWQiOiJ0ZXN0LmNvbSIsImlhdCI6MTYxNTgxMzg4NSwiZXhwIjoxNjE1ODE3NDg1LCJ1aWQiOjF9.B6xHyCnZU5m-zvO3SLtkypOssGcBOaLGNQTVDAIVccg', '1615817485');

-- ----------------------------
-- Table structure for blog
-- ----------------------------
DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `content` varchar(150) CHARACTER SET utf8 DEFAULT '',
  `images` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `create_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of blog
-- ----------------------------
INSERT INTO `blog` VALUES ('5', '1', 'test2', '[\"\\/uploads\\\\20210315\\\\6c1110f8a783dab56db8b8301434ab95.png\",\"\\/uploads\\\\20210315\\\\d30cec5c2adc877865d992914c9614e1.png\",\"\\/uploads\\\\20210315\\\\0cb54cd6dbcae8ebf32e125a751049c1.png\"]', '1615814020');
INSERT INTO `blog` VALUES ('6', '1', 'test2', '[\"\\/uploads\\\\20210315\\\\6c1110f8a783dab56db8b8301434ab95.png\",\"\\/uploads\\\\20210315\\\\d30cec5c2adc877865d992914c9614e1.png\",\"\\/uploads\\\\20210315\\\\0cb54cd6dbcae8ebf32e125a751049c1.png\"]', '1615814020');
INSERT INTO `blog` VALUES ('2', '1', '1212', '[\"\\/uploads\\\\20210315\\\\c0a192ef8b098113f147ab66c2986b33.png\",\"\\/uploads\\\\20210315\\\\021c1597e03082541cab461536db3c67.png\"]', '1615813949');
INSERT INTO `blog` VALUES ('3', '1', 'test1', '[\"\\/uploads\\\\20210315\\\\81ef2544112c2f1ee38f6a75d2c60017.png\",\"\\/uploads\\\\20210315\\\\fe5209901994c4d90a027a01fcfb687e.png\",\"\\/uploads\\\\20210315\\\\9fa051a8b0662ca15428474f5bdca2b5.png\",\"\\/uploads\\\\20210315\\\\f0a55a9643417360db7746b256b7f9b5.png\",\"\\/uploads\\\\20', '1615813972');
INSERT INTO `blog` VALUES ('4', '1', 'test2', '[\"\\/uploads\\\\20210315\\\\6c1110f8a783dab56db8b8301434ab95.png\",\"\\/uploads\\\\20210315\\\\d30cec5c2adc877865d992914c9614e1.png\",\"\\/uploads\\\\20210315\\\\0cb54cd6dbcae8ebf32e125a751049c1.png\"]', '1615814020');
INSERT INTO `blog` VALUES ('7', '1', 'hehe', '[\"\\/uploads\\\\20210315\\\\f5e3cee9658a3603701c52d0106520e1.png\",\"\\/uploads\\\\20210315\\\\062c9cc9cfc3d93ebcdb1d156761d7e2.png\"]', '1615814077');

-- ----------------------------
-- Table structure for blog_comment
-- ----------------------------
DROP TABLE IF EXISTS `blog_comment`;
CREATE TABLE `blog_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `content` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `create_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of blog_comment
-- ----------------------------

-- ----------------------------
-- Table structure for blog_like
-- ----------------------------
DROP TABLE IF EXISTS `blog_like`;
CREATE TABLE `blog_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `create_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of blog_like
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户密码',
  `salt` char(6) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `avatar` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `ip` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'w2xi', '4589736e9a9806815cf775bb2e4b20a7', 'bCjubB', '', '1615099460', '127.0.0.1');
