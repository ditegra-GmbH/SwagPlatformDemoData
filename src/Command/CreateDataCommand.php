<?php

declare(strict_types=1);

//How to create a command
//https://symfony.com/doc/current/console.html#creating-a-command
//https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/add-custom-commands.html

namespace Swag\PlatformDemoData\Command;
use Shopware\Core\Framework\Context;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

use Swag\PlatformDemoData\DemoDataServiceAiDecorator;
use Swag\PlatformDemoData\AiDataProvider\AiCategoryProvider;
use Swag\PlatformDemoData\OpenAi\GeneratorOpenAi;


#[AsCommand(name: 'dataai:create')]
class CreateDataCommand extends Command
{
    private DemoDataServiceAiDecorator $demoDataServiceAiDecorator;

    public function __construct(DemoDataServiceAiDecorator $demoDataServiceAiDecorator) {
        $this->demoDataServiceAiDecorator = $demoDataServiceAiDecorator;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $branche = $input->getArgument('branche');
        $apiKey = $input->getOption('apikey');
        $root = $input->getOption('root');
        $sub = $input->getOption('sub');

        $io->title('Generate Demo-Shop-Data with AI!');

        //User inputs
        if (!$apiKey) {
            $apiKey = $io->askHidden('Please enter OpenAI api key');
        }
        if (!$branche) {
            $branche = $io->ask('Please enter the Shop-branche');
        }
        if (!$root) {
            $root = $io->ask('Please enter the Amount of root-categories to generate', null, function (string|null $number): int {
                if (!is_numeric($number)) {
                    throw new \RuntimeException('You must type a number.');
                }
                return (int) $number;
            });
            if ($root == "") {
                $root = 0;
            }
        }
        if (!$sub) {
            $sub = $io->ask('Please enter the Amount of sub-categories to generate', null, function (string|null $number): int {
                if (!is_numeric($number)) {
                    throw new \RuntimeException('You must type a number.');
                }
                return (int) $number;
            });
            if ($sub == "") {
                $sub = 0;
            }
        }
        //Confirmations on input
        $io->info('Api-Key: ' . $apiKey . "\n" .
            'Shop-Branche: ' . $branche . "\n" .
            'Amount of Root-Categories: ' . $root . "\n" .
            'Amount of Sub-Categories: ' . $sub . "\n"
        );

        //check max amount of root- and subcategories
        //$io->info('Api Calls needed: '. ($root * $sub). "\n");

        //start processing
        GeneratorOpenAi::$apiKey = "customAPIkey"; //TODO: replace with getter/setter
        AiCategoryProvider::$rootAmount =  (int) $root;
        AiCategoryProvider::$subAmount = (int) $sub;
        AiCategoryProvider::$shopBranche = $branche;
        

        //execute generation
        $this->demoDataServiceAiDecorator->generate(Context::createDefaultContext());

        $io->info("Data successful created!");
        return Command::SUCCESS;
    }
    protected function configure(): void
    {
        $this
            ->addOption('apikey', null, InputOption::VALUE_REQUIRED, 'Your secrete Open API key')
            ->addOption('root', null, InputOption::VALUE_REQUIRED, 'The amount of root categories to generate')
            ->addOption('sub', null, InputOption::VALUE_REQUIRED, 'The amount of sub categories to generate')
            ->addArgument('branche', InputArgument::OPTIONAL, 'Shop branche of the Demoshop');
    }

}
