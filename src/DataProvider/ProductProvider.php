<?php

declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PlatformDemoData\DataProvider;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Uuid\Uuid;
use Swag\PlatformDemoData\Resources\helper\TranslationHelper;

#[Package('services-settings')]
class ProductProvider extends DemoDataProvider
{
    private const LOREM_IPSUM = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';

    private readonly TranslationHelper $translationHelper;

    public function __construct(private readonly Connection $connection)
    {
        $this->translationHelper = new TranslationHelper($this->connection);
    }

    public function getAction(): string
    {
        return 'upsert';
    }

    public function getEntity(): string
    {
        return 'product';
    }

    public function getPayload(): array
    {
        $taxId = $this->getTaxId();
        $storefrontSalesChannel = $this->getStorefrontSalesChannel();

        $TSTManufaturer120Id = '9d8c24bc552d455b8a3bf308d89721e2';
        $TSTManufaturer130Id = '3ac2a3ad116c4576909ff1c9d2f5b233';
        
        $TSTManufaturer120 = [
            'id' => $TSTManufaturer120Id,
            'name' => $this->translationHelper->adjustTranslations([
                'de-DE' => 'TST120 Hersteller',
                'en-GB' => 'TST120 Manufaturer',
            ]),
        ];

        $TSTManufaturer130 = [
            'id' => $TSTManufaturer130Id,
            'name' => $this->translationHelper->adjustTranslations([
                'de-DE' => 'TST130 Hersteller',
                'en-GB' => 'TST130 Manufaturer',
            ]),
        ];

        return [
            [
                'id' => '11dc680240b04f469ccba354cbf0b967',
                'productNumber' => 'QPC10002',
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
                    'de-DE' => 'Hauptprodukt mit erweiterten Preisen',
                    'en-GB' => 'Main product with advanced prices',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturer' => [
                    'id' => 'cc1c20c365d34cfb88bfab3c3e81d350',
                    'name' => $this->translationHelper->adjustTranslations([
                        'de-DE' => 'Shopware Freizeit',
                        'en-GB' => 'Shopware Freetime',
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
                'prices' => [
                    [
                        'ruleId' => '28caae75a5624f0d985abd0eb32aa160',
                        'price' => [[
                            'net' => 630.25,
                            'gross' => 750,
                            'linked' => true,
                            'currencyId' => Defaults::CURRENCY,
                        ]],
                        'quantityStart' => 12,
                        'quantityEnd' => null,
                    ],
                    [
                        'ruleId' => '28caae75a5624f0d985abd0eb32aa160',
                        'price' => [[
                            'net' => 672.27,
                            'gross' => 800,
                            'linked' => true,
                            'currencyId' => Defaults::CURRENCY,
                        ]],
                        'quantityStart' => 1,
                        'quantityEnd' => 11,
                    ],
                ],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
            ],
            [
                'id' => '1901dc5e888f4b1ea4168c2c5f005540',
                'productNumber' => 'QPC100013',
                'active' => false,
                'taxId' => $taxId,
                'stock' => 40,
                'purchaseUnit' => 250.0,
                'referenceUnit' => 250.0,
                'shippingFree' => false,
                'purchasePrice' => 1.99,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'Hauptprodukt mit Bewertungen',
                    'en-GB' => 'Main product with reviews',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturer' => [
                    'id' => '2326d67406134c88bcf80e52d9d2ecb7',
                    'name' => $this->translationHelper->adjustTranslations([
                        'de-DE' => 'Shopware Lebensmittel',
                        'en-GB' => 'Shopware Food',
                    ]),
                ],
                'media' => [
                    [
                        'id' => '0ca83b27e34c4b1f9ab00aed4e3b8b03',
                        'position' => 1,
                        'mediaId' => '6968ad64888844679918c638e449ffc5',
                    ],
                ],
                'coverId' => '0ca83b27e34c4b1f9ab00aed4e3b8b03',
                'categories' => [
                    [
                        'id' => 'bb22b05bff9140f3808b1cff975b75eb',
                    ],
                ],
                'price' => [[
                    'net' => 1.67,
                    'gross' => 1.99,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '22bdaee755804c1d8099c0d3696e852c',
                    ],
                    [
                        'id' => '77421c4f75af40c8a57657cdc2ad49a2',
                    ],
                ],
            ],
            [
                'id' => '2a88d9b59d474c7e869d8071649be43c',
                'productNumber' => 'QPC10001',
                'active' => true,
                'taxId' => $taxId,
                'stock' => 10,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => false,
                'purchasePrice' => 495.95,
                'weight' => 0.17,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'Hauptartikel',
                    'en-GB' => 'Main product',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturer' => [
                    'id' => '7f24e96676e944b0a0addc20d56728cb',
                    'name' => $this->translationHelper->adjustTranslations([
                        'de-DE' => 'Shopware Kleidung',
                        'en-GB' => 'Shopware Fashion',
                    ]),
                ],
                'media' => [
                    [
                        'id' => 'f0e28db1195847dc9acb8eb016473e0c',
                        'position' => 1,
                        'mediaId' => '70e352200b5c45098dc65a5b47094a2a',
                    ],
                ],
                'coverId' => 'f0e28db1195847dc9acb8eb016473e0c',
                'categories' => [
                    [
                        'id' => '251448b91bc742de85643f5fccd89051',
                    ],
                ],
                'price' => [[
                    'net' => 168.06722,
                    'gross' => 200,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '6f9359239c994b48b7de282ee19a714d',
                    ],
                    [
                        'id' => '78c53f3f6dd14eb4927978415bfb74db',
                    ],
                    [
                        'id' => '7cab88165ae5420f921232511b6e8f7d',
                    ],
                    [
                        'id' => 'dc6f98beeca44852beb078a9e8e21e7d',
                    ],
                ],
            ],
            [
                'id' => 'f46d0d4dde4c4f339f853ee3a256ecb6',
                'productNumber' => 'QPC10003',
                'active' => true,
                'taxId' => $taxId,
                'stock' => 10,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => false,
                'purchasePrice' => 495.95,
                'weight' => 0.17,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'QPC Testprodukt einfach TST120',
                    'en-GB' => 'QPC Testproduct simple TST120',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturer' => $TSTManufaturer120,
                'media' => [
                    [
                        'id' => 'f0e28db1195847dc9acb8eb016473e0c',
                        'position' => 1,
                        'mediaId' => '70e352200b5c45098dc65a5b47094a2a',
                    ],
                ],
                'coverId' => 'f0e28db1195847dc9acb8eb016473e0c',
                'categories' => [
                    [
                        'id' => '251448b91bc742de85643f5fccd89051',
                    ],
                ],
                'price' => [[
                    'net' => 84.03,
                    'gross' => 100,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '6f9359239c994b48b7de282ee19a714d',
                    ],
                    [
                        'id' => '78c53f3f6dd14eb4927978415bfb74db',
                    ]
                ],
            ],
            [
                'id' => '0f6bc3b1566b4ad5b6df2642434f6a14',
                'productNumber' => 'QPC10004',
                'active' => true,
                'taxId' => $taxId,
                'stock' => 10,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => false,
                'purchasePrice' => 495.95,
                'weight' => 0.17,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'QPC Testprodukt einfach TST130',
                    'en-GB' => 'QPC Testproduct simple TST130',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturer' => $TSTManufaturer130,
                'media' => [
                    [
                        'id' => 'f0e28db1195847dc9acb8eb016473e0c',
                        'position' => 1,
                        'mediaId' => '70e352200b5c45098dc65a5b47094a2a',
                    ],
                ],
                'coverId' => 'f0e28db1195847dc9acb8eb016473e0c',
                'categories' => [
                    [
                        'id' => '251448b91bc742de85643f5fccd89051',
                    ],
                ],
                'price' => [[
                    'net' => 2065.3613445378,
                    'gross' => 2457.78,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '6f9359239c994b48b7de282ee19a714d',
                    ],
                    [
                        'id' => '78c53f3f6dd14eb4927978415bfb74db',
                    ]
                ],
            ],
            [
                'id' => '3ac014f329884b57a2cce5a29f34779c',
                'productNumber' => 'QPC10006',
                'active' => true,
                'taxId' => $taxId,
                'stock' => 50,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => true,
                'purchasePrice' => 20,
                'weight' => 0.15,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'Hauptprodukt, versandkostenfrei mit Hervorhebung',
                    'en-GB' => 'Main product, free shipping with highlighting',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturerId' => 'cc1c20c365d34cfb88bfab3c3e81d350',
                'media' => [
                    [
                        'id' => 'd6448ce8dd0e4720a92c1bdddb9e6c96',
                        'position' => 1,
                        'mediaId' => '2de02991cd0548a4ac6cc35cb11773a0',
                    ],
                ],
                'coverId' => 'd6448ce8dd0e4720a92c1bdddb9e6c96',
                'categories' => [
                    [
                        'id' => '2185182cbbd4462ea844abeb2a438b33',
                    ],
                ],
                'price' => [[
                    'net' => 15,
                    'gross' => 20,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '5997d91dc0784997bdef68dfc5a08912',
                    ],
                    [
                        'id' => '78c53f3f6dd14eb4927978415bfb74db',
                    ],
                    [
                        'id' => 'c53fa30db00e4a84b4516f6b07c02e8d',
                    ],
                ],
            ],
            [
                'id' => '43a23e0c03bf4ceabc6055a2185faa87',
                'productNumber' => 'QPC10005',
                'active' => true,
                'taxId' => $taxId,
                'stock' => 50,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => true,
                'purchasePrice' => 19.99,
                'weight' => 0.5,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'Variantenprodukt',
                    'en-GB' => 'Variant product',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturerId' => '7f24e96676e944b0a0addc20d56728cb',
                'media' => [
                    [
                        'id' => '55a1e7d9f9e84400a17e2b86d7a3fc89',
                        'position' => 1,
                        'mediaId' => '102ac62ba27347a688030a05c1790db7',
                    ],
                ],
                'coverId' => '55a1e7d9f9e84400a17e2b86d7a3fc89',
                'categories' => [
                    [
                        'id' => '8de9b484c54f441c894774e5f57e485c',
                    ],
                ],
                'price' => [[
                    'net' => 16.799999999999997,
                    'gross' => 19.99,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '5997d91dc0784997bdef68dfc5a08912',
                    ],
                    [
                        'id' => '7cab88165ae5420f921232511b6e8f7d',
                    ],
                    [
                        'id' => '96638a1c7ab847bbb3ca64167ab30a3e',
                    ],
                    [
                        'id' => 'acfd7586d02848f1ac801f4776efa414',
                    ],
                ],
                'configuratorSettings' => [
                    [
                        'optionId' => 'acfd7586d02848f1ac801f4776efa414',
                    ],
                    [
                        'optionId' => '2bfd278e87204807a890da4a3e81dd90',
                        'mediaId' => '6cbbdc03b43f4207be80b5f752d5a1c4',
                    ],
                    [
                        'optionId' => '5997d91dc0784997bdef68dfc5a08912',
                    ],
                    [
                        'optionId' => '52454db2adf942b2ac079a296f454a10',
                        'mediaId' => 'f69ab8ae42d04e17b2bab5ec2ff0a93c',
                    ],
                    [
                        'optionId' => 'ad735af1ebfb421e93e408b073c4a89a',
                        'mediaId' => '102ac62ba27347a688030a05c1790db7',
                    ],
                ],
                'children' => [
                    [
                        'productNumber' => 'QPC10005.1',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '2bfd278e87204807a890da4a3e81dd90',
                            ],
                            [
                                'id' => '5997d91dc0784997bdef68dfc5a08912',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10005.2',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '2bfd278e87204807a890da4a3e81dd90',
                            ],
                            [
                                'id' => 'acfd7586d02848f1ac801f4776efa414',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10005.3',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '52454db2adf942b2ac079a296f454a10',
                            ],
                            [
                                'id' => '5997d91dc0784997bdef68dfc5a08912',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10005.4',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '52454db2adf942b2ac079a296f454a10',
                            ],
                            [
                                'id' => 'acfd7586d02848f1ac801f4776efa414',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10005.5',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => 'ad735af1ebfb421e93e408b073c4a89a',
                            ],
                            [
                                'id' => '5997d91dc0784997bdef68dfc5a08912',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10005.6',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => 'ad735af1ebfb421e93e408b073c4a89a',
                            ],
                            [
                                'id' => 'acfd7586d02848f1ac801f4776efa414',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'c7bca22753c84d08b6178a50052b4146',
                'productNumber' => 'QPC10007',
                'active' => true,
                'taxId' => $taxId,
                'stock' => 50,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => true,
                'purchasePrice' => 19.99,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'Hauptprodukt mit Eigenschaften',
                    'en-GB' => 'Main product with properties',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturerId' => '7f24e96676e944b0a0addc20d56728cb',
                'media' => [
                    [
                        'id' => '683c3a0a0c26464fb65332d1a9adf7e2',
                        'position' => 1,
                        'mediaId' => '5808d194947f415495d9782d8fdc92ae',
                    ],
                ],
                'coverId' => '683c3a0a0c26464fb65332d1a9adf7e2',
                'categories' => [
                    [
                        'id' => '2185182cbbd4462ea844abeb2a438b33',
                    ],
                ],
                'price' => [[
                    'net' => 16.799999999999997,
                    'gross' => 19.99,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '41e5013b67d64d3a92b7a275da8af441',
                    ],
                    [
                        'id' => '5193ffa5de8648a1bcfba1fa8a26c02b',
                    ],
                    [
                        'id' => '54147692cbfb43419a6d11e26cad44dc',
                    ],
                    [
                        'id' => '5997d91dc0784997bdef68dfc5a08912',
                    ],
                    [
                        'id' => '78c53f3f6dd14eb4927978415bfb74db',
                    ],
                    [
                        'id' => '96638a1c7ab847bbb3ca64167ab30a3e',
                    ],
                    [
                        'id' => 'acfd7586d02848f1ac801f4776efa414',
                    ],
                ],
                'configuratorSettings' => [
                    [
                        'optionId' => 'acfd7586d02848f1ac801f4776efa414',
                    ],
                    [
                        'optionId' => '41e5013b67d64d3a92b7a275da8af441',
                    ],
                    [
                        'optionId' => '5997d91dc0784997bdef68dfc5a08912',
                    ],
                    [
                        'optionId' => '54147692cbfb43419a6d11e26cad44dc',
                    ],
                ],
                'children' => [
                    [
                        'productNumber' => 'QPC10007.1',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '41e5013b67d64d3a92b7a275da8af441',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10007.2',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '54147692cbfb43419a6d11e26cad44dc',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10007.3',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '5997d91dc0784997bdef68dfc5a08912',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10007.4',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => 'acfd7586d02848f1ac801f4776efa414',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => '19dce90911c14b7892e25859ac7340bb',
                'productNumber' => 'QPC10008',
                'active' => true,
                'taxId' => $taxId,
                'stock' => 50,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => true,
                'purchasePrice' => 19.99,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'Hauptprodukt mit Eigenschaften und erweiterten preisen',
                    'en-GB' => 'Main product with properties and advanced prices',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturerId' => '7f24e96676e944b0a0addc20d56728cb',
                'media' => [
                    [
                        'id' => '683c3a0a0c26464fb65332d1a9adf7e2',
                        'position' => 1,
                        'mediaId' => '5808d194947f415495d9782d8fdc92ae',
                    ],
                ],
                'coverId' => '683c3a0a0c26464fb65332d1a9adf7e2',
                'categories' => [
                    [
                        'id' => '2185182cbbd4462ea844abeb2a438b33',
                    ],
                ],
                'price' => [[
                    'net' => 16.799999999999997,
                    'gross' => 19.99,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'prices' => [
                    [
                        'ruleId' => '28caae75a5624f0d985abd0eb32aa160',
                        'price' => [[
                            'net' => 20.16806722,
                            'gross' => 24,
                            'linked' => true,
                            'currencyId' => Defaults::CURRENCY,
                        ]],
                        'quantityStart' => 1,
                        'quantityEnd' => null,
                    ],
                    [
                        'ruleId' => '018c2470bfca719c978c7ee92fa0de7d',
                        'price' => [[
                            'net' => 0.747899159,
                            'gross' => 0.89,
                            'linked' => true,
                            'currencyId' => Defaults::CURRENCY,
                        ]],
                        'quantityStart' => 31,
                        'quantityEnd' => null,
                    ],
                    [
                        'ruleId' => '018c2470bfca719c978c7ee92fa0de7d',
                        'price' => [[
                            'net' => 1.336134453,
                            'gross' => 1.59,
                            'linked' => true,
                            'currencyId' => Defaults::CURRENCY,
                        ]],
                        'quantityStart' => 12,
                        'quantityEnd' => 30,
                    ],
                    [
                        'ruleId' => '018c2470bfca719c978c7ee92fa0de7d',
                        'price' => [[
                            'net' => 1.663865546,
                            'gross' => 1.98,
                            'linked' => true,
                            'currencyId' => Defaults::CURRENCY,
                        ]],
                        'quantityStart' => 1,
                        'quantityEnd' => 11,
                    ],
                ],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '41e5013b67d64d3a92b7a275da8af441',
                    ],
                    [
                        'id' => '5193ffa5de8648a1bcfba1fa8a26c02b',
                    ],
                    [
                        'id' => '54147692cbfb43419a6d11e26cad44dc',
                    ],
                    [
                        'id' => '5997d91dc0784997bdef68dfc5a08912',
                    ],
                    [
                        'id' => '78c53f3f6dd14eb4927978415bfb74db',
                    ],
                    [
                        'id' => '96638a1c7ab847bbb3ca64167ab30a3e',
                    ],
                    [
                        'id' => 'acfd7586d02848f1ac801f4776efa414',
                    ],
                ],
                'configuratorSettings' => [
                    [
                        'optionId' => 'acfd7586d02848f1ac801f4776efa414',
                    ],
                    [
                        'optionId' => '41e5013b67d64d3a92b7a275da8af441',
                    ],
                    [
                        'optionId' => '5997d91dc0784997bdef68dfc5a08912',
                    ],
                    [
                        'optionId' => '54147692cbfb43419a6d11e26cad44dc',
                    ],
                ],
                'children' => [
                    [
                        'productNumber' => 'QPC10008.1',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '41e5013b67d64d3a92b7a275da8af441',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10008.2',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '54147692cbfb43419a6d11e26cad44dc',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10008.3',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => '5997d91dc0784997bdef68dfc5a08912',
                            ],
                        ],
                    ],
                    [
                        'productNumber' => 'QPC10008.4',
                        'stock' => 50,
                        'options' => [
                            [
                                'id' => 'acfd7586d02848f1ac801f4776efa414',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => '61d7769368424fe78c45c674cb8a609d',
                'productNumber' => 'QPC10009',
                'active' => true,
                'taxId' => $taxId,
                'stock' => 10,
                'purchaseUnit' => 1.0,
                'referenceUnit' => 1.0,
                'shippingFree' => false,
                'purchasePrice' => 95.95,
                'weight' => 0.10,
                'releaseDate' => new \DateTimeImmutable(),
                'displayInListing' => true,
                'name' => $this->translationHelper->adjustTranslations([
                    'de-DE' => 'Hauptartikel TST110',
                    'en-GB' => 'Main product TST110',
                ]),
                'description' => $this->translationHelper->adjustTranslations([
                    'de-DE' => self::LOREM_IPSUM,
                    'en-GB' => self::LOREM_IPSUM,
                ]),
                'manufacturer' => [
                    'id' => '7f24e96676e944b0a0addc20d56728cb',
                    'name' => $this->translationHelper->adjustTranslations([
                        'de-DE' => 'Shopware Kleidung',
                        'en-GB' => 'Shopware Fashion',
                    ]),
                ],
                'media' => [
                    [
                        'id' => 'f0e28db1195847dc9acb8eb016473e0c',
                        'position' => 1,
                        'mediaId' => '70e352200b5c45098dc65a5b47094a2a',
                    ],
                ],
                'coverId' => 'f0e28db1195847dc9acb8eb016473e0c',
                'categories' => [
                    [
                        'id' => '251448b91bc742de85643f5fccd89051',
                    ],
                ],
                'price' => [[
                    'net' => 1225.2100840336,
                    'gross' => 1458,
                    'linked' => true,
                    'currencyId' => Defaults::CURRENCY,
                ]],
                'visibilities' => [
                    [
                        'id' => Uuid::randomHex(),
                        'salesChannelId' => $storefrontSalesChannel,
                        'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                    ],
                ],
                'properties' => [
                    [
                        'id' => '6f9359239c994b48b7de282ee19a714d',
                    ],
                    [
                        'id' => '78c53f3f6dd14eb4927978415bfb74db',
                    ]
                ],
            ],
        ];
    }

    private function getTaxId(): string
    {
        $result = $this->connection->fetchOne('
            SELECT LOWER(HEX(COALESCE(
                (SELECT `id` FROM `tax` WHERE tax_rate = "19.00" LIMIT 1),
	            (SELECT `id` FROM `tax`  LIMIT 1)
            )))
        ');

        if (!$result) {
            throw new \RuntimeException('No tax found, please make sure that basic data is available by running the migrations.');
        }

        return (string) $result;
    }

    private function getStorefrontSalesChannel(): string
    {
        $result = $this->connection->fetchOne('
            SELECT LOWER(HEX(`id`))
            FROM `sales_channel`
            WHERE `type_id` = :storefront_type
        ', ['storefront_type' => Uuid::fromHexToBytes(Defaults::SALES_CHANNEL_TYPE_STOREFRONT)]);

        if (!$result) {
            throw new \RuntimeException('No tax found, please make sure that basic data is available by running the migrations.');
        }

        return (string) $result;
    }
}
