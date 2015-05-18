<?php

namespace Tonis\PackageManager\TestAsset\Path;

use Tonis\PackageManager\Feature\PathProviderInterface;

class Package implements PathProviderInterface
{
    /**
     * @return string
     */
    public function getPath()
    {
        return __DIR__;
    }
}
