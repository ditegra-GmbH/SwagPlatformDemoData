<?php

declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PlatformDemoData\AiDataProvider;

use Doctrine\DBAL\Connection;

use Shopware\Core\Content\Category\CategoryCollection;
use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Uuid\Uuid;

use Swag\PlatformDemoData\AiDataProvider\AiDemoDataProvider;
use Swag\PlatformDemoData\Resources\helper\AiTranslationHelper;
use Swag\PlatformDemoData\AiDemoDataService;
use Swag\PlatformDemoData\OpenAi\GeneratorOpenAi;


#[Package('services-settings')]
class AiCategoryProvider extends AiDemoDataProvider
{
    /**
     * @var EntityRepository<CategoryCollection> $categoryRepository
     */
    private EntityRepository $categoryRepository;

    private Connection $connection;

    private AiTranslationHelper $AiTranslationHelper;

    private GeneratorOpenAi $openAi;

    private AiDemoDataService $aiDemoDataService;

    private bool $deletFlag = false;

    /**
     * @param EntityRepository<CategoryCollection> $categoryRepository
     */
    public function __construct(EntityRepository $categoryRepository, Connection $connection, AiDemoDataService $aiDemoDataService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->connection = $connection;
        $this->AiTranslationHelper = new AiTranslationHelper($connection);
        $this->aiDemoDataService = $aiDemoDataService;
    }

    public function getAction(): string
    {
        return 'upsert';
    }

    public function getEntity(): string
    {
        return 'category';
    }

    public function setDeleteFlag(bool $isMarked): void
    {
        $this->deletFlag = $isMarked;
    }


    public function getPayload(): array
    {
        $cmsPageId = $this->getDefaultCmsListingPageId();

        $RootCategory = $this->createRootCategoryPayload(10, "Autohandel"); //CLI will set the values here.

        return [
            [
                'id' => $this->getRootCategoryId(),
                'cmsPageId' => '695477e02ef643e5a016b83ed4cdf63a',
                'active' => true,
                'displayNestedProducts' => true,
                'visible' => true,
                'type' => 'page',
                'name' => $this->AiTranslationHelper->adjustTranslations([
                    'de-DE' => 'Katalog #1',
                    'en-GB' => 'Catalogue #1',
                    'pl-PL' => 'Katalog #1',
                ]),
                'children' => $RootCategory
            ]
        ];


        //             [
        //                 'id' => '77b959cf66de4c1590c7f9b7da3982f3',
        //                 'cmsPageId' => $cmsPageId,
        //                 'active' => false,
        //                 'displayNestedProducts' => true,
        //                 'visible' => true,
        //                 'type' => 'page',
        //                 'name' => $this->AiTranslationHelper->adjustTranslations([
        //                     'de-DE' => 'Lebensmittel',
        //                     'en-GB' => 'Food',
        //                     'pl-PL' => 'Jedzenie',
        //                 ]),
        //                 'children' => [
        //                     [
        //                         'id' => '19ca405790ff4f07aac8c599d4317868',
        //                         'cmsPageId' => $cmsPageId,
        //                         'active' => true,
        //                         'displayNestedProducts' => true,
        //                         'visible' => true,
        //                         'type' => 'page',
        //                         'name' => $this->AiTranslationHelper->adjustTranslations([
        //                             'de-DE' => 'Backwaren',
        //                             'en-GB' => 'Bakery products',
        //                             'pl-PL' => 'Pieczywo',
        //                         ]),
        //                     ],
        //                     [
        //                         'id' => '48f97f432fd041388b2630184139cf0e',
        //                         'cmsPageId' => $cmsPageId,
        //                         'active' => true,
        //                         'displayNestedProducts' => true,
        //                         'visible' => true,
        //                         'type' => 'page',
        //                         'afterCategoryId' => '19ca405790ff4f07aac8c599d4317868',
        //                         'name' => $this->AiTranslationHelper->adjustTranslations([
        //                             'de-DE' => 'Fisch',
        //                             'en-GB' => 'Fish',
        //                             'pl-PL' => 'Ryby',
        //                         ]),
        //                     ],
        //                     [
        //                         'id' => 'bb22b05bff9140f3808b1cff975b75eb',
        //                         'cmsPageId' => $cmsPageId,
        //                         'active' => true,
        //                         'displayNestedProducts' => true,
        //                         'visible' => true,
        //                         'type' => 'page',
        //                         'afterCategoryId' => '48f97f432fd041388b2630184139cf0e',
        //                         'name' => $this->AiTranslationHelper->adjustTranslations([
        //                             'de-DE' => 'Süßes',
        //                             'en-GB' => 'Sweets',
        //                             'pl-PL' => 'Słodycze',
        //                         ]),
        //                     ],
        //                 ],
        //             ],
        //             [
        //                 'id' => 'a515ae260223466f8e37471d279e6406',
        //                 'cmsPageId' => $cmsPageId,
        //                 'active' => true,
        //                 'displayNestedProducts' => true,
        //                 'visible' => true,
        //                 'type' => 'page',
        //                 'afterCategoryId' => '77b959cf66de4c1590c7f9b7da3982f3',
        //                 'name' => $this->AiTranslationHelper->adjustTranslations([
        //                     'de-DE' => 'Bekleidung',
        //                     'en-GB' => 'Clothing',
        //                     'pl-PL' => 'Odzież',
        //                 ]),
        //                 'children' => [
        //                     [
        //                         'id' => '8de9b484c54f441c894774e5f57e485c',
        //                         'cmsPageId' => $cmsPageId,
        //                         'active' => true,
        //                         'displayNestedProducts' => true,
        //                         'visible' => true,
        //                         'type' => 'page',
        //                         'name' => $this->AiTranslationHelper->adjustTranslations([
        //                             'de-DE' => 'Damen',
        //                             'en-GB' => 'Women',
        //                             'pl-PL' => 'Kobiety',
        //                         ]),
        //                     ],
        //                     [
        //                         'id' => '2185182cbbd4462ea844abeb2a438b33',
        //                         'cmsPageId' => $cmsPageId,
        //                         'active' => true,
        //                         'displayNestedProducts' => true,
        //                         'visible' => true,
        //                         'type' => 'page',
        //                         'afterCategoryId' => '8de9b484c54f441c894774e5f57e485c',
        //                         'name' => $this->AiTranslationHelper->adjustTranslations([
        //                             'de-DE' => 'Herren',
        //                             'en-GB' => 'Men',
        //                             'pl-PL' => 'Mężczyźni',
        //                         ]),
        //                     ],
        //                 ],
        //             ],
        //             [
        //                 'id' => '251448b91bc742de85643f5fccd89051',
        //                 'cmsPageId' => $cmsPageId,
        //                 'active' => true,
        //                 'displayNestedProducts' => true,
        //                 'visible' => true,
        //                 'type' => 'page',
        //                 'afterCategoryId' => 'a515ae260223466f8e37471d279e6406',
        //                 'name' => $this->AiTranslationHelper->adjustTranslations([
        //                     'de-DE' => 'Freizeit & Elektro',
        //                     'en-GB' => 'Free time & electronics',
        //                     'pl-PL' => 'Wypoczynek & Elektronika',
        //                 ]),
        //             ],
        //         ],
        //     ],
        // ];
    }

