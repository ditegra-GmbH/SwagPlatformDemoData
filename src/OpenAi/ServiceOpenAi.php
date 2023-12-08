<?php

declare(strict_types=1);

namespace Swag\PlatformDemoData\OpenAi;

use Orhanerday\OpenAi\OpenAi;
use Exception;

class ServiceOpenAi
{

    private OpenAI $openAi;

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
              "content": "Sedan; Coupé; Cabriolet; Kombi; Luxuslimousine; Sportlimousine; Elektrolimousine; Hybridlimousine; Kompaktlimousine; Oberklasse-Limousine"
            },
            "finish_reason": "stop",
            "index": 0
          }
        ]
      }';//TODO: REMOVE DEMO DATA


    public function __construct()
    {
        $this->openAi = new OpenAi(""); //Test API-Key:
    }

    public function generateCategories(string $amount, string $branche): array
    {
        $msg = 'Erstelle Demo-Kategorien, trennen die Kategorien mit ";". Schreiben nur die Kategorien und keine Unter-Kategorien auf. Die Kategorien sollen alle in einer Zeile sein. Erstelle keine Nummerierung. Die Branche der Produkte sollte sein: ' . $branche . ' Erstelle nur ' . $amount . ' Kategorien!';
        $categoriesList = $this->createDataAi($msg);

        return $categoriesList;
    }

    private function createDataAi(string $msg): array
    {
        //TODO: $response needs typecheck
        $response = $this->openAi->completion([
            'model' => "text-davinci-003", //Deprecatedt. Will shutdown on Junaray 2024
            'prompt' => $msg, //the quastion for the AI like hello or so
            'temperature' => 0.2, //0 means 100% predictablilty same answare every time on the same question.
            'max_tokens' => 200, //how many "Words" the answare and quastinen has. I limit the tokens to about 200, the Question takes about 70 Tokens to prozess.
            'top_p' => 0.1, //diversity of answars
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
        ]);

        //TODO: Error handeling when the return is not a message with content.
        // $responseObj = json_decode($response, true);
        // $responseObj = json_decode($this->exampleResponse, true);
        $responseObj = '{}';


        //when the response body dose not have any of the keyword catch the exception and say that there is something wrong
        if(isset($responseObj['error'])){
            //TODO: Implement Error msg
            throw new Exception("");
            return ["NO_DATA"];
        }
        if(isset($responseObj['choices'][0]['message']['content'])){
            
            $ai_dataClumb =(string) $responseObj['choices'][0]['message']['content'];

            //TODO: Implement array conversion and error handeling
            $ai_dataClumb =  preg_replace('/\s+/','', $ai_dataClumb);//removes whitespace
            $ai_data = explode(';',$ai_dataClumb);
            return [$ai_data];
        }
        throw new Exception("Unexpected response body of AI request. Please check the response JSON for \"choices\",\"error\": \n". $responseObj);
        return ["NO_DATA"];//When nothing is found
        
    }
}
