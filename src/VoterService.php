<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GCG\votertools;

/**
 * Description of VoterService
 *
 * @author jam
 */
class VoterService extends \GCG\Core\Connection {
    use VoterFormating;
    use GeorgiaVoters;
    use FloridaVoters;
    public $dbh;
    
    public $connectionName;
    public $config;
    public function setConnectionName($connectionName) {
        $this->connectionName = $connectionName;
    }
    public function setConfigFolder($folder) {
        $this->config = (new \Configula\Config($folder))->getItem('votertools', [
            'voter' => [ 
                'florida' => [
                    "civicrm" => [
                        'tablename' => 'civicrm_value_fl_voter_id_1',
                        'voterFieldMap' => []
                    ]
                ]
            ]
        ]);        
        parent::setConfigFolder($folder);
    }
    
    public function getSQLFor($filename) {
        return \file_get_contents(\dirname(__FILE__)."/../sql/". $filename .".sql");
    }
    public function createSchema($sqlfiles,$state) {
        foreach ($sqlfiles as $importFile) {
            $stmt = $this->getConnection($this->connectionName)->pdo->prepare($this->getSQLFor($state."/".$importFile));
            if ($stmt->execute()) {
                echo "Success! Executed $importFile \n";
            } else {
                echo "Failed executing $importFile \n";
            }
        }
    }
    public function loadRawData($filedate,$filename,$sqlFile) {
        echo "Using $sqlFile for LOAD DATA import\n";
        $sql = \str_replace('{$filename}',$filename,\str_replace('{$filedate}', $filedate, $this->getSQLFor($sqlFile)));
        $stmt = $this->getConnection($this->connectionName)->pdo->prepare($sql,[\PDO::MYSQL_ATTR_LOCAL_INFILE => true]);
        if ($stmt->execute()) {
            echo "Success! Executed $sqlFile against $filename \n";
        } else {
            print_r($stmt->errorInfo());
            echo "Failed executing $sqlFile against $filename \n";
        }        
        \unlink($filename);                
    }

    
    
    
    
    
}