    private function getRootCategoryId(): string
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('parentId', null));

        /** @var CategoryEntity|null $rootCategory */
        $rootCategory = $this->categoryRepository->search($criteria, new Context(new SystemSource()))->first();
        if (!$rootCategory) {
            throw new \RuntimeException('Root category not found');
        }

        return $rootCategory->getId();
    }

    private function getCategoryIdOnIndex(int $index): string
    {
        //TODO: When there is no ID, just skipp the deleton stepp
        $categoryIdList = $this->getCategoryIdList();
        return $categoryIdList[$index];
    }

    private function getCategoryIdList(): array
    {
        $criteria = new Criteria();
        $categoryIdList = [];
        $index = 0;
        
        $criteria->addFilter(new ContainsFilter('parentId', ''));
        $categorieEntitiyList = $this->categoryRepository->search($criteria, new Context(new SystemSource()))->getEntities();

        foreach($categorieEntitiyList as $categoryEntity){
            $categoryIdList[$index] = $categoryEntity->getId();
            $index++;
        }

        //print_r();
        return $categoryIdList;
    }

    private function getDefaultCmsListingPageId(): string
    {
        $id = $this->getCmsPageIdByName('Default listing layout');

        if ($id !== null) {
            return $id;
        }

        // BC support for older shopware versions - \Shopware\Core\Migration\V6_4\Migration1645019769UpdateCmsPageTranslation changed the translations
        $id = $this->getCmsPageIdByName('Default category layout');

        if ($id !== null) {
            return $id;
        }

        throw new \RuntimeException('Default Cms Listing page not found');
    }

    private function getCmsPageIdByName(string $name): ?string
    {
        $id = $this->connection->fetchOne(
            '
                    SELECT LOWER(HEX(cms_page_id)) as cms_page_id
                    FROM cms_page_translation
                    INNER JOIN cms_page ON cms_page.id = cms_page_translation.cms_page_id
                    WHERE cms_page.locked
                    AND name = :name
                ',
            ['name' => $name]
        );

        return $id !== false ? $id : null;
    }

    private function createRootCategoryPayload(int $amount, string $ShopBranche): array
    {
        $categoriesList = [];
        $rootCategoriePayload = [];
        $this->openAi = new GeneratorOpenAi();


        if ($this->deletFlag) {
            $categoriesList = $this->getCategoryIdList();
        } else {
            $categoriesList = $this->openAi->generateCategories($amount, $ShopBranche);
        }


        for ($i = 0; $i < count($categoriesList); $i++) {


            if ($this->deletFlag) {
                $uuid = $this->getCategoryIdOnIndex($i);
            } else {
                $uuid = Uuid::randomHex();
            }


            $rootCategoriePayload[$i] = [
                'id' => $uuid, //TODO: Instand of just creating IDs, we have to keep track of them, we need them to reomve the demo data.
                'cmsPageId' => '695477e02ef643e5a016b83ed4cdf63a',
                'active' => true,
                'displayNestedProducts' => true,
                'visible' => true,
                'type' => 'page',
                'name' => $this->AiTranslationHelper->adjustTranslations([
                    'de-DE' => $categoriesList[$i]
                ])
            ];
        }
        return $rootCategoriePayload;
    }
}
