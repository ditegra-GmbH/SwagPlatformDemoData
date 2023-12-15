<?php

declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PlatformDemoData;

use Bezhanov\Faker\Provider\Demographic;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Shopware\Core\Framework\Api\Controller\SyncController;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Write\Validation\RestrictDeleteViolationException;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\PlatformRequest;
use Swag\PlatformDemoData\AiDataProvider\AiDemoDataProvider;
use Swag\PlatformDemoData\DemoDataService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;


#[Package('services-settings')]
class DemoDataServiceAiDecorator extends DemoDataService
{

    private SyncController $sync;

    /**
     * @var iterable<AiDemoDataProvider>
     */
    private iterable $demoDataProvider;

    private RequestStack $requestStack;

    // private DemoDataService $demoDataService;
    private DemoDataService $innerDemoDataService;

    /**
     * @param iterable<AiDemoDataProvider> $demoDataProvider
     */

    public function __construct(SyncController $sync, iterable $demoDataProvider, RequestStack $requestStack)
    {
        $this->sync = $sync;
        $this->demoDataProvider = $demoDataProvider;
        $this->requestStack = $requestStack;
        //$this->demoDataService = new DemoDataService($sync,$demoDataProvider,$requestStack);
        $this->innerDemoDataService = new DemoDataService($sync, $demoDataProvider, $requestStack);
    }

    // Overrides the original function and uses the AIDemoDataProvider instead.
    public function generate(Context $context):void{
        $this->innerDemoDataService->generate($context);
    }

    public function delete(Context $context):void{
        foreach ($this->demoDataProvider as $dataProvider) {
            $dataProvider->setDeleteFlag(true);
        }
        $this->innerDemoDataService->delete($context);
    }
}