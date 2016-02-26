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
 * Description of FloridaCreateSchemaCommand
 *
 * @author james
 */
class FloridaCreateSchemaCommand extends Command {
    public function __construct() {
        $this->voterService = new \GCG\votertools\VoterService();
        parent::__construct();
    }
    protected function configure() {
        $this->setName("florida:schema")
            ->setDescription("Creates Schema for Florida Voter Data.")
            ->addArgument('fileName',InputArgument::OPTIONAL,'What is the roles filename?')
            ->addOption('type',null,InputOption::VALUE_REQUIRED,'Provide the system type')
            ->addOption('in',null,InputOption::VALUE_NONE,'Provide STDIN')
            ->addOption('db',null,InputOption::VALUE_REQUIRED,'Provide a specific database name')                
            ->setHelp("Usage: <info>php console.php import:roles <env></info>");
    }
    protected function execute(InputInterface $input, OutputInterface $output) {
        // $output->writeln($this->countyCodes());
        // $output->writeln($this->getSchemaFor("georgia/CountyCodes"));
        // $output->writeln($this->getSchemaFor("georgia/Voters"));
        $output->writeln($this->voterService->getSQLFor("florida/CountyCodes"));
    }
}
