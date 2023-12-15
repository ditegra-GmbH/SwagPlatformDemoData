<?php

declare(strict_types=1);

//How to create a command
//https://symfony.com/doc/current/console.html#creating-a-command
//https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/add-custom-commands.html

namespace Swag\PlatformDemoData\Command;


use Shopware\Core\Framework\Context;
use Swag\PlatformDemoData\DemoDataServiceAiDecorator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



#[AsCommand(name: 'dataai:test')]
class TestCommand extends Command
{
    private string $apiSecret;
    private DemoDataServiceAiDecorator $demoDataServiceAiDecorator;

    public function __construct(DemoDataServiceAiDecorator $demoDataServiceAiDecorator) {
    
        $this->demoDataServiceAiDecorator = $demoDataServiceAiDecorator;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $rm = $input->getOption('rm');
        $mk = $input->getOption('mk');
        $apiKey = $input->getOption('api-key');

        if($rm){
            $this->demoDataServiceAiDecorator->delete(Context::createDefaultContext());//ask if that is right
        }
        if($mk){
            $this->demoDataServiceAiDecorator->generate(Context::createDefaultContext());//ask if that is right
        }
        //TODO: access Service here! Add the Commandlineinterface Input here!

        // $this->openAi = new GeneratorOpenAi();
        // $categories = $this->openAi->generateCategories((int)"100" , "Autos");//Uses local test data for now
        // foreach($categories as $element){
        //     $output->writeln($element);
        // }
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
    protected function configure(): void
    {
        $this
            ->addOption('api-key',null,InputOption::VALUE_REQUIRED, 'Your secrete Open API key','0')
            ->addOption('rm',null,InputOption::VALUE_NONE, 'Remove generated Data')
            ->addOption('mk',null,InputOption::VALUE_NONE, 'Creates Data');

    }
}
