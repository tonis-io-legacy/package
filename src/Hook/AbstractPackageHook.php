<?php

namespace Tonis\PackageManager\Hook;

use Tonis\PackageManager\PackageManager;

abstract class AbstractPackageHook implements PackageHookInterface
{
    /**
     * {@inheritDoc}
     */
    public function onLoad(PackageManager $packageManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function onMerge(PackageManager $packageManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function afterLoad(PackageManager $packageManager, array $mergedConfig)
    {
    }
}
