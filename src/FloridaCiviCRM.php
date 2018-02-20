<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GCG\votertools;

/**
 *
 * @author james
 */
trait FloridaCiviCRM {
  //put your code here
  public function getVoterIds() {
    $votertable = $this->config['voter']['florida']['civicrm']['tablename'];
    $voterregistrationIDfield = \array_flip($this->config['voter']['florida']['civicrm']['voterFieldMap'])[$this->config['voter']['florida']['civicrm']['voterIDfield']];
    $voterids = [];
//    if($voters = $this->getConnection($this->connectionName)->select($votertable,[
//      "$voterregistrationIDfield"
//    ], 
//    Medoo::raw('WHERE LENGTH(<' . $voterregistrationIDfield . '>) > 5'))) {
//      return [$voters];
//    } else {
//      return [];
//    }
    return [ $votertable, $voterregistrationIDfield ];
  }
}
