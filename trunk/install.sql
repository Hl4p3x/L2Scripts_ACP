SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `auction`
-- ----------------------------
DROP TABLE IF EXISTS `auction`;
CREATE TABLE `auction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `item_type` int(11) NOT NULL,
  `item_enchant` int(11) NOT NULL,
  `item_grade` int(11) NOT NULL DEFAULT '0',
  `item_count` int(11) NOT NULL,
  `item_category` int(11) NOT NULL,
  `current_bid` int(11) NOT NULL,
  `bidder_id` int(11) NOT NULL DEFAULT '0',
  `buynow_price` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `approved` int(11) NOT NULL DEFAULT '0',
  `received_item` int(11) NOT NULL DEFAULT '0',
  `received_money` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `auction_log`
-- ----------------------------
DROP TABLE IF EXISTS `auction_log`;
CREATE TABLE `auction_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL,
  `bidder_id` int(11) NOT NULL,
  `bid_amount` int(11) NOT NULL,
  `bid_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `balance_log`
-- ----------------------------
DROP TABLE IF EXISTS `balance_log`;
CREATE TABLE `balance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(60) CHARACTER SET utf8 NOT NULL,
  `date` int(11) NOT NULL,
  `character` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `amount` decimal(10,0) NOT NULL,
  `balance` decimal(10,0) NOT NULL,
  `server_id` int(11) DEFAULT NULL,
  `ma_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `ip_log`
-- ----------------------------
DROP TABLE IF EXISTS `ip_log`;
CREATE TABLE `ip_log` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ma_id` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `master_account`
-- ----------------------------
DROP TABLE IF EXISTS `master_account`;
CREATE TABLE `master_account` (
  `ma_id` int(11) NOT NULL AUTO_INCREMENT,
  `master_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `master_password` varchar(128) CHARACTER SET utf8 NOT NULL,
  `email` varchar(256) CHARACTER SET utf8 NOT NULL,
  `suspended` tinyint(4) NOT NULL DEFAULT '0',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `forum_id` int(128) DEFAULT NULL,
  `pass_reset_token` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `support_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `spin_count` int(11) NOT NULL DEFAULT '0',
  `account_exp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ma_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `temp_register`
-- ----------------------------
DROP TABLE IF EXISTS `temp_register`;
CREATE TABLE `temp_register` (
  `id` int(11) NOT NULL,
  `login` varchar(60) CHARACTER SET utf8 NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `ticket`
-- ----------------------------
DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket` (
  `id` varchar(64) CHARACTER SET utf8 NOT NULL,
  `ma_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(3) NOT NULL,
  `server_id` int(11) NOT NULL,
  `account` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `character` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `create_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `ticket_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ticket_comment`;
CREATE TABLE `ticket_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` varchar(64) CHARACTER SET utf8 NOT NULL,
  `content` text NOT NULL,
  `commenter` varchar(255) CHARACTER SET utf8 NOT NULL,
  `post_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `vendor_txn_log`
-- ----------------------------
DROP TABLE IF EXISTS `vendor_txn_log`;
CREATE TABLE `vendor_txn_log` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(128) CHARACTER SET utf8 NOT NULL,
  `vendor` varchar(64) CHARACTER SET utf8 NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `ma_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- ----------------------------
-- Table structure for `vote_log`
-- ----------------------------
CREATE TABLE `vote_log` (
  `vote_id`  int NOT NULL AUTO_INCREMENT,
  `site_id`  int NOT NULL,
  `ma_id`  int NOT NULL,
  `ip`  varchar(255) NOT NULL,
  `vote_time`  int NOT NULL,
  `rewarded`  int NOT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
