DROP TABLE IF EXISTS `#__mostpopular`;

CREATE TABLE `#__mostpopular` (	
       `id` BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
       `content_id` BIGINT NOT NULL,
       `last_updated` DATETIME NOT NULL,
       `1_day_stats` MEDIUMINT NOT NULL,
       `7_day_stats` MEDIUMINT NOT NULL,
       `30_day_stats` MEDIUMINT NOT NULL,
       `all_time_stats` BIGINT NOT NULL,
       `raw_stats` text NOT NULL);
