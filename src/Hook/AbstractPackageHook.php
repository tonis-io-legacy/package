<?php

namespace Tonis\Package\Hook;

use Tonis\Package\Package;

abstract class AbstractPackageHook implements PackageHookInterface
{
    /**
     * {@inheritDoc}
     */
    public function onLoad(Package $Package)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function onMerge(Package $Package)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function afterLoad(Package $Package, array $mergedConfig)
    {
    }
}
