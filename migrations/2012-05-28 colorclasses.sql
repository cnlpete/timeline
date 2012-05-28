CREATE TABLE IF NOT EXISTS `colorclasses` (
  `color_id` VARCHAR(14) NOT NULL,
  `css_code` TEXT NOT NULL,
  `description` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
