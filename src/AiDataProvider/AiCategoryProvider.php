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
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Uuid\Uuid;

use Swag\PlatformDemoData\DataProvider\CategoryProvider;
use Swag\PlatformDemoData\Resources\helper\AiTranslationHelper;
use Swag\PlatformDemoData\OpenAi\GeneratorOpenAi;



#[Package('services-settings')]
class AiCategoryProvider extends CategoryProvider
{
    private AiTranslationHelper $aiTranslationHelper;
    private GeneratorOpenAi $openAi;
    private CategoryProvider $categoryProvider;

    //TODO:create Getter/Setter
    public static int $rootAmount;
    public static int $subAmount;
    public static string $shopBranche;


    /**
     * @param EntityRepository<CategoryCollection> $categoryRepository
     */
    public function __construct(EntityRepository $categoryRepository, Connection $connection, CategoryProvider $categoryProvider)
    {
        $this->aiTranslationHelper = new AiTranslationHelper($connection);
        // $this->demoDataServiceAiDecorator = $demoDataServiceAiDecorator;
        $this->categoryProvider = $categoryProvider; //uses decorating pattern to extend it
        parent::__construct($categoryRepository, $connection);//needs to call the parent constructor to initialize the connection bevor using it. 
    }

    public function getAction(): string
    {
        return $this->categoryProvider->getAction();
    }

    public function getEntity(): string
    {
        return  $this->categoryProvider->getEntity();
    }


    public function getPayload(): array
    {
        $rootCategory = $this->createRootCategoryPayload(self::$rootAmount, self::$subAmount, self::$shopBranche); //CLI will set the values here.

        $array = [
            [
                'id' => $this->getRootCategoryId(),
                'cmsPageId' => '695477e02ef643e5a016b83ed4cdf63a',
                'active' => true,
                'displayNestedProducts' => true,
                'visible' => true,
                'type' => 'page',
                'name' => $this->aiTranslationHelper->adjustTranslations([
                    'de-DE' => 'Katalog #1',
                    'en-GB' => 'Catalogue #1',
                ]),
                'children' => $rootCategory
            ]
        ];
        return $array;
    }

    // private function getCategoryIdOnIndex(int $index): string
    // {
    //     //TODo: When there is no ID, just skip the deletion step
    //     $categoryIdList = $this->getCategoryIdList();
    //     return $categoryIdList[$index];
    // }

    // private function getCategoryIdList(): array
    // {
    //     $criteria = new Criteria();
    //     $categoryIdList = [];
    //     $index = 0;

    //     $criteria->addFilter(new ContainsFilter('parentId', ''));
    //     $categoryEntityList = $this->categoryRepository->search($criteria, new Context(new SystemSource()))->getEntities();

    //     //TODo: Check if this approach works without bugs. Testing with Categories inside the main one.
    //     foreach ($categoryEntityList as $categoryEntity) {
    //         $categoryIdList[$index] = $categoryEntity->getId();
    //         $index++;
    //     }

    //     //print_r();
    //     return $categoryIdList;
    // }

    private function createRootCategoryPayload(int $rootAmount, int $subAmount, string $shopBranche): array
    {
        $cmsPageId = $this->getDefaultCmsListingPageId();
        $categoriesList = [];//TODO: make Attribute. Data needed for sub categories
        $rootCategoryPayload = [];
        $this->openAi = new GeneratorOpenAi();
        $categoriesList = $this->openAi->generateRootCategories($rootAmount, $shopBranche);

        for ($i = 0; $i < count($categoriesList); $i++) {

            print_r("->creating Root Category ". $categoriesList[$i] . "\n");

            $subCategory = $this->createSubCategoryPayload($subAmount, $categoriesList[$i], $cmsPageId);
            $uuid = Uuid::randomHex();

            AiProductProvider::addRootCategoryName($categoriesList[$i]);

            $rootCategoryPayload[$i] = [
                'id' => $uuid,
                'cmsPageId' => '695477e02ef643e5a016b83ed4cdf63a',
                'active' => true,
                'displayNestedProducts' => true,
                'visible' => true,
                'type' => 'page',
                'name' => $this->aiTranslationHelper->adjustTranslations([
                    'de-DE' => $categoriesList[$i]
                ]),
                'children' => $subCategory
            ];
        }
        return $rootCategoryPayload;
    }

    private function createSubCategoryPayload(int $amount, string $rootCategory, string $cmsPageId): array{

        $categoriesList = $this->openAi->generateUnderCategories($amount, $rootCategory);

        for ($i = 0; $i < count($categoriesList); $i++) {

            print_r("\t ->creating Sub Category ". $categoriesList[$i] . "\n");

            $uuid = Uuid::randomHex();
            //AIProductProvider needs some values set
            //->SubCategory UUI
            AiProductProvider::addSubCategoryID($uuid);
            //->SubCategory name
            AiProductProvider::addSubCategoryName($categoriesList[$i]);

            $subCategoryPayload[$i] = [
                'id' => $uuid,
                'cmsPageId' => $cmsPageId,
                'active' => true,
                'displayNestedProducts' => true,
                'visible' => true,
                'type' => 'page',
                'name' => $this->aiTranslationHelper->adjustTranslations([
                    'de-DE' => $categoriesList[$i]
                ])
            ];
        }
        return $subCategoryPayload;
    }
}
