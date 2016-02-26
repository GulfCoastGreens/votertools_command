CREATE TABLE IF NOT EXISTS `CountyCodes` (
  `CountyCode` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `CountyDescription` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`CountyCode`),
  UNIQUE KEY `CountyCode` (`CountyCode`) USING BTREE,
  UNIQUE KEY `CountyDescription` (`CountyDescription`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
