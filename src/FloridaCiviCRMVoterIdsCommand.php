<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GCG\votertools;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Description of FloridaCiviCRMCommand
 *
 * @author james
 */
class FloridaCiviCRMVoterIdsCommand  extends Command {
  public function __construct() {
    $this->voterService = new \GCG\votertools\VoterService();
    parent::__construct();
  }
  protected function configure() {
    $this->setName("florida:civicrmvoterids")
      ->setDescription("Output SQL updates for CiviCRM Florida Voter Data.")
      ->addArgument('dbname',InputArgument::REQUIRED,'What is the database connection name?')
      ->addOption('config',null,InputOption::VALUE_REQUIRED,'What is config folder?',false)
      ->setHelp("Usage: <info>php console.php florida:civicrmvoterids <env></info>");
  }
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->voterService->setConnectionName($input->getArgument('dbname'));
    if($input->getOption('config')) {
      $this->voterService->setConfigFolder($input->getOption('config'));
    } else {
      $this->voterService->setConfigFolder('/usr/local/etc/votertools');
    }
    // GetCiviCRMFloridaVoters.sql
    $output->writeln("anything");
    $result = $this->voterService->getVoterIds();
    $output->writeln(\json_encode($result));
  }
}
