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
