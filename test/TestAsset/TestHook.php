<?php
namespace Tonis\PackageManager\TestAsset;

use Tonis\PackageManager\Hook\PackageHookInterface;
use Tonis\PackageManager\PackageManager;

class TestHook implements PackageHookInterface
{
    public $onLoad = false;
    public $mergedConfig = [];

    public function onLoad(PackageManager $packageManager)
    {
        $this->onLoad = true;
    }

    public function afterLoad(PackageManager $packageManager, array $mergedConfig)
    {
        $this->mergedConfig = $mergedConfig;
    }

    public function onMerge(PackageManager $packageManager)
    {
        return ['foo' => 'bar'];
    }
}
