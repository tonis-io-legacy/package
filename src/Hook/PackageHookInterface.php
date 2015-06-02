<?php

namespace Tonis\Package\Hook;

use Tonis\Hookline\HookInterface;
use Tonis\Package\Manager;

interface PackageHookInterface extends HookInterface
{
    /**
     * @param Manager $manager
     * @return void
     */
    public function onLoad(Manager $manager);

    /**
     * @param Manager $manager
     * @return void
     */
    public function onMerge(Manager $manager);

    /**
     * @param Manager $manager
     * @param array $mergedConfig
     * @return void
     */
    public function afterLoad(Manager $manager, array $mergedConfig);
}
