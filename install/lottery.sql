CREATE DATABASE IF NOT EXISTS `lottery` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

CREATE TABLE `shishicai` (
  `item_date` varchar(100) NOT NULL DEFAULT '',
  `item_code` varchar(100) NOT NULL DEFAULT '',
  `recent_300_v1` varchar(100) NOT NULL DEFAULT '',
  `recent_300_v2` varchar(100) NOT NULL DEFAULT '',
  `odd_recent_300_v2` varchar(100) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `hit_v1` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `shishicai_codes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `codes_desc` varchar(1024) NOT NULL DEFAULT '',
  `codes` varchar(8192) NOT NULL DEFAULT '',
  `max_miss` varchar(100) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;