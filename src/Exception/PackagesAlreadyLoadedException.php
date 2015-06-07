<?php

namespace Tonis\Package\Exception;

final class PackagesAlreadyLoadedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Packages can not be added after loading is complete');
    }
}
