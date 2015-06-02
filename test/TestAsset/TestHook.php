<?php
namespace Tonis\Package\TestAsset;

use Tonis\Package\Hook\PackageHookInterface;
use Tonis\Package\Package;

class TestHook implements PackageHookInterface
{
    public $onLoad = false;
    public $mergedConfig = [];

    public function onLoad(Package $Package)
    {
        $this->onLoad = true;
    }

    public function afterLoad(Package $Package, array $mergedConfig)
    {
        $this->mergedConfig = $mergedConfig;
    }

    public function onMerge(Package $Package)
    {
        return ['foo' => 'bar'];
    }
}
