<?php

namespace Tonis\PackageManager\Feature;

interface NamespaceProviderInterface
{
    /**
     * @return string
     */
    public function getNamespace();
}
