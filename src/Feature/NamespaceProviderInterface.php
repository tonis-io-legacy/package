<?php

namespace Tonis\Package\Feature;

interface NamespaceProviderInterface
{
    /**
     * @return string
     */
    public function getNamespace();
}
