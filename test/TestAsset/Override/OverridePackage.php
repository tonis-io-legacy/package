<?php

namespace Tonis\Package\TestAsset\Override;

use Tonis\Package\Feature\ConfigProviderInterface;

class OverridePackage implements ConfigProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return ['bar' => 'foo', 'foo' => 'foobar', 'baz' => 'baz'];
    }
}
