<?php

namespace Tonis\Package\Subscriber;

use Tonis\Event;
use Tonis\Event\Manager;
use Tonis\Package;
use Tonis\Package\Exception\PackageLoadFailedException;
use Tonis\Package\Feature\ConfigProviderInterface;

class DefaultSubscriber implements Event\SubscriberInterface
{
    /**
     * @param Manager $events
     * @return void
     */
    public function subscribe(Manager $events)
    {
        $events->on(Package\Manager::EVENT_ON_LOAD, [$this, 'onLoad']);
        $events->on(Package\Manager::EVENT_ON_MERGE, [$this, 'onMerge']);
    }

    /**
     * {@inheritDoc}
     */
    public function onLoad(Package\Event $event)
    {
        $manager = $event->getPackageManager();
        $packages = $manager->getPackages();
        foreach ($packages as $name => $package) {
            $fcqn = null;

            if (is_string($package)) {
                $fcqn = $package;
            } elseif (empty($package)) {
                $fcqn = $name . '\\Package';
            }

            if (null !== $fcqn) {
                $package = class_exists($fcqn) ? new $fcqn() : null;
            }

            if (null === $package) {
                throw new PackageLoadFailedException($name);
            }

            $packages[$name] = $package;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function onMerge(Package\Event $event)
    {
        $manager = $event->getPackageManager();
        $config = [];

        foreach ($manager->getPackages() as $package) {
            if ($package instanceof ConfigProviderInterface) {
                $config = $manager->merge($config, $package->getConfig());
            }
        }

        $pmConfig = $manager->getConfig();
        if (null === $pmConfig['override_pattern']) {
            return $config;
        }

        $overrideFiles = glob($pmConfig['override_pattern'], $pmConfig['override_flags']);
        foreach ($overrideFiles as $file) {
            $config = $manager->merge($config, include $file);
        }

        return $config;
    }
}
