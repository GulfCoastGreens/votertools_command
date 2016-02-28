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
    public function buildFloridaUpdateSQL($voterIds) {
        $value = $this->config->getItem('votertools', [
            'voter' => [ 
                'florida' => [
                    "civicrm" => [
                        'tablename' => 'civicrm_value_fl_voter_id_1',
                        'voterFieldMap' => []
                    ]
                ]
            ]
        ]);
        
        
    }
    
}
