<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace gcg\votertools;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
/**
 * Description of GeorgiaCreateSchemaCommand
 *
 * @author jam
 */
class GeorgiaCreateSchemaCommand extends Command {
    public function __construct() {
        $this->voterService = new \GCG\votertools\VoterService();
        parent::__construct();
    }
    protected function configure() {
        $this->setName("georgia:schema")
            ->setDescription("Creates Schema for Georgia Voter Data.")
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
        $output->writeln($this->voterService->getSQLFor("georgia/CountyCodes"));
        
        if($filePath = $input->getArgument('fileName')) {
            if (\file_exists($filePath)) {
                $output->writeln("Importing roles from ".$input->getOption('type').": Started at ".date("F j, Y, g:i a"));
                // $output->writeln($this->voterService->parseFileByType($filePath,'roles',$input->getOption('type')));
                $output->writeln("Importing roles from ".$input->getOption('type').": Ended at ".date("F j, Y, g:i a"));
            } else {
                $output->writeln("ERROR: File does not exist!");
            }
        } else {
            // $output->writeln($this->voterService->importRole(\json_decode(\file_get_contents("php://stdin"),true))->encode());            
        }
    }
    public function getSchemaFor($filename) {
        //return file_get_contents(__DIR__ . "/../sql/georgia/". $filename .".sql");  
        return \file_get_contents("sql/". $filename .".sql");
    }
    public function countyCodes() {
        return file_get_contents(__DIR__ . "/../sql/georgia/CountyCodes.sql");
    } 
    public function voters() {
        return file_get_contents(__DIR__ . "/../sql/georgia/Voters.sql");
    } 
    
}
