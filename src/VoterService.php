<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace gcg\votertools;

/**
 * Description of VoterService
 *
 * @author jam
 */
class VoterService {
    public $dbh;
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
