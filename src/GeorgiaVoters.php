<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GCG\votertools;

/**
 * Description of GeorgiaVoters
 *
 * @author jam
 */
trait GeorgiaVoters {
    public function buildGeorgiaUpdateSQL($voterIds) {
        $value = $this->config->getItem('georgia', []);
        return \implode(";\n",\array_map(function($id) use ($value) {
            $voter = GeorgiaVoter::arrayToInstance($this->getConnection($this->connectionName)->get("Voters",\array_keys($value['voterFieldMap']), [
                "{$value['voterIDfield']}" => $id,
                'ORDER' => 'ExportDate DESC'
            ]));
            $setfields = [];
            foreach (\array_diff_key( $value['voterFieldMap'], \array_flip( [ $value['voterIDfield'] ] ) ) as $key => $val) {
                $setfields[] = \implode(" = ", ["`{$val}`",$this->getConnection($this->connectionName)->pdo->quote($voter->{$key})]);
            }
            // return $voter;
            $customGeorgiaVoterUpdate = "UPDATE `".$value['civicrmTable']."` SET " . \implode(", ", $setfields) . " WHERE `{$value['voterFieldMap'][$value['voterIDfield']]}` = ".$this->getConnection($this->connectionName)->pdo->quote($id);
            $setfields = [];
            foreach ($value['addressFieldMap'] as $key => $val) {                    
                $setfields[] = \implode(" = ", ["`{$val}`",$this->getConnection($this->connectionName)->pdo->quote($voter->{$key})]);
            }
            $setfields[] ="`state_province_id` = (SELECT id FROM `civicrm_state_province` WHERE `name` = 'Georgia' AND `country_id` = {$value['civicrmCountryID']} LIMIT 1)";
            $setfields[] = "`country_id` = {$value['civicrmCountryID']}";
            $county = $this->getConnection($this->connectionName)->query("SELECT `County Description` FROM `County Codes` WHERE `County Code` = '".$voter->{$value['voterCountyCodeField']}."' LIMIT 1")->fetchAll()[0];
            $setfields[] = "`county_id` = (SELECT `id` FROM `civicrm_county` WHERE `name` = '" . \trim($county['County Description']) . "' AND `state_province_id` = (SELECT id FROM `civicrm_state_province` WHERE `name`='Georgia' AND `country_id`={$value['civicrmCountryID']} LIMIT 1) LIMIT 1)";
            $customGeorgiaAddressUpdate = "UPDATE `civicrm_address` SET " . \implode(", ", $setfields) . " WHERE `contact_id` = (SELECT `entity_id` FROM `{$value['civicrmTable']}` WHERE `{$value['voterFieldMap'][$value['voterIDfield']]}` = ".$this->getConnection($this->connectionName)->pdo->quote($id). " LIMIT 1) AND `location_type_id` = (SELECT `id` FROM `civicrm_location_type` WHERE `name` = 'Home' LIMIT 1) ORDER BY `is_primary` DESC LIMIT 1";
            $setfields = [ "gender_id = (SELECT `id` FROM  `civicrm_option_value` WHERE `option_group_id` = (SELECT `id` FROM  `civicrm_option_group` WHERE `name` = 'gender') AND `name` = CASE '{$voter->{$value['voterGenderField']}}' WHEN 'M' THEN 'Male' WHEN 'F' THEN 'Female' ELSE NULL END LIMIT 1)" ];
            $customGeorgiaContactUpdate = "UPDATE `civicrm_contact` SET " . \implode(", ", $setfields) . " WHERE `gender_id` IS NULL AND `id` = (SELECT `entity_id` FROM `{$value['civicrmTable']}` WHERE `{$value['voterFieldMap'][$value['voterIDfield']]}` = ".$this->getConnection($this->connectionName)->pdo->quote($id). " LIMIT 1)";
            return \implode(";\n", [$customGeorgiaVoterUpdate,$customGeorgiaAddressUpdate, $customGeorgiaContactUpdate]);
        }, $voterIds));
    }

}
