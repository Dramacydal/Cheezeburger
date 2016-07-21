/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : cheezeburger

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2016-07-18 20:32:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for animal_gifs_images
-- ----------------------------
DROP TABLE IF EXISTS `animal_gifs_images`;
CREATE TABLE `animal_gifs_images` (
  `post_id` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `title` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`post_id`,`link`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
