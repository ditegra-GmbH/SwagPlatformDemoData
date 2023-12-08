<?php

declare(strict_types=1);

//How to creat a command
//https://symfony.com/doc/current/console.html#creating-a-command
//https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/add-custom-commands.html

namespace Swag\PlatformDemoData\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'dataai:create')]
class CreateDataCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // input shop branche and how maney categories, sub-categories and products should be created.
        // default values: 3 categories, 4 sub-categories, 5 products -> Maximum of 100 prompts!
        // creating data will start $this->container->get(DemoDataService::class)->generate($activateContext->getContext());
        // The generat function needs to be modified
        return Command::SUCCESS;
    }
}