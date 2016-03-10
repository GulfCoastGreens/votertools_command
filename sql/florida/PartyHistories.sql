CREATE TABLE IF NOT EXISTS `PartyHistories` (
  `county_code` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `voter_id` bigint(18) unsigned NOT NULL,
  `party_affiliation` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `export_date` date NOT NULL,
  PRIMARY KEY (`county_code`,`voter_id`,`party_affiliation`,`export_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
