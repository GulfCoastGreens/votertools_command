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
    public function buildFloridaUpdateSQL($voterIds) {
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
                
                echo \implode(";\n", $updatelines).";\n\n";
            }
        }            
    }
    
}
