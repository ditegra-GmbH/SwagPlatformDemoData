<?php

declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PlatformDemoData;


use Error;

use Shopware\Core\Framework\Api\Controller\SyncController;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Log\Package;

use Swag\PlatformDemoData\DataProvider\DemoDataProvider;
use Swag\PlatformDemoData\DemoDataService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;


#[Package('services-settings')]
class DemoDataServiceAiDecorator extends DemoDataService
{

    private SyncController $sync;

    /**
     * @var iterable<DemoDataProvider>
     */
    private iterable $demoDataProvider;

    private RequestStack $requestStack;

    // private DemoDataService $demoDataService;
    private DemoDataService $innerDemoDataService;

    /**
     * @param iterable<DemoDataProvider> $demoDataProvider
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
        throw new Error("delete function is not implemented");
        // $this->innerDemoDataService->delete($context);
    }
}