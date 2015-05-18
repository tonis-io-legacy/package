<?php

namespace Tonis\PackageManager\TestAsset\Application;

use Tonis\PackageManager\Feature\ConfigProviderInterface;

class Package implements ConfigProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return ['foo' => 'bar'];
    }
}
