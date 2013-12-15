CREATE DATABASE IF NOT EXISTS `lottery` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `shishicai` (
    `item_date` varchar(100) NOT NULL DEFAULT '', 
    `item_code` varchar(100) NOT NULL DEFAULT '', 
    PRIMARY KEY(`item_date`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `shishicai` ADD COLUMN `recent_300_v1` varchar(100) NOT NULL DEFAULT '',ADD COLUMN `recent_300_v2` varchar(100) NOT NULL DEFAULT '';