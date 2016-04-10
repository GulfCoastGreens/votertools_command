LOAD DATA LOCAL INFILE '{$filename}'
    REPLACE INTO TABLE `Histories`
    FIELDS TERMINATED BY '\t'
    LINES TERMINATED BY '\n'
    (`county_code`,
    `voter_id`,
    @Election_Date,
    `election_type`,
    `history_code`)
    SET `export_date` = STR_TO_DATE('{$filedate}','%Y%m%d'),
    `election_date` = STR_TO_DATE(@Election_Date,'%m/%d/%Y');

