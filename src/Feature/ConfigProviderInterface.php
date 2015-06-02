<?php

namespace Tonis\Package\Feature;

interface ConfigProviderInterface
{
    /**
     * @return array
     */
    public function getConfig();
}
