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
 * Description of FloridaVoterImportCommand
 *
 * @author jam
 */
class FloridaVoterImportCommand extends Command {
    //put your code here VoterLoadImport.sql
    public function __construct() {
        $this->voterService = new \GCG\votertools\VoterService();
        parent::__construct();
    }
    protected function configure() {
        $this->setName("florida:voterimport")
            ->setDescription("Imports Florida Voter Data from zip file.")
            ->addArgument('dbname',InputArgument::REQUIRED,'What is the database connection name?')
            ->addArgument('fileName',InputArgument::REQUIRED,'What is the zip file name?')
            ->addOption('config',null,InputOption::VALUE_REQUIRED,'What is config folder?',false)
            ->setHelp("Usage: <info>php console.php florida:voterimport <env></info>");
    }
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->voterService->setConnectionName($input->getArgument('dbname'));
        if($input->getOption('config')) {
            $this->voterService->setConfigFolder($input->getOption('config'));
        } else {
            $this->voterService->setConfigFolder('/usr/local/etc/gcg/default');
        }
        $filePath = $input->getArgument('fileName');
        $tempfile = \tempnam(\sys_get_temp_dir(), '');
        $output->writeln("Creating temp file to store unzipped voter file: ".$tempfile);
        $op = \fopen($tempfile, 'a');

        $zip = new \ZipArchive;
        $info = [];
        $voterDate = '';
        if ($zip->open($filePath) === TRUE) {
            for($i = 0; $i < $zip->numFiles; $i++) { 
                if(empty($voterDate)) {
                    $info = $zip->statIndex($i);                
                    $voterDate = date("Ymd", $info["mtime"]);
                    $output->writeln(date("Ymd", $info["mtime"]));
                }
                $output->writeln("Reading Voter File: ".$zip->getNameIndex($i));
                $fp = $zip->getStream($zip->getNameIndex($i));
                while (!feof($fp)) {
                    \fwrite($op, fread($fp, 2));
                }
                
            }
            $output->writeln("Extraced to ".$tempfile);
            $zip->close();
            fclose($op);
            chmod($tempfile, 0644);
            $output->writeln("Importing ".$tempfile." for ".$voterDate);
            $this->voterService->loadFloridaVoters($voterDate,$tempfile);
            $output->writeln("Cleaned up ".$tempfile);
        }
    }

    
}
