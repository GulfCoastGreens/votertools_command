ALTER TABLE `Voters` DISABLE KEYS;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
SET AUTOCOMMIT = 0;

CREATE TEMPORARY TABLE IF NOT EXISTS `VotersTemp` (
  `county_code` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `voter_id` bigint(18) unsigned NOT NULL,
  `name_last` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name_suffix` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name_first` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name_middle` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `suppress_address` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `residence_address_line_1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `residence_address_line_2` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `residence_city` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `residence_state` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `residence_zipcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mailing_address_line_1` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mailing_address_line_2` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mailing_address_line_3` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mailing_city` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mailing_state` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mailing_zipcode` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mailing_country` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gender` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `race` bigint(18) unsigned DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `party_affiliation` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `precinct` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `precinct_group` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `precinct_split` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `precinct_suffix` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `voter_status` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `congressional_district` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `house_district` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `senate_district` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `county_commission_district` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `school_board_district` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `daytime_area_code` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `daytime_phone_number` varchar(7) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `daytime_phone_extension` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `export_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `VotersTemp` DISABLE KEYS;

SET @import_date = '{$filedate}';

LOAD DATA LOCAL INFILE "{$filename}"
    INTO TABLE `VotersTemp`
    FIELDS TERMINATED BY '\t'
    LINES TERMINATED BY '\n'
    (`county_code`,
  `voter_id`,
  `name_last`,
  `name_suffix`,
  `name_first`,
  `name_middle`,
  `suppress_address`,
  `residence_address_line_1`,
  `residence_address_line_2`,
  `residence_city`,
  `residence_state`,
  `residence_zipcode`,
  `mailing_address_line_1`,
  `mailing_address_line_2`,
  `mailing_address_line_3`,
  `mailing_city`,
  `mailing_state`,
  `mailing_zipcode`,
  `mailing_country`,
  `gender`,
  `race`,
  @Birth_Date,
  @Registration_Date,
  `party_affiliation`,
  `precinct`,
  `precinct_group`,
  `precinct_split`,
  `precinct_suffix`,
  `voter_status`,
  `congressional_district`,
  `house_district`,
  `senate_district`,
  `county_commission_district`,
  `school_board_district`,
  `daytime_area_code`,
  `daytime_phone_number`,
  `daytime_phone_extension`)
    SET `export_date` = STR_TO_DATE(@import_date,'%Y%m%d'),
    `birth_date` = STR_TO_DATE(@Birth_Date,'%m/%d/%Y'),
    `registration_date` = STR_TO_DATE(@Registration_Date,'%m/%d/%Y');

INSERT INTO gpfl.Voters SELECT * FROM VotersTemp;

DROP TEMPORARY TABLE IF EXISTS VotersTemp;

ALTER TABLE `Voters` ENABLE KEYS;
SET UNIQUE_CHECKS = 1;
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
