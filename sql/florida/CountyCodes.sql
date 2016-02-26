CREATE TABLE `CountyCodes` (
  `CountyCode` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `CountyDescription` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`CountyCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
