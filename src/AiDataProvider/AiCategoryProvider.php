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
class AiCategoryProvider extends AiDemoDataProvider
{
    /**
     * @var EntityRepository<CategoryCollection> $categoryRepository
     */
    private EntityRepository $categoryRepository;

    private Connection $connection;

    private AiTranslationHelper $AiTranslationHelper;

    private GeneratorOpenAi $openAi;

    private bool $deleteFlag = false;

    private CategoryProvider $categoryProvider;

    /**
     * @param EntityRepository<CategoryCollection> $categoryRepository
     */
    public function __construct(EntityRepository $categoryRepository, Connection $connection, CategoryProvider $categoryProvider)
    {
        $this->categoryRepository = $categoryRepository;
        $this->connection = $connection;
        $this->AiTranslationHelper = new AiTranslationHelper($connection);
        // $this->demoDataServiceAiDecorator = $demoDataServiceAiDecorator;
        $this->categoryProvider = $categoryProvider;//user decorating pattern to extend it
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
        $this->deleteFlag = $isMarked;
    }


    public function getPayload(): array
    {
        $cmsPageId = $this->categoryProvider->getDefaultCmsListingPageId();

        $RootCategory = $this->createRootCategoryPayload(10, "Autohandel"); //CLI will set the values here.

        return [
            [
                'id' => $this->categoryProvider->getRootCategoryId(),
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

        //TODO: Check if this approach works without bugs. Testing with Categories inside the main one.
        foreach ($categorieEntitiyList as $categoryEntity) {
            $categoryIdList[$index] = $categoryEntity->getId();
            $index++;
        }

        //print_r();
        return $categoryIdList;
    }

    private function createRootCategoryPayload(int $amount, string $ShopBranche): array
    {
        $categoriesList = [];
        $rootCategoriePayload = [];
        $this->openAi = new GeneratorOpenAi();


        if ($this->deleteFlag) {
            $categoriesList = $this->getCategoryIdList();
        } else {
            $categoriesList = $this->openAi->generateCategories($amount, $ShopBranche);
        }


        for ($i = 0; $i < count($categoriesList); $i++) {


            if ($this->deleteFlag) {
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
