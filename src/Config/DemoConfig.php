<?php

namespace AIDemoData\Service\Config;

use Shopware\Core\System\SystemConfig\SystemConfigService;

class ConfigService
{
    private $openAiKey;

    //TODO: crate config
    public function __construct(SystemConfigService $configService)
    {
       // $this->openAiKey = $configService->getString('GET.AI.KEY.PLS');
    }

    public function getOpenAiKey(): string
    {
        return $this->openAiKey;
    }
    public function setOpenAiKey(string $openAiKey): void
    {
        $this->openAiKey = $openAiKey;
    }
}