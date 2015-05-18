<?php

namespace Tonis\PackageManager\Feature;

interface PathProviderInterface
{
    /**
     * @return string
     */
    public function getPath();
}
