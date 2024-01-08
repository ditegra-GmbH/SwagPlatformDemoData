<?php

declare(strict_types=1);

namespace Swag\PlatformDemoData\OpenAi;

use Orhanerday\OpenAi\OpenAi;
use Exception;


class GeneratorOpenAi
{

  private OpenAI $openAi;
  private static int $maxRootCategories = 4; //should use the Config later
  private static int $maxUnderCategories = 2; //should use the Config later
  private static int $maxProductAmount = 20; //should use the Config later

  private bool $usingFakeResponse = true;


  public static string $apiKey;

  //TODO: REMOVE DEMO DATA
  private string $exampleResponse = '{
        "id": "chatcmpl-abc123xyz456",
        "object": "chat.completion",
        "created": 1677649421,
        "model": "text-davinci-003",
        "usage": {
          "prompt_tokens": 56,
          "completion_tokens": 31,
          "total_tokens": 87
        },
        "choices": [
          {
            "message": {
              "role": "assistant",
              "content": "Sedan"
            },
            "finish_reason": "stop",
            "index": 0
          }
        ]
      }';
  private string $exampleResponseWrong = '{
        "id": "chatcmpl-abc123xyz456",
        "object": "chat.completion",
        "created": 1677649421,
        "model": "text-davinci-003",
        "usage": {
          "prompt_tokens": 56,
          "completion_tokens": 31,
          "total_tokens": 87
        },
        "choices": [
          {
            "message": {
              "role": "assistant",
              "content": "Natürlich, hier ist eine Demo-Kategorien-Liste für Autohersteller\n\n:Limousinen; Geländewagen; Elektrofahrzeuge; Sportwagen; Kompaktwagen; Hybridfahrzeuge; Luxusautos; Nutzfahrzeuge; Cabrios; Kombis"
            },
            "finish_reason": "stop",
            "index": 0
          }
        ]
      }';
  //TODO: REMOVE DEMO DATA


  public function __construct()
  {
    $this->openAi = new OpenAi(self::$apiKey); //Test API-Key from Configs
  }

  public function generateRootCategories(int $amount, string $branche): array
  {

    if ($amount > self::$maxRootCategories) {
      print_r('Too many Root-Categories are selected! (' . $amount . ') reducing to ' . self::$maxRootCategories . ".\n");
      $amount = self::$maxRootCategories;
    }

    $msg = 'Erstelle Demo-Kategorien, trennen die Kategorien mit ";". Schreiben nur die Kategorien und keine Unter-Kategorien auf. Die Kategorien sollen alle in einer Zeile sein. Erstelle keine Nummerierung. Die Branche der Produkte sollte sein: ' . $branche . ' Erstelle nur ' . $amount . ' Kategorien!';
    $categoriesList = $this->createDataAi($msg);

    if ($this->usingFakeResponse) {
      return ["Geländewagen"]; //$categoriesList;
    } else {
      return $categoriesList;
    }
  }

  public function generateUnderCategories(int $amount, string $rootCategory): array
  {

    if ($amount > self::$maxUnderCategories) {
      print_r('Too many Sub-Categories are selected! (' . $amount . ') reducing to ' . self::$maxUnderCategories . ".\n");
      $amount = self::$maxUnderCategories;
    }

    $msg = 'Nenne für ' . $rootCategory . ' Markennamen, trennen die Kategorien mit ";". Schreibe nur die Markennamen auf. Die Markennamen sollen alle in einer Zeile sein. Erstelle keine Nummerierung. Erstelle nur ' . $amount . ' Kategorien!';
    $categoriesList = $this->createDataAi($msg);

    //TODO: Remove tests
    if ($this->usingFakeResponse) {
      return ["Jeep", "Ford"]; //$categoriesList;
    } else {
      return $categoriesList;
    }
    //return ["UNDER1","UNDER2","UNDER3","UNDER4","UNDER5","UNDER6","UNDER7","UNDER8","UNDER9"];//$categoriesList;
  }
  public function generateProducts(int $amount, string $subCategory, string $rootCategory): array
  {

    if ($amount > self::$maxProductAmount) {
      print_r('Too many Products are selected! (' . $amount . ') reducing to ' . self::$maxProductAmount . ".\n");
      $amount = self::$maxProductAmount;
    }

    $msg = 'Nenne für ' . $rootCategory . " " . $subCategory . ' Produktnamen, trennen die Kategorien mit ";". Schreibe nur die Produktnamen auf. Die Produktnamen sollen alle in einer Zeile sein. Erstelle keine Nummerierung. Erstelle nur ' . $amount . ' Produktnamen!';
    $productList = $this->createDataAi($msg);

    if ($this->usingFakeResponse) {
      return ["Wrangler", "Cherokee"]; //$categoriesList;
    } else {
      return $productList;
    }
  }

  private function createDataAi(string $msg): array
  {
    //TODO: $response needs type check
    $response = $this->openAi->completion([
      'model' => "text-davinci-003", //Deprecated. Will shutdown on January 2024
      'prompt' => $msg, //the question for the AI like hello or so
      'temperature' => 0.2, //0 means 100% predictability same answer every time on the same question.
      'max_tokens' => 200, //how many "Words" the answer and question has. I limit the tokens to about 200, the Question takes about 70 Tokens to process.
      'top_p' => 0.1, //diversity of answers
      'frequency_penalty' => 0.0,
      'presence_penalty' => 0.0,
    ]);

    //TODO: the AI sometimes crates a list. I have to find a way to check if this is a list. "Thru line checks?"

    //  $responseObj = json_decode($response, true);
    $responseObj = json_decode($this->exampleResponse, true);
    //  $responseObj = json_decode($this->exampleResponseWrong, true);
    //  $responseObj = '{}'; //when te respond is an empty json
    //when the response body dose not have any of the keyword go to the end and say that there is something wrong
    if (isset($responseObj['error'])) {
      //TODO: Implement Error msg
      throw new Exception($responseObj['error']['message'] . "\n" . $responseObj['error']['code']);
      return ["NO_DATA"];
    }
    if (isset($responseObj['choices'][0]['message']['content'])) {

      $ai_dataLump = (string) $responseObj['choices'][0]['message']['content'];

      //Checks if the AI message content has a line brake. This can help when the AI output formatted data with
      // if(preg_match("/r|n/",$ai_dataLump)){
      //   return ["AI_OUTPUT_WRONG"];
      // }

      //TODO: Implement array conversion and error handling
      $ai_dataLump =  preg_replace('/\s+/', '', $ai_dataLump); //removes whitespace
      $ai_data = explode(';', $ai_dataLump);
      return $ai_data;
    }
    throw new Exception("Unexpected response body of AI request. Please check the response JSON for \"choices\",\"error\": \n" . $responseObj);
    return ["NO_DATA"]; //When nothing is found

  }
}
