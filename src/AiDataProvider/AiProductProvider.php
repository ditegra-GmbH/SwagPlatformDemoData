<?php

declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PlatformDemoData\AiDataProvider;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\Defaults;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Uuid\Uuid;

use Swag\PlatformDemoData\DataProvider\ProductProvider;
use Swag\PlatformDemoData\DataProvider\CategoryProvider;
use Swag\PlatformDemoData\Resources\helper\AiTranslationHelper;
use Swag\PlatformDemoData\OpenAi\GeneratorOpenAi;

#[Package('services-settings')]
class AiProductProvider extends ProductProvider
{
    private AiTranslationHelper $translationHelper;
    private GeneratorOpenAi $openAi;
    private Connection $connection;
    private ProductProvider $productProvider;

    private string $taxId;
    private string $storefrontSalesChannel;

    private int $productIndex = 1;

    public static int $productAmount;
    private static array $subCategoryNames = [];
    private static array $rootCategoryNames = [];
    private static array $subCategoryIdList = [];

    public function __construct(Connection $connection, ProductProvider $productProvider)
    {
        $this->productProvider = $productProvider;
        $this->connection = $connection;
        $this->translationHelper = new AiTranslationHelper($connection);
        parent::__construct($connection);
        $this->taxId = $this->getTaxId();
        $this->storefrontSalesChannel = $this->getStorefrontSalesChannel();
    }

    public function getAction(): string
    {
        return $this->productProvider->getAction();
    }

    public function getEntity(): string
    {
        return $this->productProvider->getEntity();
    }

    public function getPayload(): array
    {


        /**
         * This needs to generate products for for every subcategory
         * Find a way to store the UUI from subcategory and the name of that subcategory 
         * 
         * Produckt needs:
         * Rule -> maybe we can remove it?
         * Media
         * Manufacture -> we can fake it
         */

        $productList = [];
        $produktListIndex = 0;
        $this->openAi = new GeneratorOpenAi();
        for ($rootIndex = 0; $rootIndex < count(AiProductProvider::$rootCategoryNames); $rootIndex++) {
            for ($subIndex = 0; $subIndex < count(AiProductProvider::$subCategoryNames); $subIndex++) {
                $nameList = $this->openAi->generateProducts(AiProductProvider::$productAmount, AiProductProvider::$subCategoryNames[$subIndex], AiProductProvider::$rootCategoryNames[$rootIndex]);
                print_r("creating products for: " . AiProductProvider::$rootCategoryNames[$rootIndex] . " " . AiProductProvider::$subCategoryNames[$subIndex] . "\n\n"); //TODO: REMOVE

                for ($productIndex = 0; $productIndex < count($nameList); $productIndex++) {
                    $productList[$produktListIndex] = $this->createProduct($nameList[$productIndex], $subIndex);
                    $produktListIndex++;
                }
            }
        }
        //Arrays are warped in createProduct!
        //TODO: Products are not in the Right subcategory. Need checking why that dose not work

        return $productList; //TODO: Find my wrongdoings.

    }

    private function createProduct(string $nameList, int $categoryIndex): array
    {

        return [
            'id' => Uuid::randomHex(), //generate random UUID 018ce8aae19271a8863672758a2eb5f3
            'productNumber' =>  $this->createProductID(), //generate numbered productNumber PREFIX= AIDD
            'active' => true,
            'taxId' =>  $this->taxId,
            'stock' => rand(10, 100),
            'purchaseUnit' => 1.0,
            'referenceUnit' => 1.0,
            'shippingFree' => (rand(0, 1) == 1) ? false : true,
            'purchasePrice' => rand(1, 1250),
            'weight' => rand(1, 100000) / 100,
            'width' => rand(1, 100000) / 100,
            'height' => rand(1, 100000) / 100,
            'length' => rand(1, 100000) / 100,
            'releaseDate' => new \DateTimeImmutable(),
            'displayInListing' => true,
            'name' => $this->createName($nameList),
            'description' => $this->createDescription(),

            'manufacturer' => $this->manufacturer(),
            // 'media' => [
            //     [
            //         'id' => 'e648140ff1f04177b40128ac6b649d8a',
            //         'position' => 1,
            //         'mediaId' => '84356a71233d4b3e9f417dcc8850c82f',
            //     ],
            // ],
            // 'coverId' => 'e648140ff1f04177b40128ac6b649d8a',
            'categories' => [
                [
                    'id' => $this->getCategoryId($categoryIndex),
                ],
            ],
            'price' => $this->createPrice(),
            'visibilities' => [
                [
                    'id' => Uuid::randomHex(), //'69cd1be4be004944b923ddbe571e96f5',
                    'salesChannelId' =>  $this->storefrontSalesChannel,
                    'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                ],
            ],
        ];
    }

    private function createName(string $name): array
    {
        return $this->translationHelper->adjustTranslations([
            'de-DE' => $name //AI Name Generator
        ]);
    }

    private function createProductID(): string
    {
        $productID = "SWAIDEMO00" . (string) $this->productIndex . rand(0, 100);
        $this->productIndex++;
        return $productID;
    }

    private function createDescription(): array
    {
        return $this->translationHelper->adjustTranslations([
            'de-DE' =>  ProductProvider::LOREM_IPSUM,
        ]);
    }

    private function manufacturer(): array
    {
        return [
            'id' => 'cc1c20c365d34cfb88bfab3c3e81d350', //needs to created bevor
            'name' => $this->translationHelper->adjustTranslations([
                'de-DE' => 'Artificial Demo Manufacture'
            ])
        ];
    }

    private function createPrice(): array
    {
        $randi = rand(1,10000)/100;
        return [[
            'net' => $randi,
            'gross' => $randi,
            'linked' => true,
            'currencyId' => Defaults::CURRENCY,
        ]];
    }

    private function getCategoryId(int $index): string
    {
        return (AiProductProvider::$subCategoryIdList)[$index];
    }

    public static function addSubCategoryID(string $id): void
    {
        array_push(AiProductProvider::$subCategoryIdList, $id);
    }

    public static function addSubCategoryName(string $name): void
    {
        array_push(AiProductProvider::$subCategoryNames, $name);
    }

    public static function addRootCategoryName(string $name): void
    {
        array_push(AiProductProvider::$rootCategoryNames, $name);
    }
}


/*
'id' => '11dc680240b04f469ccba354cbf0b967', //generate random UUID
                'productNumber' => 'SWDEMO10002', //generate numbered productNumber PREFIX= AIDD
                'active' => true,
                'taxId' => $taxId,
                'stock' => 10,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => true,
                'purchasePrice' => 950,
                'weight' => 45.0,
                'width' => 590.0,
                'height' => 600.0,
                'length' => 840.0,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'Hauptprodukt mit erweiterten Preisen'
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM //use faker
                ]),
                'manufacturer' => [
                    'id' => 'cc1c20c365d34cfb88bfab3c3e81d350', //needs to created bevor
                    'name' => $this->translationHelper->adjustTranslations([
                        'de-DE' => 'Shopware Freizeit'
                    ]),
                ],
                'media' => [
                    [
                        'id' => 'e648140ff1f04177b40128ac6b649d8a',
                        'position' => 1,
                        'mediaId' => '84356a71233d4b3e9f417dcc8850c82f',
                    ],
                ],
                'coverId' => 'e648140ff1f04177b40128ac6b649d8a',
                'categories' => [
                    [
                        'id' => '251448b91bc742de85643f5fccd89051',
                    ],
                ],
                'price' => [[
                    'net' => 798.3199999999999,
                    'gross' => 950,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => '69cd1be4be004944b923ddbe571e96f5',
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
            ],
*/