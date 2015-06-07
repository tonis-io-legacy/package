<?php

namespace Tonis\Package\Exception;

final class PackageExistsException extends \InvalidArgumentException
{
    /**
     * @param string $packageName
     */
    public function __construct($packageName)
    {
        parent::__construct(sprintf(
            'Package with name "%s" already exists',
            $packageName
        ));
    }
}
