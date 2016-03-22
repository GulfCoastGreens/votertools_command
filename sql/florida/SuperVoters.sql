DROP TABLE IF EXISTS KarenMorian.`District12Voters`;

CREATE TABLE IF NOT EXISTS KarenMorian.District12Voters 
    SELECT 
        *,
        DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),`Birth Date`)), '%Y')+0 AS age
    FROM FloridaVoterData.`Voters` 
    WHERE `House District`=12 AND `Export Date` = (SELECT `Export Date` FROM  FloridaVoterData.`Voters` GROUP BY `Export Date` ORDER BY `Export Date` DESC LIMIT 1);

ALTER TABLE `KarenMorian`.`District12Voters` CHANGE COLUMN `age` `age` DOUBLE(17,0) NULL DEFAULT NULL AFTER `Birth Date`;

DROP TABLE IF EXISTS KarenMorian.`District12 Super Voter Count`;

CREATE TABLE IF NOT EXISTS KarenMorian.`District12 Super Voter Count`  
SELECT dv.*, 
(
    SELECT 
        COUNT(DISTINCT `Election Date`) 
    FROM `FloridaVoterData`.`Histories` h 
    WHERE h.`Voter ID`=dv.`Voter ID` AND YEAR(h.`Election Date`)+0 >= 2008 AND `History Code` IN('Y','E','A','Z','F','P','B')
) AS `Super Voter Count` 
FROM KarenMorian.District12Voters dv;

ALTER TABLE `KarenMorian`.`District12 Super Voter Count` CHANGE COLUMN `Super Voter Count` `Super Voter Count` DOUBLE(17,0) NULL DEFAULT NULL AFTER `age`;

