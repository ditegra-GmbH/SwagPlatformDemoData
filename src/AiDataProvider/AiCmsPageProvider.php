<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PlatformDemoData\AiDataProvider;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Cms\DataResolver\FieldConfig;
use Shopware\Core\Framework\Log\Package;
use Swag\PlatformDemoData\Resources\helper\AiTranslationHelper;

#[Package('services-settings')]
class AiCmsPageProvider extends AiDemoDataProvider
{
    private AiTranslationHelper $AiTranslationHelper;

    private bool $deletFlag = false;

    public function __construct(Connection $connection)
    {
        $this->AiTranslationHelper = new AiTranslationHelper($connection);
    }

    public function getAction(): string
    {
        return 'upsert';
    }

    public function getEntity(): string
    {
        return 'cms_page';
    }

    public function setDeleteFlag(bool $isMarked): void
    {
        $this->deletFlag = $isMarked;   
    }

    public function getPayload(): array
    {
        return [
            [
                'id' => '695477e02ef643e5a016b83ed4cdf63a',
                'type' => 'landingpage',
                'locked' => 0,
                'name' => $this->AiTranslationHelper->adjustTranslations([
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
                                        'translations' => $this->AiTranslationHelper->adjustTranslations([
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
