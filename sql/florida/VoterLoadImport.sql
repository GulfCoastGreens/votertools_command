LOAD DATA LOCAL INFILE '{$filename}' 
    REPLACE INTO TABLE `Voters`
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
    SET `export_date` = STR_TO_DATE('{$filedate}','%Y%m%d'),
    `birth_date` = STR_TO_DATE(@Birth_Date,'%m/%d/%Y'),
    `registration_date` = STR_TO_DATE(@Registration_Date,'%m/%d/%Y');