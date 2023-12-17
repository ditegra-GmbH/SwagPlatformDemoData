<?php declare(strict_types=1);

namespace Swag\PlatformDemoData\DataProvider;

use Doctrine\DBAL\Connection;

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
                'name' => 'Stream - QPC10001',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'type'=> 'multi',
                                'operator'=> 'AND',
                                'queries'=> [
                                    [
                                        'type'=> 'multi',
                                        'operator'=> 'OR',
                                        'queries'=> [
                                            [
                                                'type'=> 'equals',
                                                'field'=> 'productNumber',
                                                'value'=> 'QPC10001'
                                            ]
                                        ],                                        
                                    ],
                                ],                
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => '40969639d0eb4aecba3321a8ba81ed1c',
                'name' => 'Stream - QPC10002',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'type'=> 'multi',
                                'operator'=> 'AND',
                                'queries'=> [
                                    [
                                        'type'=> 'multi',
                                        'operator'=> 'OR',
                                        'queries'=> [
                                            [
                                                'type'=> 'equals',
                                                'field'=> 'productNumber',
                                                'value'=> 'QPC10002'
                                            ]
                                        ],                                        
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => '3e05412efab34da392ed8a541fd1a399',
                'name' => 'Stream - QPC10005',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'type'=> 'multi',
                                'operator'=> 'AND',
                                'queries'=> [
                                    [
                                        'type'=> 'multi',
                                        'operator'=> 'OR',
                                        'queries'=> [
                                            [
                                                'type'=> 'equals',
                                                'field'=> 'id',
                                                'value'=> '43a23e0c03bf4ceabc6055a2185faa87'
                                            ]
                                        ],                                        
                                    ],
                                ],                
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'bd0630a8294b4477a28b3de9cd255927',
                'name' => 'Stream - QPC10007',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'type'=> 'multi',
                                'operator'=> 'AND',
                                'queries'=> [
                                    [
                                        'type'=> 'multi',
                                        'operator'=> 'OR',
                                        'queries'=> [
                                            [
                                                'type'=> 'equals',
                                                'field'=> 'id',
                                                'value'=> 'c7bca22753c84d08b6178a50052b4146'
                                            ]
                                        ],                                        
                                    ],
                                ],                
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'baee1e435c8641ca820149b51bb2d68b',
                'name' => 'Stream - QPC10008',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'type'=> 'multi',
                                'operator'=> 'AND',
                                'queries'=> [
                                    [
                                        'type'=> 'multi',
                                        'operator'=> 'OR',
                                        'queries'=> [
                                            [
                                                'type'=> 'equals',
                                                'field'=> 'id',
                                                'value'=> '19dce90911c14b7892e25859ac7340bb'
                                            ]
                                        ],                                        
                                    ],
                                ],                
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => '884507b2d8aa48eaa0c4c369acb21943',
                'name' => 'Stream - QPC10009',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'type'=> 'multi',
                                'operator'=> 'AND',
                                'queries'=> [
                                    [
                                        'type'=> 'multi',
                                        'operator'=> 'OR',
                                        'queries'=> [
                                            [
                                                'type'=> 'equals',
                                                'field'=> 'id',
                                                'value'=> '61d7769368424fe78c45c674cb8a609d'
                                            ]
                                        ],                                        
                                    ],
                                ],                
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}