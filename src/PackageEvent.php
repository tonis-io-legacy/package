<?php
namespace Tonis\Package;

use Tonis\Event\Event as BaseEvent;

final class PackageEvent extends BaseEvent
{
    /** @var PackageManager */
    private $packageManager;

    /**
     * @param PackageManager $manager
     */
    public function __construct(PackageManager $manager)
    {
        $this->packageManager = $manager;
    }

    /**
     * @return PackageManager
     */
    public function getPackageManager()
    {
        return $this->packageManager;
    }
}
