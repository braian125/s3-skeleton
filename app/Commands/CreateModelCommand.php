<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModelCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('create:model')
            ->setDescription('Create an Model Class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name of the Class to Create'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        
        $directory = "app/Models/";

        $file = file_get_contents("app/Commands/resources/model_template.txt");

        $file = str_replace("!name", ucfirst($name), $file);

        if (is_dir($directory) && !is_writable($directory)) {
            $output->writeln('The "%s" directory is not writable');
            return;
        }

        if (!is_dir($directory)) {
            $dialog = $this->getHelperSet()->get('dialog');

            if (!$dialog->askConfirmation($output, '<question>Directory doesn\'t exist. Would you like to try to create it?</question>')) {
                return;
            }

            @mkdir($directory);
            if (!is_dir($directory)) {
                $output->writeln('<error>Couldn\'t create directory.</error>');
                return;
            }
        }

        if (!file_exists($directory.ucfirst($name).".php")) {
            $fh = fopen($directory . ucfirst($name) . ".php", "w");
            fwrite($fh, $file);
            fclose($fh);

            $className = ucfirst($name) . ".php";

            $output->writeln("Created $className in App\\Models");
        } else {
            $output->writeln("Model ".$className." already Exists!");
        }
    }
}
