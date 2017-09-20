<?php
require 'vendor/autoload.php';

use App\Commands\CreateControllerCommand;
use Phpmig\Console\Command;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new CreateControllerCommand());
/*$application->add(new CreateMiddlewareCommand());
$application->add(new CreateModelCommand());
$application->add(new CreateScaffoldCommand());
$application->add(new MigrationGeneratorCommand());*/
$application->addCommands(array(
            new Command\InitCommand(),
            new Command\StatusCommand(),
            new Command\CheckCommand(),
            new Command\GenerateCommand(),
            new Command\UpCommand(),
            new Command\DownCommand(),
            new Command\MigrateCommand(),
            new Command\RollbackCommand(),
            new Command\RedoCommand()
        ));
$application->run();