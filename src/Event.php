<?php
namespace Tonis\Package;

use Tonis\Event\Event as BaseEvent;

final class Event extends BaseEvent
{
    /** @var Manager */
    private $packageManager;

    /**
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->packageManager = $manager;
    }

    /**
     * @return Manager
     */
    public function getPackageManager()
    {
        return $this->packageManager;
    }
}
