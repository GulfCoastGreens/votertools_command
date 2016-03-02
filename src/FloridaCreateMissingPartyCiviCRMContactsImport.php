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
 * Description of FloridaCreateMissingPartyCiviCRMContactsImport
 *
 * @author jam
 */
class FloridaCreateMissingPartyCiviCRMContactsImport extends Command {
    public function __construct() {
        $this->voterService = new \GCG\votertools\VoterService();
        parent::__construct();
    }
    protected function configure() {
        $this->setName("florida:missingpartycontacts")
            ->setDescription("Imports Florida Voter Data from zip file.")
            ->addArgument('dbname',InputArgument::REQUIRED,'What is the database connection name?')
            ->addArgument('fileName',InputArgument::REQUIRED,'What is the json file name?')
            ->addOption('config',null,InputOption::VALUE_REQUIRED,'What is config folder?',false)
            ->addOption('tmp',null,InputOption::VALUE_REQUIRED,'What is config folder?',false)
            ->addOption('voterkey',null,InputOption::VALUE_REQUIRED,'What is key for json voter id?',false)
            ->addOption('partycode',null,InputOption::VALUE_REQUIRED,'What is voter party code?',false)
            ->setHelp("Usage: <info>php console.php florida:missingpartycontacts <env></info>");
    }
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->voterService->setConnectionName($input->getArgument('dbname'));
        if($input->getOption('config')) {
            $this->voterService->setConfigFolder($input->getOption('config'));
        } else {
            $this->voterService->setConfigFolder('/usr/local/etc/gcg/default');
        }
        
        $voterIds = \array_map(function($obj) use($input) {
            return $obj[$input->getOption('voterkey')];
        }, \json_decode(\file_get_contents($input->getArgument('fileName')), true));
        
        
        $this->voterService->buildFloridaMissingVoters($input->getOption('partycode'),$voterIds);
        
    }
}
