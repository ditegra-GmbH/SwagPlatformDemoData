<?php declare(strict_types=1);

namespace Swag\PlatformDemoData\DataProvider;

use Doctrine\DBAL\Connection;
use Swag\PlatformDemoData\Resources\helper\TranslationHelper;

class ProductStreamProvider extends DemoDataProvider
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAction(): string
    {
        return 'upsert';
    }

    public function getEntity(): string
    {
        return 'product_stream';
    }

    public function getPayload(): array
    {
        return [
            [
                'id' => 'dc6ea235320c4f40aeb28dcc16496a28',
                'name' => 'TST100 - QPC10001',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'type'=> 'multi',
                                'operator'=> 'AND',
                                'position'=> 0,
                                'queries'=> [
                                    [
                                        'type'=> 'equals',
                                        'field'=> 'id',
                                        'value'=> '2a88d9b59d474c7e869d8071649be43c'
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => '40969639d0eb4aecba3321a8ba81ed1c',
                'name' => 'TST110 - QPC10002',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'type'=> 'multi',
                                'operator'=> 'AND',
                                'position'=> 0,
                                'queries'=> [
                                    [
                                        'type'=> 'equals',
                                        'field'=> 'id',
                                        'value'=> '11dc680240b04f469ccba354cbf0b967'
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
