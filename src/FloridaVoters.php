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
    public function loadFloridaVoters($filedate,$filename) {
        $sql = \str_replace('{$filename}',$filename,\str_replace('{$filedate}', $filedate, $this->getSQLFor("florida/VoterLoadImport")));
        $stmt = $this->getConnection($this->connectionName)->pdo->prepare($sql,[\PDO::MYSQL_ATTR_LOCAL_INFILE => true]);
        if ($stmt->execute()) {
            echo "Success! Executed florida/VoterLoadImport against $filename \n";
        } else {
            print_r($stmt->errorInfo());
            echo "Failed executing florida/VoterLoadImport against $filename \n";
        }        
        \unlink($filename);
        
    }
}
