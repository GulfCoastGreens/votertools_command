CREATE TABLE IF NOT EXISTS `Parties` (
  `Code` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `ShortName` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SELECT * FROM gpfl.Parties;