<?php

namespace Tonis\PackageManager\Feature;

interface ConfigProviderInterface
{
    /**
     * @return array
     */
    public function getConfig();
}
