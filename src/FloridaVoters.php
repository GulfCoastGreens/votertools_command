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
        
    }
    public function loadFloridaVoters($filedate,$filename) {
        $sql = \str_replace('{$filename}',$filename,\str_replace('{$filedate}', $filedate, $this->getSQLFor("florida/VoterLoadImport")));
        echo $sql."\n";
        \unlink($filename);
        
    }
}
