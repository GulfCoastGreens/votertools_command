<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GCG\votertools;

/**
 * Description of FloridaVoters
 *
 * @author jam
 */
trait FloridaVoters {
    public $floridaNameFormatArray = [
        "name_last",
        "name_suffix",
        "name_first",
        "name_middle",        
    ];
    public $floridaFormatArray = [
        "suppress_address",
        "residence_address_line_1",
        "residence_address_line_2",
        "residence_city",
        "mailing_address_line_1",
        "mailing_address_line_2",
        "mailing_address_line_3",
        "mailing_city",        
    ];
    public function buildFloridaUpdateSQL($voterIds,$maxlines = 2000) {
        $rootfilename = "civicrmupdate";
        $filenumber = 1;
        $lines = 0;
        $fp = @fopen($rootfilename.$filenumber.'.sql', 'a'); // open or create the file for writing and append info
        foreach ($voterIds as $id) {
            $updatelines = [];
            $voterregistrationIDfield = \array_flip($this->config['voter']['florida']['civicrm']['voterFieldMap'])[$this->config['voter']['florida']['civicrm']['voterIDfield']];
            if($voter = $this->getConnection($this->connectionName)->get("Voters",\array_keys($this->config['voter']['florida']['civicrm']['voterFieldMap']), [
                "{$voterregistrationIDfield}" => $id,
                'ORDER' => 'export_date DESC'
            ])) {
                $setfields = [];
                foreach (\array_diff_key( $this->config['voter']['florida']['civicrm']['voterFieldMap'], \array_flip( [ $voterregistrationIDfield ] ) ) as $key => $val) {
                    if(\in_array($key,$this->floridaNameFormatArray)) {
                        $setfields[] = \implode(" = ", ["`{$val}`",$this->getConnection($this->connectionName)->pdo->quote(VoterService::titleCase($voter[$key]))]);                                    
                    } elseif (\in_array($key,$this->floridaFormatArray)) {
                        $setfields[] = \implode(" = ", ["`{$val}`",$this->getConnection($this->connectionName)->pdo->quote(VoterService::tidy($voter[$key]))]);                                    
                    } else {
                        $setfields[] = \implode(" = ", ["`{$val}`",$this->getConnection($this->connectionName)->pdo->quote($voter[$key])]);                
                    }
                }
                //  join(' Good \n', $this->config['voter']['florida']['civicrm']['voterFieldMap']);
                $updatelines[] = "UPDATE `".$this->config['voter']['florida']['civicrm']['tablename']."` SET " . \implode(", ", $setfields) . " WHERE `{$this->config['voter']['florida']['civicrm']['voterIDfield']}` = ".$this->getConnection($this->connectionName)->pdo->quote($id);
                
                
                $setfields = [];
                foreach(\array_intersect_key($this->config['voter']['florida']['civicrm']['voterFieldMap'], \array_flip(\array_merge($this->floridaFormatArray, [
                    "residence_zipcode",
                    "mailing_zipcode",
                ]))) as $key => $val) {
                    $setfields[] = "`{$val}`";
                }
                
                $sourcequery = "SELECT ".\implode(", ", $setfields)." FROM `".$this->config['voter']['florida']['civicrm']['tablename']."` WHERE `{$this->config['voter']['florida']['civicrm']['voterIDfield']}` = ".$this->getConnection($this->connectionName)->pdo->quote($id);
                
                $setfields = [];
                $setfields[] = \implode(" = ", ["ea.`{$this->config['voter']['florida']['civicrm']['voterFieldMap']['suppress_address']}`", $this->getConnection($this->connectionName)->pdo->quote('N')]);
                $setfields[] = \implode(" = ", ["ea.`{$this->config['voter']['florida']['civicrm']['voterIDfield']}`", $this->getConnection($this->connectionName)->pdo->quote($id)]);
                $wherequery = "WHERE ".\implode(" AND ", $setfields);
                $setfields = [];
                foreach([
                    'street_address' => $this->config['voter']['florida']['civicrm']['voterFieldMap']['residence_address_line_1'],
                    'supplemental_address_1'  => $this->config['voter']['florida']['civicrm']['voterFieldMap']['residence_address_line_2'],
                    'city'  => $this->config['voter']['florida']['civicrm']['voterFieldMap']['residence_city'],
                    'postal_code' => $this->config['voter']['florida']['civicrm']['voterFieldMap']['residence_zipcode']
                ] as $key => $val) {
                    if($key == 'postal_code') {
                        $setfields[] = \implode(" = ", ["a.`{$key}`","LEFT(ea.`{$val}`,5)"]);                                        
                        $setfields[] = \implode(" = ", ["a.`postal_code_suffix`","SUBSTRING(ea.`{$val}`,6)"]);                                                                
                    } else {
                        $setfields[] = \implode(" = ", ["a.`{$key}`","ea.`{$val}`"]);                                        
                    }
                }
                $setfields[] = \implode(" = ", ["a.`country_id`","(SELECT id FROM civicrm_country WHERE `iso_code` = 'US' LIMIT 1)"]);
                $setfields[] = \implode(" = ", ["a.`state_province_id`","(SELECT id FROM `civicrm_state_province` WHERE `abbreviation` = 'FL' AND `country_id` = (SELECT id FROM civicrm_country WHERE `iso_code` = 'US' LIMIT 1) LIMIT 1)"]);
                $setfields[] = \implode(" = ", ["a.`county_id`","(SELECT `id` FROM `civicrm_county` WHERE `abbreviation` = '" . \trim($voter['county_code']) . "' AND `state_province_id` = (SELECT id FROM `civicrm_state_province` WHERE `abbreviation` = 'FL' LIMIT 1) LIMIT 1)"]);
                
                $updatequery = "UPDATE `civicrm_address` a JOIN `".$this->config['voter']['florida']['civicrm']['tablename']."` AS ea ON ea.entity_id = a.contact_id AND a.`is_primary` = 1 AND a.`location_type_id` = (SELECT `id` FROM `civicrm_location_type` WHERE `name` = 'Home' LIMIT 1) SET " . \implode(", ", $setfields) . " ".$wherequery;
                
                $updatelines[] = $updatequery;

                $setfields = [];
//                $setfields[] = \implode(" = ", [ "`gender_id`", "(SELECT `id` FROM  `civicrm_option_value` WHERE `option_group_id` = (SELECT `id` FROM `civicrm_option_group` WHERE `name` LIKE 'gender') AND `name` LIKE CASE '{$voter['gender']}' WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE 'Unknown' END LIMIT 1)" ]);
                $setfields[] = \implode(" = ", [ "`gender_id`", "CASE '{$voter['gender']}' WHEN 'M' THEN 2 WHEN 'F' THEN 1 ELSE 4 END" ]);
                $setfields[] = \implode(" = ", ["`birth_date`",$this->getConnection($this->connectionName)->pdo->quote($voter['birth_date'])]);
                $updatelines[] = "UPDATE `civicrm_contact` SET " . \implode(", ", $setfields) . " WHERE  `id` IN(SELECT `entity_id` FROM `{$this->config['voter']['florida']['civicrm']['tablename']}` WHERE `{$this->config['voter']['florida']['civicrm']['voterIDfield']}` = ".$this->getConnection($this->connectionName)->pdo->quote($id). " )";
                
                if($lines+4 > $maxlines) {
                    $lines = 0;
                    fclose($fp); // close the file
                    
                    $zip = new \ZipArchive;        
                    if($zip->open($rootfilename.$filenumber.'.sql.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                      exit('cannot create zip');            
                    }
                    $zip->addFile($rootfilename.$filenumber.'.sql', $rootfilename.$filenumber.'.sql');
                    $zip->close();
                    \unlink($rootfilename.$filenumber.'.sql');
                    
                    $filenumber++;
                    $fp = @fopen($rootfilename.$filenumber.'.sql', 'a'); // open or create the file for writing and append info
                }
                // echo \implode(";\n", $updatelines).";\n\n";
                fputs($fp, \implode(";\n", $updatelines).";\n\n"); // write the data in the opened file
                $lines += 4;
            }
        }            
        fclose($fp); // close the file
        $zip = new \ZipArchive;        
        if($zip->open($rootfilename.$filenumber.'.sql.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
          exit('cannot create zip');            
        }
        $zip->addFile($rootfilename.$filenumber.'.sql', $rootfilename.$filenumber.'.sql');
        $zip->close();
        \unlink($rootfilename.$filenumber.'.sql');
    }
    
}
