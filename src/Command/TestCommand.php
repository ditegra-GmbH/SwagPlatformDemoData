<?php

declare(strict_types=1);

//How to creat a command
//https://symfony.com/doc/current/console.html#creating-a-command
//https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/add-custom-commands.html

namespace Swag\PlatformDemoData\Command;

use Swag\PlatformDemoData\OpenAi\ServiceOpenAi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



#[AsCommand(name: 'dataai:test')]
class TestCommand extends Command
{
    private ServiceOpenAi $openAi;
   

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->openAi = new ServiceOpenAi();

        $categories = $this->openAi->generateCategories((int)"100" , "Autos");//Uses local test data for now
        //TODO: This categories needs to be usede by the CategoryProvider.php! 
        

        foreach($categories as $element){
            $output->writeln($element);
        }
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }

}
