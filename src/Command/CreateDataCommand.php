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
use Swag\PlatformDemoData\AiDataProvider\AiProductProvider;
use Swag\PlatformDemoData\OpenAi\GeneratorOpenAi;


#[AsCommand(name: 'dataai:create')]
class CreateDataCommand extends Command
{
    private DemoDataServiceAiDecorator $demoDataServiceAiDecorator;

    public function __construct(DemoDataServiceAiDecorator $demoDataServiceAiDecorator)
    {
        $this->demoDataServiceAiDecorator = $demoDataServiceAiDecorator;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $branche = $input->getArgument('branche');
        $apiKey = $input->getOption('apikey');
        $root = $input->getOption('root');
        // $sub = $input->getOption('sub');
        // $product = $input->getOption('product');

        $io->title('Generate Demo-Shop-Data with AI!');

        //User inputs
        if (!$apiKey) {
            $apiKey = str_replace(" ","", $io->askHidden('Please enter OpenAI api key'));
        }
        if (!$branche) {
            $branche = $io->ask('Please enter the Shop-branche', null, function (string|null $text): string {
                if (is_null($text)) {
                    throw new \RuntimeException('You must enter a Shop-branche');
                }
                return $text;
            });
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
        /* Excluding under categories and products.
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
        if (!$product) {
            $product = $io->ask('Please enter the Amount of Products to be generate for every sub-category', null, function (string|null $number): int {
                if (!is_numeric($number)) {
                    throw new \RuntimeException('You must type a number.');
                }
                return (int) $number;
            });
            if ($product == "") {
                $product = 0;
            }
        }
        */

        //Confirmations on input
        $io->info(
            'Api-Key: ' . $apiKey . "\n" .
                'Shop-Branche: ' . $branche . "\n" .
                'Amount of root-categories: ' . $root . "\n" //.
                // 'Amount of sub-categories: ' . $sub . "\n" . 
                // 'Amount of products for every category: ' . $sub . "\n"
        );


        //start processing
        //Initializes first values.
        GeneratorOpenAi::setApiKey($apiKey);
        AiCategoryProvider::setShopBranche($branche);
        AiCategoryProvider::setRootAmount((int) $root);
         /*
        AiCategoryProvider::setSubAmount((int) $sub);
        AiProductProvider::setProductAmount((int) $product);
        */

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
            // ->addOption('sub', null, InputOption::VALUE_REQUIRED, 'The amount of sub categories to generate')
            // ->addOption('product',null, InputOption::VALUE_REQUIRED, 'Amount of products to generate for every Category')
            ->addArgument('branche', InputArgument::OPTIONAL, 'Shop branche of the Demoshop');
    }
}
