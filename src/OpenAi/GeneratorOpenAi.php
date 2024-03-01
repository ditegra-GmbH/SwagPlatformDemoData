<?php

declare(strict_types=1);

namespace Swag\PlatformDemoData\OpenAi;

use Orhanerday\OpenAi\OpenAi;
use Faker;
use Exception;
use Generator;

class GeneratorOpenAi
{

  private OpenAI $openAi;
  

  private static string $apiKey; 

  public function __construct()
  {
    $this->openAi = new OpenAi(GeneratorOpenAi::$apiKey); 
  }

  public function generateRootCategories(int $amount, string $branche): array
  {
    $msg = $msg = "Erstelle mir ein JSON-Array. Fülle dieses Array mit ".  $amount ." " . ($amount === 1 ? "Stichwort"  : "Stichwörtern")   ." über " . $branche . ".";
    $categoriesList = $this->createDataAi($msg);
    return $categoriesList;
  }


  public function generateUnderCategories(int $amount, string $rootCategory): array{return [];}
  public function generateProducts(int $amount, string $subCategory, string $rootCategory): array{return [];}
  /* Excluding under categories and products.
  public function generateUnderCategories(int $amount, string $rootCategory): array
  {

    if ($amount > self::$maxUnderCategories) {
      print_r('Too many Sub-Categories are selected! (' . $amount . ') reducing to ' . self::$maxUnderCategories . ".\n");
      $amount = self::$maxUnderCategories;
    }

    $msg = 'Nenne für ' . $rootCategory . ' Markennamen, trennen die Kategorien mit ";". Schreibe nur die Markennamen auf. Die Markennamen sollen alle in einer Zeile sein. Erstelle keine Nummerierung. Erstelle nur ' . $amount . ' Kategorien!';
    $categoriesList = $this->createDataAi($msg);
    return $categoriesList;
  }
  public function generateProducts(int $amount, string $subCategory, string $rootCategory): array
  {

    if ($amount > self::$maxProductAmount) {
      print_r('Too many Products are selected! (' . $amount . ') reducing to ' . self::$maxProductAmount . ".\n");
      $amount = self::$maxProductAmount;
    }

    $msg = 'Nenne für ' . $rootCategory . " " . $subCategory . ' Produktnamen, trennen die Kategorien mit ";". Schreibe nur die Produktnamen auf. Die Produktnamen sollen alle in einer Zeile sein. Erstelle keine Nummerierung. Erstelle nur ' . $amount . ' Produktnamen!';
    $productList = $this->createDataAi($msg);
    return $productList;
  }
*/
  private function createDataAi(string $msg): array
  {

    $response = $this->openAi->completion([
      'model' => "gpt-3.5-turbo-instruct", //"text-davinci-003" Deprecated. Will shutdown on January 2024
      'prompt' => $msg, //the question for the AI like hello or so
      'temperature' => 0.2, //0 means 100% predictability same answer every time on the same question.
      'max_tokens' => 120, //how many "Words" the answer and question has. I limit the tokens to about 200, the Question takes about 70 Tokens to process.
      'top_p' => 0.1, //diversity of answers
      'frequency_penalty' => 0.0,
      'presence_penalty' => 0.0,
    ]);
    $responseObj = json_decode($response, true);
    // $responseObj = json_decode($this->exampleResponse,true);


    //when the response body dose not have any of the keyword go to the end and say that there is something wrong
    if (isset($responseObj['error'])) {
      //TODO: Implement Error msg
      throw new Exception($responseObj['error']['message'] . "\n" . $responseObj['error']['code']);
      return ["NO_DATA"];
    }
    if (isset($responseObj['choices'][0]['text'])) {

      $ai_dataLump = (string) $responseObj['choices'][0]['text'];


      $ai_dataLump =  preg_replace('/\s+/', '', $ai_dataLump); //removes whitespace
      $ai_dataList = explode(';', $ai_dataLump);
      $ai_dataListFiltered = [];

      for($i=0 ;count($ai_dataList) > $i ;$i++){
        if(!(empty($ai_dataList[$i]) || $ai_dataList[$i] === "")){
          array_push($ai_dataListFiltered, $ai_dataList[$i]);
        }
      }
      return $ai_dataListFiltered;
    }
    throw new Exception("Unexpected response body of AI request. Please check the response JSON for \"choices\",\"error\": \n" . $response);
    return ["NO_DATA"]; //When nothing is found
  }

  public static function setApiKey(string $apiKey): void
  {
    GeneratorOpenAi::$apiKey = $apiKey;
  }
  public static function getApiKey(): string
  {
    return GeneratorOpenAi::$apiKey;
  }
}
