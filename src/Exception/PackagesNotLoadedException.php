<?php

namespace Tonis\Package\Exception;

final class PackagesNotLoadedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Packages have not been loaded');
    }
}
