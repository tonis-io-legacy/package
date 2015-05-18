<?php

namespace Tonis\PackageManager\TestAsset\Override;

use Tonis\PackageManager\Feature\ConfigProviderInterface;

class Package implements ConfigProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return ['bar' => 'foo', 'foo' => 'foobar', 'baz' => 'baz'];
    }
}
