<?php

namespace Tonis\Package\TestAsset\Path;

use Tonis\Package\Feature\PathProviderInterface;

class PathPackage implements PathProviderInterface
{
    /**
     * @return string
     */
    public function getPath()
    {
        return __DIR__;
    }
}
