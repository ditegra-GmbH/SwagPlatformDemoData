<?php

declare(strict_types=1);

namespace Swag\PlatformDemoData\OpenAi;

use Orhanerday\OpenAi\OpenAi;
use Exception;
use Swag\PlatformDemoData\AiDemoDataService;


class GeneratorOpenAi
{

  private OpenAI $openAi;
  private int $maxCategories = 10; //should use the Config later

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
              "content": "Sedan; Coupé; Cabriolet; Kombi"
            },
            "finish_reason": "stop",
            "index": 0
          }
        ]
      }'; //TODO: REMOVE DEMO DATA


  public function __construct()
  {
    $this->openAi = new OpenAi("secret"); //Test API-Key from Configs
  }

  public function generateCategories(int $amount, string $branche): array
  {

    if($amount > $this->maxCategories){
      echo 'Too many Categories are selected! ('. $amount .') Reducing to '. $this->maxCategories.".\n";
      $amount = $this->maxCategories;
    }

    $msg = 'Erstelle Demo-Kategorien, trennen die Kategorien mit ";". Schreiben nur die Kategorien und keine Unter-Kategorien auf. Die Kategorien sollen alle in einer Zeile sein. Erstelle keine Nummerierung. Die Branche der Produkte sollte sein: ' . $branche . ' Erstelle nur ' . $amount . ' Kategorien!';
    $categoriesList = $this->createDataAi($msg);

    print_r($categoriesList); 
    return $categoriesList;
  }

  private function createDataAi(string $msg): array
  {
    //TODO: $response needs typecheck
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
    // $responseObj = json_decode($response, true);
     $responseObj = json_decode($this->exampleResponse, true);
    // $responseObj = '{}'; //when te respond is an empty json


    //when the response body dose not have any of the keyword go to the end and say that there is something wrong
    if (isset($responseObj['error'])) {
      //TODO: Implement Error msg
      throw new Exception($responseObj['error']['message'] . "\n" . $responseObj['error']['code']);
      return ["NO_DATA"];
    }
    if (isset($responseObj['choices'][0]['message']['content'])) {

      $ai_dataLump = (string) $responseObj['choices'][0]['message']['content'];

      //TODO: Implement array conversion and error handling
      $ai_dataLump =  preg_replace('/\s+/', '', $ai_dataLump); //removes whitespace
      $ai_data = explode(';', $ai_dataLump);
      return $ai_data;
    }
    throw new Exception("Unexpected response body of AI request. Please check the response JSON for \"choices\",\"error\": \n" . $responseObj);
    return ["NO_DATA"]; //When nothing is found

  }
}
