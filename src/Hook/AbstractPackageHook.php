<?php

namespace Tonis\Package\Hook;

use Tonis\Package\Manager;

abstract class AbstractPackageHook implements PackageHookInterface
{
    /**
     * {@inheritDoc}
     */
    public function onLoad(Manager $manager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function onMerge(Manager $manager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function afterLoad(Manager $manager, array $mergedConfig)
    {
    }
}
