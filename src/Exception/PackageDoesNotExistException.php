<?php

namespace Tonis\Package\Exception;

final class PackageDoesNotExistException extends \InvalidArgumentException
{
    /**
     * @param string $packageName
     */
    public function __construct($packageName)
    {
        parent::__construct(sprintf(
            'Package with name "%s" does not exist',
            $packageName
        ));
    }
}
