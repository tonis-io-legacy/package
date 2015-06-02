<?php

namespace Tonis\Package\TestAsset\Application;

use Tonis\Package\Feature\ConfigProviderInterface;

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
