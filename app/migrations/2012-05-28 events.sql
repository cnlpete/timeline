CREATE TABLE IF NOT EXISTS `events` (
  `event_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(30) NOT NULL,
  `details` TEXT NOT NULL,
  `startdate` DATE NOT NULL,
  `enddate` DATE NOT NULL,
  `colorclass` VARCHAR(14) NOT NULL,
  `type` VARCHAR(10) NOT NULL,
  `image` VARCHAR(100) NOT NULL,
  `source` TEXT NOT NULL,
  PRIMARY KEY (`event_id`),
  INDEX `startdate` (`startdate`),
  INDEX `enddate` (`enddate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
