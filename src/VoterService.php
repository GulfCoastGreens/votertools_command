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
        return \file_get_contents("sql/". $filename .".sql");
    }
    public function georgiaCreateSchema() {
        $stmt = $this->getConnection($this->connectionName)->pdo->prepare($this->getSQLFor("georgia/CountyCodes"));
        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Fail";
        }
    }
    public function initializeGeorgiaVoterTable() {
        $query = \file_get_contents("sql/GeorgiaVotersTable.sql");
        $stmt = $this->getConnection($this->connectionName)->pdo->prepare($query);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Fail";
        }
    }

    
    
    
    
    
    public function build($user,$passwd,$dsn) {
        $this->settings = parse_ini_file("settings.ini", true);
        $this->dbh = new PDO(
            $dsn, 
            $user, 
            $passwd,
            array(
                PDO::ATTR_PERSISTENT => true
            )
        );                
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    }
    public function __destruct() {
        $this->dbh = null;
        unset($this->settings);
    }
}
