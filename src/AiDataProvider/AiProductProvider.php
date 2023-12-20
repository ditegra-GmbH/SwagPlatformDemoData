<?php declare(strict_types=1);
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
use Swag\PlatformDemoData\Resources\helper\AiTranslationHelper;
use Swag\PlatformDemoData\DataProvider\ProductProvider;

#[Package('services-settings')]
class AiProductProvider extends ProductProvider
{
    private AiTranslationHelper $translationHelper;
    private Connection $connection;
    private ProductProvider $productProvider;

    public function __construct(Connection $connection, ProductProvider $productProvider)
    {   
        $this->productProvider = $productProvider;
        $this->connection = $connection;
        $this->translationHelper = new AiTranslationHelper($connection);
        parent::__construct($connection);
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
        return[];
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