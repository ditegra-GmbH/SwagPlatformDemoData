<?php declare(strict_types=1);

namespace Swag\PlatformDemoData\DataProvider;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Swag\PlatformDemoData\Resources\helper\TranslationHelper;


#[Package('services-settings')]
class ProductStreamProvider extends DemoDataProvider
{
   
    private Connection $connection;

    private TranslationHelper $translationHelper;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->translationHelper = new TranslationHelper($connection);
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
                'id' => "dc6ea235320c4f40aeb28dcc16496a28",
                'name' => 'TST100 - QPC10001',
                'filters' => [
                    [
                        'type' => 'multi',
                        'operator' => 'OR',
                        'queries' => [
                            [
                                'queries' => [
                                    [
                                        'type' => 'equals',
                                        'field' => 'product.id',
                                        'value' => '2a88d9b59d474c7e869d8071649be43c',
                                    ],
                                ],
                                'queries' => [
                                    [
                                        'type' => 'equals',
                                        'field' => 'product.parentId',
                                        'value' => '2a88d9b59d474c7e869d8071649be43c',
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
