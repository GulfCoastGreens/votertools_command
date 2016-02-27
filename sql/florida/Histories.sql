CREATE TABLE IF NOT EXISTS `Histories` (
  `county_code` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `voter_id` bigint(18) unsigned NOT NULL DEFAULT '0',
  `election_date` date NOT NULL,
  `election_type` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `history_code` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `export_date` date NOT NULL,
  PRIMARY KEY (`county_code`,`voter_id`,`election_date`,`election_type`,`history_code`),
  KEY `county_code` (`county_code`),
  KEY `voter_id` (`voter_id`),
  KEY `election_date` (`election_date`),
  KEY `election_type` (`election_type`),
  KEY `history_code` (`history_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
