<?php

namespace Tonis\PackageManager\Exception;

class PackagesNotLoadedException extends \RuntimeException
{
    public function __construct()
    {
        return parent::__construct('Packages have not been loaded');
    }
}
