<?php

declare(strict_types=1);

//How to create a command
//https://symfony.com/doc/current/console.html#creating-a-command
//https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/add-custom-commands.html

namespace Swag\PlatformDemoData\Command;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Swag\PlatformDemoData\AiDataProvider\AiCategoryProvider;
use Swag\PlatformDemoData\AiDataProvider\AiProductProvider;
use Swag\PlatformDemoData\DemoDataServiceAiDecorator;
use Swag\PlatformDemoData\OpenAi\GeneratorOpenAi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



#[AsCommand(name: 'dataai:test')]
class TestCommand extends Command
{
    private DemoDataServiceAiDecorator $demoDataServiceAiDecorator;
    private Connection $connection;

    public function __construct(DemoDataServiceAiDecorator $demoDataServiceAiDecorator, Connection $connection)
    {
        $this->connection = $connection;
        $this->demoDataServiceAiDecorator = $demoDataServiceAiDecorator;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $rm = $input->getOption('rm');
        $mk = $input->getOption('mk');
        $test = $input->getOption('test');
        $providerTest = $input->getOption('provider');
        $apiKey = $input->getOption('api-key');


        GeneratorOpenAi::$apiKey = "customAPIkey";
        
        AiCategoryProvider::$rootAmount = 0;
        AiCategoryProvider::$subAmount = 0; //When there is no int in the input, it will instant just output 0
        AiProductProvider::$productAmount = 1; 
        AiCategoryProvider::$shopBranche ="null";

        if ($rm) {
            // $this->demoDataServiceAiDecorator->delete(Context::createDefaultContext());//ask if that is right
            //TODO:Fix problem
            $this->connection->fetchOne(
                '
                DELETE FROM category;
                '
            );
        }
        if ($mk) {
            $rootAmount = $input->getOption('root');
            $subAmount = $input->getOption('sub');
            $shopBranche = $input->getArgument('branche');

            AiCategoryProvider::$rootAmount =  (int) $rootAmount;
            AiCategoryProvider::$subAmount = (int) $subAmount; //When there is no int in the input, it will instant just output 0
            AiCategoryProvider::$shopBranche = $shopBranche;

            $this->demoDataServiceAiDecorator->generate(Context::createDefaultContext());
            $output->writeln("Data successful created!");
        }

        if ($test) {
            $ai = new GeneratorOpenAi();
            $ai->generateRootCategories(5, "Autohaus");
            $ai->generateUnderCategories(5, "Kleinwagen");
        }
        if ($providerTest){
            $ai = new GeneratorOpenAi();
            $ai->generateRootCategories(1, "Autohaus");
            $ai->generateUnderCategories(1, "Kleinwagen");
            $ai->generateProducts(1,"Audi","Kleinwagen");
            $this->demoDataServiceAiDecorator->generate(Context::createDefaultContext());
            $output->writeln("Data successful created!");
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
            ->addOption('api-key', null, InputOption::VALUE_REQUIRED, 'Your secrete Open API key', '0')
            ->addOption('rm', null, InputOption::VALUE_NONE, 'Remove generated Data')
            ->addOption('test', null, InputOption::VALUE_NONE, 'test ai Outputs')
            ->addOption('mk', null, InputOption::VALUE_NONE, 'Creates Data')
            ->addOption('root', null, InputOption::VALUE_REQUIRED, 'The amount of root categories to generate', 1)
            ->addOption('sub', null, InputOption::VALUE_REQUIRED, 'The amount of sub categories to generate', 1)
            ->addOption('provider',null, InputOption::VALUE_NONE, 'Tests the usage of the provider.')
            ->addArgument('branche', InputArgument::OPTIONAL, 'Shop branche');
    }
}
