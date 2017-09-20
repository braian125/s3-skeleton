<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateControllerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('create:controller')
            ->setDescription('Create an Controller Class')
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
        
        $directory = "app/Controller/";

        $file = file_get_contents("app/Commands/resources/controller_template.txt");

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

        if (!file_exists($directory.ucfirst($name)."Controller.php")) {
            $fh = fopen($directory . ucfirst($name) . "Controller.php", "w");
            fwrite($fh, $file);
            fclose($fh);


            $className = ucfirst($name) . "Controller.php";

            $controllers = array_filter(scandir('app/Controller'), function($item) {
                return !is_dir('app/Controller/' . $item);
            });
            $controllers = array_values($controllers);
            
            $content = "<?php\r\n";
            foreach ($controllers as $key => $value) {
                $name = explode('.', $value);
                if( $value <> "Controller.php"){
                    $content .="\r\n";
                    $content .= '$container["' . $name[0] . '"] = function($container) {';
                    $content .= "\r\n";
                    $content .= 'return new \App\Controller\ ' . $name[0] . '(';
                    $content .= '$container);';
                    $content .= "\r\n };";
                }
            }

            $fp = fopen("bootstrap/CallableControllers.php","wb");
            fwrite($fp,$content);
            fclose($fp);

            $output->writeln("Created $className in App\\Controller");
        } else {
            $output->writeln("Controller " . $name . " already Exists!");
        }
    }
}