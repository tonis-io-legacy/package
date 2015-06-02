<?php

namespace Tonis\Package\Hook;

use Tonis\Hookline\HookInterface;
use Tonis\Package\Package;

interface PackageHookInterface extends HookInterface
{
    /**
     * @param Package $Package
     * @return void
     */
    public function onLoad(Package $Package);

    /**
     * @param Package $Package
     * @return void
     */
    public function onMerge(Package $Package);

    /**
     * @param Package $Package
     * @param array $mergedConfig
     * @return void
     */
    public function afterLoad(Package $Package, array $mergedConfig);
}
