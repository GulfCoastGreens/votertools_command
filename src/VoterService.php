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
        $this->config = new \Configula\Config($folder); 
        parent::setConfigFolder($folder);
    }
    
    public function getSQLFor($filename) {
        return @\file_get_contents("sql/". $filename .".sql");
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

    
    
    
    
    
}
