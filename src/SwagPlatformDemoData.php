<?php

declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PlatformDemoData;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;



#[Package('services-settings')]
class SwagPlatformDemoData extends Plugin
{
    private $ai= true; //TODO: find better methode
    //Demo Data shuld still be able to crate the Data in the Old fashion way. I only extend the functuonlity to use AI.

    public function activate(ActivateContext $activateContext): void
    {
        
        if(!$this->ai){
            // @phpstan-ignore-next-line
            $this->container->get(DemoDataService::class)->generate($activateContext->getContext());
            
        }else{
            echo "\n\n ___generating demo data with AI___ \n\n";
        }
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        if(!$this->ai){
            // @phpstan-ignore-next-line
            $this->container->get(DemoDataService::class)->delete($deactivateContext->getContext());
        }
    }


}
