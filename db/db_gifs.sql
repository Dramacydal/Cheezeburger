/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : cheezeburger

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2016-07-18 20:32:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for animal_gifs
-- ----------------------------
DROP TABLE IF EXISTS `animal_gifs`;
CREATE TABLE `animal_gifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` text NOT NULL,
  `title` text NOT NULL,
  `text` text NOT NULL,
  `post_link` text NOT NULL,
  `tags` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `published` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `vk_id` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5801 DEFAULT CHARSET=utf8;
