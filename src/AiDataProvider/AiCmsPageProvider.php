<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PlatformDemoData\AiDataProvider;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Cms\DataResolver\FieldConfig;
use Shopware\Core\Framework\Log\Package;
use Swag\PlatformDemoData\Resources\helper\AiTranslationHelper;
use Swag\PlatformDemoData\DataProvider\CmsPageProvider;

#[Package('services-settings')]
class AiCmsPageProvider extends CmsPageProvider
{
    private AiTranslationHelper $aiTranslationHelper;
    private CmsPageProvider $cmsPageProvider;

    public function __construct(Connection $connection, CmsPageProvider $cmsPageProvider)
    {
        $this->aiTranslationHelper = new AiTranslationHelper($connection);
        $this->cmsPageProvider = $cmsPageProvider;
    }

    public function getAction(): string
    {
        return $this->cmsPageProvider->getAction();
    }

    public function getEntity(): string
    {
        return $this->cmsPageProvider->getEntity();
    }

    public function setDeleteFlag(bool $isMarked): void{}

    
    //Overwrites other Language. Only DE is used
    public function getPayload(): array
    {
        //$this->cmsPageProvider->getPayload();// calls the original cmsPageProvider, but we don't need it here
        return [
            [
                'id' => '695477e02ef643e5a016b83ed4cdf63a',
                'type' => 'landingpage',
                'locked' => 0,
                'name' => $this->aiTranslationHelper->adjustTranslations([
                    'de-DE' => 'Startseite'
                ]),
                'sections' => [
                    [
                        'id' => '935477e02ef643e5a016b83ed4cdf63a',
                        'position' => 1,
                        'type' => 'default',
                        'blocks' => [
                            [
                                'position' => 0,
                                'type' => 'image-cover',
                                'locked' => 0,
                                'sizingMode' => 'boxed',
                                'backgroundMediaMode' => 'cover',
                                'slots' => [
                                    [
                                        'id' => '9e2f55fac84647098fe5b0f17ee4786f',
                                        'type' => 'image',
                                        'slot' => 'image',
                                        'locked' => 0,
                                        'translations' => $this->aiTranslationHelper->adjustTranslations([
                                            'de-DE' => [
                                                'config' => [
                                                    'url' => [
                                                        'value' => null,
                                                        'source' => FieldConfig::SOURCE_STATIC,
                                                    ],
                                                    'media' => [
                                                        'value' => 'de4b7dbe9d95435092cb85ce146ced28',
                                                        'source' => FieldConfig::SOURCE_STATIC,
                                                    ],
                                                    'newTab' => [
                                                        'value' => false,
                                                        'source' => FieldConfig::SOURCE_STATIC,
                                                    ],
                                                    'minHeight' => [
                                                        'value' => '340px',
                                                        'source' => FieldConfig::SOURCE_STATIC,
                                                    ],
                                                    'displayMode' => [
                                                        'value' => 'standard',
                                                        'source' => FieldConfig::SOURCE_STATIC,
                                                    ],
                                                ],
                                            ],
                                        ]),
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
