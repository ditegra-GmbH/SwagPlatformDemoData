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
use Symfony\Component\Console\Style\SymfonyStyle;

use Orhanerday\OpenAi\OpenAi;

#[AsCommand(name: 'dataai:test')]
class TestCommand extends Command
{
  private OpenAI $openAi;

  private array $mokList = [
    "Autohersteller",
    "Klamotten",
    "Radio",
    "Schuhe",
    "E-Commerce-Agenturen",
    "Spiele Entwickler",
    "Einzelhandel Lebensmittel",
    "Elektronikeinzelhandel",
    "Möbel",
    "Bücher",
    "Spielzeug",
    "Sport- und Outdoor-Einzelhandel",
    "Hygiene"

  ];

  public function __construct(DemoDataServiceAiDecorator $demoDataServiceAiDecorator, Connection $connection)
  {
    parent::__construct();
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);
    $this->openAi = new OpenAi("");
  
    $number = 1;

    $io->title("Testing API Responses");
    $io->text("Question Text:;" . "AI Response:;");

    for ($i = 0; $i < count($this->mokList); $i++) {

      $msg = "Erstelle mir ein JSON-Array. Fülle dieses Array mit ". $number ." " . ($number === 1 ? "Stichwort"  : "Stichwörtern")   ." über " . $this->mokList[$i] . ".";
      $io->text($msg . ";" . $this->askAI($msg) . ";");


      $number = random_int(1,2);
    }

    return Command::SUCCESS;
  }
  protected function configure(): void
  {
  }

  private function askAI(string $msg): string
  {
    $response = $this->openAi->completion([
      'model' => "gpt-3.5-turbo-instruct", //"text-davinci-003" Deprecated. Will shutdown on January 2024
      'prompt' => $msg, //the question for the AI like hello or so
      'temperature' => 0.2, //0 means 100% predictability same answer every time on the same question.
      'max_tokens' => 140, //how many "Words" the answer and question has. I limit the tokens to about 200, the Question takes about 70 Tokens to process.
      'top_p' => 0.1, //diversity of answers
      'frequency_penalty' => 0.0,
      'presence_penalty' => 0.0,
    ]);

    $responseObj = json_decode($response, true);

    if (isset($responseObj['choices'][0]['text'])) {
      return  (string) $responseObj['choices'][0]['text'];
    }
    return preg_replace('/\s+/', '', $response);
  }
}
