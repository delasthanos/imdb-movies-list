CREATE TABLE `movies_list` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`moviename` varchar(255) NOT NULL,
	`imdb` varchar(255) NOT NULL DEFAULT '0',
	`yearmovie` smallint(5) unsigned NOT NULL,
	`rating` decimal(2,1)  NOT NULL,
	`enabled` tinyint(1) unsigned DEFAULT '1',

  PRIMARY KEY (`id`),
  UNIQUE KEY `imdb` (`imdb`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

